<?php
/*
***************************************************************************
*   maker.tp@gmail.com                                                    *
*   Usage:安装第二步骤的检查集合                                             *
***************************************************************************
*/


/*
* Usage: 显示第二步骤的每一个项目是否通过;
*/
function check_msg($temp) { 
     if($temp) { 
     	$s='<font color="green"> 通过 √ </font>'; 
     } else { 
     	$s='<font color="red"> 不通过 × </font>';
     	remark_setup(1,0);
      } 
	 return $s; 
}

/*
* Usage:检查PHP的版本号;
*/
function check_version($ary){
	$v=floatval(substr(PHP_VERSION,0,2));
	if($v>=$ary['value']){
		$ary['pass']=check_msg(true);
	}else{
		$ary['pass']=check_msg(false);
	}
	return $ary;
}

/*
* Usage:检查register_globals,有些程序需要用到;
*/
function check_globals($ary){
	$ary['pass']=check_msg(get_cfg_var("register_globals"));
	return $ary;	
}

/*
* Usage:检查是否用到allow_url_fopen;
*/
function check_fopen($ary){
	$ary['pass']=check_msg(get_cfg_var("allow_url_fopen"));
	return $ary;	
}

/*
* Usage:检查mysql,检查版本号迟点再加上;
*/
function check_mysql($ary){
	$ary['pass']=check_msg(function_exists("mysql_close"));
	return $ary;	
}

/*
* Usage:检查GD;
*/
function check_gd($ary){
	if(function_exists("imageline")){
		$ary['pass']=check_msg(true);
	}else{
		$ary['pass']=check_msg(false);
	}
	return $ary;
}

/*
* Usage:检查smtp的支持;
*/
function check_smtp($ary){
	$ary['pass']=check_msg(get_magic_quotes_gpc("smtp"));
	return $ary;	
}

/*
* Usage:检查check_iconv有些转换需要用到;
*/
function check_iconv($ary){
	$ary['pass']=check_msg(function_exists("iconv"));
	return $ary;
}

/*
* Usage:检查stocket;
*/
function check_stocket($ary){
	$ary['pass']=check_msg(socket_create(AF_INET, SOCK_STREAM, SOL_TCP));
	return $ary;	
}

/*
* Usage:检查主机是否联入互联网,用了笨方法;
*/
function check_online($ary){
	/*if(@fsockopen("www.baidu.com",80,&$errno,&$errstr,5)||(@fsockopen("www.g.cn",80,&$errno,&$errstr,5))){
		$ary['pass']=check_msg(true);
	}else{
		$ary['pass']=check_msg(false);
	}
	return $ary;	*/
	return null;
}
?>