<?php
/**
  * @author nanze
  * @link 
  * @todo IP锁定
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-18
*/ 
class IpblockAction extends AdminCommAction{
	public function index() {
		$citys=GetValue("ipblock_city");
		$cityinfo=unserialize($citys);
		$this->cityinfo=$cityinfo;
		$this->citylist=rtrim(implode(',',$cityinfo),',');
		$this->display();
	}
	public function setblock() {
	    $citys=explode(',',$_POST['citys']);
		SetValue('ipblock_city',serialize($citys));
		SetValue('ipblock',$_POST['ipblock']);
		SetValue('ipblock_pwd',$_POST['ipblock_pwd']);
		SetValue('ipblock_lang',$_POST['ipblock_lang']);
		cleanCache ();
		$this->jumpUrl=U('Ipblock/index');
		$this->success('设置成功!');
	}
	public function blockall() {
	    $citys=explode(',','北京,浙江,天津,安徽,上海,福建,莆田,重庆,江西,山东,河南,内蒙古,湖北,新疆,湖南,宁夏,广东,西藏,海南,广西,四川,河北,贵州,山西,云南,辽宁,陕西, 吉林,甘肃,黑龙江,青海,江苏,'.$_POST['city_list']);
		SetValue('ipblock_city',serialize($citys));
		cleanCache ();
		$this->jumpUrl=U('Ipblock/index');
		$this->success('设置成功!');
	}
}	
?>