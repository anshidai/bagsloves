<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-26
*/ 
class RpcCommAction extends Action{
	protected static $Model=null;	//数据Model
	protected static $Server = null;

	function _initialize() {
		header("Content-Type:text/html; charset=utf-8");

	}
	public function index() {
		Vendor ( 'phpRPC.phprpc_server' );
		self::$Server = new PHPRPC_Server ();
		$moudel_name = MODULE_NAME."Action";
		$methods=get_class_methods($moudel_name);
		self::$Server->add($methods,new $moudel_name);
		self::$Server->start();
	}
	public function CheckSession($sessionid){
		$dao=D("Session");
		$list=$dao->GetSession($sessionid);
		if (!$list) {
			$res["code"]="201";
			$res["message"]="NO Login!";
			return $res;
		}
	}
	
	//用户登录
	public function login($username, $password) {
		if(empty($username) || empty($password)){
			$res ["code"] = "101";
			$res ["msg"] = "参数不全！";
			return $res;
		}
		$map ['account'] = $username;
		$map ['status'] = array ('gt', 0 );
		import ( '@.ORG.RBAC' );
		$authInfo = RBAC::authenticate ( $map );
		if (! $authInfo) {
			$res ["code"] = "101"; //
			$res ["msg"] = "登录错误：可能这个账户已被禁用！";
			return $res;
		}
		if ($authInfo ['password'] != md5 ( $password )) {
			$res ["code"] = "102"; //
			$res ["msg"] = "登录失败：密码错误！";
			return $res;
		}
		$model = D ( 'User' );
		$model->find ( $authInfo ['id'] );
		$model->last_login_time = time ();
		$model->last_login_ip = get_client_ip ();
		$model->save ();
		//保存Session

		$session_id=md5 ( uniqid ( rand () ) );
		$dao=D("Session");
		$list=$dao->where("adminid=".$authInfo ['id'])->find();
		if ($list){
			$session_id=$list["sessionid"];
			$data["dateline"]=time();
			$dao->where("adminid=".$authInfo ['id'])->save($data);
		}
		else{
			$dao->AddSession($session_id,$authInfo ['id']);
		}
		$res["code"]=0;
		$res["sessionid"]=$session_id;
		$res["adminid"]=$authInfo ['id'];
		return $res;
	}

	//获取用户信息
	public function getAdminInfo($sessionid){
		if(empty($sessionid)){
			$res ["code"] = "101";
			$res ["msg"] = "参数不全！";
			return $res;
		}
		$this->CheckSession($sessionid);
		
		$model = D ( 'User' );
		$list=$this->GetSession($sessionid);
		$list=$model->find ( $list['adminid'] );
		if($list){
			$res["code"]=0;
			$res["msg"]='';
			$res["userinfo"]=$list;
		}else{
			$res["code"]=1;
			$res["msg"]='找不到该记录';
		}
		return $res;
	}
	
	function _list($map = null,$firstRow,$firstRow) {
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
			$this->sort="id desc";
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
		$list = $this->dao->where ( $map )->order ( $this->sort )->limit ( $firstRow . ',' . $firstRow )->select ();
		if($list){
			$res['show'] = $page->show ();
			$res['list'] = $list;
			$res['msg'] = '';
			$res['code']=0;
			return $res;
		}else{
			$res['msg'] = '没有记录';
			$res['code']=1;
			return $res;
		}
	}
}




?>