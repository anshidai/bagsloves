<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-18
 */
class CateAction extends AdminCommAction {

	function catelist(){
		if($this->isPost()){
			$this->key=$_POST['key'];
			$this->type=$_POST['type'];
			switch (true){
				case $_POST['type']=='id':
					$catetree=$this->dao->where(array('id'=>$_POST['key']))->select();
					$this->assign('catetree',$catetree);
					break;
				case $_POST['type']=='name':
					$catetree=$this->dao->where(array('name'=>array('like','%'.$_POST['key'].'%')))->select();
					$this->assign('catetree',$catetree);
					break;
				case $_POST['type']=='ishot':
					if(empty($_POST['key'])){
						$catetree=$this->dao->where(array('ishot'=>1))->select();
					}else{
						$map['_string']="`ishot`='1' and (`name` like '%".$_POST['key']."%' or `id`='".$_POST['key']."')";
						$catetree=$this->dao->where($map)->select();
					}
					$this->assign('catetree',$catetree);
					break;
				case $_POST['type']=='nothot':
					if(empty($_POST['key'])){
						$catetree=$this->dao->where(array('ishot'=>0))->select();
					}else{
						$map['_string']="`ishot`='0' and (`name` like '%".$_POST['key']."%' or `id`='".$_POST['key']."')";
						$catetree=$this->dao->where($map)->select();
					}
					$this->assign('catetree',$catetree);
					break;
			}

		}
		cleanCache ();
		$this->display();
	}

	function Delete() {
		$map ['pid'] = $_GET ['id'];
		$i = $this->dao->where ( $map )->find ();
		if (! $i) {
			$map1['id']= $_GET ['id'];
			$img=$this->dao->where ( $map1 )->getField('imgurl');
			$img=auto_charset($img,'utf-8','gbk');
			if(file_exists($img)){
				unlink($img);
			}
			$this->dao->where ( $map1 )->delete ();
			$this->success ( "删除成功！" );
			cleanCache ();
		}
		else{
			$this->error('无法删除，还有下级分类！');
		}

	}
	function delall(){
		if($_REQUEST['id']){
			self::$Model=D('Products');
			foreach (array($_REQUEST['id']) as $id){
				if($id){
					$cate_ids=implode(',',$this->dao->getChildren($id,$id));

					$map1['cateid']=$map['id']=array('in',$cate_ids);

					if(self::$Model->where($map1)->count()){
						$this->error('删除失败，该类别下面还有产品！');
					}
					$imgurl=$this->dao->field('imgurl')->where($map)->select();

					foreach ($imgurl as $img){
						$img=auto_charset($img,'utf-8','gbk');
						if(file_exists($img)){
							unlink($img);
						}
					}
					$this->dao->where ( $map )->delete ();
					cleanCache ();
					$this->ajaxReturn($cate_ids,'删除成功',1);
				}

			}


		}
		$this->error('删除失败，该类别不存在！');

	}

	function moveall(){

		$this->jumpUrl=U('Cate/catelist');
		$in_id=$_POST['id'];
		if($in_id){
			self::$Model=D('Cate');
			$map['id']=array('in',$in_id);
			$this->list=self::$Model->field('id,name')->where($map)->select();
			$this->assign('moveall',1);
			$this->assign('id',$_POST['id']);
			$this->display('Cate-move');
		}else{
			$this->error('没有数据！');
		}
	}
	function batchAddType(){

		$this->jumpUrl=U('Cate/catelist');
		$in_id=$_POST['id'];
		if($in_id){
			self::$Model=D('Cate');
			$map['id']=array('in',$in_id);
			$this->list=self::$Model->field('id,name')->where($map)->select();
			$this->assign('addtype',1);
			$this->assign('id',$_POST['id']);
			$this->display('Cate-move');
		}else{
			$this->error('没有数据！');
		}
	}
	function do_moveall(){

		$this->jumpUrl=U('Cate/catelist');
		if(isset($_POST['id']) && isset($_POST['pid'])){
			$map['id']=array('in',$_POST['id']);
			$data['pid']=$_POST['pid'];
			if($count=$this->dao->where($map)->count()){
				$this->dao->where($map)->save($data);
				cleanCache ();
				$this->success('移动成功,共移动了'.$count.'个类别!');
			}else{
				$this->error('移动失败，请选择类别！');
			}
		}
		$this->error('移动失败，该类别不存在！');
	}
	function do_addtype(){

		$this->jumpUrl=U('Cate/catelist');
		if(isset($_POST['id']) && isset($_POST['type_id'])){
			$map['id']=array('in',$_POST['id']);
			$data['type_id']=$_POST['type_id'];
			if($count=$this->dao->where($map)->count()){
				$cate_ids=$_POST['id'];
				$map1['cateid']=array('in',$cate_ids);
				parent::$Model=D('Products');
				$products_ids=implode(',',array_map('reset',parent::$Model->field('id')->where($map1)->select()));
				if($products_ids){
					//删除产品属性
					parent::$Model=D('Products_attr');
					parent::$Model->del_attrs($products_ids);
				}
				$this->dao->where($map)->save($data);
				cleanCache ();
				$this->success('修改成功,共修改了 '.$count.' 个类别!');
			}else{
				$this->error('修改商品类型失败，请选择类别！');
			}
		}
		$this->error('修改商品类型失败，该类别不存在！');
	}
	function movecate(){
		$id=$_GET['id'];

		$this->list=$this->dao->find($id);
		$this->catetree = get_indexcate_arr ();
		$this->assign('iscate',1);
		$this->display('Cate-move');
	}
	function do_movecate(){
		if(isset($_POST['pid']) && $_GET['id']){
			$map['id']=$_GET['id'];
			if($this->dao->where($map)->count()){
				$data['pid']=$_POST['pid'];
				$this->dao->where($map)->save($data);
				cleanCache ();
				$this->success('类别移动成功!');
			}
			$this->error('移动失败，该类别不存在！');
		}
		$this->error('移动失败，该类别不存在！');
	}
	function do_movepro(){
		if(isset($_POST['cateid']) && $_GET['id']){
			self::$Model=D('Products');
			$map['cateid']=$_GET['id'];
			if(self::$Model->where($map)->count()){
				$data['cateid']=$_POST['cateid'];
				self::$Model->where($map)->save($data);
				cleanCache ();
				$this->success('产品移动成功!');
			}
			$this->error('移动失败，该类别不存在！');
		}
		$this->error('移动失败，该类别不存在！');
	}
	function movepro(){
		$id=$_GET['id'];

		$this->list=$this->dao->find($id);
		$this->catetree = get_indexcate_arr ();
		$this->assign('ispro',1);
		$this->display('Cate-move');
	}
	//批量添加属性
	function batchAddAttr(){

		if($_POST['id']){

			$i=$j=$k=$l=0;
			$map['id']=array('in',$_POST['id']);
			//类别列表
			$catelist=$this->dao->field("id,type_id")->where($map)->select();
			$Type_attr=D("Type_attr");
			$Products_attr=D('Products_attr');
			$Products=D('Products');
			foreach ($catelist as $cate){
				$k++;
				//产品列表
				$productslist=$Products->field('id')->where(array('cateid'=>$cate['id']))->select();
				if($productslist){

					//类型属性列表
					$attr=$Type_attr->where(array('type_id'=>$cate['type_id'],'status'=>1))->select();
					$attr=array_filter($attr);
					//类型中所有属性加到产品
					foreach ($productslist as $pro){
						foreach ($attr as $kk=>$vv){


							$vv['values']=explode ( chr(13), $vv['values']);

							if(is_array($vv['values'])){
								$vv['values']=str_replace ( "\n", "", $vv['values'] );
								foreach ($vv['values'] as $values){
									$data=array();
									$data ['products_id'] = $pro['id'];
									$data ['attr_id'] = $vv['id'];
									$data ['attr_value'] = $values;
									if ($Products_attr->create ( $data )) {
										if(!$Products_attr->where($data)->count()){
											$Products_attr->add ( $data );
											$j++;
										}else{
											$l++;
										}
									}
								}
							}else{
								$data=array();
								$data ['products_id'] = $pro['id'];
								$data ['attr_id'] = $vv['id'];
								$data ['attr_value'] = $vv['values'];

								if ($Products_attr->create ( $data )) {
									if(!$Products_attr->where($data)->count()){
										$Products_attr->add ( $data );
										$j++;
									}else{
										$l++;
									}
								}
							}
						}

						$i++;
					}

				}
			}
			$str='';
			if($l){
				$str.=' , 跳过了 '.$l.' 个已添加的产品属性';
			}
			$this->success('共为 '.$k.' 个类别 '.$i.' 个产品添加了 '.$j.' 个产品属性 '.$str);
		}else{
			$this->error('请选择类别');
		}
	}
	function delProducts(){
		$id=intval($_REQUEST['id']);
		if($id){

			//获得所有子类
			parent::$Model=D('Cate');
			$cate_in_id=implode(',',parent::$Model->getChildren($id,$id));

			if($cate_in_id){

				//获得所有产品
				parent::$Model=D('Products');
				$map['cateid']=array('in',$cate_in_id);
				$count=parent::$Model->where($map)->count();
				$j=$k=$l=0;
				if($count){
					$list=parent::$Model->where($map)->select();

					$products_ids=implode(',',array_map('reset',parent::$Model->field('id')->where($map)->select()));
					if($products_ids){
						//删除产品属性
						parent::$Model=D('Products_attr');
						parent::$Model->del_attrs($products_ids);
					}
					parent::$Model=D('Products_gallery');
					foreach ($list as $v) {
						$v['bigimage']=auto_charset($v['bigimage'],'utf-8','gbk');
						$v['smallimage']=auto_charset($v['smallimage'],'utf-8','gbk');
						if(file_exists($v['bigimage'])){
							$j++;
							unlink($v['bigimage']);
						}
						if(file_exists($v['smallimage'])){
							unlink($v['smallimage']);
						}
						//删除产品相册
						$g=parent::$Model->where(array('pid'=>$v['id']))->find();
						$g['img_url']=auto_charset($v['img_url'],'utf-8','gbk');
						$g['thumb_url']=auto_charset($v['thumb_url'],'utf-8','gbk');
						if(file_exists($v['img_url'])){
							$k++;
							unlink($v['img_url']);
						}
						if(file_exists($v['thumb_url'])){
							unlink($v['thumb_url']);
						}
						parent::$Model->where(array('pid'=>$v['id']))->delete();

						//删除关联产品数据
						parent::$Model=D("Products_related");
						parent::$Model->where(array("products_id"=>$v['id']))->delete();
						$l++;

					}

					//最后删除产品
					parent::$Model=D('Products');
					parent::$Model->where($map)->delete();
					$str="共删除了 ".$count." 个产品,其中删除了".$j."个产品图片 ".$k." 个相关图片 ".$l." 个关联产品!";
					$this->success($str);

				}else{
					$this->error('没有产品数据!');
				}
			}else{
				$this->error('请选择类别!');
			}
		}else{
			$this->error('请选择类别!');
		}
	}

	public function ishot(){
		$map['id']=intval($_REQUEST['id']);
		$ishot=$this->dao->where($map)->getField('ishot');
		if($ishot==1){
			$ishot=0;
			$data['imgurl']=__ROOT__.'/Tpl/default/Public/images/mod_0.gif';
		}else{
			$ishot=1;
			$data['imgurl']=__ROOT__.'/Tpl/default/Public/images/mod_1.gif';
		}
		$this->dao->where($map)->data(array('ishot'=>$ishot))->save();
		$this->ajaxReturn($data,'保存成功',1);

	}

}
?>