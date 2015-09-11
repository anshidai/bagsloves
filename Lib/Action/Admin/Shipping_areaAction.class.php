<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2011-3-15
*/ 
class Shipping_areaAction extends AdminCommAction{
	function index(){
		$list=$this->dao->where("shipping_id=".$_GET["id"])->select();
		foreach ($list as $k=>$v){
			$config=unserialize($v['config']);

			foreach ((array)$config as $vo){
				$list[$k]['area'][]=get_region_name($vo);
			}
			$list[$k]['area']=implode(',',$list[$k]['area']);
		}
		$this->list=$list;

		$this->id=$_GET["id"];
		$this->display();
	}
	function edit() {
		$map ['id'] = $_GET ['id'];
		$list = $this->dao->where ( $map )->find ();
		if ($list) {
			$map1['type']=0;
			$this->country=D('Region')->where($map1)->select();
			$this->config1=unserialize($list["config"]);
			$this->list = $list;
			$this->display ();
		} else {
			$this->error ( '没有数据！' );
		}
	}
	function add(){
		$this->id=$_GET['id'];
		$map['type']=0;
		$this->country=D('Region')->where($map)->select();
		parent::add();
	}
	function Insert(){
		$_POST["config"]=serialize($_POST["config"]);
		parent::Insert();
	}
	function Update(){
		$_POST["config"]=serialize($_POST["config"]);
		$map['type']=0;
		$this->country=D('Region')->where($map)->select();
		parent::Update ();
	}
	function area(){
		$dao=D('Region');
		$Region=$dao->select();
		foreach ($Region as $k=>$v){
			$Region[$k]['type_name']=$this->type_name($dao,'type',$v['id']);
			$Region[$k]['pid_name']=$this->type_name($dao,'pid,name',$v['id']);
		}
		$this->Region=$Region;
		$this->display ();
	}
	function editarea(){
		$dao=D('Region');
		$Region=$dao->select();
		foreach ($Region as $k=>$v){
			$Region[$k]['type_name']=$this->type_name($dao,'type',$v['id']);
			$Region[$k]['pid_name']=$this->type_name($dao,'pid,name',$v['id']);
		}
		$this->Region=$Region;

		$this->volist=$dao->find($_GET['id']);
		$this->display ();
	}
	function type_name($dao,$type,$id){

		$list=$dao->field($type)->find($id);
		switch ($type){
			case 'type':
				switch ($list[$type]){
					case '0':
						return '国家';
					case '1':
						return '省/州';
					case '2':
						return '城市';
				}
			case 'pid,name':
				$list=$dao->field($type)->where(array('id'=>$list['pid']))->find();
				if($list['name']){
					return $list['name'];
				}else{
					return '最上级';
				}
		}
		return '';
	}
	function insertArea(){
		$dao=D('Region');
		if ($dao->create ()) {
			$id = $dao->add ();
			$this->success ( '添加成功！' );
		} else {
			$this->error ( $dao->getError () );
		}
	}

	function saveArea(){
		$dao=D('Region');
		if ($dao->create ()) {
			$list = $dao->save ();
			if ($list !== false) {
				$this->success ( '数据更新成功！' );
			} else {
				$this->error ( "没有更新任何数据!" );
			}
		} else {
			$this->error ( $dao->getError () );
		}
	}
	function delArea() {
		$dao=D('Region');
		$id=$_REQUEST ['id'];
		if($id){
			$map ['id'] = array('in',$id);
			if($dao->where ( $map )->count ()){
				$dao->where ( $map )->delete ();
			}
		}
		$this->success ( "删除成功！" );
	}
}
?>