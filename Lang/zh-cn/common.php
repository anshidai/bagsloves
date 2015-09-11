<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-17
*/ 
$array=array(
'DO_OK'=>'操作成功',
'HOME'=>'主页',
'ABOUT_US'=>'关于我们',
'CONTACT_US'=>'联系我们',
'NEW_PRODUCTS'=>'新进产品',
'NEED_HELP'=>'需要帮助',
'LEAVE_MESSAGE'=>'留言',
'ALL_CATEGORY'=>'所有分类',
'HOT_SEARCH'=>'热门搜索',
'TAGS'=>'标签',
'BROSWER_HISTORY'=>'浏览历史',
'NEW_ARRIVAL_ITEMS'=>'新上架物品',
'HOT_SALES_ITEMS'=>'热销物品',
'SPECIAL_OFFERS'=>'特价',
'LOGIN'=>'登录',
'USER_NAME'=>'用户名',
'PASSWORD'=>'密码',
'SIGN_IN'=>'登录',
'JOIN_FREE'=>'免费加入',
'BUYER_GUIDE'=>'买家指南',
'GOT_A_QUESTION'=>'有个问题',
'SEND_AN_INQUIRY'=>'询盘',
'TOP_10_BEESTSELLERS'=>'十大热销',
'SEARCH'=>'搜索',
'COPYRIGHT'=>'版权',
'ALL_RIGHT_RESERVED'=>'保留所有权利',
'YOUR_POSITION'=>'您的位置',
'SORT_BY_TIME'=>'按时间排序',
'SORT_BY_PRICE'=>'按价格排序',
'SORT_BY_UPDATE'=>'按更新排序',
'DESCENDING'=>'降序',
'ASCENDING'=>'升序',
'ALL_PRODUCTS'=>'所有产品',
'DISPLAY'=>'显示',
'TOTAL'=>'总共',
'RECORDS'=>'记录',
'PAGES'=>'页',
'NEXT'=>'下一页',
'LAST'=>'最后一页',
'PRODUCT_NAME'=>'产品名称',
'PRODUCT_ID'=>'产品ID',
'PRICE'=>'价格',
'ORIGINAL_PRICE'=>'原始价格',
'SIZE'=>'尺寸',
'QUANTITY'=>'数量',
'ADD_TO_CART'=>'添加到购物车',
'DESCRIPTION'=>'描述',
'RELATED_PRODUCTS'=>'相关产品',
'STEP1'=>'第一步',
'THE_SHOPPING_CART'=>'购物车',
'SHOPPING_ADDRESS'=>'购物地址',
'FINISH_ORDER'=>'完成订单',
'ITEM_NAME'=>'名称',
'MODEL'=>'型号',
'PRICE'=>'价格',
'TOTAL'=>'总计',
'DELETE'=>'删除',
'SUB_TOTAL'=>'小计',
'SHIPPING_FEE'=>'运费',
'INSURANCE'=>'保险',
'PAY_FEE'=>'支付费用',
'TOTAL_PRICE'=>'总价',
'CONTINUE_SHOPPING'=>'继续购物',
'CLEAR_CART'=>'清空购物车',
'CHECKOUT'=>'结帐',
'MEMBERS_CENTRE'=>'会员中心',
'MY_ACCOUNT_INFORMATION'=>'我的帐号信息',
'ENTER_YOUR_EMAIL_ADDRESS_AND_CREATE_A_PASSWORD'=>'输入您的电子邮件地址和创建一个密码',
'NOTE:'=>'注',
'FIRST_NAME'=>'名',
'LAST_NAME'=>'姓',
'EMAIL_ADDRESS'=>'邮件地址',
'PASSWORD'=>'密码',
'CONFIRM_PASSWORD'=>'确认密码',
'CREATE_NEW_ACCOUNT'=>'创建新帐号',
'RETURNING_CUSTOMERS'=>'老顾客',
'EMAIL'=>'电子邮件',
'PASSWORD'=>'密码',
'FORGOT_YOUR_PASSWORD'=>'忘记密码',
'WISH_LIST'=>'收藏列表',
'CHANGE_PSSWORD'=>'更改密码',
'LOG_OUT'=>'注销',
'USER_CENTER'=>'用户中心',
'HELP'=>'帮助'
);
$alipay	=	require LANG_PATH.C('DEFAULT_LANG').'/alipay.php';
$array= array_merge($alipay,$array);
$member=require LANG_PATH.C('DEFAULT_LANG').'/member.php';
$array= array_merge($member,$array);
$cart=require LANG_PATH.C('DEFAULT_LANG').'/cart.php';
$array= array_merge($cart,$array);
return $array;
?>