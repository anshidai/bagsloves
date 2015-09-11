<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-12-21
 */
class _95epay {
	/**
	 * 必须
	 *
	 */

	var $fields = array();           // array holds the fields to submit to paypal
	var $form='';					//表单
	var $submit_url;				//提交地址

	function _95epay() {
		$this->submit_url="https://www.95epay.com/PayReduceRequestAction.action";
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
		echo " will be redirected to the 95epay website.</h2></center>\n";
		echo "<form method=\"post\" name=\"pay_form\" ";
		echo "action=\"" . $this->submit_url . "\">\n";
		foreach ( $this->fields as $name => $value ) {
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}
		echo "<center><br/><br/>If you are not automatically redirected to ";
		echo "95epay within 5 seconds...<br/><br/>\n";
		echo "<input type=\"submit\" value=\"Pay by 95epay\"></center>\n";

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

		$this->add_field('MerNo',GetValue ( $pname . "_no" ));//商号
		$MD5key=GetValue ( $pname . "_key" );//私钥
		$this->add_field('Currency',1);
		$this->add_field('BillNo',$list ['sn']);
		$this->add_field('Amount',$list ['orders_total']);
		$this->add_field('ReturnURL',$this_script.U('Payment/return_95pay'));
		$this->add_field('Language','en');

		$this->add_field('MD5info',strtoupper(md5($this->fields['MerNo'].$this->fields['BillNo'].$this->fields['Currency'].$this->fields['Amount'].$this->fields['Language'].$this->fields['ReturnURL'].$MD5key)));
		$this->add_field('Remark',$list['BuyNote']);
		$this->add_field('MerWebsite',$this_script);




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