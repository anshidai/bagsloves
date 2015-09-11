<?php
/*
***************************************************************************
*   maker.tp@gmail.com                                                    *
*   Usage:默认首页                                             *
***************************************************************************
*/

error_reporting(E_ALL & ~ E_NOTICE );
set_time_limit(0);
session_start();
header("Content-type:text/html; charset=UTF-8");
require("./common.php");
require("./config.php");
require("./class/phpsvnclient.php");
require("./class/tpl.class.php");
require("./setup.php");
require("./check.php");
if(file_exists("../Runtime")){
	deldir("../Runtime");
}

$step=(isset($_GET['step']) && $_GET['step'] !='')?$_GET['step']:0;
$action=isset($_GET['action'])?$_GET['action']:'';
if($action!='del'){
if(file_exists("install_lock")){
	die("If you need to reinstall, please delete file install_lock");
}
}
switch ($action) {
	case 'show':
		$show_fun="showstep".$step;
		$content=$show_fun();
		showmain($content);
		break;
	case 'check':
		$check_fun="checkstep".$step;
		$result=$check_fun();
		steupgo($step);
		break;
	case 'del':
		dodel();//删除安装目录
		break;
	case 'out':
		doout();//退出安装
		break;
	case 'svn':
		dosvn();//退出安装
		break;
	case 'sql':
		dosql();//退出安装
		break;
	default:
		header("location:./index.php?action=show&step=0");
		break;
}

?>