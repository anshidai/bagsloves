<?php

if (isset($_GET['GLOBALS']) ||isset($_POST['GLOBALS']) ||  isset($_COOKIE['GLOBALS']) || isset($_FILES['GLOBALS'])) {
	die;
}
if (@ini_get('magic_quotes_sybase') != 0) @ini_set('magic_quotes_sybase', 0);

/*if (MAGIC_QUOTES_GPC) {
	if(!function_exists('stripslashes_deep')) {
		function stripslashes_deep($value) {
			$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
			return $value;
		}
	}
	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}*/
Load('extend');

function addslashes_deep($string) {
	if(!MAGIC_QUOTES_GPC) {
		$_GET = sop_addslashes($_GET);
		$_POST = sop_addslashes($_POST);
		$_COOKIE = sop_addslashes($_COOKIE);
	}
	if(!is_null($string)){
		sop_addslashes($string);
	}
}
function sop_addslashes($string, $force = 1) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			unset($string[$key]);
			$string[addslashes($key)] = sop_addslashes($val, $force);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

/*产品JQzoom集合*/
function productzoompicpath($list,$i){
	return "./Public/Uploads/Products/detailgallery/".strtoupper(trim($list["picprefix"]))."_".$i.".jpg";
}

//统计数量  产品JQzoom
function productzoompiccount($list){
	$dirpath = "./Public/Uploads/Products/zoomgallery/";
	$i = 1;
	while(true){
		$filepath = $dirpath.strtoupper(trim($list["picprefix"]))."_".$i.".jpg";
		$fileisexist = file_exists($filepath);
		if($fileisexist){
			$i++;
		}else{
			break;
		}
	}
	return $i-1;
}

/*产品主图*/
function productmainpicpath($list){
	return "./Public/Uploads/Products/".strtoupper(trim($list["picprefix"])).".jpg";
}

/*产品详情图片集合*/
function productdetailpicpath($list,$i){
	return "./Public/Uploads/Products/detailgallery/".strtoupper(trim($list["picprefix"]))."_".$i.".jpg";
}
//统计数量   产品详情图片
function productdetailpiccount($list){
	$dirpath = "./Public/Uploads/Products/detailgallery/";
	//glob($list["picprefix"])
	$i = 1;
	while(true){
		$filepath = $dirpath.strtoupper(trim($list["picprefix"]))."_".$i.".jpg";
		$fileisexist = file_exists($filepath);
		if($fileisexist){
			$i++;
		}else{
			break;
		}
	}
	return $i-1;
}







function product_history(){
	if(isset($_SESSION['product_history'])){
		$proModel=D("Products");
		$info=$proModel->where(array('id'=>array('in',$_SESSION['product_history'])))->select();
		return $info;
	}
	return '';
}

function format_size($size) {
	$sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	if ($size == 0) { return('n/a'); } else {
		return (round($size/pow(1024, ($i = floor(log($size, 1024)))), $i > 1 ? 2 : 0) . $sizes[$i]); }
}

function products_ask($str){
	switch ($str){
		case 'Ask':
			return '询问';
		case 'Buy':
			return '购物';
		case 'Message':
			return '留言';
		case 'Other':
			return '其它';
		case 'Contact Us':
			return '联系我们';
		case 'Review':
			return '评论';
		default:
			return $str;
	}
}
function orderstatus_convert($status,$lang='en'){
	if($lang=='en'){
		switch ($status){
			case '等待中':
				return 'Pending';
			case '处理中':
				return 'Processing';
			case '已关闭':
				return 'Close';
			case '已发货':
				return 'Delivered';
			default:
				return $status;
		}
	}else{
		switch ($status){
			case 'Pending':
				return '等待中';
			case 'Processing':
				return '处理中';
			case 'Close':
				return '已关闭';
			case 'Delivered':
				return '已发货';
			default:
				return $status;
		}
	}
}
function build_url($vo,$type){
	switch ($type){
		case 'pro_url':
			$url=U(tourlstr($vo['name']).'/pid/'.$vo['id']);
			return $url;
		case 'pid':
			$url=U(tourlstr($vo['name']).'/pid/'.$vo['pid']);
			return $url;
		case 'products_id':
			$url=U(tourlstr($vo['name']).'/pid/'.$vo['products_id']);
			return $url;
		case 'pro_name':
			return $vo['name'];
		case 'pro_smallimage':
			return __ROOT__.'/'.$vo['smallimage'];
		case 'pro_bigimage':
			return __ROOT__.'/'.$vo['bigimage'];
		case 'altimg':
			if(GetValue('isBigimg')==0){
				return '';
			}
			return __ROOT__.'/'.$vo['bigimage'];
		case 'pro_price':
			return getprice($vo['price'],$vo['pricespe']);//特价表示
			//return $vo['price'];
		case 'pro_pricespe':
			return $vo['pricespe'];
		case 'cate_url':
			return $url=U(tourlstr($vo['name']).'/cid/'.$vo['id']);
		case 'cate_name':
			return $vo['name'];
		case 'ad_name':
			return $vo['name'];
		case 'ad_remark':
			return $vo['remark'];
		case 'ad_link':
			return $vo['link'];
		case 'ad_img':
			return __ROOT__.'/'.$vo['img_url'];
		case 'article_url':
			return U('Article/index',array('id'=>$vo['id']));
		case 'article_title':
			return $vo['title'];
		case 'article_dateline':
			return date('Y-m-d H:i:s',$vo['dateline']);
		case 'cate_img':
			return __ROOT__.'/'.$vo['imgurl'];
		case 'g_bigimage':
			return __ROOT__.'/'.$vo['img_url'];
		case 'g_smallimage':
			return __ROOT__.'/'.$vo['thumb_url'];
		case 'hotmail'://msn
		$r=array();
		foreach (explode(',',$vo) as $k=>$v){
			$r[$v]="msnim:chat?contact=".$v;
		}
		return $r;
		case 'yahoo'://yahoo
		$r=array();
		foreach (explode(',',$vo) as $k=>$v){
			$r[$v]="ymsgr:sendIM?".$v;
		}
		return $r;
		case 'email'://email
		$r=array();
		foreach (explode(',',$vo) as $k=>$v){
			$r[$v]="mailto:".$v;
		}
		return $r;
		case 'skype'://skype
		$r=array();
		foreach (explode(',',$vo) as $k=>$v){ 
			$r[$v]="callto://".$v;
		}
		return $r;
		case 'qq'://qq
		$r=array();
		foreach (explode(',',$vo) as $k=>$v){
			$r[$v]="tencent://Message/?Menu=YES&Uin=".$v."&websiteName=im.qq.com";
		}
		return $r;
		case 'gtalk'://gtalk
		$r=array();
		foreach (explode(',',$vo) as $k=>$v){
			$r[$v]="gtalk:chat?jid=".$v."&from_jid=";
		}
		return $r;
		case 'tel'://tel
		$r=array();
		foreach (explode(',',$vo) as $k=>$v){
			$r[$v]=$v;
		}
		return $r;
	}

}
//调用购物车购买数量
function itemCount(){
	return D( "Cart" )->get_item_count ( Session::get ( 'sessionID' ) );
}
function TotalCount(){
	return D( "Cart" )->get_item_totalcount ( Session::get ( 'sessionID' ) );
}
function cart_total(){
	return D( "Cart" )->cart_total ( Session::get ( 'sessionID' ) );
}
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}

/**
	 +----------------------------------------------------------
 * 获取缩略图名称
	 +----------------------------------------------------------
 * @param string $filename 原图名称
	 +----------------------------------------------------------
 * @return string
	 +----------------------------------------------------------
 */
function get_thumb_name($filename){
	$file_part=pathinfo($filename);
	return $file_part['dirname']."/thumb_".$file_part['basename'];
}
function get_sn(){
	return toDate(time(),'YmdHis');
}
function getOrderSN(){
	$dao=D('Orders');
	$OrderNo	= $dao->query("show table status where name='__TABLE__'");
	return "P".sprintf("%04d",$OrderNo[0]['Auto_increment']);
}
/**
 * 输出Meta内容
 *
 * @param String $pagetitle 标题
 * @param String $pagekeywords 关键词
 * @param String $pagedesc 描述
 * @param String $list 相关信息
 */
function Meta($pagetitle='',$pagekeywords='',$pagedesc='',$list=''){
	$sitename=GetValue('sitename');
	$Meta="";
	//标题
	if(!empty($pagetitle)){
		$Meta.="<title>$pagetitle </title>\n";
	}else{
		$Meta.="<title>$sitename</title>\n";
	}
	//关键词
	$Meta.='<meta name="keywords" content="';
	if(!empty($pagekeywords)){
		$Meta.=$pagekeywords;
	}else{
		$Meta.=GetValue('keywords');
	}
	$Meta.="\" />\n";

	//描述
	$Meta.='<meta name="description" content="';
	if(!empty($pagedesc)){
		$Meta.=$pagedesc;
	}else{
		$Meta.=GetValue('description');
	}
	$Meta.="\" />\n";
	return $Meta;

}
function pwdHash($password, $type = 'md5') {
	return hash ( $type, $password );
}
function getGroupName($id) {
	if ($id == 0) {
		return '无上级组';
	}
	$dao = D( "Role" );
	$list = $dao->find ( array ('id' => $id ) );

	$name = $list ['name'];
	return $name;
}
function getNodeName($id) {
	if ($id == 0) {
		return '无上级组';
	}
	$dao = D( "Node" );
	$list = $dao->find ( array ('id' => $id ) );

	$name = $list ['title'];
	return $name;
}

function getStatus($status, $imageShow = false) {
	switch ($status) {
		case 0 :
			$showText = '禁用';
			$showImg = '<IMG SRC="'.__PUBLIC__.'/skin/images/mod_0.gif" WIDTH="15" HEIGHT="15" BORDER="0" ALT="禁用">';
			break;
		case 2 :
			$showText = '待审';
			$showImg = '<IMG SRC="'.__PUBLIC__.'/skin/images/prected.gif" WIDTH="15" HEIGHT="15" BORDER="0" ALT="待审">';
			break;
		case - 1 :
			$showText = '删除';
			$showImg = '<IMG SRC="'.__PUBLIC__.'/skin/images/del.gif" WIDTH="15" HEIGHT="15" BORDER="0" ALT="删除">';
			break;
		case 1 :
			$showText = '正常';
			$showImg = '<IMG SRC="'.__PUBLIC__.'/skin/images/mod_1.gif" WIDTH="15" HEIGHT="15" BORDER="0" ALT="正常">';

	}
	return ($imageShow === true) ?  $showImg  : $showText;
}
//树的前字符操作
function class_str_insert($deep, $str) {
	$r = "";
	for($i = 0; $i < $deep; $i ++) {
		$r .= $str;
	}
	return $r;
}

//获取前台分类树
function get_indexcate_arr($pid=0){
	if (S ( 'S_INDEXCATETREE' ) == "") {
		S('S_INDEXCATETREE',get_subcate_arr());
	}
	return S ( 'S_INDEXCATETREE' );
}
function get_subcate_arr($pid=0){
	$data=array();
	$cate=get_catetree_arr();
	$count=0;
	$model=D('Cate');
	$productCount=GetValue('productCount');
	for($i=0;$i<count($cate);$i++){
		if ($cate[$i]['pid']==$pid){
			$data[$count]=$cate[$i];
			$data[$count]['sub']=get_subcate_arr($cate[$i]['id']);
			$data[$count]['subcount']=count(get_subcate_arr($cate[$i]['id']));
			if($productCount==1){
				$data[$count]['procount']=$model->get_cate_pro_num($cate[$i]['id']);
			}
			$count++;
		}
	}
	return $data;
}

//获取分类树
function get_artcate_arr() {
	$DAO = D( "Article_cate" );
	$result = $DAO->order ( 'sort desc' )->select ();
	$arr = parse ( $result );
	return $arr;
}
function get_catetree_arr() {
	if (S ( 'S_CATETREE' ) == "") {
		$classDAO = D( "Cate" );
		$result = $classDAO->order ( 'sort desc' )->select ();
		$arr = parse ( $result );
		S('S_CATETREE',$arr);
	}

	return S ( 'S_CATETREE' );
}
//获取虚拟分类树
function get_virtualcat_arr() {
	$DAO = D( "Virtual_cat" );
	$result = $DAO->order ( 'sort desc' )->select ();
	$arr = parse ( $result );
	return $arr;
}

//获取节点树
function get_nodetree_arr() {
	$classDAO = D( "Node" );
	$result = $classDAO->order ( 'sort' )->select ();


	$arr = parse ( $result );
	return $arr;
}
//获取权限角色树

//class_tree

function get_roletree_arr() {
	$classDAO = D( "Role" );
	$result = $classDAO->order ( 'sort' )->select ();

	/*	foreach($result as $var){
	$arr[count($arr)]=$var;
	$arr=get_classtree_arr($var['cid'],$arr);
	}*/
	$arr = parse ( $result );
	return $arr;
}
function parse($tree_arr) {
	uasort ( $tree_arr, "parent_cmp" );
	$tree = array ();
	$index = array ();
	$deep = 0;
	foreach ( $tree_arr as $v ) {
		if ($v ['pid'] == 0) {
			$key = '0_' . $v ['sort'] . '_' . $v ['id'] . '_';
			$v ['deep'] = 0;
			$v ['key'] = $key;
			$tree [$key] = $v;
		} else {
			$key = $index [$v ['pid']] ['key'] . '_' . $v ['pid'] . '_' . $v ['sort'] . '_' . $v ['id'] . '_';
			$v ['deep'] = count ( explode ( '_', $key ) ) / 4 - 1;
			$v ['key'] = $key;
			$tree [$key] = $v;
		}
		$index [$v ['id']] = & $tree [$key];
	}
	usort ( $tree, 'kcmp' );
	return $tree;
}
function kcmp($a, $b) {
	$akl = strlen ( $a ['key'] );
	$bkl = strlen ( $b ['key'] );
	if ($akl < $bkl && substr ( $b ['key'], 0, $akl ) == $a ['key'])
	return - 1;
	elseif ($akl > $bkl && substr ( $a ['key'], 0, $bkl ) == $b ['key'])
	return 1;
	return strnatcmp ( $b ['key'], $a ['key'] );
}
function parent_cmp($a, $b) {
	if ($a ['pid'] == $b ['pid'])
	return 0;
	return $a ['pid'] > $b ['pid'] ? 1 : - 1;
}

/**
 * 返回存储的值
 *
 * @param String $valuename 名称
 * @return String 值
 */
function GetValue($valuename,$cache=true){
	if (S('S_'.$valuename)=="" || !$cache){
		$setting=D('Setting');
		$map['valuename']=$valuename;
		$settInfo=$setting->where($map)->find();
		if(false == $settInfo) {
			return  null;
		}
		else {
			if(get_magic_quotes_runtime()){
				$settInfo['valuetxt'] = stripslashes($settInfo['valuetxt']);
			}
			S('S_'.$valuename,$settInfo['valuetxt']);
			return S('S_'.$valuename);
		}
	}
	else {
		return S('S_'.$valuename);
	}
}
/**
 * 快速保存一个值到数据库
 * @param String $valuename 名称
 * @param String $valuetxt 值
 */

function SetValue($valuename,$valuetxt=null){
	//echo $valuename."-".$valuetxt."<br>";
	if(is_null($valuetxt)){
		return true;
	}else{
		$setting	=	D('Setting');
		$map['valuename']=$valuename;
		$date['valuetxt']=$valuetxt;
		$info=$setting->where($map)->find();
		if ($info) {
			$setting->where($map)->data($date)->save();
		}
		else {
			$d	=	D('Setting');
			$data['valuename']=$valuename;
			$d->add($data);

		}
		return true;
	}
}
function cleanCache() {
	delFileUnderDir('./Runtime');
}

function delFileUnderDir($directory)
{
    if(is_dir($directory) == false){
        //exit("The Directory Is Not Exist!");
        return false;
    }
    $handle = opendir($directory);
    while(($file = readdir($handle)) !== false) {
        if($file != "." && $file != "..") {
            if(is_dir("$directory/$file")) {
                delFileUnderDir("$directory/$file");    
            }else {
                unlink("$directory/$file");
            }   
        }    
    }
    closedir($handle);
}    

function get_brand_tree(){
	$dao=D("Brand");
	return $dao->where("status=1")->order("sort")->select();
}
//取得广告代码

function get_ad_arr($type) {
	if (S ( 'S_AD_' . $type ) == "") {
		$dao = D ( "Ad" );
		
		$list = $dao->where ( "type='" . $type."'" )->order ( "sort desc" )->select ();
		if($list) {
			foreach($list as $k=>$val) {
				$data[$val['id']] = $val;
			}
		}
		S ( 'S_AD_' . $type, $data) ;
	}
	return S ( 'S_AD_' . $type );
}

function get_ad($type,$field) {
	if (S ( 'S_AD_' . $type ) == "") {
		$dao = D ( "Ad" );
		if(isset($field)){
			$list=$dao->field($field)->where ( "type='" . $type."'" )->order ( "sort desc" )->find();
			$list=$list[$field];
		}else{
			$list=$dao->where ( "type='" . $type."'" )->order ( "sort desc" )->find();
		}
		if($list) {
			foreach($list as $k=>$val) {
				$data[$val['id']] = $val;
			}
		}
		S ( 'S_AD_' . $type,$data) ;
	}
	return S ( 'S_AD_' . $type );
}

//获取货币
function get_currencies_arr(){
	if (S ( 'S_CURRENCIES'  ) == ""){
		$dao = D ( "Currencies" );
		S ( 'S_CURRENCIES',$dao->where('status=1')->order ( "sort desc" )->select ()  );

	}
	return S ( 'S_CURRENCIES'  );
}
/**
 * 获取价格
 *
 * @param Integer $price 价格
 * @param Integer $spe 特价
 */
function getprice($price,$spe,$discount=true){
	//如果没有选择汇率，读取系统默认汇率
	$currencies=get_currencies_arr();
	if (! isset ( $_SESSION ['currency'] )) {
		for($row = 0; $row < count ( $currencies ); $row ++) {
			if ($currencies [$row] ['symbol'] == C ( 'DEFAULT_CURRENCIES_SYMBOL' )) {
				$_SESSION ['currency'] = $currencies [$row];
			}
		}
	}
	if ( $spe >=$price) {
		//货币汇率
		$re= $_SESSION ['currency'] ['code'] . (sprintf("%01.2f", $spe * $_SESSION ['currency'] ['rate']));
		$r_price=$price;
	} else {
		$price *= $_SESSION ['currency'] ['rate'];
		$spe *= $_SESSION ['currency'] ['rate'];
        $spe = sprintf("%01.2f", $spe);
        $price = sprintf("%01.2f", $price);
		if($discount){
			//$re=  '<span style="color:red;text-decoration: line-through;">'.$_SESSION ['currency'] ['code'] . $price . '</span>&nbsp;&nbsp;&nbsp;<span style="color:red;">' . $_SESSION ['currency'] ['code'] . $spe . '</span><br />Save:' . number_format ( (($price - $spe) / $price * 100), 0 ) . '% off';
			$re=  '<span class="make-price">'.$_SESSION ['currency'] ['code'] . $price . '</span><span class="shop-price">' . $_SESSION ['currency'] ['code'] . $spe . '</span>';
		}else{
			$re= $_SESSION ['currency'] ['code'] . $spe;
		}
		$r_price=$spe;
	}
	$memberID = Session::get ( 'memberID' );
	//在价格里头显示vip价格
	if (!$memberID) {
		$memberID = 0;
	}
	$ginfo=get_members_group($memberID);
	if($ginfo){
		if($discount){
			return $re." ".$ginfo["name"].":".$_SESSION ['currency'] ['code'] .$r_price*$ginfo["discount"];
		}
		else{
			return $re;
		}

	}
	return $re;


}
function getprice_str($price){
	return "<strong style='color:red;font-weight: bold;'>".$_SESSION ['currency'] ['code'] . (sprintf("%01.2f", $price * $_SESSION ['currency'] ['rate']))."</strong>";
}
//获取真实的价格
function get_real_price($price,$pricespe){
	if ($price>=$pricespe){
		return $pricespe;
	}
	else{
		return $price;
	}
}
/**
 * 替换冲突url名
 *
 * @param String $name
 * @return String 过滤后
 */
//返回类别导航条
/*function tourlstr($name){
return str_replace(array('*','&','#','（','）',';',' '),'_',$name);
}*/
function tourlstr($string){
	if (preg_match("/[\x{4e00}-\x{9fa5}]+/u", $string)) {
		return $string;
	}
	$depr=C('URL_PATHINFO_DEPR');
	$regex='/[^-a-zA-z0-9_ ]/';
	$string=preg_replace($regex,'',$string);
	$string=trim($string);
	$string=preg_replace('/[-_ ]+/','-',$string);
	$regex="/".
	$depr."(".C('VAR_GROUP').'|'.
	C('VAR_MODULE').'|'.
	C('VAR_ACTION').'|'.
	C('VAR_ROUTER').'|'.
	C('VAR_TEMPLATE').'|'.
	C('VAR_AJAX_SUBMIT').'|'.
	C('VAR_PATHINFO').")".$depr."/";
	$string=preg_replace($regex,"_$1_",$string);//中间
	$regex="/".
	$depr."(".C('VAR_GROUP').'|'.
	C('VAR_MODULE').'|'.
	C('VAR_ACTION').'|'.
	C('VAR_ROUTER').'|'.
	C('VAR_TEMPLATE').'|'.
	C('VAR_AJAX_SUBMIT').'|'.
	C('VAR_PATHINFO').")$/";
	$string=preg_replace($regex,"_$1",$string);//结尾
	return $string;
}
function get_cate_info($cid){
	$cate=get_catetree_arr();
	for ($row=0;$row<count($cate);$row++){
		if ($cate[$row]['id']==$cid) {
			return $cate[$row];
		}
	}
}
function get_cate_nav($id){
	$str="";
	$M=M('Article_cate');
	$list=$M->find($id);
	if($list['pid']==0){
		$str.=" > <a href=\"".__APP__."/Article-index-pid-".$list['id']."\">".$list['name']."</a>";
		return $str;
	}else{
		$str.=" > <a href=\"".__APP__."/Article-index-pid-".$list['id']."\">".$list['name']."</a>";

		$str=get_cate_nav($list['pid']).$str;
	}
	return $str;
}
function get_catep_arr($cid) {
	$cate = get_cate_info ( $cid );
	$arr = explode ( "__", $cate ['key'] );
	//dump($arr);
	for ($row=0;$row<count($arr);$row++){
		$subarr = explode ( "_", $arr[$row] );
		//dump($subarr);
		$r [] = get_cate_info ( $subarr [2] );
	}
	return $r;
}
function get_country($id){
	$dao=D("Countries");
	$list=$dao->where("countries_id=".$id)->find();
	return $list['countries_name'];
}
function get_orders_status($id){
	$dao=D("Orders");
	$status=$dao->field("orders_status")->where("id=".$id)->find();
	return L("orders_status_".$status["orders_status"]);
}
function get_members_name($id){
	$dao=D("Members");
	$list=$dao->where("id=".$id)->find();
	return $list['email'];
}
//获取属性的输入方式名称
function get_type_inputtype_name($type){
	switch ($type) {
		case "0": return "手工录入";break;
		case "1":return "从列表中选择";break;
		case "3":return "多选项选择";break;
	}

}
function get_article_catename($id){
	$dao=D('Article_cate');
	$name=$dao->where(array('id'=>$id))->getField('name');
	return $name?$name:'最上级';
}
//发送邮件
function sendmail($sendTo,$subject,$body){

	$body = eregi_replace ( "[\]", '', $body );
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	if (GetValue ( "sendemailtype" ) == "smtp") {
		foreach ( $sendTo as $key => $val ) {
			mail ( $val, $subject, $body, $headers );
		}
	}
	if (GetValue ( "sendemailtype" ) == "phpmail") {
		import ( "@.ORG.phpmailer" );
		$mail = new PHPMailer();
		$mail->IsSMTP ();
		$mail->SMTPDebug = false;
		$mail->SMTPAuth = true;
		if (GetValue ( "smtpisssl" ) == "1") {
			$mail->SMTPSecure = "ssl";
		}
		$mail->Host = GetValue ( 'smtphost' );
		$mail->Port =GetValue( 'smtpport' );
		$mail->Username = GetValue ( 'smtpusername' );
		$mail->Password = GetValue ( 'smtppassword' ); // GMAIL password
		if(empty($mail->Host) || empty($mail->Port) || empty($mail->Username) || empty($mail->Password)){
			return;
		}
		$mail->SetFrom ( GetValue ( 'mailfrom' ),GetValue ( 'siteurl' ));
		$mail->Subject = $subject;
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
		$mail->MsgHTML ( $body );
		foreach ( $sendTo as $key => $val ) {
			$mail->AddAddress($val);
		}
		$mail->Send();
	}

}
//付款方式费用
function get_orders_Fees($total,$itemTotal,$payment_id){
	$feeModel=D('Fee');
	$fee=$feeModel->where(array('payment_id'=>$payment_id))->find();
	if(!$fee){
		$fee['min_insurance']=0;
		$fee['min_freepaymoney']=0;
		$fee['minimum_money']=0;
	}
	$r=array();
	$r["products_total"]=$total;
	$r["total"]=$total;
	$r['minimum_money']=$fee['minimum_money'];
	if ($fee['min_insurance'] && $fee['insurance'] && $r["total"]<=$fee['min_insurance']){
		$r['insurance']=$itemTotal*$fee['insurance'];
	}
	else{
		$r['insurance']=0;
	}
	$r["total"]+=$r['insurance'];
	if ($fee['min_freepaymoney'] && $fee['paymoney'] && $r["total"]<=$fee['min_freepaymoney']){
		$r['paymoney']=(float)$r["total"]*(float)$fee['paymoney'];
	}
	else{
		$r['paymoney']=0;
	}
	$r["total"]=round($r["total"]+$r['paymoney'],2);
	$r['insurance']=round($r['insurance'],2);
	$r['paymoney']=round($r['paymoney'],2);
	return $r;
}
//3.10更新


function get_members_points($uid){
	$dao=D("Members");
	$map["id"]=$uid;
	$list=$dao->where($map)->find();
	if ($list){
		return $list["points"];
	}
	else{
		return 0;
	}
}
function get_members_group($uid){
	$points=get_members_points($uid);
	$dao=D("Members_group");
	$map["minpoints"]=array("elt",$points);
	$map["maxpoints"]=array("egt",$points);
	$list=$dao->where($map)->find();
	if ($list){
		return $list;
	}
	else{
		return null;
	}

}

/**
 * 根据VIP会员等级取得VIP会员价
 *
 */
function VipPrice($price){
	$GroupInfo=get_members_group(Session::get('memberID'));
	if($GroupInfo["discount"]){
		$VipPrice=$GroupInfo["discount"]*$price;
	}else{
		$VipPrice=$price;
	}
	return $VipPrice;
}
/**
 * 赠送用户积分
 *
 * @param String $sn 订单编号
 * 
 */
function give_member_points($sn){
	$dao = D ( "Orders" );
	$list = $dao->where ( "sn=" . $sn )->find ();

	if ($list) {
		$orders_id = $list ["id"];
		$member_id=$list["member_id"];
		$dao = D ( "Orders_products" );
		$list=$dao->where("orders_id=".$orders_id)->select();
		if ($list){
			$dao=D("Members");
			for ($i=0;$i<count($list);$i++){
				$dao->setInc("points","id=".$member_id,get_products_points($list[$i]["products_id"]));

			}
		}
	}

}
function get_products_points($pid){
	$dao=D("Products");
	$list=$dao->where("id=".$pid)->find();
	if ($list){
		return $list["points"];
	}
	else{
		return 0;
	}
}
//3.10更新
//3.17更新
function get_region_name($id){
	$dao = D ( "Region" );
	$list = $dao->where ( "id=" . $id )->find ();
	if ($list) {
		return $list ["name"];
	} else {
		return null;
	}

}
function get_region_id($name){
	$dao = D ( "Region" );
	$list = $dao->where ( "name='" . $name."'" )->find ();
	if ($list) {
		return $list ["id"];
	} else {
		return null;
	}

}

function get_shipping_name($id){
	$model=D('Shipping');
	return $model->where(array('id'=>$id))->getField('name');
}
function get_ip_area($ip){
	import('ORG.Net.IpLocation');
	$ipLocatoin=IpLocation::getInstance();
	$result=$ipLocatoin->init(__ROOT__."./Public/ipdata/ip.dat")->getcity($ip);
	return $result['country'].$result['area'];
}
function is_seo($modelname,$id){
	$count=M($modelname)->where("pagetitle='' and pagekey='' and pagedec='' and id=".$id)->count();
	if($count){
		return '<img src="'.__ROOT__.'/Public/skin/admin/mod_1.gif" />';
	}else{
		return '<img src="'.__ROOT__.'/Public/skin/admin/mod_0.gif" />';
	}
}
function ip_location_youdao($ip) {
	$url = "http://www.youdao.com/smartresult-xml/search.s?type=ip&q={$ip}";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($ch, CURLOPT_TIMEOUT, 2);
	$result = curl_exec($ch);
	$r = mb_convert_encoding($result, 'UTF-8', 'GBK');
	preg_match("#<location>(.+)</location>#Ui", $r, $m);
	return strval($m[1]);
}

function nodeLevel($level){
	switch ($level){
		case 1:
			return '应用';
		case 0:
			return '&nbsp;&nbsp;分组';
		case 2:
			return '&nbsp;&nbsp;&nbsp;&nbsp;模块';
		case 3:
			return '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;操作';
		default:
			return '';
	}
}
function getArticleContent($title,$id){
	if(isset($title)){
		$model=D('Article');
		$list=$model->where(array('title'=>$title))->find();
		return $list['content'];
	}
	if(isset($id)){
		$model=D('Article');
		$list=$model->find($id);
		return $list['content'];
	}
	return '';
}

function currency_name()
{
    $currency_name = 'USD';
    
    $currency = $_SESSION ['currency'];
    if(!empty($currency)) {
        $currency_name = $currency['symbol'];     
    }
    return $currency_name;
}

?>