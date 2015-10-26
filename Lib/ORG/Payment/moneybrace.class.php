<?php
/**
 * @author libaoan
 * @version 1.0
 * @lastupdate 2015-09-15
 */
class moneybrace {
    
    var $fields = array();           // array holds the fields to submit to paypal
    var $form = '';                    //表单
    var $submit_url;                //提交地址
    
    function moneybrace()
    {
        $this->submit_url = 'https://payment.onlinecreditpay.com/Payment4/Payment.aspx';    
    }
    
    function add_field($field, $value)
    {
        $this->fields["$field"] = trim($value);
    }
    
    function submit_post()
    {
        echo "<html>";
        echo "<head><title>Processing Payment...</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>\n";
        /*echo "<body onLoad=\"document.forms['pay_form'].submit();\">\n";*/
        echo "<body >\n";
        echo "<center><h2>Please remember your order number ".$this->sn.", and then click on the button below to make a payment!</h2></center>\n";
        echo "<center><h2>Please wait, your order is being processed and you";
        echo " will be redirected to the payment website.</h2></center>\n";
        echo "<form method=\"post\" name=\"pay_form\" ";
        echo "action=\"" . $this->submit_url . "\">\n";
        foreach($this->fields as $name => $value ) {
            echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }
        echo "<center><br/><br/>If you are not automatically redirected to ";
        echo "ECPSS within 5 seconds...<br/><br/>\n";
        echo "<input type=\"submit\" value=\"Pay by ECPSS\"></center>\n";

        echo "</form>\n";
        echo "<script>function jump(){ document.forms[\"pay_form\"].submit();} setTimeout(\"jump()\", 5000);</script>";
        echo "</body></html>\n";    
    }
    
    function create_form($list)
    {
        $pname = get_class($this); 
        
        $this_script = "http://{$_SERVER['HTTP_HOST']}";
        
        $this->add_field('merchant_id', GetValue($pname . "_merchant_id") * 818 + 5201314); // 商户号
        $this->add_field('order_type', GetValue($pname . "_order_type")); // 订单类型
        $this->add_field('gw_version', 'php(D1.2)'); // 接口版本
        $this->add_field('language', $this->getLanguage()); // 接口语言
        $this->add_field('merch_order_ori_id', $list['id']);  // 商户原始订单号
        $this->add_field('merch_order_id', $list['sn']); // 商品订单号
        $this->add_field('merch_order_date', date('YmdHis', time())); // 订单交易时间
        $this->add_field('price_currency', $_SESSION['currency']['symbol'] ? $_SESSION['currency']['symbol']: 'USD'); // 订单标价币种
        $this->add_field('price_amount', $list['orders_total']); // 订单标价金额
        $this->add_field('ip', $this->getIPaddress());
        $this->add_field('url_sync', $this_script.U('Pment/mace_return')); // 服务器返回地址（订单状态同步地址）
        $this->add_field('url_succ_back', $this_script.U('Pment/mace_return')); // 浏览器返回地址（成功订单返回地址）
        $this->add_field('url_fail_back', $this_script.U('Pment/mace_return'));  // 交易地址（失败订单返回地址）
        $this->add_field('order_remark', $list['remark']); // 备注
        
        $this->add_field('bill_address', $list['delivery_address']);  // 账单地址
        $this->add_field('bill_country', 'CN');  // 账单国家简码
        $this->add_field('bill_province', $list['delivery_state']);  // 账单地区
        $this->add_field('bill_city', $list['delivery_city']);  // 账单城市
        $this->add_field('bill_email', 'service@bagsloves.com'); // 账单EMAIL
        $this->add_field('bill_phone', $list['delivery_telephone']); // 账单电话
        $this->add_field('bill_post', $list['delivery_zip']);  // 账单邮编
        
        $this->add_field('delivery_name', $list['delivery_lastname'].' '. $list['delivery_firstname']); // 收货姓名
        $this->add_field('delivery_address', $list['delivery_address']); // 收货地址
        $this->add_field('delivery_country', 'US'); // 收货国家简码
        $this->add_field('delivery_province', $list['delivery_state']); // 收货地区
        $this->add_field('delivery_city', $list['delivery_city']); // 收货城市
        $this->add_field('delivery_email', $list['delivery_email']); // 收货人联系EMAIL 
        $this->add_field('delivery_phone', $list['delivery_telephone']); // 收货人联系电话
        $this->add_field('delivery_post', $list['delivery_zip']); // 收货人邮编
        
        $this->add_field('product_name', ''); // 单个产品名称
        $this->add_field('product_sn', ''); // 单个产品货号
        $this->add_field('quantity', ''); // 单个产品数量
        $this->add_field('unit', ''); // 单个产品单价
       
        /**
        * 订单签名
        * 订单签名=md5(order_hash+merchant_id+merch_order_id +price_currency+price_amount 
        */
        $hashkey = trim(GetValue($pname . "_key")); // 商户证书
        $strValue = $hashkey . 
                    ($this->fields['merchant_id'] - 5201314) / 818 . 
                    $this->fields['merch_order_id'] . 
                    $this->fields['price_currency'] . 
                    $this->fields['price_amount'];
        $this->add_field('signature', md5($strValue)); 
        
        $this->add_field('client_fingername', ''); //瑞德风控的指纹，按特定代码生成-可选
        $this->add_field('client_finger_cybs', $this->create_guid());  //用于调用风控接口的唯一字符串，一般是32位的guid字符串 -可选
        
        $this->add_field('card_exp_year', ''); //卡的有效期(4位的年份)
        $this->add_field('card_exp_month', ''); //卡的有效期(2位的月份)
        $this->add_field('hash_num', ''); //信用卡号。一般是16位的数字
        $this->add_field('hash_sign', ''); //CVV或安全码。一般是3位的数字。
        $this->add_field('issue_bank', ''); //发卡银行
        $this->add_field('card_holder', ''); //持卡人姓名-可选
        $this->add_field('bin_country', ''); //发卡行国家-可选

        foreach($this->fields as $name => $value) {
            $this->form .= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }
        
        $fromfields = '<script type="text/javascript">
                var str = "<table style=\'width: 280px;\' cellspacing=0 cellpadding=0>";
                str = str + "<tr><td>";
                str = str + "<div style=\'float: left;\'><table border=0>";
                str = str + "<tr><td align=left width=\'110px;\'><i style=\'color: red;\'>*</i>Card Number:</td>";
                str = str + "<td><input type=\'text\' name=\'Debit_Card_Num\' id=\'Debit_Card_Num\' maxlength=\'19\' style=\'width:150px\' onkeypress=\'AutoAddSpaceDebit(this);\' value=\'\' /><lable id=\'lblDebitNum\' class=\'labfontcolor\'></lable></td></tr>";
                str = str + "<tr><td align=left><i style=\'color: red;\'>*</i>Expiration Date:</td>";
                str = str + "<td><select name=\'Debit_Card_ExpireMonth\' id=\'Debit_Card_ExpireMonth\' style=\'width: 80px;\'>";
                str = str + "<option value=\'\'>Month</option>";
                str = str + "<option value=\'01\'>January - (01)</option>";
                str = str + "<option value=\'02\'>February - (02)</option>";
                str = str + "<option value=\'03\'>March - (03)</option>";
                str = str + "<option value=\'04\'>April - (04)</option>";
                str = str + "<option value=\'05\'>May - (05)</option>";
                str = str + "<option value=\'06\'>June - (06)</option>";
                str = str + "<option value=\'07\'>July - (07)</option>";
                str = str + "<option value=\'08\'>August - (08)</option>";
                str = str + "<option value=\'09\'>September - (09)</option>";
                str = str + "<option value=\'10\'>October - (10)</option>";
                str = str + "<option value=\'11\'>November - (11)</option>";
                str = str + "<option value=\'12\'>December - (12)</option>";
                str = str + "</select>  ";
                str = str + "<select name=\'Debit_Card_ExpireYear\' id=\'Debit_Card_ExpireYear\' style=\'width: 60px;\'>";
                str = str + "<option value=\'\'>Year</option>";
                str = str + "<option value=\'2015\'>2015</option>";
                str = str + "<option value=\'2016\'>2016</option>";
                str = str + "<option value=\'2017\'>2017</option>";
                str = str + "<option value=\'2018\'>2018</option>";
                str = str + "<option value=\'2019\'>2019</option>";
                str = str + "<option value=\'2020\'>2020</option>";
                str = str + "<option value=\'2021\'>2021</option>";
                str = str + "<option value=\'2022\'>2022</option>";
                str = str + "<option value=\'2023\'>2023</option>";
                str = str + "<option value=\'2024\'>2024</option>";
                str = str + "<option value=\'2025\'>2025</option>";
                str = str + "<option value=\'2026\'>2026</option>";
                str = str + "<option value=\'2027\'>2027</option>";
                str = str + "<option value=\'2028\'>2028</option>";
                str = str + "<option value=\'2029\'>2029</option>";
                str = str + "<option value=\'2030\'>2030</option>";
                str = str + "<select><lable id=\'lblDebitExpireMsg\' class=\'labfontcolor\'></lable></td></tr>";
                str = str + "<tr><td align=left><i style=\'color: red;\'>*</i>CVV2/CVC2:</td>";
                str = str + "<td><input type=\'password\' maxlength=\'3\' name=\'Debit_Card_CVV\' id=\'Debit_Card_CVV\' style=\'width:150px\' value=\'\' /><lable id=\'lblDebitCvvMsg\' class=\'labfontcolor\'></lable></td></tr>";
                str = str + "<tr><td align=left><i style=\'color: red;\'>*</i>Issuing Bank:</td>";
                str = str + "<td><input type=\'text\' name=\'Debit_issue_bank\' id=\'Debit_issue_bank\' style=\'width:150px\' value=\'\' /><lable id=\'lblDebitIssueBankMsg\' class=\'labfontcolor\'></lable></td></tr>";
                str = str + "</table></div>";
                str = str + "</td></tr></table>";</script>';

        $logoinfo = M('moneybraceLogo')->where("id='1'")->find();
        if($logoinfo['logoname'] !== '') {
            $scn_db_logo = str_replace ("{scn_db_tr}", $this->htmldecode($fromfields), base64_decode($logoinfo['logoname']));
            $scn_db_info = base64_decode($logoinfo['logoname']);
            $scn_db_exist = "1";
        }else {
            $scn_db_exist = "0";
            $scn_db_logo = "";
            $scn_db_info = "";
        }
        
        $form_html = file_get_contents(__DIR__.'/moneybrace_form.html');
        $form_html = str_replace('{$form}', $this->form, $form_html);
        $form_html = str_replace('{$scn_db_info}', $scn_db_info, $form_html);
        $form_html = str_replace('{$scn_db_logo}', $scn_db_logo, $form_html);
        $form_html = str_replace('{$submit_url}', $this->submit_url, $form_html);
        $form_html = str_replace('{$fromfields}', $fromfields, $form_html);
        $form_html = str_replace('{$guid}', $this->fields['client_finger_cybs'], $form_html);
        $form_html = str_replace('{$scn_db_exist}', $scn_db_exist, $form_html);
        $form_html = str_replace('{$merchant_id}', $this->fields['merchant_id'], $form_html);

        return $form_html;
    }
    

    // 获取浏览器的语言
    function getLanguage() 
    {
        $lang = substr ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'], 0, 4 );
        $language = '';
        if (preg_match ( "/en/i", $lang ))
            $language = 'en-us'; // 英文
        elseif (preg_match ( "/fr/i", $lang ))
            $language = 'fr-fr'; // 法语
        elseif (preg_match ( "/de/i", $lang ))
            $language = 'de-de'; // 德语
        elseif (preg_match ( "/ja/i", $lang ))
            $language = 'ja-jp'; // 日语
        elseif (preg_match ( "/ko/i", $lang ))
            $language = 'ko-kr'; // 韩语
        elseif (preg_match ( "/es/i", $lang ))
            $language = 'es-es'; // 西班牙语
        elseif (preg_match ( "/ru/i", $lang ))
            $language = 'ru-ru'; // 俄罗斯
        elseif (preg_match ( "/it/i", $lang ))
            $language = 'it-it'; // 意大利语
        else
            $language = 'en-us'; // 英文
        return $language;
    } 
    
    
    // 获取客户端的ip
    function getIPaddress() 
    {
        $IPaddress = '';
        if (isset ( $_SERVER )) {
            if (isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) {
                $IPaddress = $_SERVER ["HTTP_X_FORWARDED_FOR"];
            } else if (isset ( $_SERVER ["HTTP_CLIENT_IP"] )) {
                $IPaddress = $_SERVER ["HTTP_CLIENT_IP"];
            } else {
                $IPaddress = $_SERVER ["REMOTE_ADDR"];
            }
        } else {
            if (getenv ( "HTTP_X_FORWARDED_FOR" )) {
                $IPaddress = getenv ( "HTTP_X_FORWARDED_FOR" );
            } else if (getenv ( "HTTP_CLIENT_IP" )) {
                $IPaddress = getenv ( "HTTP_CLIENT_IP" );
            } else {
                $IPaddress = getenv ( "REMOTE_ADDR" );
            }
        }
        $ips = explode ( ",", $IPaddress );
        return $ips [0];
    } 
    
    // 获取唯一的id
    function create_guid()
    {
        mt_srand ( ( double ) microtime () * 10000 );
        $charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
        $hyphen = chr ( 45 );
        $uuid = substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
        return $uuid;
    }
    
    
    function htmldecode($strvalue)
    {
        $strvalue = str_replace ( "str = str + \"", "", $strvalue );
        $strvalue = str_replace ( "var str = \"", "", $strvalue );
        $strvalue = str_replace ( "<script type=\"text/javascript\">", "", $strvalue );
        $strvalue = str_replace ( "\";", "", $strvalue );
        $strvalue = str_replace ( "\"</script>", "", $strvalue );
        return $strvalue;
    }
    
    
    
}