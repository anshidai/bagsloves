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
		$this->sessionID = Cookie::get ( 'sessionID' );
		if (! $this->sessionID) {
			$this->sessionID = create_session_id();
			Cookie::set('sessionID', $this->sessionID);
		}
		
		//读取用户id
		$auth_cookie = Cookie::get('auth');
		if(empty($auth_cookie)) {
			$this->memberID = 0;
			Session::set('back',$_SERVER['REQUEST_URI']);
		}else {
			$auth = daddslashes(explode("\t", authcode($auth_cookie, 'DECODE', C('AUTHKEY'))));
			list($member_id, $member_email) = empty($auth) || count($auth) < 2 ? array('', '') : $auth;
			if(!empty($member_id)) {
				$this->memberID = $member_id;
			}
		}
		Cookie::set('memberID', $this->memberID);
		
		if($this->memberID) {

			//读取用户信息
			$this->mid=$this->memberID;
			self::$Model = D ( "Members" );
			$this->member_Info = $this->memberInfo = self::$Model->where ( "id=" . $this->memberID )->find ();
			self::$Model = D ( "Shippingaddress" );
			$this->memberShippingAddress = self::$Model->where ( "id=" . $this->memberID )->find ();
		}
		
		
		//购物车商品
		self::$Model = D ( "Cart" );
		$this->cart_list = self::$Model->cart_list($this->sessionID);
		$this->item_count = itemCount();
		$this->total_count = TotalCount();
		
		if(F('Common_Cache')){
			$this->assign(F('Common_Cache'));
		}

	}


}
?>