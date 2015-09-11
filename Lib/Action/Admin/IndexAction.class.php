<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-15
*/ 
class IndexAction extends AdminCommAction{
	function index(){
		$info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式'=>php_sapi_name(),
            '版本'=>C('SYSTEM_NAME').C('SYSTEM_VAR').' [ <a href="http://soputrade.googlecode.com" target="_blank">查看最新版本</a> ]',
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->info=$info;
        
        
        $day=mktime(0,0,0,date('m'),0,date('Y'));//今天
        $week=mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"));//本周
        $month=mktime(0,0,0,date("m"),1,date("Y"));//本月
        
        //产品类别
        $this->product_count=self::$Model=D('Products')->count();
        self::$Model=D('Cate');
        $this->cate_count=self::$Model->count();
        
        //新增留言
		self::$Model=D('Products_ask');
		$this->ask_count=self::$Model->where("`dateline`>'$day'")->count();
		$this->week_ask_count=self::$Model->where("`dateline`>'$week'")->count();
		$this->month_ask_count=self::$Model->where("`dateline`>'$month'")->count();
		
        //新增购物车
		self::$Model=D('Cart');
		$this->cart_count=self::$Model->where("`dateline`>'$day'")->count();
		
		//新增会员
        self::$Model=D('Members');
        $this->member_count=self::$Model->where("`createdate`>'".$day."'")->count();
        $this->week_member_count=self::$Model->where("`createdate`>'".$week."'")->count();
        $this->month_member_count=self::$Model->where("`createdate`>'".$month."'")->count();
        $this->member_last_login_count=self::$Model->where("`lastlogindate`>'".$day."'")->count();
        
        //新增订单
        self::$Model=D('Orders');
        $this->week_order_count=self::$Model->where("`dateline`>'$week'")->count();
        $this->month_order_count=self::$Model->where("`dateline`>'$month'")->count();
        $this->order_count=self::$Model->where("`dateline`>'$day'")->count();
        $this->orders_status1=self::$Model->where("`dateline`>'$day' and orders_status=1")->count();
        $this->orders_status2=self::$Model->where("`dateline`>'$day' and orders_status=2")->count();
        
        $this->display();
	}
	public function loginOut() {
			Session::clear (); 
		setcookie("username", null);
		setcookie("password", null);
		/*
		Session::set(C('USER_AUTH_KEY'),'');
		Session::set('email','');
		Session::set('loginUserName','');
		Session::set('login_count','');
		Session::set('administrator',false);
		$this->jumpUrl=__APP__."/AdminPublic-adminlogin";
		*/
		$this->success ( '已安全退出系统，谢谢使用！' );
	}
	//操作图片
	public function renamepicprefix(){
		$dao = D("Products");
		$proList = $dao->field('id,picprefix' )->select();
		for( $i=0;$i<count($proList);$i++){
			$picprefix = $proList[$i]["picprefix"];
			$match2 = '/[\s]+/';//匹配空格
			$replace2 = '-';
			$result =  preg_replace("/[\s]+/",$replace2,$picprefix);
			$proList[$i]["picprefix"] = strtoupper($result); //字符串转换成大写
			$dao->data($proList[$i])->where( 'id='.$proList[$i]["id"] )->save();
			echo $dao->getlastsql()."<br />";
		}
	}
	//重命名目录中的文件名     要开启修改的权限
	public function renameDirfilename(){
		$dirPath = "./Public/Uploads/Products/detailgallery/";
		//	$dirPath = "temp/";
		$files = glob($dirPath."*.*");
		foreach($files as $file)
		{
			$pathinfoarray = pathinfo($file);
			$sourceFilePath = $dirPath.$pathinfoarray["basename"];//源文件全路径
			echo $sourceFilePath."<br />";
			$fileName = $pathinfoarray["filename"];//源文件名
			$match2 = '/[\s]+/';//匹配空格
			$replace2 = '-';
			$result =  preg_replace("/[\s]+/",$replace2,$fileName);
			$result2 = strtoupper($result); //字符串转换成大写
			$desFilePath = $dirPath.$result2.".jpg";//目标文件全路径
			echo $desFilePath."<br />";
			rename($sourceFilePath,$desFilePath);
		}
		echo "<br /> -----------------------finish-------------------------";
	}
}
?>