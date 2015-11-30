<?php

class richcard {
    
    var $fields = array();           // array holds the fields to submit to paypal
    var $form = '';                    //表单
    var $submit_url;                //支付接口提交url
    var $form_url;                //本站curl提交url
    var $return_url;                //返回支付结果地址
    
	public function __construct()
	{
		 $this->submit_url = 'https://ssl.notroublessl.com/payment/payment/direct.xml';
		 $this->form_url = U('Pment/richcard_http');
		 $this->return_url = "http://{$_SERVER['HTTP_HOST']}".U('Pment/richcard_http');
	}
	
    public function add_field($field, $value)
    {
        $this->fields["$field"] = trim($value);
    }
    
    public function submit_post()
    {
        echo "<html>";
        echo "<head><title>Processing Payment...</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>\n";
        /*echo "<body onLoad=\"document.forms['pay_form'].submit();\">\n";*/
        echo "<body >\n";
        echo "<center><h2>Please remember your order number ".$this->sn.", and then click on the button below to make a payment!</h2></center>\n";
        echo "<center><h2>Please wait, your order is being processed and you";
        echo " will be redirected to the payment website.</h2></center>\n";
        echo "<form action='{$this->form_url}' method='post' name='pay_form' target='_blank'>\n";
        foreach($this->fields as $name => $value ) {
            echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }
        echo "<center><br/><br/>If you are not automatically redirected to ";
        echo "ECPSS within 5 seconds...<br/><br/>\n";
        echo "<input type=\"submit\" class=\"common_btn2\" value=\"Pay by ECPSS\"></center>\n";

        echo "</form>\n";
        echo "<script>function jump(){ document.forms[\"pay_form\"].submit();} setTimeout(\"jump()\", 5000);</script>";
        echo "</body></html>\n";    
    }
    
    public function create_form($list)
    {
        $pname = get_class($this); 
        
		$this->add_field('payNumber', GetValue($pname . "_order_type")); //支付类 默认0
		$this->add_field('merNo', GetValue($pname . "_merchant_id")); //商户编号
		$this->add_field('orderNo', $list['sn']); //订单编号
		$this->add_field('amount', $list['orders_total']); //交易金额 保留2位小数
		$this->add_field('currency', $this->get_currencycode($list['currencies_code'])); //交易币种
		$this->add_field('email', $list['delivery_email']); //持卡人邮箱
		$this->add_field('phone', $list['delivery_telephone']); //持卡人电话
		$this->add_field('billZip', $list['delivery_zip']); //账单邮编
		$this->add_field('billFirstName', $list['delivery_firstname']); //账单人名
		$this->add_field('billLastName', $list['delivery_lastname']); //账单人姓
		$this->add_field('billAddress', $list['delivery_address']); //账单地址
		$this->add_field('billCity', $list['delivery_city']); //账单城市
		$this->add_field('billState', $list['delivery_state']); //账单州(省)
		$this->add_field('billCountry', $this->get_country_code($list['delivery_country'])); //账单国家
		$this->add_field('shipFirstName', $list['delivery_firstname']); //收货人名
		$this->add_field('shipLastName', $list['delivery_lastname']); //收货人姓
		$this->add_field('shipZip', $list['delivery_zip']); //收货邮编
		$this->add_field('shipAddress', $list['delivery_address']); //收货地址
		$this->add_field('shipCity', $list['delivery_city']); //收货城市
		$this->add_field('shipState', $list['delivery_state']); //收货州(省)
		$this->add_field('shipCountry', $this->get_country_code($list['delivery_country'])); //收货国家
		
		$this->add_field('returnURL', $this->return_url); //返回支付结果地址
		//$this->add_field('shopName', ''); //网店名称 不限制
		$this->add_field('acceptLanguage', $this->getLanguage()); //浏览器的语言
		$this->add_field('userAgent', $_SERVER['HTTP_USER_AGENT']); //浏览器信息
		$this->add_field('payIp', get_client_ip()); //支付时持卡人所在的IP地址
		$this->add_field('paymentUrl', $this->submit_url); //订单支付提交 URL
		$this->add_field('remark', $list['remark']); //支付备注
		
		/**
        * 订单签名
        * 订单签名 md5Info = MD5(merNo+md5key+orderNo+amount+currency+email+returnURL);
        */
		$hashkey = trim(GetValue($pname . "_key")); // 商户证书
		$md5Info = $this->fields['merNo'] . 
					$hashkey . 
					$this->fields['orderNo'] . 
					$this->fields['amount'] . 
					$this->fields['currency'] . 
					$this->fields['email'] . 
					$this->fields['returnURL'];
		$this->add_field('md5Info', MD5($md5Info)); //支付加密唯一签名			
		
		$this->form = "<div style='width:500px;margin:0 auto;'>";
		$this->form .= "<form action='{$this->form_url}' method='post'>";
        foreach($this->fields as $name => $value) {
            $this->form .= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }
		$this->form .="<table border='0px'><tr ><th colspan='2'  align='right'>Please fill in credit card Information below.(ALL Required)</th></tr><tr><td  align='right'>Credit Card Type:</td><td><img src='/Public/skin/bagsloves/css/img/richcard_submit.jpg'/><img src='/Public/skin/bagsloves/css/img/visa.jpg'/></td ><tr><td align='right'>Card Number:</td><td ><input type='text' name='cardNo' id='cardNo' maxlength='16'/></td></tr><tr><td align='right'>Expiration Date:</td><td ><select id='expirationYear' name='expirationYear'><option value=''>--</option><option value='2012'>2012</option><option value='2013'>2013</option><option value='2014'>2014</option><option value='2015'>2015</option><option value='2016'>2016</option><option value='2017'>2017</option><option value='2018'>2018</option><option value='2019'>2019</option><option value='2020'>2020</option><option value='2021'>2021</option><option value='2022'>2022</option><option value='2023'>2023</option><option value='2024'>2024</option><option value='2025'>2025</option></select>&nbsp;&nbsp;<select id='expirationMonth' name='expirationMonth' ><option value=''>--</option><option value='01'>01</option><option value='02'>02</option><option value='03'>03</option><option value='04'>04</option><option value='05'>05</option><option value='06'>06</option><option value='07'>07</option><option value='08'>08</option><option value='09'>09</option><option value='10'>10</option><option value='11'>11</option><option value='12'>12</option></select></td></tr><tr><td align='right'>CVV2/CSC:</td><td><input type='password' name='cvv' id='cvv' maxlength='4'/></td></tr>";
        $this->form .='<tr><td></td><td><input type="submit" name="btn_submit" class="common_btn2" value="submit" /></td></tr>';
		$this->form .= '</table></form>';
		$this->form .= "</div>";
        return $this->form;
    }
    

    // 获取浏览器的语言
    public function getLanguage() 
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
    public function getIPaddress() 
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
    
	//国家代码转换
    public function get_country_code($country) {	
		$arr = array(
			'Macao'=>'MO',
			'HongKong'=>'HK',
			'Japan'=>'JP',
			'Korea'=>'KR',
			'Mongolia'=>'MN',
			'Taiwan'=>'TW',
			'Malaysia'=>'MY',
			'Singapore'=>'SG',
			'Thailand'=>'TH',
			'Vietnam'=>'VN',
			'Cambodia'=>'KH',
			'Indonesia'=>'ID',
			'Philippines'=>'PH',
			'Australia'=>'AU',
			'New Zealand'=>'NZ',
			'Papua New Cuinea'=>'PG',
			'Belgium'=>'BE',
			'United Kingdom'=>'GB',
			'Denmark'=>'DK',
			'Finland'=>'AX',
			'Greece'=>'GR',
			'Ireland'=>'IE',
			'Italy'=>'IT',
			'Malta'=>'MT',
			'Norway'=>'NO',
			'Switzerland'=>'CH',
			'Portugal'=>'PT',
			'Germany'=>'DE',
			'Sweden'=>'SE',
			'France'=>'FR',
			'Spain'=>'ES',
			'Austria'=>'AT',
			'United States'=>'US',
			'Canada'=>'CA',
			'India'=>'IN',
			'Pakistan'=>'PK',
			'Sri Lanka'=>'LK',
			'Laos'=>'LA',
			'Turkey'=>'TR',
			'Nepal'=>'NP',
			'Brazil'=>'BR',
			'Cuba'=>'CU',
			'Guyana'=>'GY',
			'Argentina'=>'AR',
			'Colombia'=>'CO',
			'Mexico'=>'MX',
			'Bahrain'=>'BH',
			'Iraq'=>'IQ',
			'Uganda'=>'UG',
			'Jordan'=>'JO',
			'Israel'=>'IL',
			'Oman'=>'OM',
			'Qatar'=>'QA',
			'Kuwait Kuwait'=>'KW',
			'Iran'=>'IR',
			'Madagascar'=>'MG',
			'Syria'=>'SY',
			'Ivory Coast'=>'CI',
			'Djibouti'=>'DJ',
			'Senegal'=>'SN',
			'Kenya'=>'KE',
			'Cayman Is'=>'KY',
			'Belarus'=>'BY',
			'Czech Republic'=>'CZ',
			'Kazakstan'=>'KZ',
			'Russia'=>'RU',
			'Latvia'=>'LV',
			'Hungary'=>'HU',
			'Poland'=>'PL',
			'Romania'=>'RO',
			'Puerto Rico'=>'PR',
			'Tunisia'=>'TN',
			'United Arab Emirates'=>'AE',
			'Luxembourg'=>'LU',
            '中国'=>'CN'			
     );
	
	 foreach($arr as $k=>$v) {
		 if(strpos(strtolower($k), strtolower($country))) {
			 $code = $v;
			 break;
		 }
	 }
	 return isset($code)? $code: 'US';
    }
	
	public function get_currencycode($code)
	{
		$arr = array(
			'$' => 'USD',
			'€' => 'EUR',
			'£' => 'GBP',
		);
		return $arr[$code]? $arr[$code]: 'USD';
	}
    
    
}
