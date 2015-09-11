<?php
/*
***************************************************************************
*   maker.tp@gmail.com                                                    *
*   Usage:公共的函数集合                                             *
***************************************************************************
*/

/*写入
* Usage: $this->write_file($content);
**/
function write_file($file,$content,$mode='w+')
{
	mk_dir($file);
	if (!$fp = @fopen($file,$mode)) {
		msg($file." 目录或者文件属性无法写入.",0);
		Return false;
	} else{
		msg($file." 目录或者文件写入成功.",1);
		@fwrite($fp,$content);
		@fclose($fp);
		@umask($oldmask);
		Return true;
	}
}

/*
* Usage: $this->mk_dir();
*/
function mk_dir($file)
{    //$file    = str_replace('','/');
	$dir    = @explode("/", $file);
	$num    = @count($dir)-1;
	$tmp    = './';
	for($i=0; $i<$num; $i++){
		$tmp    .= $dir[$i];
		if(!file_exists($tmp)){
			@mkdir($tmp);
			@chmod($tmp, 0777);
		}
		$tmp    .= '/';
	}
}

/*
* Usage: 删除目录函数
*/
function deldir($dirName)
{
		$result = false;
		if(!is_dir($dirName))
		{
			return false;
		}
		$handle = opendir($dirName);
		while(($file = readdir($handle)) !== false)
		{
			if($file != '.' && $file != '..')
			{
				$dir = $dirName . DIRECTORY_SEPARATOR . $file;
				is_dir($dir) ? deldir($dir) : unlink($dir);
			}
		}
		closedir($handle);
		$result = @rmdir($dirName) ? true : false;
		return $result;
}

function dirpath($sdir)
{
	if(substr($sdir,-1,1)=='/'){
		$sdir=substr($sdir,0,-1);
	}
	return $sdir;
}
/*
* Usage: 拷贝目录函数
*/
function copydir($source,$destination)
{
	$source=dirpath($source);
	$destination=dirpath($destination);
	$result = true;
	if(!is_dir($source)){
		//读取源目录
		msg("源目录名称错误",0);
	}
	if(!is_dir($destination)){
		//读取目标目录
		if(!mk_dir($destination)){
			msg("无法创建目标目录",0);
		}
	}
	$handle =opendir($source);//取得目录的句柄
	while(($file=readdir($handle))!==false){
		if($file!='.' && $file!='..'){
			$src = $source."/".$file;		//源文件路径
			$dtn = $destination."/".$file;	//目标文件路径
			if(is_dir($src)){
				copydir($src,$dtn);
			}else{
				copyfile($src,$dtn);
			}
			//msg($src.'--->'.$dtn.'<br>');
		}
	}
	closedir($handle);

	return $result;
}

function copyfile($infilename,$outfilename) {

	$content=file_get_contents($infilename);
	msg('正在COPY生成'.$outfilename);
	write_file($outfilename,$content);
}

function msg($info,$isok=1)
{
	$msgtime=microtime();
	if($isok==1){
		echo "<font style='font-size:12px'>".$info."</font>";
	}else{
		echo "<font style='font-size:12px;color:red'>".$info."</font>";
	}
	echo "<br><a name='".$msgtime."'></a>";
	echo "<script language='JavaScript'>document.location.href='#".$msgtime."';</script>";
	ob_flush();
	flush();
}

/*记录每一步的信息*/
function remark_setup($sta,$err=array())
{
	global $app_lang;
	$tep=($_GET['step']!='')?$_GET['step']:0;
	$tep=intval($tep);
	if($sta==1){
		$_SESSION['steup'][$tep]['pass']=true;
		$_SESSION['steup'][$tep]['err']=array();
	}else{
		$_SESSION['steup'][$tep]['pass']=false;
		if(count($err)==0){
			//先显示一个以后扩展再变
			$_SESSION['steup'][$tep]['err'][0]=array('notes'=>$app_lang['err_step'.$tep]);
		}else{
			$_SESSION['steup'][$tep]['err'][0]=$err;
		}
		$_SESSION['steup'][$tep]['err']=array_unique($_SESSION['steup'][$tep]['err']);
		//过滤重复的数组
	}
}

/*判断是否已经通过步骤,如果通过则转向*/
function steupgo($step)
{
	if($_SESSION['steup'][$step]['pass']){
		header("location:?action=show&step=".($step+1));
	}else{
		header("location:?action=show&step=".$step);
	}
}

/*去掉重复元素(包括一维数组)后的二维数组*/
function array_unique_fb($array2D){
	foreach ($array2D as $v){
		$v = join(",",$v);              //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
		$temp[] = $v;
	}
	$temp = array_unique($temp);      //去掉重复的字符串,也就是重复的一维数组
	foreach ($temp as $k => $v){
		$temp[$k] = explode(",",$v);       //再将拆开的数组重新组装
	}
	return $temp;
}


/*去掉重复维数组,二维数组*/
function array_clear($array2D){
	$temp=array();
	foreach ($array2D as $v){
		if(!in_array($v,$temp)){
			$temp[] = $v;
		}
	}
	return $temp;
}

?>