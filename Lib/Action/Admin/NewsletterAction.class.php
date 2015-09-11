<?php
/**
  * @author nanze
  * @link 
  * @todo 邮件订阅
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-18
*/ 
class NewsletterAction extends AdminCommAction{
	public function index(){

		$this->display();
	}
	public function doSend(){
		$sendTo=array();//收件人
		if(empty($_POST['title'])){
			$this->error('请填写邮件标题');
		}
		if(empty($_POST['content'])){
			$this->error('请填写邮件正文');
		}
		switch ($_POST['type']){
			case null://
			$this->error('请选择邮件类型');
			break;
			case 'isNewsletter'://所有订阅者
			$model=D('Newsletter');
			$list=$model->select();
			foreach ($list as $k=>$v){
					$sendTo[]=$v['email'];
			}
			break;
			case 'order_email'://订单中的邮箱地址
			self::$Model=D('Orders');
			$list=self::$Model->field('delivery_email')->select();
			foreach ($list as $k=>$v){
				$sendTo[]=$v['delivery_email'];
			}
			break;
			case 'member_email'://最近三个月的会员
			self::$Model=D('Members');
			$map['createdate']=array('egt',strtotime('-3 month'));
			$list=self::$Model->field('createdate,email')->where($map)->select();
			foreach ($list as $k=>$v){
				$sendTo[]=$v['email'];
			}
			break;
			case 'member_login'://最近三个月登录过的会员
			self::$Model=D('Members');
			$map['lastlogindate']=array('egt',strtotime('-3 month'));
			$list=self::$Model->field('lastlogindate,email')->where($map)->select();
			foreach ($list as $k=>$v){
				$sendTo[]=$v['email'];
			}
			break;
			case 'all'://所有会员
			default:
				self::$Model=D('Members');
				$list=self::$Model->field('email')->select();
				foreach ($list as $k=>$v){
					$sendTo[]=$v['email'];
				}
		}
		$sendTo=array_filter($sendTo);
		sendmail($sendTo,$_POST['title'],$_POST['content']);
		$this->success('发送成功!');
	}
}
?>