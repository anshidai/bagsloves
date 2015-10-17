<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-18
 */
class ProductsAction extends AdminCommAction {
	function productslist() {
		if ($_GET['name']){
			$name=auto_charset($_GET['name'],'gbk','utf-8');
			$map ['name'] = array ('like', '%' . $name . '%' );
			$map['id']=$name;
			$map['_logic']='or';
		}
		switch ($_GET ['cateid']){
			case 'isnew':
				$map['isnew']=1;
				break;
			case 'ishot':
				$map['ishot']=1;
				break;
			case 'isrec':
				$map['isrec']=1;
				break;
			case 'isprice':
				$map['isprice']=1;
				break;
			case 'isdown':
				$map['isdown']=1;
				break;
			default:
				if(!empty($_GET ['cateid'])){
					$map ['cateid'] = $_GET ['cateid'];
					$this->cateid=$_GET['cateid'];
				}
		}

		$this->sort='id desc';
		$this->_list ($map);
		$this->display ();
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
		$this->success('共修改了'.$j.'个类别排序!');
	}
	function move() {
		if(!empty($_REQUEST['id'])){
			$map['id']=array('in',$_REQUEST['id']);
			$data['cateid']=$_REQUEST['cateid'];
			if($count=$this->dao->where($map)->count()){
				$this->dao->where($map)->data($data)->save();

			}
			$this->success('成功移动了'.$count.'个产品!');
		}
		$this->error('请选择要转移的产品!');
	}
	function movetobrand() {
		if(!empty($_REQUEST['id'])){
			$map['id']=array('in',$_REQUEST['id']);
			$data['brandid']=$_REQUEST['brandid'];
			if($count=$this->dao->where($map)->count()){
				$this->dao->where($map)->data($data)->save();

			}
			$this->success('成功移动了'.$count.'个产品!');
		}
		$this->error('请选择要转移的产品!');
	}
	function Delete() {
		if($_POST['file']){
			if(file_exists($_POST['file']) && unlink($_POST['file'])){
				die('删除成功');
			}else{
				die('删除失败');
			}
		}
		if($_REQUEST['id']){

			$map ['id'] = array('in',$_REQUEST ['id']);
			$list = $this->dao->where ( $map )->select ();
			if ($list) {
				//删除产品属性
				parent::$Model=D('Products_attr');
				parent::$Model->del_attrs($_REQUEST ['id']);
				parent::$Model = M ( "Products_gallery" );
				foreach ($list as $k=>$v){
					$v['bigimage']=auto_charset($v['bigimage'],'utf-8','gbk');
					$v['smallimage']=auto_charset($v['smallimage'],'utf-8','gbk');


					if(file_exists($v['bigimage'])){
						unlink($v['bigimage']);
					}
					if(file_exists($v['smallimage'])){
						unlink($v['smallimage']);
					}
					//删除产品相册
					$glist=parent::$Model->where(array("pid"=>$v['id']))->select();
					foreach ($glist as $g){
						$g['img_url']=auto_charset($g['img_url'],'utf-8','gbk');
						$g['thumb_url']=auto_charset($g['thumb_url'],'utf-8','gbk');
						if(file_exists($g['img_url'])){
							unlink($g['img_url']);
						}
						if(file_exists($g['thumb_url'])){
							unlink($g['thumb_url']);
						}
					}

					parent::$Model->where(array("pid"=>$v['id']))->delete();
					//删除关联产品连接
					parent::$Model=D("Products_related");
					parent::$Model->where(array("products_id"=>$v['id']))->delete();

				}
				$this->dao->where ( $map )->delete ();
			}

			$this->success ( "删除成功！" );
		}
		$this->error ( "删除失败！" );
	}
	function Insert() {
		dump($_POST);
		exit;

		for($i = 0; $i < count ( $_POST ['imgurl'] ); $i ++) {
			//判断是否为封面
			if (!empty($_POST ['imgurl'] [$i]) && $_POST ['timestamp'] [$i] == $_POST ['isIndex']) {
				//echo "It is Index! ";
				$_POST ['bigimage'] = $_POST ['imgurl'] [$i];
				$_POST ['smallimage'] = get_thumb_name ( $_POST ['imgurl'] [$i] );
			}
		}
		if(empty($_POST ['bigimage'])){
			for($i = 0; $i < count ( $_POST ['imgurl'] ); $i ++) {
				//取第一张做封面
				if(!empty($_POST ['imgurl'] [$i])){
					$_POST ['bigimage'] = $_POST ['imgurl'] [$i];
					$_POST ['smallimage'] = get_thumb_name ( $_POST ['imgurl'] [$i] );
				}
			}
		}


		if ($this->dao->create ()) {
			$id = $this->dao->add ();
			//插入相册开始
			$model = M ( "Products_gallery" );
			for($i = 0; $i < count ( $_POST ['imgurl'] ); $i ++) {
				if (! empty ( $_POST ['imgurl'][$i] ))
				{
					$data ['pid'] = $id;
					$data ['img_url'] = $_POST ['imgurl'] [$i];
					$data ['thumb_url'] = get_thumb_name ( $_POST ['imgurl'] [$i] );
					$data ['img_desc'] = $_POST ['img_desc'] [$i];
					empty($data['img_desc'])?$data['img_desc']=$_POST ['name']:'';
					$data ['sort'] = $_POST ['sort'] [$i]; //sort
					empty($data['sort'])?$data['sort']=$i:'';
					$model->add ( $data );
				}
			}
			//插入相册结束
			$this->success ( '添加成功！' );
		} else {
			$this->error ( $this->dao->getError () );
		}
	}

	public function edit() {
		$map ['id'] = $_GET ['id'];
		/**
		 * 产品购买数量折扣
		 */
		parent::$Model=D("Products_lot");
		$this->lot_list=parent::$Model->table(C('DB_PREFIX')."products a")->join('right join '.C('DB_PREFIX').'products_lot b on a.id = b.product_id')->where(array('a.id'=>$map['id']))->select();
		if(isset($_GET['lot_id'])){
			$this->lot=parent::$Model->find($_GET['lot_id']);
		}
		/**
		 * 关联产品
		 */
		parent::$Model=D("Products_related");
		$this->related=parent::$Model->field('a.id,b.name,b.smallimage')->table(C('DB_PREFIX').products_related." a")->join(C('DB_PREFIX').'products b on a.related_products_id = b.id')->where(array('a.products_id'=>$map['id']))->select();
		$list = $this->dao->where ( $map )->find ();
		if ($list) {
			$this->list = $list;
			$model = M ( "Products_gallery" );
			$glist=$model->where("pid=".$_GET ['id'])->order("sort")->select();
			if(GetValue('auto_find_gallery')){
				if(file_exists(__ROOT__.$list['bigimage'])){
					$bigimage=pathinfo(__ROOT__.$list['bigimage']);
					$filename=$bigimage['filename'];
					$dirname=$bigimage['dirname'];
					$files=glob($dirname.'/'.$filename.'_*');
					$count=count($glist);
					foreach ($files as $f){
						$glist[$count]['thumb_url']=__ROOT__.$f;
						$glist[$count]['is_other']=1;
						$count++;
					}
				}
			}

			$this->glist=$glist;
			$this->display ();
		} else {
			$this->error ( '没有数据！' );
		}

	}
	function set_lot(){
		$lot_model=D('Products_lot');
		switch ($_REQUEST['action']){
			case 'insert':
				if($lot_model->create()){
					$lot_model->add();
					$this->success('添加成功!');
				}else{
					$this->error($lot_model->getError());
				}

				break;
			case 'update':
				if($lot_model->create()){
					$lot_model->save();
					$this->success('修改成功!');
				}else{
					$this->error($lot_model->getError());
				}

				break;
			case 'update_switch':
				$this->dao->where(array('id'=>$_POST['id']))->save(array('activity'=>$_POST['activity'],'piece'=>$_POST['piece']));
				$this->success('设置成功!');
				break;
			case 'edit':
				$this->lot_list=$lot_model->find($id);
				break;
			case 'del':
				$map ['id'] = array('in',$id);
				if($lot_model->where ( $map )->count ()){
					$lot_model->where ( $map )->delete ();
				}
				$this->success ( "删除成功！" );
				break;
		}
	}
	/**
	 * 添加关联产品
	 *
	 */
	function addrelated() {
		if ($_GET['name']){
			$this->name=$_GET['name'];
			$map ['name'] = array ('like', '%' . $_GET ['name'] . '%' );
		}
		if ($_GET ['cateid']) {
			$this->cateid=$map ['cateid'] = $_GET ['cateid'];
		}
		$products_id=$_GET['id'];
		
		$this->products_info = D('Products')->where("id={$products_id}")->find();
		
		$this->products_id=$products_id;

		//排除自己和已经关联
		parent::$Model=D('Products_related');
		$neq_products_id=array_map('reset',parent::$Model->field('related_products_id')->where(array('products_id'=>$products_id))->select());
		$neq_products_id[]=$products_id;
		$map['id']=array('not in',$neq_products_id);
		$this->_list ($map);
		$list=$this->__get('list');

		parent::$Model=D('Products_gallery');
		foreach ($list as $k=>$v){
			$list[$k]['thumb_url']=__ROOT__."/".parent::$Model->where(array('pid'=>$v['id']))->getField('thumb_url');
		}
		$this->list=$list;
		$this->display ();
	}
	function doAddRelated(){
		parent::$Model=D('Products_related');
		$products_id=$_POST['products_id'];
		$ids=$_POST['id'];
		if(in_array($products_id,$ids)){
			$this->error('产品不能和自己关联!');
		}
		$j=0;

		foreach ($ids as $id){
			if(!parent::$Model->where(array('products_id'=>$products_id,'related_products_id'=>$id))->count()){
				$j++;
				parent::$Model->data(array('products_id'=>$products_id,'related_products_id'=>$id))->add();

			}
		}
		$this->success('增加了'.$j.'个关联产品!');
	}
	function doAddRelatedByCate(){
		parent::$Model=D('Products');
		$Products_list=parent::$Model->field('id')->where(array('cateid'=>$_POST['cateid']))->select();
		$ids=array_map('reset',$Products_list);
		$products_id=$_POST['products_id'];
		parent::$Model=D('Products_related');
		$j=0;
		foreach ($ids as $id){
			if(($products_id!=$id) && !parent::$Model->where(array('products_id'=>$products_id,'related_products_id'=>$id))->count()){
				$j++;
				parent::$Model->data(array('products_id'=>$products_id,'related_products_id'=>$id))->add();

			}
		}
		$this->success('增加了'.$j.'个关联产品!');
	}
	function doDelRelated(){
		parent::$Model=D('Products_related');
		if($_REQUEST['id']){
			$map['id']=array('in',$_REQUEST['id']);
			$j=parent::$Model->where($map)->delete();
			$this->success('删除了'.$j.'个关联产品!');
		}
		$this->error('删除失败!');
	}
	public function Update(){

		for($i = 0; $i < count ( $_POST ['imgurl'] ); $i ++) {
			//判断是否为封面
			if (!empty($_POST ['imgurl'] [$i]) && $_POST ['timestamp'] [$i] == $_POST ['isIndex']) {
				//echo "It is Index! ";
				$_POST ['bigimage'] = $_POST ['imgurl'] [$i];
				$_POST ['smallimage'] = get_thumb_name ( $_POST ['imgurl'] [$i] );
			}

		}
		if(empty($_POST ['bigimage'])){
			for($i = 0; $i < count ( $_POST ['imgurl'] ); $i ++) {
				//取第一张做封面
				if(!empty($_POST ['imgurl'] [$i])){
					$_POST ['bigimage'] = $_POST ['imgurl'] [$i];
					$_POST ['smallimage'] = get_thumb_name ( $_POST ['imgurl'] [$i] );
				}
			}
		}

		if ($this->dao->create ()) {
			$list = $this->dao->save ();
			if ($list !== false) {
				$model = D( "Products_gallery" );
				$model->where("pid=".$_POST ['id'])->delete();
				//更新相册开始
				for($i = 0; $i < count ( $_POST ['imgurl'] ); $i ++) {
					if (! empty ( $_POST ['imgurl'][$i] )) {
						$data ['pid'] = $_POST ['id'];
						$data ['img_url'] = $_POST ['imgurl'] [$i];
						$data ['thumb_url'] = get_thumb_name ( $_POST ['imgurl'] [$i] );
						$data ['img_desc'] = $_POST ['img_desc'] [$i];
						$data ['sort'] = $_POST ['sort'] [$i]; //sort
						empty($data['img_desc'])?$data['img_desc']=$_POST ['name']:'';
						empty($data['sort'])?$data['sort']=$i:'';
						$model->add ( $data );
					}
				}
				//更新相册结束
				$this->success ( '数据更新成功！' );
			} else {
				$this->error ( "没有更新任何数据!" );
			}
		} else {
			$this->error ( $this->dao->getError () );
		}

	}

	public function easyupload(){
		//echo ACTION_NAME;
		if($this->isPost()){

			self::$Model=D("Cate");
			$typeid=self::$Model->field("type_id")->where(array('id'=>$_POST['cateid']))->getField('type_id');

			self::$Model=D("Type_attr");
			$attr=self::$Model->where(array('type_id'=>$typeid,'status'=>1))->order("sort desc")->select();
			foreach ($attr as $k=>$v){
				$attr[$k]['values']=explode(chr(13),$v['values']);
				foreach ($attr[$k]['values'] as $kk=>$vv){
					$attr[$k]['values'][$kk]=str_replace("\n","",$vv);
					//展开所有属性
					$attr[$k]['attrs'][$kk]['attr_id']=$v['id'];
					$attr[$k]['attrs'][$kk]['attr_value']=$attr[$k]['values'][$kk];
				}
			}
			$this->attr=$attr;

			die($this->fetch('Products-getattr'));
		}
		$this->display();
	}
	public function doEasyUpload(){
		if(!$_POST['cateid']){
			$this->error('请选择产品分类!');
		}else{
			$j=0;
			for($i=0;$i<count($_POST['imgurl']);$i++){
				if (! empty ( $_POST ['imgurl'][$i] )) {
					$j++;
					if ($_POST ['name']) {
						$_POST ['tmp_name'] = $_POST ['name'];
						$_POST ['name'] = $_POST ['name'] . "0" . $j;
					}
					else{
						$fileinfo=pathinfo($_POST['imgname'][$i]);
						//dump($fileinfo);
						$_POST['name']=$fileinfo['filename'];
					}
					$_POST ['bigimage'] = $_POST ['imgurl'] [$i];
					$_POST ['smallimage'] = get_thumb_name ( $_POST ['imgurl'] [$i] );
					if ($this->dao->create()) {
						$id = $this->dao->add ();

						/**
				 * 插入属性bof
				 */
						self::$Model=D("Products_attr");

						$k=0;
						foreach ($_POST ['attr_id'] as $key => $attr_id ) {
							foreach ( $_POST ['attr_value_' . $attr_id] as $key => $attr_value ) {
								if (!empty($attr_value))
								{
									//增加产品属性表
									$data ['products_id'] = $id;
									$data ['attr_id'] = $attr_id;
									$data ['attr_value'] = str_replace ( "\n", "", $attr_value );
									$data ['sort'] = $k++;
									$data ['dateline'] = time();
									if (self::$Model->create ( $data )) {
										self::$Model->add ( $data );

									} else {
										$this->error ( self::$Model->geterror () );
									}
								}
							}
						}
						/**
				 * 属性eof
				 */

						$_POST ['name']=$_POST['tmp_name'];
						//开始插入相册
						$_POST ['pid'] = $id;
						$_POST ['img_url'] = $_POST ['imgurl'] [$i];
						$_POST ['thumb_url'] = get_thumb_name ( $_POST ['imgurl'] [$i] );
						$model = D( "Products_gallery" );
						if ($model->create ()) {
							$model->add ();
						} else {
							$this->error ( $this->dao->getError () );
						}
					}
					else {
						$this->error ( $this->dao->getError () );
					}

				}


			}
			$this->success("本次操作共上传".$j."个新产品！");
		}
	}

	public function doEasyUploadThumb(){
		if(!$_POST['cateid']){
			$this->error('请选择产品分类!');
		}else{

			$model = D( "Products_gallery" );
			$imgurl=array();
			foreach ($_POST['imgurl'] as $k=>$v){
				if(strpos($v,'_')>0){
					$imgurl[0][]=$v;//细节图
				}else{
					$imgurl[1][]=$v;//主图
				}
			}

			$count=count($imgurl[1]);
			for($i=0;$i<$count;$i++){
				if (! empty ($imgurl[1][$i] )) {

					$fileinfo=pathinfo(basename($imgurl[1][$i]));
					$_POST['name']=$fileinfo['filename'];
					$_POST ['bigimage'] = $imgurl[1][$i];//主图
					$_POST ['smallimage'] = get_thumb_name ( $imgurl[1][$i] );//小图
					//找出细节图
					$gallery=array();
					foreach ($imgurl[0] as $kk=>$vv){
						if(strpos($vv,$_POST['name'].'_')>0){
							$gallery[]=$vv;
						}
					}
					if ($this->dao->create()) {
						$id = $this->dao->add ();

						//插入相册封面
						$data=array();
						$data['pid'] = $id;
						$data['img_url'] = $_POST ['bigimage'];
						$data['thumb_url'] = $_POST ['smallimage'];
						$data['img_desc'] = $_POST['name'];
						$model->add ($data);
						/**
						 * 插入属性bof
						 */
						self::$Model=D("Products_attr");
						$k=0;
						foreach ($_POST ['attr_id'] as $key => $attr_id ) {

							foreach ( $_POST ['attr_value_' . $attr_id] as $key => $attr_value ) {
								if (!empty($attr_value))
								{
									//增加产品属性表
									$data ['products_id'] = $id;
									$data ['attr_id'] = $attr_id;
									$data ['attr_value'] = str_replace ( "\n", "", $attr_value );
									$data ['sort'] = $k++;
									$data ['dateline'] = time();
									if (self::$Model->create ( $data )) {
										self::$Model->add ( $data );

									} else {
										$this->error ( self::$Model->geterror () );
									}
								}
							}
						}
						/**
						 * 属性eof
						 */

						//开始插入细节图

						foreach ($gallery as $kkk=>$vvv){
							$data=array();
							$data['pid'] = $id;
							$data['img_url'] = $vvv;
							$data['thumb_url'] = get_thumb_name ($vvv);
							if ($model->create ($data)) {
								$model->add ($data);
							}
						}
					}
					else {
						$this->error ( $this->dao->getError () );
					}

				}

			}
			$this->success("本次操作共上传".$i."个新产品！");
		}
	}
	function attrdel(){
		$id=$_REQUEST["id"];
		self::$Model=D("Products_attr");
		$map['id']=array('in',$id);
		$list=self::$Model->where($map)->select();
		if($list){
			foreach ($list as $k=>$v){
				$v['img_url']=auto_charset($v['img_url'],'utf-8','gbk');
				if(file_exists($v['img_url'])){
					unlink($v['img_url']);
				}
			}
		}
		self::$Model->where($map)->delete();
		echo json_encode($id);

	}

	function attrsave(){
		self::$Model=D("Products_attr");
		if(is_array($_POST['attr_value'])){
			for($i=0;$count=count($_POST['attr_value']),$i<$count;$i++){
				$data=array();
				$data['attr_value']=$_POST['attr_value'][$i];
				$data['attr_price']=$_POST['attr_price'][$i];
				$data['img_url']=$_POST['img_url'][$i];
				$data['thumb_url']=$_POST['thumb_url'][$i];
				$data['attr_id']=$_POST['attr_id'][$i];
				$data['products_id']=$_POST['products_id'];
				$data['sort']=$_POST['sort'][$i];
				$data['dateline']=time();
				if(intval($_POST['products_attr_id'][$i])===0 && !empty($data['attr_value'])){
					self::$Model->add($data);
				}else{
					self::$Model->where("id=".$_POST['products_attr_id'][$i])->save($data);
				}
			}
		}else{
			$id=$_REQUEST["id"];
			$data["attr_id"]=$_REQUEST["attr_id"];
			$data["attr_value"]=$_REQUEST["attr_value"];
			$data["products_id"]=$_REQUEST["products_id"];
			$data["img_url"]=$_REQUEST["img_url"];
			$data["thumb_url"]=$_REQUEST["thumb_url"];
			$data["sort"]=$_REQUEST["sort"];

			/*if (empty($data["attr_value"])){
			$r["status"]=0;
			echo json_encode($r);die;
			}*/
			if (empty($_REQUEST["attr_price"])){
				$data["attr_price"]=0;
			}
			else{
				$data["attr_price"]=$_REQUEST["attr_price"];
			}
			if ($id==0){
				$r["id"]=self::$Model->add($data);
				//echo self::$Model->getlastsql();
				$r["status"]=1;

				echo json_encode($r);die;
			}
			else{
				self::$Model->where("id=".$id)->save($data);
				$r["status"]=1;
				$r["id"]=$id;
				echo json_encode($r);die;
			}
		}

	}
	function attredit(){
		is_null($_REQUEST['id']) && $this->error('没有数据');
		if (is_array($_REQUEST['id'])){
			$map['id']=array("in",implode(",",$_REQUEST['id']));
			$this->pid=implode(",",$_REQUEST['id']);
		}
		else{
			$map['id']=$_REQUEST['id'];
			$this->pid=$_REQUEST['id'];
		}
		$cate=$this->dao->field("cateid")->where($map)->group("cateid")->select();
		if (count($cate)>1){
			$this->error("请选择同一类别下的产品进行属性编辑！");
		}

		self::$Model=D("Cate");
		$typeid=self::$Model->field("type_id")->where("id=".$cate[0]['cateid'])->select();

		self::$Model=D("Type_attr");
		$attr=self::$Model->where("type_id=".$typeid[0]['type_id']." and status=1")->order("sort desc")->select();
		self::$Model=D("Products_attr");
		for ($row=0;$row<count($attr);$row++){
			$map1['products_id']=$map['id'];
			$map1['attr_id']=$attr[$row]['id'];
			$attr[$row]['attrs']=self::$Model->where($map1)->order('sort asc')->select();
			$attr[$row]['values']=explode(chr(13),$attr[$row]['values']);
			foreach ($attr[$row]['values'] as $k=>$v){
				$attr[$row]['values'][$k]=str_replace("\n","",$v);
			}
		}

		//dump($attr);
		$this->attr=$attr;
		$this->display();
	}
	function attrUpdate(){

		$products_id=explode(",",$_POST['products_id']);
		//先删除原来的属性
		self::$Model=D("Products_attr");
		$map['products_id']=array("in",$_POST['products_id']);
		self::$Model->where($map)->delete();
		//echo self::$Model->getlastsql();
		foreach ($products_id as $key=>$pid){
			foreach ( $_POST ['attr_id'] as $key => $attr_id ) {
				foreach ( $_POST ['attr_value_' . $attr_id] as $key => $attr_value ) {
					if (!empty($attr_value))
					{
						$data ['products_id'] = $pid;
						$data ['attr_id'] = $attr_id;
						$data ['attr_value'] = str_replace ( "\n", "", $attr_value );
						if (self::$Model->create ( $data )) {
							self::$Model->add ( $data );
							//echo self::$Model->getlastsql()."<br>";

						} else {
							$this->error ( self::$Model->geterror () );
						}
						//echo $pid . "-" . $attr_id . "-" . $attr_value . "<br>";
					}
				}
			}
		}
		$this->jumpUrl=U('Products/productslist');
		$this->success("操作成功！");
	}
	/**
	 * 批量修改产品属性
	 */
	function batchedit(){
		if($this->isPost()){

			self::$Model=D("Cate");
			$typeid=self::$Model->field("type_id")->where(array('id'=>$_POST['cateid']))->getField('type_id');

			self::$Model=D("Type_attr");
			$attr=self::$Model->where(array('type_id'=>$typeid,'status'=>1))->order("sort desc")->select();
			foreach ($attr as $k=>$v){
				$attr[$k]['values']=explode(chr(13),$v['values']);
				foreach ($attr[$k]['values'] as $kk=>$vv){
					$attr[$k]['values'][$kk]=str_replace("\n","",$vv);
					//展开所有属性
					$attr[$k]['attrs'][$kk]['attr_id']=$v['id'];
					$attr[$k]['attrs'][$kk]['attr_value']=$attr[$k]['values'][$kk];
				}
			}
			$this->attr=$attr;
			$this->cateid=$_POST['cateid'];
			die($this->fetch('Products-getattr'));
		}
		$this->display();
	}
	function doBatchUpdate(){
		if($arr=$this->dao->create()){
			if(!$this->dao->cateid){
				$this->error('请选择修改类别!');
			}
			$cateModel=D("Cate");
			//更改所有子类别
			$cateid_list=$cateModel->getChildren($this->dao->cateid,$this->dao->cateid);
			$map['cateid']=array('in',implode(",",$cateid_list));
			//如果有产品则修改
			$count=$this->dao->where($map)->count();
			if($count){
				$data=array();
				foreach ($arr as $key=>$value){
					if($key=='cateid'){
						continue;
					}
					if($arr[$key] != ''){
						$data[$key]=$value;
					}
				}


				$this->dao->where($map)->data($data)->save();
				$str="修改了".$count."个产品";

				//产品id列表
				$products_list=$this->dao->where($map)->field('id')->select();
				$products_id=array_map("reset",$products_list);
				//修改属性
				//先删除原来的属性
				self::$Model=D("Products_attr");
				$map=$data=array();
				$map['products_id']=array("in",$products_id);
				self::$Model->where($map)->delete();

				foreach ($products_id as $key=>$pid){
					foreach ($_POST ['attr_id'] as $key => $attr_id ) {

						foreach ( $_POST ['attr_value_' . $attr_id] as $key => $attr_value ) {
							if (!empty($attr_value))
							{
								//增加产品属性表
								$data ['products_id'] = $pid;
								$data ['attr_id'] = $attr_id;
								$data ['attr_value'] = str_replace ( "\n", "", $attr_value );
								if (self::$Model->create ( $data )) {
									self::$Model->add ( $data );

								} else {
									$this->error ( self::$Model->geterror () );
								}
							}
						}
					}
				}
				$this->success("修改成功!\n".$str);
			}else{
				$this->error('修改失败,该类别下没有产品!');
			}
		}else{
			$this->error($this->dao->getError());
		}


	}

	function SetStatus(){
		if($_REQUEST['id']){
			$type=$_REQUEST['type'];
			$id=$_REQUEST['id'];
			$time=time();
			$status=$this->dao->where(array('id'=>$id))->getField($type);
			if($status==1){
				$status=0;
				$data['imgurl']=__ROOT__.'/Public/skin/images/mod_0.gif';
			}else{
				$status=1;
				$data['imgurl']=__ROOT__.'/Public/skin/images/mod_1.gif';
			}
			$map['id']=$id;
			$data[$type]=$status;
			$this->dao->where($map)->data($data)->save();
			$this->ajaxReturn($data,'保存成功',1);
		}
	}
	function SetStatus2(){
		if($_REQUEST['id']){
			$type=$_REQUEST['type'];
			$id=$_REQUEST['id'];
			$time=time();
			$count=$this->dao->where(array('id'=>array('in',$id)))->count();
			$list=$this->dao->where(array('id'=>array('in',$id)))->select();
			foreach ($list as $v){
				if($_REQUEST['reverse']==1){
					$this->dao->query("update __TABLE__ set `$type`=if(`$type`=1,0,1),`dateline`=\"$time\" where id={$v['id']};");
				}else{
					$this->dao->query("update __TABLE__ set `$type`=1,`dateline`=\"$time\" where id={$v['id']};");
				}
			}
			$this->success('成功设置'.$count.'个产品!');
		}
		$this->error('请选择产品');
	}
	function csv(){
		$dao=D('Cate');
		$list = $dao->get_catetree_arr ();
		$this->assign('list',$list);
		$this->display();
	}

	function exportcsv(){
		$csvfile=TEMP_PATH."products.csv";
		self::$Model=D('Products');
		if(isset($_POST['cateid']) && !empty($_POST['cateid'])){
			$cateid=D('Cate')->getChildren($_POST['cateid'],$_POST['cateid']);
			$map['cateid']=array('in',$cateid);
			$list=self::$Model->where($map)->select();
		}else{
			$list=self::$Model->select();
		}
		if($list){
			$fields=self::$Model->query("SHOW COLUMNS FROM __TABLE__");
			$dbfields=array();//位置
			foreach ($fields as $field){
				$dbfields[]=$field['Field'];
			}
			$file = fopen($csvfile,"w+");
			//翻译
			$lang= array(
			'id' =>'产品id',
			'cateid'=>'类别id',
			'name'=>'产品名称',
			'serial'=>'产品货号',
			'price'=>'市场价格',
			'pricespe'=>'商店价格',
			'weight'=>'重量',
			'bigimage'=>'原图',
			'smallimage'=>'缩略图',
			'remark'=>'产品描述',
			'isnew'=>'是否新产品',
			'ishot'=>'是否热门产品',
			'isrec'=>'是否推荐产品',
			'isprice'=>'是否特价产品',
			'isdown'=>'是否下架产品',
			'dateline'=>'添加时间',
			'sort'=>'排序',
			'brandid'=>'品牌id',
			'viewcount'=>'点击数',
			'points'=>'积分',
			'costprice'=>'成本价',
			'provider'=>'供应商',
			'stock'=>'库存',
			'pagetitle'=>'meta标题',
			'pagekey'=>'meta关键字',
			'pagedec'=>'meta描述'
			);
			//标题
			$title=array();
			foreach($dbfields as $key=>$val){
				$title[]=$lang[$val]?$lang[$val]:$val;
			}

			fputcsv($file,auto_charset($title,'utf-8','gbk'));
			//内容
			foreach ($list as $line)
			{
				fputcsv($file,auto_charset($line,'utf-8','gbk'));
			}
			fclose($file);

			import("ORG.Net.Http");
			Http::download ($csvfile);

		}else{
			$this->error('没有产品数据');
		}

	}


	function importcsv(){
		setlocale(LC_ALL,'zh_CN.GBK');
		$csvfile=$_FILES['csvfile']['tmp_name'];
		if(is_uploaded_file($csvfile)){
			$file = fopen($csvfile,"r");
			$lang= array(
			"产品id"=>'id',
			"类别id"=>'cateid',
			"产品名称"=>'name',
			"产品货号"=>'serial',
			"市场价格"=>'price',
			"商店价格"=>'pricespe',
			"重量"=>'weight',
			"原图"=>'bigimage',
			"缩略图"=>'smallimage',
			"产品描述"=>'remark',
			"是否新产品"=>'isnew',
			"是否热门产品"=>'ishot',
			"是否推荐产品"=>'isrec',
			"是否特价产品"=>'isprice',
			"是否下架产品"=>'isdown',
			"添加时间"=>'dateline',
			"排序"=>'sort',
			"品牌id"=>'brandid',
			"点击数"=>'viewcount',
			"积分"=>'points',
			"成本价"=>'costprice',
			"供应商"=>'provider',
			"库存"=>'stock',
			"meta标题"=>'pagetitle',
			"meta关键字"=>'pagekey',
			"meta描述"=>'pagedec'
			);
			//读取标题
			$title = fgetcsv($file);
			if($title){
				$title=auto_charset($title,'gbk','utf-8');
				$fields=array();
				//找出位置
				for($i=0;$count=count($title),$i<$count;$i++){
					$fields[]=$lang[$title[$i]]?$lang[$title[$i]]:$title[$i];
				}

			}else{
				$this->error('请上传正确的文件');
			}
			self::$Model=D('Products');
			//内容
			$modify=$add=0;
			while ($line = auto_charset(fgetcsv($file),'gbk','utf-8')) {

				$data=array();
				for ($c=0; $num=count($line),$c < $num; $c++) {
					$data[$fields[$c]]=$line[$c];
				}
				$data=array_filter($data);

				if($data==null){
					continue;
				}

				//过滤多余的字段
				if(false===self::$Model->create($data)){
					$this->error(self::$Model->getError());
				}
				if(false === self::$Model->add()){
					if(false !== self::$Model->save()){
						$modify++;
					}
				}else{
					$add++;
				}
			}
			$str='';
			if($add){
				$str.=' 增加了'.$add.'产品';
			}
			if($modify){
				$str.=' 修改了'.$modify.'个产品';
			}
			fclose($file);
			$this->success('导入成功!'.$str);

		}else{
			$this->error('请上传文件');
		}

	}
	function replace_special_char($str)
	{
		$str = str_replace("\r\n", "", $str);
		$str = str_replace("\t", "    ", $str);
		$str = str_replace("\n", "", $str);
		$str =str_replace('"', '""', $str);
		return $str;
	}
}
?>