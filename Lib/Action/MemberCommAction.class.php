<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-26
*/ 
class MemberCommAction extends Action{
	protected static $Model=null;	//数据Model
	var $sessionID, $memberID;
	protected $theme;
	function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		//获取国家列表
		self::$Model=D("Region");
		$this->Countries=self::$Model->where("type=0")->order('name asc')->select();
		//主题
		if($this->theme=GetValue('theme')){

		}else{
			$this->theme='default';
		}
		//货币
		L('_OPERATION_SUCCESS_',"Operation Success");
		L('_OPERATION_FAIL_','Operation Fail');
		//$this->currencies=get_currencies_arr(); 
		//生产一个唯一的session id
		$this->sessionID = Session::get ( 'sessionID' );
		if (! $this->sessionID) {
			$this->sessionID = md5 ( uniqid ( rand () ) );
			Session::set ( 'sessionID', $this->sessionID );
		}
		
		//读取用户id
		$this->memberID = Session::get ( 'memberID' );
		if (! $this->memberID) {
			$this->memberID = 0;
			Session::set('back',$_SERVER['REQUEST_URI']);
			$this->redirect("Public/Login");
		} else {

			//读取用户信息
			$this->mid=$this->memberID;
			self::$Model = D ( "Members" );
			$this->member_Info = $this->memberInfo = self::$Model->where ( "id=" . $this->memberID )->find ();
			self::$Model = D ( "Shippingaddress" );
			$this->memberShippingAddress = self::$Model->where ( "id=" . $this->memberID )->find ();

		}
		if(F('Common_Cache')){
			$this->assign(F('Common_Cache'));
		}

	}


}
?>