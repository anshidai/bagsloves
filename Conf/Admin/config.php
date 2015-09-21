<?php
$config	=	require './config.php';
$config2 =	require './Conf/config.php';


//Admin分组配置
$array=array(

//多语言
'LANG_SWITCH_ON'=>true,
'LANG_AUTO_DETECT'=>false,
'DEFAULT_LANG'=>'zh-cn',

'TAGLIB_PRE_LOAD' => 'html'
);
return array_merge($config,$config2,$array);
?>