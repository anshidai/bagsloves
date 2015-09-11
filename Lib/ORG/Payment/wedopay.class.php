<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-12-21
 */
class wedopay {

	var $fields = array();           // array holds the fields to submit to paypal
	var $form='';					//表单
	var $submit_url;				//提交地址
	function wedopay() {
		$this->submit_url="https://secure.wedopay.net/sslpayment";
	}
	function add_field($field, $value) {

		// adds a key=>value pair to the fields array, which is what will be
		// sent to paypal as POST variables.  If the value is already in the
		// array, it will be overwritten.


		$this->fields ["$field"] = $value;
	}
	function submit_post() {
		echo "<html>";
		echo "<head><title>Processing Payment...</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>\n";
		/*echo "<body onLoad=\"document.forms['pay_form'].submit();\">\n";*/
		echo "<body >\n";
		echo "<center><h2>Please remember your order number, and then click on the button below to make a payment!</h2></center>\n";
		echo "<center><h2>Please wait, your order is being processed and you";
		echo " will be redirected to the payment website.</h2></center>\n";
		echo "<form method=\"post\" name=\"pay_form\" ";
		echo "action=\"" . $this->submit_url . "\">\n";
		foreach ( $this->fields as $name => $value ) {
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}
		echo "<center><br/><br/>If you are not automatically redirected to ";
		echo "Webdopay within 5 seconds...<br/><br/>\n";
		echo "<input type=\"submit\" value=\"Pay by Webdopay\"></center>\n";

		echo "</form>\n";
		echo "<script>function jump(){ document.forms[\"pay_form\"].submit();} setTimeout(\"jump()\", 5000);</script>";
		echo "</body></html>\n";
	}
	/**
	 * 新增
	 */
	function create_form($list) {
		$pname=get_class($this);
		$this_script = "http://{$_SERVER['HTTP_HOST']}";

		$MD5key=GetValue ( $pname . "_Md5Key" );
		$MerNo=GetValue ( $pname . "_MerNo" );
		$BillNo=$list ['sn'];
		$Amount=$list ['orders_total'];
		$OrderDesc=$list ['BuyNote'];
		$Currency="1";
		$Language = "2";
		$ReturnURL=$this_script.U('Payment/wedopay_return');
		$md5src = $MerNo.$BillNo.$Currency.$Amount.$Language.$ReturnURL.$MD5key;		//校验源字符串
		$MD5info = strtoupper(md5($md5src));		//MD5检验结果

		$this->add_field('MerNo',$MerNo);
		$this->add_field('Currency',$Currency);
		$this->add_field('BillNo',$BillNo);
		$this->add_field('Amount',$Amount);
		$this->add_field('ReturnURL',$ReturnURL);
		$this->add_field('Language',$Language);
		$this->add_field('MD5info',$MD5info);
		$this->add_field('Remark','');
		$this->add_field('shippingFirstName',$list ['delivery_firstname']);
		$this->add_field('shippingLastName',$list ['delivery_lastname']);
		$this->add_field('shippingEmail',$list ['member_email']);
		$this->add_field('shippingPhone',$list ['delivery_telephone']);
		$this->add_field('shippingZipcode',$list ['delivery_zip']);
		$this->add_field('shippingAddress',$list ['delivery_address']);
		$this->add_field('shippingCity',$list ['delivery_city']);
		$this->add_field('shippingSstate',$list ['delivery_state']);
		$this->add_field('shippingCountry',$list ['delivery_country']);
		$this->add_field('products','products');


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