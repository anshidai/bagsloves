<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-12-21
 */
class ips {
	var $fields = array();           // array holds the fields to submit to paypal
	var $form='';					//表单
	var $submit_url;				//提交地址
	function ips() {
		$this->submit_url="https://pay.ips.com.cn/icpay/customization/newforeigntrade/payment.aspx";
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
		echo "<center><h2>Please remember your order number ".$this->sn.", and then click on the button below to make a payment!</h2></center>\n";
		echo "<center><h2>Please wait, your order is being processed and you";
		echo " will be redirected to the payment website.</h2></center>\n";
		echo "<form method=\"post\" name=\"pay_form\" ";
		echo "action=\"" . $this->submit_url . "\">\n";
		foreach ( $this->fields as $name => $value ) {
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}
		echo "<center><br/><br/>If you are not automatically redirected to ";
		echo "Payment within 5 seconds...<br/><br/>\n";
		echo "<input type=\"submit\" value=\"Pay by IPS\"></center>\n";

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

		$Version = '2.0.0';
		$pMerchantCode = trim(GetValue ( $pname . "_mer" ));
		$pMerchantKey = trim(GetValue ( $pname . "_key" ));

		$pMerchantTransactionTime = date('YmdHis');
		$pMerchantOrderNum = getOrderSN();
		$pLanguage='EN';
		$pOrderCurrency='CNY';
		$pOrderAmount=number_format($list ['orders_total']*GetValue ( $pname . "_rate" ), 2, '.', '');
		$pDisplayAmount='$'.$list ['orders_total'];

		$pProductName="Products";
		$pProductDescription = '';
		$pAttach = '';
		$pSuccessReturnUrl=$this_script;
		$pS2SReturnUrl=$this_script.U('Index/Index');
		$pResHashArithmetic=12;
		$pResType=1;
		$pEnableFraudGuard=1;
		$pICPayReq = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><IPSReqRoot><ICPay><Version><![CDATA[$Version]]></Version><StandardPaymentReq><pMerchantOrderNum><![CDATA[$pMerchantOrderNum]]></pMerchantOrderNum><pOrderAmount><![CDATA[$pOrderAmount]]></pOrderAmount><pDisplayAmount><![CDATA[$pDisplayAmount]]></pDisplayAmount><pMerchantTransactionTime><![CDATA[$pMerchantTransactionTime]]></pMerchantTransactionTime><pOrderCurrency><![CDATA[$pOrderCurrency]]></pOrderCurrency><pLanguage><![CDATA[$pLanguage]]></pLanguage><pSuccessReturnUrl><![CDATA[$pSuccessReturnUrl]]></pSuccessReturnUrl><pFailReturnUrl><![CDATA[$pFailReturnUrl]]></pFailReturnUrl><pErrorReturnUrl><![CDATA[$pErrorReturnUrl]]></pErrorReturnUrl><pS2SReturnUrl><![CDATA[$pS2SReturnUrl]]></pS2SReturnUrl><pResType><![CDATA[$pResType]]></pResType><pResHashArithmetic><![CDATA[$pResHashArithmetic]]></pResHashArithmetic><pProductName><![CDATA[$pProductName]]></pProductName><pProductDescription><![CDATA[$pProductDescription]]></pProductDescription><pAttach><![CDATA[$pAttach]]></pAttach><pEnableFraudGuard><![CDATA[$pEnableFraudGuard]]></pEnableFraudGuard></StandardPaymentReq></ICPay></IPSReqRoot>";
		$pICPayReqB64 = base64_encode($pICPayReq);
		$pICPayReqHashValue = md5($pICPayReq . $pMerchantKey);//00518847228994856151214381286034373160268923638865209509623755128452179689329064232083487454640280528679651027955842303507571503
		$this->add_field('pMerchantCode',$pMerchantCode);//222378
		$this->add_field('pICPayReq',$pICPayReqB64);
		$this->add_field('pICPayReqHashValue',$pICPayReqHashValue);
		//反欺诈验证信息：
		//持卡人信息
		$pAccID             =  '';
		$pAccEMail          =  '';
		$pAccLoginIP        =  '';
		$pAccLoginDate      =  '';
		$pAccLoginDevice    =  '';
		$pAccRegisterDate   =  '';
		$pAccRegisterDevice =  '';
		$pAccRegisterIP     =  '';

		//帐单信息
		$pBillFName         =  $_POST['pBillFName'];
		$pBillMName         =  $_POST['pBillMName'];
		$pBillLName         =  $_POST['pBillLName'];
		$pBillStreet        =  $_POST['pBillStreet'];
		$pBillCity          =  $_POST['pBillCity'];
		$pBillState         =  $_POST['pBillState'];
		$pBillCountry       =  strtolower($_POST['pBillCountry']); //请使用国家/地区的小写二字英文代码
		$pBillZIP           =  $_POST['pBillZIP'];
		$pBillEmail         =  $_POST['pBillEmail'];
		$pBillPhone         =  $_POST['pBillPhone'];

		//产品信息
		$pProductData1      =  $_POST['pProductData1'];
		$pProductData2      =  $_POST['pProductData2'];
		$pProductData3      =  $_POST['pProductData3'];
		$pProductData4      =  $_POST['pProductData4'];
		$pProductData5      =  $_POST['pProductData5'];
		$pProductData6      =  $_POST['pProductData6'];
		$pProductType       =  $_POST['pProductType'];

		//货运信息
		$pShipFName         =  $_POST['pShipFName'];
		$pShipMName         =  $_POST['pShipMName'];
		$pShipLName         =  $_POST['pShipLName'];
		$pShipStreet        =  $_POST['pShipStreet'];
		$pShipCity          =  $_POST['pShipCity'];
		$pShipState         =  $_POST['pShipState'];
		$pShipCountry       =  strtolower($_POST['pShipCountry']); //请使用国家/地区的小写二字英文代码
		$pShipZIP           =  $_POST['pShipZIP'];
		$pShipEmail         =  $_POST['pShipEmail'];
		$pShipPhone         =  $_POST['pShipPhone'];

		//使用接口版本号(*):请固定使用“1.0.0”
		$fVersion = '1.0.0';

		//指定使用验证规则库的编号,默认为1
		$pCheckRuleBaseID = '1';

		//反欺诈信息按接口文档中格式写成XML(*)
		$pAFSReq = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><IPSReqRoot><AFS><Version><![CDATA[$fVersion]]></Version><cBasicParameters><pCheckRuleBaseID><![CDATA[$pCheckRuleBaseID]]></pCheckRuleBaseID></cBasicParameters><StandardPaymentReq><cBillParameters><pBillFName><![CDATA[$pBillFName]]></pBillFName><pBillMName><![CDATA[$pBillMName]]></pBillMName><pBillLName><![CDATA[$pBillLName]]></pBillLName><pBillStreet><![CDATA[$pBillStreet]]></pBillStreet><pBillCity><![CDATA[$pBillCity]]></pBillCity><pBillState><![CDATA[$pBillState]]></pBillState><pBillCountry><![CDATA[$pBillCountry]]></pBillCountry><pBillZIP><![CDATA[$pBillZIP]]></pBillZIP><pBillEmail><![CDATA[$pBillEmail]]></pBillEmail><pBillPhone><![CDATA[$pBillPhone]]></pBillPhone></cBillParameters><cShipParameters><pShipFName><![CDATA[$pShipFName]]></pShipFName><pShipMName><![CDATA[$pShipMName]]></pShipMName><pShipLName><![CDATA[$pShipLName]]></pShipLName><pShipStreet><![CDATA[$pShipStreet]]></pShipStreet><pShipCity><![CDATA[$pShipCity]]></pShipCity><pShipState><![CDATA[$pShipState]]></pShipState><pShipCountry><![CDATA[$pShipCountry]]></pShipCountry><pShipZIP><![CDATA[$pShipZIP]]></pShipZIP><pShipEmail><![CDATA[$pShipEmail]]></pShipEmail><pShipPhone><![CDATA[$pShipPhone]]></pShipPhone></cShipParameters><cProductParameters><pProductType><![CDATA[$pProductType]]></pProductType><pProductName><![CDATA[$pProductName]]></pProductName><pProductData1><![CDATA[$pProductData1]]></pProductData1><pProductData2><![CDATA[$pProductData2]]></pProductData2><pProductData3><![CDATA[$pProductData3]]></pProductData3><pProductData4><![CDATA[$pProductData4]]></pProductData4><pProductData5><![CDATA[$pProductData5]]></pProductData5><pProductData6><![CDATA[$pProductData6]]></pProductData6></cProductParameters><cAccountParameters><pAccID><![CDATA[$pAccID]]></pAccID><pAccEMail><![CDATA[$pAccEMail]]></pAccEMail><pAccRegisterIP><![CDATA[$pAccRegisterIP]]></pAccRegisterIP><pAccLoginIP><![CDATA[$pAccLoginIP]]></pAccLoginIP><pAccRegisterDate><![CDATA[$pAccRegisterDate]]></pAccRegisterDate><pAccLoginDate><![CDATA[$pAccLoginDate]]></pAccLoginDate><pAccRegisterDevice><![CDATA[$pAccRegisterDevice]]></pAccRegisterDevice><pAccLoginDevice><![CDATA[$pAccLoginDevice]]></pAccLoginDevice></cAccountParameters></StandardPaymentReq></AFS></IPSReqRoot>";

		//对反欺诈信息进行base64_encode(*)
		$pAFSReqB64 = base64_encode($pAFSReq);

		//反欺诈签名验证串(*)：MD5原文=反欺诈信息+商户证书
		$pAFSReqHashValue = md5($pAFSReq . $pMerchantKey);
		$this->add_field('pAFSReq',$pAFSReqB64);
		$this->add_field('pAFSReqHashValue',$pAFSReqHashValue);


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