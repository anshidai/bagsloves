<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class TemplatesAction extends AdminCommAction{
	public static $templates=array(
	array("AdminPublic-adminlogin.html","后台登录界面"),
	array("Ajax-get_shipping_list.html","js获取快递列表"),
	array("Ajax-get_total_amount.html","js获得总价"),
	array("Ajax-join.html","js弹出注册"),
	array("Ajax-login.html","js弹出登录"),
	array("Ajax-shippingaddress.html","js收货地址"),
	array("Article-Article_list.html","文章列表页"),
	array("Article-index.html","文章内容页"),
	array("Cart-checked_pment.html","购物车确认付款页"),
	array("Cart-disp.html","购物车产品列表页"),
	array("Cart-payment.html","购物车最终付款页面"),
	array("Down-index.html","下载列表页"),
	array("Empty-Article.html","单页文章页"),
	array("Empty-cate.html","产品分类列表页"),
	array("Empty-contact_us.html","联系我们"),
	array("Empty-disp.html","产品内页"),
	array("Empty-disp_lot.html","产品内页（批发）"),
	array("Empty-FAQs.html","常见问题列表页"),
	array("Empty-reviews.html","产品评论列表页"),
	array("Empty-sitemap.html","网站地图"),
	array("Index-index.html","首页"),
	array("Ipblock-login.html","IP限制访问"),
	array("MailTpl-checkout.html","邮件-订单信息"),
	array("MailTpl-forgotpwd.html","邮件-忘记密码"),
	array("MailTpl-orderstatus.html","邮件-订单状态改变"),
	array("Member-left.html","会员中心左侧菜单"),
	array("MemberIndex-ChangePWD.html","会员中心修改密码"),
	array("MemberIndex-Orders.html","会员中心订单列表"),
	array("MemberIndex-profav.html","会员中心产品收藏"),
	array("MemberIndex-ShippingAddress.html","会员中心收货地址管理"),
	array("MemberPublic-disporders.html","会员-查看订单信息页"),
	array("MemberPublic-ForgotPWD.html","会员-忘记密码"),
	array("MemberPublic-Join.html","会员-注册页"),
	array("MemberPublic-Login.html","会员-登录页"),
	array("Pro-guestbook.html","会员-留言板"),
	array("Pro-index.html","所有产品列表页"),
	array("Pro-tell_a_friend.html","产品通过邮件分享给朋友"),
	array("Pro-write_a_review.html","对产品填写评论"),
	array("Public-404.html","找不到页面"),
	array("Public-banner.html","FLASH展示横幅"),
	array("Public-floatmenu.html","产品类别水平弹出式菜单"),
	array("Public-footer.html","底部公共页"),
	array("Public-header.html","头部公共页"),
	array("Public-left.html","左边公共页"),
	array("Public-menu.html","产品类别垂直折叠菜单"),
	array("Public-success.html","操作提示页"),
	array("Search-index.html","查找产品"),
	);
	function index(){
		$this->temp_dir='./Tpl/Home/'.THEME_NAME.'/';
		$this->real_temp_dir="http://".$_SERVER['HTTP_HOST'].'/'.trim(__ROOT__,'/').'/Tpl/Home/'.THEME_NAME.'/';
		$this->list=self::$templates;
		$this->display();
	}
	function edit(){
		$this->file=$file='./Tpl/Home/'.THEME_NAME.'/'.base64_decode($this->_get('file'));
		if(file_exists($file)){
			$this->content=file_get_contents($file);
			$this->display();
		}else{
			$this->error("模板不存在");
		}
	}
	public function Update() {
		if($_POST['content']){
			$file='./Tpl/Home/'.THEME_NAME.'/'.base64_decode($_POST['file']);
			if(file_exists($file)){
				if(file_put_contents($file,$_POST['content'])){
					$this->success("保存成功");
				}else{
					$this->error("保存失败");
				}
			}else{
				$this->error("文件不存在");
			}
		}else{
			$this->redirect('Templates/index');
		}
	}
}
?>