<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-15
 */
class AdminCommAction extends Action {
	public $dao,$sort;
	protected static $Model;	//数据Model
	function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		$this->roletree = get_roletree_arr ();
		$this->nodetree = get_nodetree_arr ();
		$this->brandtree = get_brand_tree ();
		//setcookie('think_template','default',time()+3600,'/');
		self::$Model = D ( "Cate" );
		$this->catetree = self::$Model->get_catetree_arr();
		self::$Model = D ( "Type_cate" );
		$this->type_id = self::$Model->where ( "status=1" )->order ( "id" )->select ();
		self::$Model=D("Countries");
		$this->countries=self::$Model->getlist();
		//定义模型
		if (in_array(MODULE_NAME,array('Brand','Cate','Node','Products','Role','Setting','User','Ad','Currencies','Members','Orders','Article','Article_cate','Down','Down_cate','Products_ask','Shipping','Shipping_area','Fee','Coupon', 'Cart'))){
			$this->dao = D ( MODULE_NAME );
		}
		$this->userName = Session::get ( 'loginUserName' );
		//echo APP_NAME;
		// 用户权限检查
		if (C ( 'USER_AUTH_ON' ) && ! in_array ( MODULE_NAME, explode ( ',', C ( 'NOT_AUTH_MODULE' ) ) )) {
			import ( '@.ORG.RBAC' );
			if (! RBAC::AccessDecision (GROUP_NAME)) {

				//检查认证识别号
				if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
					//跳转到认证网关
					redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
				}
				// 没有权限 抛出错误
				if (C ( 'RBAC_ERROR_PAGE' )) {
					// 定义权限错误页面
					redirect ( C ( 'RBAC_ERROR_PAGE' ) );
				} else {
					if (C ( 'GUEST_AUTH_ON' )) {
						$this->assign ( 'jumpUrl', PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
					}
					// 提示错误信息
					$this->error ( L ( '_VALID_ACCESS_' ) );
				}
			}
		}

	}

	function setimg_empty(){
		$f=$_GET['f'];
		$id=intval($_GET['id']);
		if($f && $id){
			$this->dao->where($this->dao->getPK()."='".$id."'")->setField($f,'');
		}
		$this->success('删除图片成功');
	}

	function doSortUpdate(){
		$j=0;
		foreach($_POST['sort'] as $k=>$v){
			if($_POST['sort'][$k]!=''){
				if($this->dao->where(array('id'=>$k))->data(array('sort'=>$v))->save()){
					$j++;
				}
			}
		}
		cleanCache();
		$this->success('共修改了'.$j.'个排序!');
	}
	function upload() {
		import ( "ORG.Net.UploadFile" );
		$upload = new UploadFile ();
		//检查客户端上传文件参数设置
		if (isset ( $_POST ['_uploadFileSize'] ) && is_numeric ( $_POST ['_uploadFileSize'] )) {
			//设置上传文件大小
			$upload->maxSize = $_POST ['_uploadFileSize'];
		} else {
			$upload->maxSize = C ( 'FILE_UPLOAD_MAXSIZE' );
		}
		/*if (! empty ( $_POST ['_uploadFileType'] )) {
		//设置上传文件类型
		$upload->allowExts = explode ( ',', strtolower ( $_POST ['_uploadFileType'] ) );
		} else {
		$upload->allowExts = explode ( ',', C ( 'FILE_UPLOAD_ALLOWEXTS' ) );
		}*/
		$upload->allowExts = explode ( ',', C ( 'FILE_UPLOAD_ALLOWEXTS' ) );

		if (! empty ( $_POST ['_uploadSavePath'] )) {
			//设置附件上传目录
			$upload->savePath = $_POST ['_uploadSavePath'];
		} else {
			$upload->savePath = "./Public/Uploads/" . MODULE_NAME . "/";
		}
		//是否自动命名
		if(GetValue('uploadsaveRule')==1){

			if (isset ( $_POST ['_uploadSaveRule'] )) {
				//设置附件命名规则
				$upload->saveRule = $_POST ['_uploadSaveRule'];
			} else {
				$upload->saveRule = 'uniqid';
			}
		}
		if (MODULE_NAME == 'Products') {
			//创建目录

			$upload->savePath = $upload->savePath . toDate ( time (), 'Ymd' ) . "/";
			if (! file_exists ( "$upload->savePath" )) {
				mk_dir ( $upload->savePath );
			}
			//设置需要生成缩略图，仅对图像文件有效
			$upload->thumb = true;
			//设置需要生成缩略图的文件后缀
			//$upload->thumbSuffix = "_thumb";
			//设置缩略图最大宽度
			$upload->thumbMaxWidth = GetValue ( 'ImgThumbW' );
			//设置缩略图最大高度
			$upload->thumbMaxHeight = GetValue ( 'ImgThumbH' );
		}
		$upload->uploadReplace = true;
		if (! $upload->upload ()) {
			$error = $upload->getErrorMsg ();

			$this->ajaxReturn ( '', $error, 0 );
		} else {
			$uploadSuccess = true;
			$uploadList = $upload->getUploadFileInfo ();
			//是否打上水印
			$imgwater=GetValue('ImgWater');
			$ImageWaterAlpha=GetValue('ImageWaterAlpha');
			if(empty($ImageWaterAlpha)){$ImageWaterAlpha=80;}
			import("ORG.Util.Image");
			foreach ( $uploadList as $key => $file ) {
				$savename ['savename'] = $upload->savePath . $file ['savename'];
				if($imgwater==1){
					if(GetValue('is_only_proimg_water')==1 && MODULE_NAME == 'Products'){
						Image::water($savename ['savename'],GetValue('ImgWaterPath'),GetValue('ImgWaterPos'),'',$ImageWaterAlpha);
					}elseif(GetValue('is_only_proimg_water')==0){
						Image::water($savename ['savename'],GetValue('ImgWaterPath'),GetValue('ImgWaterPos'),'',$ImageWaterAlpha);
					}
				}
				$savename ['name'] = $file ['name'];
				$pathinfo = pathinfo($savename ['savename']);
				$savename['thumb'] = str_replace($pathinfo['filename'], 'thumb_'.$pathinfo['filename'], $savename ['savename']);
			}
			$this->ajaxReturn ( $savename, '上传成功！', 1 );
		}
	}
	function _list($map = null) {
		//排序字段
		if(isset($_REQUEST['order'])) {
			$this->sort = $_REQUEST['order']?$_REQUEST['order']:'sort';
		}

		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if(isset($_REQUEST['sort'])) {
			$this->sort = $this->sort.' '.($_REQUEST['sort']?'asc':'desc');
		}

		if (!$this->sort){
			$this->sort="sort desc";
		}
		if(!empty($_REQUEST['listRows'])){
			$listRows = $_REQUEST['listRows'];
			SetValue('listRows',$listRows);
		}elseif(GetValue('listRows',false)) {
			$listRows  =  GetValue('listRows',false);
		}else {
			$listRows  =  20;
		}
		$count = $this->dao->where ( $map )->count ();
		import ( 'ORG.Util.Page' );
		$page = new Page ( $count, $listRows );
		/*foreach ( $map as $key => $val ) {
		$page->parameter .= "key=" . urlencode ( $val ) . "&";
		}*/
		$list = $this->dao->where ( $map )->order ( $this->sort )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$show = $page->show ();
		$this->listRows=$listRows;
		$this->list = $list;
		$this->show = $show;
	}
	function Delete() {
		$id=$_REQUEST ['id'];
		if($id){
			$map ['id'] = array('in',$id);
			if($this->dao->where ( $map )->count ()){
				$this->dao->where ( $map )->delete ();
			}
		}
		$this->success ( "删除成功！" );
	}
	public function edit() {
		$map ['id'] = $_GET ['id'];
		$list = $this->dao->where ( $map )->find ();
		if ($list) {
			$this->list = $list;

			$this->display ();
		} else {
			$this->error ( '没有数据！' );
		}

	}
	public function rename() {
		$data=$_POST;
		unset($data['id']);
		if($this->dao->create($data)){
			$this->dao->id=$_POST['id'];
			if($this->dao->save ()){
				$this->ajaxReturn($data,'修改成功',1);
			}else{
				$this->ajaxReturn($data,'修改失败',0);
			}
		}else {
			$this->ajaxReturn('',$this->dao->getError(),0);
		}

	}
	public function Update() {
		if (isset ( $_POST ['pid'] )) {
			if ($_POST ['id'] == $_POST ['pid']) {
				$this->error ( "无法更新数据!" );
			}
		}
		if ($this->dao->create ()) {
			$list = $this->dao->save ();
			if ($list !== false) {
				$this->success ( '数据更新成功！' );
				cleanCache ();
			} else {
				$this->error ( "没有更新任何数据!" );
			}
		} else {
			$this->error ( $this->dao->getError () );
		}
	}
	public function add() {
		$this->display ();
	}
	public function Insert() {
		if ($this->dao->create ()) {

			$id = $this->dao->add ();
			$this->success ( '添加成功！' );
		} else {
			$this->error ( $this->dao->getError () );
		}
	}

	public function admenage() {
		$this->display ();
	}


	public function status(){
		$map['id']=intval($_REQUEST['id']);
		$status=$this->dao->where($map)->getField('status');
		if($status==1){
			$status=0;
			$data['imgurl']=__ROOT__.'/Tpl/default/Public/images/mod_0.gif';
		}else{
			$status=1;
			$data['imgurl']=__ROOT__.'/Tpl/default/Public/images/mod_1.gif';
		}
		$this->dao->where($map)->data(array('status'=>$status))->save();
		$this->ajaxReturn($data,'保存成功',1);

	}

}

?>