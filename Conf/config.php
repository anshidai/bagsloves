<?php
$config	=	require './config.php';
$array=array(

//项目参数
'SYSTEM_NAME'=>'EasyCart',
'SYSTEM_VAR'=>'3.0RC1预览版',

//分页标示
'VAR_PAGE'=> 'p', 

//URL模式,1=>pathinfo模式,2=>隐藏index.php
'URL_MODEL'=>1,
'TMPL_SWITCH_ON'			=>	true,
'TMPL_DETECT_THEME'     => true,
'TMPL_ACTION_ERROR'=>'Public:success',
'TMPL_ACTION_SUCCESS'=>'Public:success',
'TMPL_EXCEPTION_FILE'=>THINK_PATH.'/Tpl/think_exception_en.tpl',
'ERROR_MESSAGE'=>'The page you are browsing a temporary error occurred! Please try again later ~',
'THINK_PLUGIN_ON' =>true,
/*'URL_ROUTER_ON'=>true,
'URL_ROUTE_RULES'=>array(
	
),*/
//自动加载类库的路径
'APP_AUTOLOAD_PATH'=>'ORG.Util',
//多语言
'LANG_SWITCH_ON'=>true,
'LANG_AUTO_DETECT'=>true,
'DEFAULT_LANG'   =>	'en-us',


//允许上传的文件类型
'FILE_UPLOAD_ALLOWEXTS'=>'jpg,jpeg,bmp,png,gif,tif,zip,rar,doc,xls,7z,rtf,csv',


'URL_HTML_SUFFIX'=>'.html',
'DEFAULT_THEME'=>'default',
'URL_PATHINFO_DEPR'=>'-',


//分组
'APP_GROUP_LIST'=>'Admin,Home,Rpc',
'DEFAULT_GROUP'=>'Home',
'TMPL_FILE_DEPR'=>"-",

//权限
'USER_AUTH_ON'=>true,
'USER_AUTH_TYPE'			=>2,		// 默认认证类型 1 登录认证 2 实时认证
'USER_AUTH_KEY'			=>'authId',	// 用户认证SESSION标记
'ADMIN_AUTH_KEY'			=>'administrator',
'USER_AUTH_MODEL'		=>'User',	// 默认验证数据表模型
'AUTH_PWD_ENCODER'		=>'md5',	// 用户认证密码加密方式
'USER_AUTH_GATEWAY'	=>'/AdminPublic-adminlogin',	// 默认认证网关
'NOT_AUTH_MODULE'		=>'AdminPublic',		// 默认无需认证模块
'REQUIRE_AUTH_MODULE'=>'',		// 默认需要认证模块
'NOT_AUTH_ACTION'		=>'',		// 默认无需认证操作
'REQUIRE_AUTH_ACTION'=>'',		// 默认需要认证操作
'GUEST_AUTH_ON'          => false,    // 是否开启游客授权访问
'GUEST_AUTH_ID'           =>    0,     // 游客的用户ID
'DB_LIKE_FIELDS'=>'title|remark',
'RBAC_ROLE_TABLE'=>'role',
'RBAC_USER_TABLE'	=>	'role_user',
'RBAC_ACCESS_TABLE' =>	'access',
'RBAC_NODE_TABLE'	=> 'node',
'TOKEN_ON'=>false,


//调试
'SHOW_DIY_TRACE'  =>0,
'SHOW_PAGE_TRACE' =>0,
'SHOW_ADV_TIME'   =>0,
'LOG_RECORD'      => false,
'LOG_LEVEL'       => 'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL',// 允许记录的日志级别
);
return array_merge($config,$array);
?>