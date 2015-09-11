<?php
class IpblockAction extends CommAction {
	public $ipdatafile;
	function _initialize()
	{
		header("Content-Type:text/html; charset=utf-8");
		import('ORG.Net.IpLocation');
		$this->ipdatafile=__ROOT__.'./Public/ipdata/ip.dat';
		/*if($_COOKIE["ipblock"] ==0 || GetValue('ipblock') == 0 || GetValue('ipblock_pwd') == '') {
			$this->redirect('Index/index');
		}*/
	}
	public function index(){
		if(GetValue("ipblock_lang")==1 && substr_count($_SERVER["HTTP_ACCEPT_LANGUAGE"],'zh-cn')>0){
			$this->redirect('Ipblock/login');
		}
		$ipLocatoin=IpLocation::getInstance();
		$result=$ipLocatoin->init($this->ipdatafile)->getcity($ipLocatoin->get_client_ip());
		$address=$result['country'].$result['area'];
		$ip=$result['ip'];
		$city=GetValue("ipblock_city");
		$cityinfo=unserialize($city);
		//$cityinfo[]='本机地址';
		foreach ($cityinfo as $k=>$v){
			if (substr_count($address, $v)>0 || substr_count($ip, $v)>0) {
				$this->redirect('Ipblock/login');
			}
		}
		setcookie("ipblock",0,time()+3600*6,"/");//不在范围
	}

	public function login(){
		 $this->view->display ('Ipblock-login');
	}
	public function dologin(){
		
		if(GetValue('ipblock_pwd')==$_POST['password']) {
			setcookie("ipblock",0,time()+3600*6,"/");
			$this->redirect('Index/index');
		}
		else {
			setcookie("ipblock",1,time()+3600*6,"/");
			$this->redirect('Ipblock/login');
		}

	}



}
?>