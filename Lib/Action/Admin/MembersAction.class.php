<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-29
*/ 
class MembersAction extends AdminCommAction{
	function memberslist(){	
		$Map=array();
		if(!empty($_REQUEST['email'])){
			$Map['email']=array('like','%'.$_REQUEST['email'].'%');
		}
		$this->sort="id desc";
		$this->_list($Map);
		$this->display ();
	}
	function Delete(){
		$map ['id'] = $_GET ['id'];
		$dao=D("Shippingaddress");
		$dao->where($map)->delete();
		parent::Delete();
	}
	function edit(){
		$map ['id'] = $_GET ['id'];
		$dao=D("Shippingaddress");
		$this->slist=$dao->where($map)->find();
		parent::edit();
	}
	function Update(){
		self::$Model=D("Shippingaddress");
		$list=self::$Model->where("id=".$_POST['id'])->find();
		if ($list){
			if (self::$Model->create()){
				self::$Model->save();
				parent::Update();
			}
			else{
				$this->error ( "没有更新任何数据!" );
			}
		}
		else{
		if (self::$Model->create()){
				self::$Model->add();
				parent::Update();
			}
			else{
				$this->error ( "没有更新任何数据!" );
			}
		}
	}
	function savePWD(){
		$data['password']=md5($_POST['newpassword']);
		self::$Model=D("Members");
		self::$Model->where("id=".$_POST['id'])->save($data);
		$this->success ( "操作成功!" );
	}
	//=====================3.10添加函数========================================//
	function groupslist(){
		self::$Model=D("Members_group");
		$this->list=self::$Model->order("minpoints")->select();
		$this->display();
	}
	function groupsadd() {
		self::$Model = D ( "Members_group" );
		if (self::$Model->create ()) {
			self::$Model->add ();
			$this->success("添加数据成功！");
		} else {
			$this->error ( "没有添加任何数据!" );
		}
	}
	function groupsdel(){
		$map ['id'] = $_GET ['id'];
		self::$Model = D ( "Members_group" );
		self::$Model->where($map)->delete();
		parent::Delete();
	}
	function groupsedit(){
		$map ['id'] = $_GET ['id'];
		self::$Model = D ( "Members_group" );
		$this->list=self::$Model->order("minpoints")->select();
		$this->volist=self::$Model->where($map)->find();
		$this->display();
	}
	function groupsupdate() {
		self::$Model = D ( "Members_group" );
		if (self::$Model->create ()) {
			self::$Model->save ();
			$this->success("更新数据成功！");
		} else {
			$this->error ( "没有更新任何数据!" );
		}
	}
	function points(){
		
		if (empty($_POST["email"])){
			$this->point=0;
		}
		else{
			self::$Model = D ( "Members" );
			$map["email"]=($_POST["email"]);
			$list=self::$Model->where($map)->find();		
			if ($list){
				$this->group=get_members_group($list["id"]);
				$this->point=$list["points"];
				$this->email=$list["email"];
				
			}
			else{
				$this->error("没有找到该会员！");
			}
		}
		$this->display();
	}
	function addpoints(){
		self::$Model = D ( "Members" );
		$map ["email"] = ($_POST ["email"]);
		$list = self::$Model->where ( $map )->find ();
		if ($list) {
			if ($list["points"]+intval($_POST["points"])>0){
				$data["points"]=$list["points"]+intval($_POST["points"]);
			}
			else{
				$data["points"]=0;
			}			
			self::$Model->where($map)->save($data);
			$this->success("更新会员积分成功！");
		
		} else {
			$this->error ( "没有找到该会员！" );
		}
	}
	//=====================3.10添加函数========================================//
	
	function discountlist(){
		self::$Model=D("Discount");
		$this->list=self::$Model->order("minmoney")->select();
		$this->display();
	}
	function discountadd() {
		self::$Model = D ( "Discount" );
		if (self::$Model->create ()) {
			self::$Model->add ();
			$this->success("添加数据成功！");
		} else {
			$this->error ( "没有添加任何数据!" );
		}
	}
	function discountdel(){
		$map ['id'] = $_GET ['id'];
		self::$Model = D ( "Discount" );
		self::$Model->where($map)->delete();
		parent::Delete();
	}
	function discountedit(){
		$map ['id'] = $_GET ['id'];
		self::$Model = D ( "Discount" );
		$this->list=self::$Model->order("minmoney")->select();
		$this->volist=self::$Model->where($map)->find();
		$this->display();
	}
	function discountupdate() {
		self::$Model = D ( "Discount" );
		if (self::$Model->create ()) {
			self::$Model->save ();
			$this->success("更新数据成功！");
		} else {
			$this->error ( "没有更新任何数据!" );
		}
	}
}
?>