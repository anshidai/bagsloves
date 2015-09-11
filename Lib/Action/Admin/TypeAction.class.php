<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-12-1
*/ 
class TypeAction extends AdminCommAction{
	function catelist(){
		self::$Model=D("Type_cate");
		$this->list=self::$Model->order("id")->select();
		$this->display();
	}
	function Insert(){
		self::$Model=D("Type_cate");
		if (self::$Model->create()) {
			self::$Model->add();
			$this->success("添加成功!");
		}
		else{
			$this->error ( self::$Model->getError () );
		}
	}
	function edit() {
		self::$Model = D ( "Type_cate" );
		$list = self::$Model->where ( "id=" . $_GET ['id'] )->find ();
		if ($list) {
			$this->list=$list;
			$this->display();		
		}
		else{
			$this->error ( '没有数据！' );
		}
	
	}
	function Update() {
		self::$Model = D ( "Type_cate" );
		if (self::$Model->create ()) {
			self::$Model->save ();
			$this->success ( "修改成功!" );
		} else {
			$this->error ( self::$Model->getError () );
		}
	}
	function attrlist(){
		$this->typeid=$_GET['id'];
		self::$Model=D("Type_attr");
		$this->list=self::$Model->where("type_id=".$_GET['id'])->order("sort desc")->select();
		$this->display();
	}
	function attradd(){
		$this->typeid=$_GET['id'];
		$this->display();
	}
	function attrInsert() {
		self::$Model = D ( "Type_attr" );
		if (self::$Model->create ()) {
			self::$Model->add ();
			$this->success ( "添加成功!" );
		} else {
			$this->error ( self::$Model->getError () );
		}
	}
	function Delete() {
		$id=$_REQUEST ['id'];
		if($id){
			$map ['id'] = array('in',$id);
			self::$Model = D ( "Type_cate" );
			if(self::$Model->where ( $map )->count ()){
				self::$Model->where ( $map )->delete ();
			}
		}
		$this->success ( "删除成功！" );
	}
	function attrDelete() {
		$id=$_REQUEST ['id'];
		if($id){
			$map ['id'] = array('in',$id);
			self::$Model = D ( "Type_attr" );
			if(self::$Model->where ( $map )->count ()){
				self::$Model->where ( $map )->delete ();
			}
		}
		$this->success ( "删除成功！" );
	}
	function arrtedit() {
		
		self::$Model = D ( "Type_attr" );
		$list = self::$Model->where ( "id=" . $_GET ['id'] )->find ();
		if ($list) {
			$this->list = $list;
			$this->display ();
		} else {
			$this->error ( '没有数据！' );
		}
		
	}
	function attrUpdate() {
		self::$Model = D ( "Type_attr" );
		if (self::$Model->create ()) {
			self::$Model->save ();
			//echo self::$Model->getlastsql();
			$this->success ( "修改成功!" );
		} else {
			$this->error ( self::$Model->getError () );
		}
	}
}
?>