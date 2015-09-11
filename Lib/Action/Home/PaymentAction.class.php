<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-29
*/ 
class PaymentAction extends CommAction{
	public $paypal_c_url="http://qy.soupw.net";//跳回网址，原网站
	public function Pin(){
		$pname=$_GET['type'];
		import ( '@.ORG.Payment.' . $pname );
		$p = new $pname ();
		if ($pname == "Paypal") {
			self::$Model=D("Orders");
			$data['orders_status']="2";
			/*	if($p->validate_ipn()==true){
			self::$Model->where("sn=".$p->ipn_data['item_number'])->save($data);
			}*/
			if(strcasecmp($_POST['payer_status'],'verified')==0){
				if(!empty($_POST['item_number'])){
					self::$Model->where("sn=".$_POST['item_number'])->save($data);
					//赠送用户积分
					give_member_points($_POST['item_number']);
				}
			}
		}
		if ($pname=="Alipay"){			
			$alipay=new alipay_notify(GetValue ( $pname . "_"."partner" ),GetValue ( $pname . "_security_code" ),"MD5",GetValue ( $pname . "_"."input_charset" ));
			$verify_result = $alipay->notify_verify();  //计算得出通知验证结果
			if($verify_result) {
				$dingdan = $_POST ['out_trade_no']; //获取支付宝传递过来的订单号
				$total = $_POST ['price']; //获取支付宝传递过来的总价格
				if ($_POST ['trade_status'] == 'WAIT_SELLER_SEND_GOODS'||$_GET['trade_status'] == 'TRADE_FINISHED') {//担保交易和即时到账
					self::$Model = D ( "Orders" );
					$data ['orders_status'] = "2";
					self::$Model->where("sn=".$dingdan)->save($data);
					give_member_points($_POST['item_number']);
				}
			}
		}
	}
	public function Pin_c(){

		$pname=$_GET['type'];
		import ( '@.ORG.Payment.' . $pname );
		$p = new $pname ();
		if ($pname == "Paypal") {
			if($p->validate_ipn()){//验证ipn

				$post_string = '';
				foreach ($_POST as $field=>$value) {
					$post_string .= $field.'='.urlencode(stripslashes($value)).'&';
				}
				$ch = curl_init() ;
				//通知网址
				curl_setopt($ch, CURLOPT_URL,$this->paypal_c_url.U('Payment/Pin',array('type'=>'Paypal'))) ;
				curl_setopt($ch, CURLOPT_POST,count($_POST)) ;
				//curl_setopt($ch, CURLOPT_RETURNTRANSFER,1) ;
				curl_setopt($ch, CURLOPT_POSTFIELDS,$post_string) ;
				$result = curl_exec($ch) ;
			}
		}
	}

	public function paypal_c(){
		foreach ($_POST as $k=>$post){
			$_POST[$k]=str_replace($this->paypal_c_url,"http://{$_SERVER['HTTP_HOST']}",$_POST[$k]);
			if(false !== strpos($_POST[$k])){
				$_POST[$k]=str_replace('Payment-Pin','Payment-Pin_c',$_POST[$k]);
			}
		}
		import ( '@.ORG.Payment.Paypal' );
		$p = new Paypal();
		$p->add_field ( 'business',$_POST['business']); //收款人账号'402660_1224487424_biz@qq.com'
		//$p->add_field ( 'return',$_POST['return'] );//网站中指定返回地址
		$p->add_field ( 'cancel_return', $_POST['cancel_return'] );
		$p->add_field ( 'notify_url', $_POST['notify_url'] );
		$p->add_field ( 'item_name', $_POST['item_name'] ); //产品名称
		$p->add_field ( 'item_number', $_POST['item_number'] ); //订单号码
		$p->add_field ( 'amount', $_POST['amount'] ); //交易价格
		$p->add_field ( 'currency_code', $_POST['currency_code'] ? $_POST['currency_code'] : 'USD' ); //货币代码
		$p->submit_paypal_post_c();//简洁提交
	}
	public function gspay_success_c(){
		if(C('URL_MODEL')==2){
			redirect($this->gspay_c_url.'/index.php'.U('Payment/gspay_success',array('transactionAmount'=>$_REQUEST['transactionAmount'])));
		}else{
			redirect($this->gspay_c_url.U('Payment/gspay_success',array('transactionAmount'=>$_REQUEST['transactionAmount'])));
		}

	}
	public function gspay_error_c(){
		if(C('URL_MODEL')==2){
			redirect($this->gspay_c_url.'/index.php'.U('Payment/gspay_error'));
		}else{
			redirect($this->gspay_c_url.U('Payment/gspay_error'));
		}
	}
	public function wedopay_return(){
		$this_script = "http://{$_SERVER['HTTP_HOST']}";
		$MD5key=GetValue ( "wedopay_Md5Key" );
		$MerNo=GetValue ( "wedopay_MerNo" );
		//订单号
		$BillNo = $_POST["BillNo"];
		//币种
		$Currency = $_POST["Currency"];
		//金额
		$Amount = $_POST["Amount"];
		//支付状态
		$Succeed = $_POST["Succeed"];
		//支付结果
		$Result = $_POST["Result"];
		//取得的MD5校验信息
		$MD5info = $_POST["MD5info"];
		//备注
		$Remark = $_POST["Remark"];
		//校验源字符串
		$md5src = $BillNo.$Currency.$Amount.$Succeed.$MD5key;
		//MD5检验结果
		$md5sign = strtoupper(md5($md5src));
		echo "<html><head><title>php</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head><body>";
		if ($MD5info==$md5sign){
			echo "<table width=\"728\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">  <tr>   <td  align=\"right\" valign=\"top\">Your order number is:</td>    <td  align=\"left\" valign=\"top\">$BillNo</td>  </tr>  <tr>    <td  align=\"right\" valign=\"top\">Amount:</td>    <td align=\"left\" valign=\"top\">$Amount </td>  </tr>  <tr>    <td  align=\"right\" valign=\"top\">Payment result:</td>";
			if ($Succeed=="1" || $Succeed=="9" || $Succeed=="19" || $Succeed=="88") {
				echo "<td align=\"left\" valign=\"top\" style=\"color:green;\">".urldecode($Result)."</td>";
			}else{
				echo "<td  align=\"left\" valign=\"top\" style=\"color:red;\">".urldecode($Result)."&nbsp;&nbsp;&nbsp;&nbsp;$Succeed</td>";
			}
			echo "</tr></table>";
		}else{
			echo "<table width=\"728\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"> <tr>    <td  align=\"center\" valign=\"top\" style=\"color:red;\">Validation failed!</td>	</tr>	</table>";
		}
		echo "<p align=\"center\"><a href=\"#\" onClick=\"javascript:window.close()\"><font size=2 color=blove>Close</font></a></p></body></html>";
	}
	public function return_95pay(){
		//MD5私钥
		$MD5key = GetValue('	_95epay_key');
		//支付平台流水号
		$TradeNo=$_POST["TradeNo"];//供商户在支付平台查询订单时使用,请合理保存
		//支付状态
		$PaymentResult = $_POST["PaymentResult"];//返回码: 1 :表示交易成功 ; 0: 表示交易失败
		//订单号
		$BillNo = $_POST["BillNo"];
		//币种
		$Currency = $_POST["Currency"];
		//金额
		$Amount = $_POST["Amount"];
		//支付状态
		$Succeed = $_POST["Succeed"];
		//支付结果
		$Result = $_POST["Result"];
		//取得的MD5校验信息
		$MD5info = $_POST["MD5info"];
		//备注
		$Remark = $_POST["Remark"];
		//金额单位
		$currencyName = $_POST["currencyName"];

		/**
		**判断是哪次返回的数据【顾客支付完立即返回，还是支付处理完以后返回的数据】
		*/
		//服务器返回数据开始
		if(isset($TradeNo) && !empty($TradeNo) && isset($PaymentResult) && !empty($PaymentResult)){

			//校验源字符串
			$returnMd5src = $TradeNo.$BillNo.$Currency.$Amount.$PaymentResult.$MD5key;
			//本地MD5检验结果
			$returnMd5sign = strtoupper(md5($returnMd5src));

			if($returnMd5sign==$MD5info){
				if($PaymentResult=='1'){//支付已成功
					//请修改订单状态为成功状态
				}
				else if($PaymentResult=='0'){//支付已失败
					//请修改订单状态为失败状态
				}
			}
			exit; //处理完以后返回的数据.只要根据订单号改变数据库订单状态就可以了。
		}
		//服务器返回数据结束
		//校验源字符串
		$md5src = $BillNo.$Currency.$Amount.$Succeed.$MD5key;
		//MD5检验结果
		$md5sign = strtoupper(md5($md5src));

		self::$Model=D("Orders");
		if ($MD5info==$md5sign){//MD5验证成功
			echo '<html><head><title>php</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><table width="728" border="0" cellspacing="0" cellpadding="0" align="center"> <tr>
    <td  align="right" valign="top">Your order number is:</td>  <td  align="left" valign="top">$BillNo</td>
  </tr>  <tr>    <td  align="right" valign="top">Amount:</td>    <td align="left" valign="top">$Amount $currencyName</td>  </tr>  <tr>   <td  align="right" valign="top">Payment result:</td>';
			if ($Succeed=="88") {//支付成功，返回绿色的提示信息,可修改订单状态为付款成功
				$data['orders_status']="2";
				self::$Model->where("sn='".$BillNo."'")->save($data);
				//赠送用户积分
				give_member_points($BillNo);
				echo '<td align="left" valign="top" style="color:green;">'.urldecode($Result).'</td>';
			}elseif($Succeed=="1" || $Succeed=="9" || $Succeed=="19") {//提交支付信息成功，返回绿色的提示信息,可修改订单状态为正在付款中
				$data['orders_status']="1";
				self::$Model->where("sn='".$BillNo."'")->save($data);
				echo '<td align="left" valign="top" style="color:green;">'.urldecode($Result).'</td>';
			}else{//提交支付信息失败，返回红色的提示信息
				echo '<td  align="left" valign="top" style="color:red;">'.urldecode($Result).'&nbsp;&nbsp;&nbsp;&nbsp;'.$Succeed.'</td>';
			}
			echo '</tr>	</table>';
		}else{//MD5验证失败
			echo '<table width="728" border="0" cellspacing="0" cellpadding="0" align="center"> <tr>    <td  align="center" valign="top" style="color:red;">Validation failed!</td>	</tr>	</table>';
		}
		echo '<p align="center"><a href="#" onClick="javascript:window.close()"><font size=2 color=blove>Close</font></a></p></body></html>';

	}
	public function gspay_success(){

		echo "<html>";
		echo "<head><title>Payment successful!</title>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>\n";
		echo "<body >\n";
		echo "<center><h2>Payment successful!</h2></center>\n";
		echo "<center><h2>";
		echo "Transaction Amount:$".$_REQUEST['transactionAmount']."<br/>";
		echo "</h2></center>\n";
		echo "<center><a href=\"".__APP__."\">Go Home Page</a></center>\n";
		echo "</body></html>\n";
	}
	public function gspay_error(){
		echo "<html>";
		echo "<head><title>Payment Failure!</title>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>\n";
		echo "<body >\n";
		echo "<center><h2>Payment Failure!</h2></center>\n";
		echo "<center><h2>";
		echo "</h2></center>\n";
		echo "<center><a href=\"".__APP__."\">Go Home Page</a></center>\n";
		echo "</body></html>\n";
	}
	public function payeasy_return(){

		echo "<html>";
		echo "<head><title>Payment Return!</title>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>\n";
		echo "<body >\n";
		echo "<center><h2>Order SN:".$_GET['v_oid']."</h2></center>\n";
		if($_GET['v_pstatus']==20){
			echo "<center><h2>Payment successful!</h2></center>\n";
			echo "Transaction Amount:$".$_GET['v_amount']."<br/>";

		}elseif($_GET['v_pstatus']==30){
			echo "<center><h2>Payment Failure!<br/>".$_GET['v_pstring']."</h2></center>\n";
		}
		echo "<center><a href=\"".__APP__."\">Go Home Page</a></center>\n";
		echo "</body></html>\n";
	}
	public function ecpss_return(){

		echo "<html>";
		echo "<head><title>Payment Return!</title>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>\n";
		echo "<body >\n";
		if($_POST["Succeed"]=="19"){
			echo "Your payment is being processed .We will notify you of the final payment statement in 24 hours by an E-mail.";
		}else{
			echo "Your payment is being processed .We will notify you of the final payment statement in 24 hours by an E-mail.";
		}
		echo "<a href=\"".__APP__."\">Go Home Page</a>\n";
		echo "</body></html>\n";
	}
}
?>