<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-12-21
 */
class payeasy3d {
	var $fields = array();           // array holds the fields to submit to paypal
	var $form='';					//表单
	var $submit_url;				//提交地址
	function __construct() {
		$this->submit_url="http://pay.beijing.com.cn/prs/e_user_payment.checkit";
	}
	function add_field($field, $value) {

		// adds a key=>value pair to the fields array, which is what will be
		// sent to paypal as POST variables.  If the value is already in the
		// array, it will be overwritten.


		$this->fields ["$field"] = $value;
	}
	function other() {

	}
	/**
	 * 新增
	 */
	function create_form($list) {
		$pname=get_class($this);
		$this_script = "http://{$_SERVER['HTTP_HOST']}";

		$this->add_field('v_mid',GetValue ( $pname . "_id" ));//商户编号
		$this->add_field('v_oid',date('Ymd').'-'.GetValue ( $pname . "_id" ).'-'.$list ['sn']);//订单编号
		$this->add_field('v_rcvname',$list['delivery_firstname']." ".$list['delivery_lastname']);//收货人姓名
		$this->add_field('v_rcvaddr',$list['delivery_address']);//收货人地址
		$this->add_field('v_rcvtel',$list['delivery_telephone']);//收货人电话
		$this->add_field('v_rcvpost',$list['delivery_zip']);//收货人邮编
		$this->add_field('v_amount',$list ['orders_total']);//订单总金额
		//$this->add_field('v_language','en');//订单语言
		$this->add_field('v_ymd',date('Ymd',$list['dateline']));//订单产生日期
		$this->add_field('v_orderstatus',1);//配货状态
		$this->add_field('v_ordername',$list['delivery_firstname']." ".$list['delivery_lastname']);//订货人姓名
		$this->add_field('v_moneytype',1);//币种,0为人民币,1为美元，2为欧元，3为英镑，4为日元，5为韩元，6为澳大利亚元
		$this->add_field('v_url',$this_script.U('Payment/payeasy_return'));//支付动作完成后返回到该url，支付结果以GET方式发送
		$this->add_field('v_md5info',bin2hex(mhash(MHASH_MD5,"1".date('Ymd',$list['dateline']).$list ['orders_total'].$list['delivery_firstname']." ".$list['delivery_lastname'].date('Ymd').'-'.GetValue ( $pname . "_id" ).'-'.$list ['sn'].GetValue ( $pname . "_id" ).$this_script.U('Payment/payeasy_return'),GetValue ( $pname . "_key" ))));//订单数字指纹
		$this->add_field('v_shipstreet',$list['delivery_address']);//送货街道地址
		$this->add_field('v_shipcity',$list['delivery_city']);//送货城市
		//$this->add_field('v_shipstate',$_POST['']);//送货省/州
		$this->add_field('v_shippost',$list['delivery_zip']);//送货邮编
		//$this->add_field('v_shipcountry','840');//送货国家
		$this->add_field('v_shipphone',$list['delivery_telephone']);//送货电话
		$this->add_field('v_shipemail',$list['delivery_email']);//送货Email



		$this->form.= "<form method=\"post\" name=\"pay_form\" ";
		$this->form.= "action=\"".$this->submit_url."\">\n";
		foreach ($this->fields as $name => $value) {
			$this->form.= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}

		$this->form.= "</form>\n";
		if(GetValue($list['payment_module_code'].'_autosubmit')==1){
			$delay=GetValue($list['payment_module_code'].'_delay');
			$delay=$delay?$delay:5;
			$this->form.=$this->submit($delay);//是否自动提交,延迟5秒
		}
		return $this->form;
	}
	function submit($delay){
		if(!$delay){
			$this->form.= "<script>function jump(){ document.forms[\"pay_form\"].submit();} jump();</script>";
		}else{
			$delay*=1000;
			$this->form.= "<script>function jump(){ document.forms[\"pay_form\"].submit();} setTimeout('jump()',$delay);</script>";
		}
	}
}
?>