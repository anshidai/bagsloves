<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-26
*/ 
class MemberIndexAction extends MemberCommAction{
	public function index(){
		$this->redirect ( 'MemberIndex/Orders' );
	}
	public function Logout(){
		//清空会员session
		/*$cartModel=D('Cart');
		$data['session_id']='';
		$cartModel->where ( "uid='".Session::get("memberID")."' or session_id='" . Session::get('sessionID') . "'")->data ($data)->save();*/
		Session::set("memberID",0);
		redirect ( 'MemberIndex-index' );
	}
	public function profav(){
		self::$Model = D ( "Products" );
		$memberInfo=$this->__get('memberInfo');
		$map['id']=array('in',$memberInfo['profav']);
		$count = self::$Model->where ($map)->count ();
		$this->count=$count;
		import ( 'ORG.Util.Page' );
		$page = new Page ( $count, 20 );
		$this->list=self::$Model->where($map)->order("id")->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->show = $page->show ();
		
		$this->display();
		
	}
	public function Addprofav(){
		$memberInfo=$this->__get('memberInfo');
		$id=intval($_REQUEST['id']);
		if(!$memberInfo['profav']) {
			$data['profav']=$id;
		}else {
			$profav = explode(',',$memberInfo['profav']);
			array_push($profav,$id);
			$profav = array_unique($profav);
			if(count($profav) > 50){
				array_shift($profav);
			}
			$data['profav']=implode(',',$profav);
		}
		self::$Model = D ( "Members" );
		self::$Model->data($data)->where("id=".$this->memberID)->save();
		$this->jumpUrl=$_SERVER['HTTP_REFERER' ];
		$this->success("The product has been added to your favorites!");
	}
	public function Delprofav(){
		$memberInfo=$this->__get('memberInfo');
		$id=intval($_REQUEST['id']);
		if(!$memberInfo['profav']) {
			$this->error("The product id does not exist!");
		}else {
			$profav = explode(',',$memberInfo['profav']);
			foreach ($profav as $k=>$v){
				if($id==$v){
					unset($profav[$k]);
				}
			}
			$data['profav']=implode(',',$profav);
		}
		self::$Model = D ( "Members" );
		self::$Model->data($data)->where("id=".$this->memberID)->save();
		$this->jumpUrl=$_SERVER['HTTP_REFERER' ];
		$this->success("The product from your favorites out of the!");

	}
	public function ShippingAddress(){
		self::$Model=D("Shippingaddress");
		$list=self::$Model->where("id=".$this->memberID)->find();
		if($list){
			$dao=D("Region");
			$map['pid']=$list["country"];
			$map['type']=1;
			$this->state=$dao->where($map)->select();

			if($list['isNewsletter']){
				$model=D('Newsletter');
				$this->Newsletter=$model->where(array('memberid'=>$list['id']))->find();
			}
		}
		
		$this->display();
		
	}
	public function doShippingAddress(){
		self::$Model=D("Shippingaddress");
		$list=self::$Model->where("id=".$this->memberID)->find();
		if (self::$Model->create()){
			//邮件订阅
			$model=D('Newsletter');
			if(isset($_POST['isNewsletter']) && $_POST['isNewsletter']==1){
				$data['email']=$_POST['email'];
				$data['addtime']=time();
				if(isset($_POST['id']) && $_POST['id']>0){

					$map['memberid']=$data['memberid']=$_POST['id'];
					if($_POST['Newsletter_id']){
						$map['id']=$_POST['Newsletter_id'];
					}
					$map['_logic']='or';
					if(false == $model->where($map)->save($data)){
						$model->add($data);
					}
				}
			}elseif(isset($_POST['isNewsletter']) && $_POST['isNewsletter']==0 && $_POST['Newsletter_id']){
				$model->where('id='.$_POST['Newsletter_id'])->delete();
			}
			if ($list){
				self::$Model->save();

			}
			else{
				self::$Model->add();
			}
			if($this->isAjax()){
				$this->success('do shippingaddress success');
			}elseif (isset($_SESSION['back'])){
				redirect ($_SESSION['back']);
			}else{
				$this->redirect ( 'MemberIndex/ShippingAddress' );
			}
		}

		else{
			$this->error(self::$Model->getError());
		}
	}

	public function ChangePWD(){
		
		$this->display();
		
	}
	public function doChangePWD(){
		self::$Model = D ( "Members" );
		$list=self::$Model->where("password='".md5($_POST['password'])."' and id=".$this->memberID)->find();
		if (!$list){
			$this->error ( "Password error!");
		}
		else{
			if ($_POST['new_password']==$_POST['re_password']){
				$data['password']=md5($_POST['new_password']);
				self::$Model->where("id=".$this->memberID)->save($data);
				//echo self::$Model->getlastsql();
				$this->success ( "Operation is successful!");
				//$this->redirect ( 'Index/ChangePWD' );
			}
			else{
				$this->error ( "Confirm password error!");
			}
		}

	}
	public function Orders(){
		self::$Model = D ( "Orders" );
		$map['member_id']=$this->memberID;
		$count = self::$Model->where ($map)->count ();
		$this->count=$count;
		import ( 'ORG.Util.Page' );
		$page = new Page ( $count, 20 );
		$this->orderslist=self::$Model->where($map)->order("dateline desc")->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->show = $page->show ();
		
		$this->display();
		
	}
	function ConfirmOrders(){
		$id=$_GET['id'];
		$data['orders_status']=4;
		self::$Model = D ( "Orders" );
		self::$Model->where("id=".$id)->save($data);
		$this->success(L("DO_OK"));
	}
}
?>