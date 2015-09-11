<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-12-21
 */
class gspay {
	/**
	 * 必须
	 *
	 */

	var $fields = array();           // array holds the fields to submit to paypal
	var $form='';					//表单
	var $submit_url;				//提交地址

	function gspay() {
		$this->submit_url="https://secure.redirect2pay.com/payment/pay.php";
		//$this->submit_url="http://www.aodas.com/".U('Payment/gspay_c');
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
		echo "<body onLoad=\"document.forms['pay_form'].submit();\">\n";
		echo "<body >\n";
		echo "<center><h2>Please remember your order number ".$this->sn.", and then click on the button below to make a payment!</h2></center>\n";
		echo "<center><h2>Please wait, your order is being processed and you";
		echo " will be redirected to the gspay website.</h2></center>\n";
		echo "<form method=\"post\" name=\"pay_form\" ";
		echo "action=\"" . $this->submit_url . "\">\n";
		foreach ( $this->fields as $name => $value ) {
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}
		echo "<center><br/><br/>If you are not automatically redirected to ";
		echo "gspay within 5 seconds...<br/><br/>\n";
		echo "<input type=\"submit\" value=\"Pay by Gspay\"></center>\n";

		echo "</form>\n";
		echo "<script>function jump(){ document.forms[\"pay_form\"].submit();} setTimeout(\"jump()\", 5000);</script>";
		echo "</body></html>\n";
	}
	function submit_post_c() {
		echo "<html>\n";
		echo "<head><title>Processing Payment...</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>\n";
		echo "<body onLoad=\"document.forms['paypal_form'].submit();\">\n";
		echo "<form method=\"post\" name=\"paypal_form\" ";
		echo "action=\"".$this->submit_url."\">\n";
		foreach ($this->fields as $name => $value) {
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}
		echo "</form>\n";
		//  echo "<script>function jump(){ document.forms[\"paypal_form\"].submit();} jump();</script>";
		echo "</body></html>\n";
	}
	/**
	 * 新增
	 */
	function create_form($list) {
		$pname=get_class($this);
		$this_script = "http://{$_SERVER['HTTP_HOST']}";
		
		$this->add_field('siteID',GetValue ( $pname . "_Site_Id" ));
		$this->add_field('orderID',$list ['sn']);
		$this->add_field('Amount[1]',$list ['orders_total']);
		$this->add_field('ApproveURL',$this_script);
		$this->add_field('Qty[1]',1);
		$this->add_field('OrderDescription[1]',"The Order's Sn is".$list ['sn']);
		$this->add_field('customerFullName',$list['member_firstname']." ".$list['member_lastname']);
		$this->add_field('customerAddress',$list['member_address']);
		$this->add_field('customerCity',$list['member_city']);
		$this->add_field('customerZip',$list['member_zip']);
		$this->add_field('customerCountry',$list['member_country']);
		$this->add_field('customerEmail',$list['member_email']);
		$this->add_field('customerPhone',$list['member_telephone']);
		//$this->add_field('TransactionMode',"test");
		$this->add_field('ApproveURL',$this_script.U('Payment/gspay_success'));
		$this->add_field('DeclineURL',$this_script.U('Payment/gspay_error'));
		//$this->add_field('returnUrl',$this_script.U('Payment/gspay_return'));


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