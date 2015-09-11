<?php
/**
 * 查找
 *
 */
class ProAction extends CommAction {
	public function _empty(){
		$_REQUEST[ACTION_NAME]=1;
		$this->index();
	}
	public function index(){

		self::$Model=D("Products");
		$map=array();
		switch (true){
			case isset($_REQUEST['new']):
				$map['isnew']=1;
				$this->disp_text="New";
				break;
			case isset($_REQUEST['hot']):
				$map['ishot']=1;
				$this->disp_text="HotSell";
				break;
			case isset($_REQUEST['rec']):
				$map['isrec']=1;
				$this->disp_text="Featured";
				break;
			case isset($_REQUEST['price']):
				$map['isprice']=1;
				$this->disp_text="Specials";
				break;
			case isset($_REQUEST['brand']):
				$map['brandid']=$_REQUEST['id'];
				$this->disp_text="Brand";
				break;
			case isset($_REQUEST['filter']):
				/*
				调用<volist name="vcat" id="vo">
				<li> <a href="{:U('Pro/index',array('id'=>$vo['id'],'filter'=>$vo['filter']))}">{$vo.name} </a></li>
				</volist>*/
				parse_str(rawurldecode($_REQUEST['filter']), $_REQUEST['filter']);
				if(isset($_REQUEST['filter']['pricefrom'])){
					$map['pricespe'][]=array('egt',$_REQUEST['filter']['pricefrom']);
				}
				if(isset($_REQUEST['filter']['priceto'])){
					$map['pricespe'][]=array('elt',$_REQUEST['filter']['priceto']);
				}
				if(isset($_REQUEST['filter']['searchname'])){
					$map['name']=array('like','%'.$_REQUEST['filter']['searchname'].'%');
					$map['pagetitle']=array('like','%'.$_REQUEST['filter']['searchname'].'%');
					$map['pagekey']=array('like','%'.$_REQUEST['filter']['searchname'].'%');
					$map['pagedec']=array('like','%'.$_REQUEST['filter']['searchname'].'%');
					$map['_logic']='or';
				}
				if(isset($_REQUEST['filter']['type_id'])){
					$map['type_id']=array('in',$_REQUEST['filter']['type_id']);
				}
				if(isset($_REQUEST['filter']['cateid'])){
					$map['cateid']=array('in',$_REQUEST['filter']['cateid']);
				}
				if(isset($_REQUEST['filter']['brandid'])){
					$map['brandid']=array('in',$_REQUEST['filter']['brandid']);
				}
				$id=intval($_GET['id']);
				$dao=D('Virtual_cat');
				$cateinfo=$dao->find($id);
				$this->disp_text=$cateinfo['name'];
				break;
			default:
				$this->disp_text="All";
		}
		if(isset($_REQUEST['limit_name'])){
			$map['name']=array('like',$_REQUEST['limit_name']."%");
		}
		$map['isdown']=array('neq','1');

		self::$Model->_list($this->view,$map,'sort',false);
		$this->display('Pro:index');
	}

	public function guestbook(){
		$dao=D('Products_ask');
		$map['status']=1;
		$count = $dao->where ( $map )->count ();
		import ( 'ORG.Util.Page' );
		$page = new Page ( $count, 20 );
		$list = $dao->where ( $map )->order ('id desc' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$show = $page->show ();
		$this->list = $list;
		$this->show = $show;
		$this->display();
	}
	public function write_a_review(){

		if(isset($_GET['id'])){
			$id=intval($_GET['id']);
			$list=D('Products')->find($id);
			if($list){
				if ($this->memberID <= 0  && GetValue('quickbuy')==0) {
					Session::set('back',U('Pro/write_a_review',array('id'=>$list['id'])));
					$this->redirect ( 'MemberPublic/Login' );
				}
				$this->list=$list;

				$this->display('Pro:write_a_review');
			}
		}else{
			$this->redirect("Index/index");
		}
	}
	public function tell_a_friend(){
		if(isset($_POST['id'])){
			$list=D('Products')->find($id);
			import("ORG.Util.Input");
			$to_email_address = Input::getVar($_POST['to_email_address']);
			$to_name = Input::getVar($_POST['to_name']);
			$from_email_address = Input::getVar($_POST['from_email_address']);
			$from_name = Input::getVar($_POST['from_name']);
			$message = Input::safeHtml($_POST['message']);
			$sitename=GetValue('siteurl');
			sendmail(array($to_email_address),"Your friend $from_name has recommended this great product from $sitename","Hi $to_name!<br/><br/>Your friend, $from_name($from_email_address), thought that you would be interested in {$list['name']} from $sitename.<br/>$message");
			$this->jumpUrl=build_url($list,'pro_url');
			$this->success("Your message has been successfully sent!");
		}
		if(isset($_GET['id'])){
			$id=intval($_GET['id']);
			$list=D('Products')->find($id);
			if($list){
				if ($this->memberID <= 0  && GetValue('quickbuy')==0) {
					Session::set('back',U('Pro/tell_a_friend',array('id'=>$list['id'])));
					$this->redirect ( 'MemberPublic/Login' );
				}
				$this->list=$list;

				$this->display('Pro:tell_a_friend');
			}
		}else{
			$this->redirect("Index/index");
		}
	}
	/**
	 * 留言板，产品评论
	 *
	 */
	public function insert_ask(){
		self::$Model = D("Products_ask");
		$this->jumpUrl=U('Pro/guestbook');
		if ($list=self::$Model ->create()){
			$list['status']=1;//是否审核
			$list['ip']=get_client_ip();
			$list['dateline']=time();
			import("ORG.Util.Input");
			$list['content'] = Input::safeHtml($list['content']);
			//是否产品的评论
			$id=intval($list['products_id']);
			if(!empty($id)){
				$pro=D('Products')->find($id);
				$this->jumpUrl=build_url($pro,'pro_url');
			}
			if(self::$Model->add($list)){
				$ask_mail_enable=GetValue('ask_mail_enable');
				$ask_mail=GetValue('ask_mail');
				if($ask_mail_enable && $ask_mail){
					$sendto=array($ask_mail,GetValue('mailcopyTo'));//抄送
					if($pro){
						$products_id="(products name:".$pro['name'] .",products id:".$pro['id'].")";
					}else{
						$products_id="";
					}
					sendmail($sendto,"你的网站 ".GetValue('sitename')." 有一个新的评论!",/*正文*/
					"<table><tr><td>星级:</td><td>{$list['star']}星</td></tr><tr><td>类型:</td><td>{$list['type']}$products_id</td></tr><tr><td>邮箱:</td><td>{$list['email']}</td><tr><td>昵称:</td><td>{$list['name']}</td></tr><tr><td>标题:</td><td>{$list['title']}</td></tr><tr><td>内容:</td><td>{$list['content']}</td></tr><tr><td>IP地址:</td><td>{$list['ip']}</td></tr></table>"
					/*正文*/);
				}

				$this->success('Your message has been submitted!');
			}else{
				$this->error('Failure to submit your message!');
			}
		}else{
			$this->redirect("Index/index");
		}
	}
}

?>