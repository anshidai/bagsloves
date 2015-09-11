<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-16
 */
class WebcallAction extends AdminCommAction {

	function index(){
		if($this->isPost()){

			SetValue('365webcall_name',rawurlencode($_POST['365webcall_name']));
			SetValue('365webcall_email',$_POST['365webcall_email']);
			SetValue('365webcall_accountid','0594trade');
			SetValue('365webcall_status',$_POST['365webcall_status']);
			SetValue('365webcall_password',rawurlencode($_POST['365webcall_password']));
			SetValue('365webcall_url',$_SERVER['HTTP_HOST']);
			cleanCache();
			$this->success('操作成功！');
		}else{
			$this->name=GetValue('365webcall_name');
			$this->email=GetValue('365webcall_email');
			$this->accountid=GetValue('365webcall_accountid');
			$this->pwd=GetValue('365webcall_password');
			$this->url=GetValue('365webcall_url');

			$this->name2=rawurldecode(GetValue('365webcall_name'));
			$this->email2=GetValue('365webcall_email');
			$this->accountid2=GetValue('365webcall_accountid');
			$this->pwd2=rawurldecode(GetValue('365webcall_accountid'));


			$this->display();
		}
	}
	function opencall(){
		$url="http://p.365webcall.com/invite.aspx?".http_build_query($_GET);
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$content=curl_exec($ch);
		header("Content-Type:text/html; charset=utf-8"); 
		if($content>0){
			echo "<script>alert('开通成功!')</script>";
		}else{
			echo "<script>alert('开通失败!')</script>";
		}
		die("<script type=\"text/javascript\">window.location='".U('Webcall/index')."';</script>");
		  
	}
}
?>