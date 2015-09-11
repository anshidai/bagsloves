<?php
/*
***************************************************************************
*   maker.tp@gmail.com                                                    *
*   Usage:整个安装过程序每个步骤的程序                                             *
***************************************************************************
*/

/*
* Usage: 所有步骤的公共显示部分;
*/
function showmain($content){
	global $tpl_path,$conf,$app_lang,$app_default;
	$step=($_GET['step']!='')?$_GET['step']:0;
	$app_lang['step'][$step]['step_class']='up';
	$tpl=new tpl($tpl_path.'index.html');
	$tpl->tplblocksign("topstep",$app_lang['step']); //替换顶部的分类
	$tpl->tplsign("tpl_path",$tpl_path); //替换模板的路径
	$tpl->tplsign("app_name",$conf['APP_NAME']); //替换项目名称
	if($step>2){
		$tpl->tplissign('show_next',0);//第3步之前都是可以到下一步的
	}else{
		$tpl->tplissign('show_next',1);
	}
	if($step==0 || $step==5){	//第1,6步不需要重新操作
		$tpl->tplissign('show_redo',0);
	}else{
		$tpl->tplissign('show_redo',1);
	}

	$tpl->tplsign('tpl_main',$content);
	$tpl->tplsign("step_next",$step+1); //替换项目名称
	$tpl->tplsign("step_now",$step); //替换项目名称



	/*统一显示错误提示信息*/
	$steuperr=$_SESSION['steup'][$step]['err'];
	$tpl->tplissign("err",$steuperr); //信息提示
	$tpl->tplblocksign("note_info",$steuperr); //信息提示的内容


	if(!$_SESSION['app_conf']){
		$_SESSION['app_conf']=$app_default;
	}
	$tpl->tplblocksign('app_default',$_SESSION['app_conf']);//默认的设置
	$tpl->tpllangsign($app_lang);//语言处理

	$tpl->tplshow();

}

/*
* Usage:第一步的版权信息;
*/
function showstep0(){
	global $tpl_path,$conf,$conf_copyright;
	$tpl_main=new tpl($tpl_path.'step0.html');
	foreach ($conf['COPYRIGHT'] as $k=>$c){
		$type=$c['COPY_TYPE'];
		$c1=$conf_copyright[$type];
		$copy[$k]=array_merge($c,$c1);
	}
	$tpl_main->tplblocksign("copyright",$copy); //替换
	$content=$tpl_main->tplreturn();
	return $content;
}

/*
* Usage:第二步检查基本条件;
*/
function showstep1(){
	global $tpl_path,$conf,$app_lang,$app_check;
	$tpl_main=new tpl($tpl_path.'step1.html');
	foreach ($app_check as $a=>$c){
		if($c['ischeck']){
			$check[]=$a($c);
		}
	}
	$tpl_main->tplblocksign("check",$check); //替换
	$content=$tpl_main->tplreturn();
	return $content;
}

/*
* Usage:第三步显示数据库设置;
*/
function showstep2(){
	global $tpl_path,$conf,$app_default;
	$tpl_main=new tpl($tpl_path.'step2.html');

	$tpl_main->tplissign('issvn',count($conf['APP_SVN']));//是否有SVN下载
	$tpl_main->tplissign('issql',count($conf['APP_SQL']));//是否有SQL文件导入
	$tpl_main->tplissign('isdb',count($app_default));//是否需要设定数据库
	$tpl_main->tplissign('isroot',$conf['APP_ROOT']);//是否需要copy文件

	$tpl_main->tplblocksign("APP_SVN",$conf['APP_SVN']); //替换
	$tpl_main->tplblocksign("APP_SQL",$conf['APP_SQL']); //替换
	$content=$tpl_main->tplreturn();
	return $content;
}

/*
* Usage:第四步获取文件;
*/
function showstep3(){
	global $tpl_path,$conf;
	$tpl_main=new tpl($tpl_path.'step3.html');
	$url='?action=svn&step=3';
	$tpl_main->tplsign("url",$url); //替换
	$content=$tpl_main->tplreturn();
	return $content;
}

/*
* Usage:第五步显示数据库设置;
*/
function showstep4(){
	global $tpl_path,$conf;
	$tpl_main=new tpl($tpl_path.'step4.html');
	$url='?action=sql&step=4';
	$tpl_main->tplsign("url",$url); //替换
	$content=$tpl_main->tplreturn();
	return $content;
}

function showstep5(){
	global $tpl_path,$conf;
	$tpl_main=new tpl($tpl_path.'step5.html');
	$tpl_main->tplblocksign("app_info",$conf['APP_INFO']); //替换
	$tpl_main->tplsign("app_root",$conf['APP_ROOT']); //替换
	$tpl_main->tplsign("app_name",$conf['APP_NAME']); //替换
	$content=$tpl_main->tplreturn();
	@file_put_contents('./install_lock','installed');
	return $content;
}

function checkstep0(){
	global $conf;
	$app_count=count($conf['COPYRIGHT']);
	$allow_count=count($_GET['allow']);
	if($app_count==$allow_count){
		remark_setup(1);
		return true;
	}else{
		remark_setup(0);
		return false;
	}
}

function checkstep1(){
	global $app_check;
	foreach ($app_check as $a=>$c){
		if($c['ischeck']){
			$check=$a($c);
			if($check['pass']==false){
				remark_setup(0);
				return false;
			}
		}
	}
	remark_setup(1);
	return true;
}

function checkstep2(){
	global $conf,$app_default;
	if(count($app_default)!=0){
		//之后会支持多数据库
		$_SESSION['app_conf'][0]['mysql_host']=$_GET['mysql_host'];
		$_SESSION['app_conf'][0]['mysql_port']=$_GET['mysql_port'];
		$_SESSION['app_conf'][0]['mysql_user']=$_GET['mysql_user'];
		$_SESSION['app_conf'][0]['mysql_pwd']=$_GET['mysql_pwd'];
		$_SESSION['app_conf'][0]['db_name']=$_GET['db_name'];
		$_SESSION['app_conf'][0]['db_pre']=$_GET['db_pre'];
		$_SESSION['app_conf'][0]['app_root']=$_GET['app_root'];
		$_SESSION['app_conf'][0]['db_isdrop']=$_GET['db_isdrop'];
		$dbserver=$_GET['mysql_host'].(isset($_GET['mysql_port'])?(":".$_GET['mysql_port']):'');
		$conn=@mysql_connect($dbserver,$_GET['mysql_user'],$_GET['mysql_pwd']);	//连接到MySQL Server
		if(!$conn){
			$notes=array('notes'=>"数据库设置不正确!");
			remark_setup(0,$notes);
			return false;
		}else{
			$_SESSION['setup_conf']['conf']=$_SESSION['app_conf'][0];
		}

	}

	if(count($conf['APP_SVN'])>0){
		foreach ($conf['APP_SVN'] as $s){
			$svn  = new phpsvnclient($s['SVN_URL'],$s['SVN_USER'],$s['SVN_PWD']);
			$files= $svn->getDirectoryFiles($s['SVN_DIR']);
			if(count($files)==0){
				$notes=array('notes'=>$s['SVN_TITLE']."-SVN设置不正确!");
				remark_setup(0,$notes);
				return false;
			}else{
				$setup_conf_svn[]=$s;
			}
		}
		$_SESSION['setup_conf']['svn']=array_clear($setup_conf_svn);
		//过滤重复的数组
	}

	if(count($conf['APP_SQL'])>0){
		foreach ($conf['APP_SQL'] as $s){
			$files=$s['SQL_FILE'];
			if(!file_exists($files)){
				$notes=array('notes'=>$s['SQL_INFO'].'-'.$s['SQL_FILE'].'-'."-SQL文件不存在!");
				remark_setup(0,$notes);
				return false;
			}else{
				$setup_conf_sql[]=$s;
			}
		}
		$_SESSION['setup_conf']['sql']=array_clear($setup_conf_sql);
		//过滤重复的数组
	}

	remark_setup(1);
	return true;

}

function checkstep3(){
	return true;
}

function checkstep4(){
	return true;
}


function dodel(){
	session_destroy();
	delDir("../Setup");
	header("location:../index.php");
}

function doout(){
	session_destroy();
	echo "<script language='JavaScript'>window.close();</script>";
}

function dosql(){
	global $conf;
	//global $app_tmppath;
	$sv=$_SESSION['setup_conf']['sql'];
	$db_conf=$_SESSION['setup_conf']['conf'];
	//创建配置文件
	$set['DB_TYPE']='mysql';
	$set['DB_HOST']=$db_conf['mysql_host'];
	$set['DB_NAME']=$db_conf['db_name'];
	$set['DB_USER']=$db_conf['mysql_user'];
	$set['DB_PWD']=$db_conf['mysql_pwd'];
	$set['DB_PORT']=$db_conf['mysql_port'];
	$set['DB_PREFIX']=$db_conf['db_pre'];
	$set['FILE_UPLOAD_MAXSIZE']='1024000000';
	$set['FILE_UPLOAD_ALLOWEXTS']='jpg,gif,png,bmp';
	$set['DEFAULT_CURRENCIES_SYMBOL']='USD';
	$arr="<?php\n return ".var_export($set,"true").";\n?>";
	$fp=fopen("../config.php","w");
	fwrite($fp,$arr);
	fclose($fp);
	$dbserver=$db_conf['mysql_host'].(isset($db_conf['mysql_port'])?(":".$db_conf['mysql_port']):'');
	$conn=@mysql_connect($dbserver,$db_conf['mysql_user'],$db_conf['mysql_pwd']);	//连接到MySQL Server
	if($conn){
		if($db_conf['db_isdrop']!=''){
			@mysql_query("DROP DATABASE IF EXISTS `".$db_conf['db_name']."` ;") or die(ErrorInfo());	 //按条件删除原DB
			msg("创建数据库 ...<br />");
		}
		@mysql_query("CREATE DATABASE IF NOT EXISTS `".$db_conf['db_name']."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;") or die(ErrorInfo());		//创建DB
		msg("创建数据库".$db_conf['db_name'].",OK!");
		@mysql_select_db($db_conf['db_name'],$conn) or die(ErrorInfo());	//打开数据库
		msg( "正在创建数据表...");
	}
	if(count($sv)>0){
		@mysql_query("set names utf8");
		foreach ($sv as $s){
			$files=$s['SQL_FILE'];
			if(file_exists($files)){
				msg('<font color="green">正在处理文件:'.$files.'</font>',0);
				$fp=fopen($files,'r');
				while($SQL=GetNextSQL($fp)){
					$SQL=str_replace('{key}',$db_conf['db_pre'],$SQL);
					if (@mysql_query($SQL)){
						$SQL=str_replace("\r",'<br>',$SQL);
						msg('<br><font color="green">处理SQL成功:</font><br>'.$SQL);
					}else{
						msg('<br>处理SQL失败:</font><br>'.$SQL,0);
					}
				}

				/*$sqlcontent=file_get_contents($files);
				$sqlcontent=str_replace('{key}',$db_conf['db_pre'],$sqlcontent);
				$sqlcontent=explode(';',$sqlcontent);
				foreach ($sqlcontent as $sql){
				if(trim($sql)!=''){
				if(@mysql_query($sql)){
				//$sql=str_replace("\r",'<br>',$sql);
				msg('<br><font color="green">处理SQL成功:</font><br>'.$sql);
				}else{
				msg('<br>处理SQL失败:</font><br>'.$sql,0);
				}
				}
				}
				*/
			}else{
				msg('处理文件:'.$files.'失败!',0);
			}
		}
	}
	remark_setup(1);
	echo "<script language='JavaScript'>top.godourl(5);</script>";
}
//从文件中逐条取SQL
function GetNextSQL($fp) {
	$sql="";
	while ($line = @fgets($fp, 40960)) {
		$line = trim($line);
		/*
		//以下三句在高版本php中不需要
		$line = str_replace("\\\\","\\",$line);
		$line = str_replace("\'","'",$line);
		$line = str_replace("\\r\\n",chr(13).chr(10),$line);
		// $line = stripcslashes($line);
		*/
		if (strlen($line)>1) {
			if ($line[0]=="-" && $line[1]=="-") {
				continue;
			}
		}
		$sql.=$line.chr(13).chr(10);
		if (strlen($line)>0){
			if ($line[strlen($line)-1]==";"){
				break;
			}
		}
	}
	return $sql;
}

function dosvn(){
	global $app_tmppath;
	$svntemppath=$app_tmppath."svn/";
	$sv=$_SESSION['setup_conf']['svn'];
	$root=$_SESSION['setup_conf']['conf']['app_root'];
	if(count($sv)>0){
		//deldir($svntemppath);
		foreach ($sv as $v=>$s){
			$temppath=$svntemppath."$v/";
			msg('正在从'.$s['SVN_URL'].'上下载文件,请耐心等待……<br>');
			$svn  = new phpsvnclient($s['SVN_URL'],$s['SVN_USER'],$s['SVN_PWD']);
			$files= $svn->getDirectoryTree($s['SVN_DIR']);
			foreach ($files as $k=>$f){
				if($f['type']=='file'){
					$content=$svn->getFile($f['path']);
					$file=str_replace($s['SVN_DIR'],$temppath,$f['path']);
					write_file($file,$content);
				}
			}
		}
		foreach ($sv as $v=>$s){
			$temppath=$svntemppath."$v/";
			copydir($temppath,$root.$s['DIR_PATH']);
		}

	}
	remark_setup(1);
	echo "<script language='JavaScript'>top.godourl(4);</script>";
}

?>