<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-17
*/ 

require_once("alipay_function.php");
class alipay{


	var $gateway; //网关地址
	var $security_code; //安全校验码
	var $mysign; //加密结果（签名结果）
	var $sign_type; //加密类型
	var $parameter; //需要加密的参数数组
	var $_input_charset; //字符编码格式
	var $submiturl;

	/**
	 * 新增
	 */
	function create_form($list) {
			$this_script = "http://{$_SERVER['HTTP_HOST']}";
			$pname = "alipay";
			//支付宝
			$parameter = array (
				"service" => "trade_create_by_buyer", //接口名称，不需要修改
				"payment_type" => "1", //交易类型，不需要修改
				//获取配置文件(alipay_config.php)中的值
				"partner" => GetValue ( $pname . "_"."partner" ), 
				"seller_email" => GetValue ( $pname . "_"."seller_email" ), 
				"return_url" =>$this_script, // "http://www.eshayu.com",
				"notify_url" => $this_script . U ( 'Payment/Pin', array ("type" => "Alipay" ) ) ,// "http://www.eshayu.com".U ( 'Payment/Pin', array ("type" => "Alipay" ) ) ,
				"_input_charset" => GetValue ( $pname . "_"."input_charset" ), 
				"show_url" => $this_script,
				//从订单数据中动态获取到的必填参数
				"out_trade_no" => $list ['sn'], 
				"subject" => $list ['sn'],
				 "body" => "无", 
				 "price" => $list ['orders_total'] , 
				 "quantity" => "1", 
				 "logistics_fee" => "0",
				 "logistics_type" => "EXPRESS", 
				 "logistics_payment" => "BUYER_PAY"
			 );
			$this->setconig ( $parameter, GetValue ( $pname . "_security_code" ),"MD5" );//$this->mysign = "";

			$this->form.= $this->build_postform();
			
			$pname=get_class($this);
			if(GetValue($list['payment_module_code'].'_autosubmit')==1){
				$delay=GetValue($list['payment_module_code'].'_delay');
				$delay=$delay?$delay:5;
				$this->form.=$this->submit($delay);//是否自动提交,延迟5秒
			}
			
			
		return $this->form;
	}
	
	function submit(){
		if(!$delay){
			$this->form.= "<script>function jump(){ document.forms[\"pay_form\"].submit();} jump();</script>";
		}else{
			$delay*=1000;
			$this->form.= "<script>function jump(){ document.forms[\"pay_form\"].submit();} setTimeout('jump()',$delay);</script>";
		}

	}
	
	/**构造函数
	 *从配置文件及入口文件中初始化变量
	 *$parameter 需要加密的参数数组
	 *$security_code 安全校验码
	 *$sign_type 加密类型
	 */
	function setconig($parameter, $security_code, $sign_type) {//
		$this->gateway = "https://www.alipay.com/cooperate/gateway.do?";
		$this->security_code = $security_code;
		$this->sign_type = $sign_type;
		$this->parameter = para_filter ( $parameter );
		
		//设定_input_charset的值,为空值的情况下默认为GBK
		if ($parameter ['_input_charset'] == '')
			$this->parameter ['_input_charset'] = 'GBK';
		
		$this->_input_charset = $this->parameter ['_input_charset'];
		
		//获得签名结果
		$sort_array = arg_sort ( $this->parameter ); //得到从字母a到z排序后的加密参数数组
		$this->mysign = build_mysign ( $sort_array, $this->security_code, $this->sign_type );//
	}
	
	/********************************************************************************/
	
	/**构造请求URL（GET方式请求）
	 *return 请求url
	 */
	function create_url() {
		$url = $this->gateway;
		$sort_array = array ();
		$sort_array = arg_sort ( $this->parameter );
		$arg = create_linkstring_urlencode ( $sort_array ); //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		

		//把网关地址、已经拼接好的参数数组字符串、签名结果、签名类型，拼接成最终完整请求url
		$url .= $arg . "&sign=" . $this->mysign . "&sign_type=" . $this->sign_type;
		return $url;
	}
	
	/********************************************************************************/
	
	/**构造Post表单提交HTML（POST方式请求）
	 *return 表单提交HTML文本
	 */
	function build_postform() {
		
		$sHtml = "<form id='alipaysubmit' name='pay_form' action='" . $this->gateway . "_input_charset=" . $this->parameter ['_input_charset'] . "' method='post'>";
		
		while ( list ( $key, $val ) = each ( $this->parameter ) ) {
			$sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
		}
		
		$sHtml = $sHtml . "<input type='hidden' name='sign' value='" . $this->mysign . "'/>";
		$sHtml = $sHtml . "<input type='hidden' name='sign_type' value='" . $this->sign_type . "'/></form>";
		
		//$sHtml = $sHtml . "<input type='button' name='v_action' value='支付宝确认付款' onClick='document.forms[\"alipaysubmit\"].submit();'>";
		return $sHtml;
	}
/********************************************************************************/
}

class alipay_notify {
    var $gateway;           //网关地址
    var $security_code;  	//安全校验码
    var $partner;           //合作伙伴ID
    var $sign_type;         //加密方式 系统默认
    var $mysign;            //加密结果（签名结果）
    var $_input_charset;    //字符编码格式
    var $transport;         //访问模式

    /**构造函数
	*从配置文件中初始化变量
	*$partner 合作身份者ID
	*$security_code 安全校验码
	*$sign_type 加密类型
	*$_input_charset 字符编码格式
	*$transport 访问模式
     */
    function alipay_notify($partner,$security_code,$sign_type,$_input_charset = "GBK",$transport= "https") {

        $this->transport = $transport;
        if($this->transport == "https") {
            $this->gateway = "https://www.alipay.com/cooperate/gateway.do?";
        }else {
            $this->gateway = "http://notify.alipay.com/trade/notify_query.do?";
        }
        $this->partner          = $partner;
        $this->security_code    = $security_code;
        $this->mysign           = "";
        $this->sign_type	    = $sign_type;
        $this->_input_charset   = $_input_charset;
    }

    /********************************************************************************/

    /**对notify_url的认证
	*返回的验证结果：true/false
     */
    function notify_verify() {
        //获取远程服务器ATN结果，验证是否是支付宝服务器发来的请求
        if($this->transport == "https") {
            $veryfy_url = $this->gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_POST["notify_id"];
        } else {
            $veryfy_url = $this->gateway. "partner=".$this->partner."&notify_id=".$_POST["notify_id"];
        }
        $veryfy_result = $this->get_verify($veryfy_url);

        //生成签名结果
		if(empty($_POST)) {							//判断POST来的数组是否为空
			return false;
		}
		else {		
			$post          = para_filter($_POST);	    //对所有POST返回的参数去空
			$sort_post     = arg_sort($post);	    //对所有POST反馈回来的数据排序
			$this->mysign  = build_mysign($sort_post,$this->security_code,$this->sign_type);   //生成签名结果
	
			//写日志记录
			log_result("veryfy_result=".$veryfy_result."\n notify_url_log:sign=".$_POST["sign"]."&mysign=".$this->mysign.",".create_linkstring($sort_post));
	
			//判断veryfy_result是否为ture，生成的签名结果mysign与获得的签名结果sign是否一致
			//$veryfy_result的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_POST["sign"]) {
				return true;
			} else {
				return false;
			}
		}
    }

    /********************************************************************************/

    /**对return_url的认证
	*return 验证结果：true/false
     */
    function return_verify() {
        //获取远程服务器ATN结果，验证是否是支付宝服务器发来的请求
        if($this->transport == "https") {
            $veryfy_url = $this->gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_GET["notify_id"];
        } else {
            $veryfy_url = $this->gateway. "partner=".$this->partner."&notify_id=".$_GET["notify_id"];
        }
        $veryfy_result = $this->get_verify($veryfy_url);

        //生成签名结果
		if(empty($_GET)) {							//判断GET来的数组是否为空
			return false;
		}
		else {
			$get          = para_filter($_GET);	    //对所有GET反馈回来的数据去空
			$sort_get     = arg_sort($get);		    //对所有GET反馈回来的数据排序
			$this->mysign  = build_mysign($sort_get,$this->security_code,$this->sign_type);    //生成签名结果
	
			//写日志记录
			//log_result("veryfy_result=".$veryfy_result."\n return_url_log:sign=".$_GET["sign"]."&mysign=".$this->mysign."&".create_linkstring($sort_get));
	
			//判断veryfy_result是否为ture，生成的签名结果mysign与获得的签名结果sign是否一致
			//$veryfy_result的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_GET["sign"]) {            
				return true;
			}else {
				return false;
			}
		}
    }

    /********************************************************************************/

    /**获取远程服务器ATN结果
	*$url 指定URL路径地址
	*return 服务器ATN结果集
     */
    function get_verify($url,$time_out = "60") {
        $urlarr     = parse_url($url);
        $errno      = "";
        $errstr     = "";
        $transports = "";
        if($urlarr["scheme"] == "https") {
            $transports = "ssl://";
            $urlarr["port"] = "443";
        } else {
            $transports = "tcp://";
            $urlarr["port"] = "80";
        }
        $fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
        if(!$fp) {
            die("ERROR: $errno - $errstr<br />\n");
        } else {
            fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
            fputs($fp, "Host: ".$urlarr["host"]."\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $urlarr["query"] . "\r\n\r\n");
            while(!feof($fp)) {
                $info[]=@fgets($fp, 1024);
            }
            fclose($fp);
            $info = implode(",",$info);
            return $info;
        }
    }

    /********************************************************************************/
	
}
?>