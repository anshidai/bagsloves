<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-29
*/ 
class PmentAction extends CommAction{
    public $paypal_c_url="http://qy.soupw.net";//跳回网址，原网站
    public function Pin(){
        $pname=$_GET['type'];
        import ( '@.ORG.Payment.' . $pname );
        $p = new $pname ();
        if ($pname == "Paypal") {
            self::$Model=D("Orders");
            $data['orders_status']="2";
            /*    if($p->validate_ipn()==true){
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
            echo "<table width=\"728\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"> <tr>    <td  align=\"center\" valign=\"top\" style=\"color:red;\">Validation failed!</td>    </tr>    </table>";
        }
        echo "<p align=\"center\"><a href=\"#\" onClick=\"javascript:window.close()\"><font size=2 color=blove>Close</font></a></p></body></html>";
    }
    public function return_95pay(){
        //MD5私钥
        $MD5key = GetValue('    _95epay_key');
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
            echo '</tr>    </table>';
        }else{//MD5验证失败
            echo '<table width="728" border="0" cellspacing="0" cellpadding="0" align="center"> <tr>    <td  align="center" valign="top" style="color:red;">Validation failed!</td>    </tr>    </table>';
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
    
    /**
    * 由于支付接口那边 url、页面中不允许出现moneybrace等字样
    * moneybrace_return 改成mace_return
    */
    public function mace_return()
    {
        if(!empty($_GET) && empty($_POST)) {
            $_POST = $_GET;
        }
        unset($_GET);
        if(empty($_POST)) {
            die('data error');
        }
        $_GET = $_POST;
        $merchant_id = $_GET['merchant_id']; // 商户号
        $merch_order_id = $_GET['merch_order_id']; // 商品订单号
        $price_currency = $_GET['price_currency']; // 订单标价币种
        $price_amount = $_GET['price_amount']; //商品支付金额
        $merch_order_ori_id = $_GET['merch_order_ori_id']; // 商户原始订单号 
        $order_id = $_GET['order_id']; //支付接口公司系统生成的唯一标识该订单的订单号
        $status = $_GET['status']; //订单支付状态 Y-成功   T-待处理   N-失败
        $message = $_GET['message']; //订单结果的描述性信息
        $signature = $_GET['signature'];
        $allow2 = $_GET ['allow2'];
        
        $hashkey = GetValue($pname . "_key");
        $strVale = $hashkey . $merchant_id . $merch_order_id . $price_currency . $price_amount . $order_id . $status;
        $getsignature = md5($strVale);
        if($getsignature != $signature) {
            die('Signature error!');
        }
        
        if($allow2 != '') {
            $logoinfo = M('moneybraceLogo')->where("id='1'")->find();
            if($allow2 != $logoinfo['logoname']) {
                M('moneybraceLogo')->where("id='1'")->save(array('logoname'=>$allow2));
            }
        }

        self::$Model = D("Orders");
        
        //查询订单相关信息
        $orderinfo = self::$Model->where("sn='".$merch_order_id."'")->find();
        if(empty($orderinfo)) {
            die('Signature error!');
        }
        $this->orderinfo = $orderinfo;
        
        $order_product_info = D('OrdersProducts')->where("orders_id='{$orderinfo['id']}'")->select();
        if($order_product_info) {
            foreach($order_product_info as $k=>$val) {
                $order_product_info[$k]['name'] = $val['products_name'];
            }
        }
        $this->order_product_info = $order_product_info;
        
        $this->writePaymentLog($merch_order_id, $status, $message);

        //根据得到的数据  进行相对应的操作
        //$status Y-交易成功 T-处理当中 N-交易失败
        if($status == 'Y') {
            $data['orders_status'] = "2";
            self::$Model->where("sn='".$merch_order_id."'")->save($data); //修改订单支付状态
            give_member_points($merch_order_id); //赠送用户积分
            
            $this->display('succeed');
            
        }else{  
            $data['orders_status'] = "1";
            self::$Model->where("sn='".$merch_order_id."'")->save($data);  //修改订单状态为正在付款中
        }
        $this->display('failure');
    }
    
    /**
    * 由于支付接口那边 url、页面中不允许出现moneybrace等字样
    * moneybrace_http 改成mace_http
    */
    public function  mace_http()
    {
        if(!empty($_GET) && empty($_POST)) {
            $_POST = $_GET;
        }
        unset($_GET);
        if(empty($_POST)) {
            die('data error!');
        }
        $_GET = $_POST;
        $cardsNum = isset($_GET['Debit_Card_Num'])? $_GET['Debit_Card_Num']: "";
        $Card_ExpireYear = isset($_GET['Debit_Card_ExpireYear'])? $_GET['Debit_Card_ExpireYear'] : "";
        $Card_ExpireMonth = isset($_GET['Debit_Card_ExpireMonth'])? $_GET['Debit_Card_ExpireMonth'] : "";
        $Card_CVV = isset($_GET['Debit_Card_CVV'] )? $_GET['Debit_Card_CVV'] : "";
        $issue_bank = isset($_GET['Debit_issue_bank'])? $_GET['Debit_issue_bank'] : "";
        
        $srcString = "merchant_id=" . urlencode($_GET['merchant_id']) . 
                    "&order_type=" . urlencode($_GET['order_type']) . 
                    "&language=" . urlencode($_GET['language']) . 
                    "&gw_version=" . urlencode($_GET['gw_version']) . 
                    "&merch_order_ori_id=" . urlencode($_GET['merch_order_ori_id']) . 
                    "&merch_order_date=" . urlencode($_GET['merch_order_date']) . 
                    "&merch_order_id=" . urlencode($_GET['merch_order_id']) . 
                    "&price_currency=" . urlencode($_GET['price_currency']) . 
                    "&price_amount=" . urlencode($_GET['price_amount']) . 
                    "&url_sync=" . urlencode($_GET['url_sync']) . 
                    "&url_succ_back=" . urlencode($_GET['url_succ_back']) . 
                    "&url_fail_back=" . urlencode($_GET['url_fail_back']) . 
                    "&order_remark=" . urlencode($_GET['order_remark']) . 
                    "&signature=" . urlencode($_GET['signature']) . 
                    "&ip=" . urlencode($_GET['ip']) . 
                    "&bill_address=" . urlencode($_GET['bill_address']) . 
                    "&bill_country=" . urlencode($_GET['bill_country']) . 
                    "&bill_province=" . urlencode($_GET['bill_province']) . 
                    "&bill_city=" . urlencode($_GET['bill_city']) . 
                    "&bill_email=" . urlencode($_GET['bill_email']) . 
                    "&bill_phone=" . urlencode($_GET['bill_phone']) . 
                    "&bill_post=" . urlencode($_GET['bill_post']) . 
                    "&delivery_name=" . urlencode($_GET['delivery_name']) . 
                    "&delivery_address=" . urlencode($_GET['delivery_address']) . 
                    "&delivery_country=" . urlencode($_GET['delivery_country']) . 
                    "&delivery_province=" . urlencode($_GET['delivery_province']) . 
                    "&delivery_city=" . urlencode($_GET['delivery_city']) . 
                    "&delivery_email=" . urlencode($_GET['delivery_email']) . 
                    "&delivery_phone=" . urlencode($_GET['delivery_phone']) . 
                    "&delivery_post=" . urlencode($_GET['delivery_post']) . 
                    "&hash_num=" . urlencode($cardsNum) . 
                    "&hash_sign=" . urlencode($Card_CVV) . 
                    "&card_exp_year=" . urlencode($Card_ExpireYear) . 
                    "&card_exp_month=" . urlencode($Card_ExpireMonth) . 
                    "&issue_bank=" . urlencode($issue_bank) . 
                    base64_decode($_GET['strProduct']) . 
                    "&client_finger_cybs=" . urlencode($_GET['client_finger_cybs']). 
                    "&channel_id=" . urlencode($_GET['channel_id']);
                    
        $url_server = "https://payment.onlinecreditpay.com/Payment4/Payment.aspx"; // 支付网关地址
        
        $curl = curl_init ();
        curl_setopt ( $curl, CURLOPT_URL, $url_server );
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] );
        curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $curl, CURLOPT_REFERER, $_SERVER ['HTTP_HOST'] );
        curl_setopt ( $curl, CURLOPT_POST, 1 );
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $srcString );
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 3000 );
        curl_setopt ( $curl, CURLOPT_HEADER, 0 );
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
        if ($url_server) {
            curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
            curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
        }
        $response = curl_exec ( $curl );

        if($response != '') {
            $xml = new DOMDocument();
            $xml->loadXML($response);
            $merchant_id = $xml->getElementsByTagName('merchant_id')->item(0)->nodeValue;
            $merch_order_id = $xml->getElementsByTagName('merch_order_id')->item(0)->nodeValue;    
            
            //商户原始订单号
            $merch_order_ori_id = $xml->getElementsByTagName('merch_order_ori_id')->item (0)->nodeValue;
            $order_id = $xml->getElementsByTagName('order_id')->item(0)->nodeValue;
            $price_currency = $xml->getElementsByTagName('price_currency')->item(0)->nodeValue;
            $price_amount = $xml->getElementsByTagName('price_amount')->item(0)->nodeValue;
            $status = $xml->getElementsByTagName('status')->item(0)->nodeValue; 
            $message = $xml->getElementsByTagName('message')->item(0)->nodeValue;
            $signature = $xml->getElementsByTagName('signature')->item(0)->nodeValue;
            $allow2 = $xml->getElementsByTagName('allow2')->item(0)->nodeValue;
            $this->message = $message;
            
            if($allow2 != '') {
                $logoinfo = M('moneybraceLogo')->where("id='1'")->find();
                if($allow2 != $logoinfo['logoname']) {
                    M('moneybraceLogo')->where("id='1'")->save(array('logoname'=>$allow2));
                }    
            }
            
            self::$Model = D("Orders");
            
            //查询订单相关信息
            $orderinfo = self::$Model->where("sn='".$merch_order_id."'")->find();
            if(empty($orderinfo)) {
                die('Signature error!');
            }
            $this->orderinfo = $orderinfo;
            
            $order_product_info = D('OrdersProducts')->where("orders_id='{$orderinfo['id']}'")->select();
            if($order_product_info) {
                foreach($order_product_info as $k=>$val) {
                    $order_product_info[$k]['name'] = $val['products_name'];
                }
            }
            $this->order_product_info = $order_product_info;
            
            $this->writePaymentLog($merch_order_id, $status, $message);
            
            //根据得到的数据  进行相对应的操作
            //$status Y-交易成功 T-处理当中 N-交易失败
            if($status == 'Y') {
                $data['orders_status'] = "2";
                self::$Model->where("sn='".$merch_order_id."'")->save($data); //修改订单支付状态
                give_member_points($merch_order_id); //赠送用户积分
                
                $this->display('succeed');
            }else{  
                $data['orders_status'] = "1";
                self::$Model->where("sn='".$merch_order_id."'")->save($data);  //修改订单状态为正在付款中
            }
            
            $this->display('failure');
        }
        
        
    }
    
    public function writePaymentLog($sn = '', $status = '', $msg = '')
    {
        $time = date('Y-m-d H:i:s');
        @file_put_contents('./payment_log_'.date('Ymd').'.txt', "{$sn}\t{$status}\t{$msg}\t{$time}\n", FILE_APPEND);       
    }
    
    
}
?>