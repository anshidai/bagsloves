<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-30
*/ 
class OrdersAction extends AdminCommAction{
	function orderslist() {
		$map=array();
		if(!empty($_REQUEST['sn'])){
			$map['sn']=array('like','%'.$_REQUEST['sn'].'%');
		}
		$this->sort="id desc";
		$this->_list ($map);

		$this->display ();
	}
	function orders(){
		$id=intval($_GET['id']);

		//收货地址
		$this->shippingInfo=$shippingInfo=$this->dao->find($id);
		//产品信息
		$list=$this->dao->query("select a.*,b.bigimage,b.serial,if(a.products_pricespe,a.products_pricespe,a.products_price) as price from ".C('DB_PREFIX')."orders_products a left join ".C('DB_PREFIX')."products b on a.products_id = b.id where a.orders_id='".$id."'");
		//产品总价
		$orders_products_Model=D('Orders_products');
		$this->products_total=$orders_products_Model->where("orders_id=".$id)->sum('products_total');

		//产品总数
		$this->products_sum=$orders_products_Model->where("orders_id=".$id)->sum('products_quantity');

		foreach ($list as $k=>$v){
			$list[$k]['products_model']=unserialize($v['products_model']);
		}
		//订单产品总重量

		$this->list=$list;
		$this->orders_total=$shippingInfo['orders_total'];
		$this->total_weight=$shippingInfo['total_weight'];
		if(ACTION_NAME=='orders'){
			$this->display();
		}
	}
	function delivery(){
		$this->orders();
		$this->display();
	}
	function express(){
		$this->orders();
		$this->display();
	}
	function ems(){
		$this->orders();
		$this->display();
	}
	public function edit() {
		$map ['id'] = $_GET ['id'];
		$list = $this->dao->where ( $map )->find ();

		$list['ip_area']=get_ip_area($list['ip_address']);
		if ($list) {
			$this->list = $list;

			$this->display ();
		} else {
			$this->error ( '没有数据！' );
		}

	}
	function printall(){
		$ids=explode(",",$_REQUEST['id']);
		$count=count($ids);
		$i=0;
		$this->display('Orders-printall_header');

		$orders_products_Model=D('Orders_products');
		foreach ($ids as $id){

			//收货地址
			$this->shippingInfo=$shippingInfo=$this->dao->find($id);
			//产品信息
			$list=$this->dao->query("select a.*,b.bigimage,b.serial,if(a.products_pricespe,a.products_pricespe,a.products_price) as price from ".C('DB_PREFIX')."orders_products a left join ".C('DB_PREFIX')."products b on a.products_id = b.id where a.orders_id='".$id."'");
			//产品总价
			$this->products_total=$orders_products_Model->where("orders_id=".$id)->sum('products_total');

			//产品总数
			$this->products_sum=$orders_products_Model->where("orders_id=".$id)->sum('products_quantity');

			foreach ($list as $k=>$v){
				$list[$k]['products_model']=unserialize($v['products_model']);
			}
			//订单产品总重量

			$this->list=$list;
			$this->orders_total=$shippingInfo['orders_total'];
			$this->total_weight=$shippingInfo['total_weight'];

			$this->display();
			$i++;
			if($i!=$count){
				echo "<br clear=all style='page-break-before:always;' >";
			}
		}
		$this->display('Orders-printall_footer');

	}
	/**
	 * 条形�?
	 *
	 */
	function UPCAbarcode()
	{
		$code=substr($_GET['code'],0,11);
		import("ORG.Util.Image");
		Image::UPCA($code,'png',3);
	}
	function Delete() {
		$map ['orders_id'] = array('in',$_GET ['id']);
		$dao = D ( "Orders_products" );
		$dao->where ( $map )->delete ();
		parent::Delete ();
	}
	public function Update() {
		if ($this->dao->create ()) {
			$list = $this->dao->save ();

			if ($list !== false) {
				//获取订单用户email
				self::$Model=D("Orders");
				$orderlist=self::$Model->where("id=".$_POST['id'])->find();
				self::$Model=D("Members");
				//$memberlist=self::$Model->where("id=".$orderlist['member_id'])->find();
				$sendto=array($orderlist['delivery_email'],GetValue('mailcopyTo'));
				$this->orders_status=orderstatus_convert(get_orders_status($_POST['id']));
				$this->orderlist=$orderlist;
				$this->memberlist=$memberlist;
				if(!empty($orderlist['express_method'])){
					$this->express_method="Express delivery:".$orderlist['express_method'];
				}
				$body=$this->fetch("MailTpl:orderstatus");
				sendmail($sendto,GetValue('sitename')." on your order has been shipped",$body)	;
				$this->success ( '数据更新成功！' );
				//cleanCache ();
			} else {
				$this->error ( "没有更新任何数据!" );
			}
		} else {
			$this->error ( $this->dao->getError () );
		}
	}
	public function orders_status(){
		if($_REQUEST['id']){
			$map['id']=array('in',$_REQUEST['id']);
			$count=$this->dao->where($map)->count();
			$status=$this->dao->where($map)->setField('orders_status',$_REQUEST['orders_status']);
			$this->success('设置成功'.$count.'个订单');
		}
		$this->error('设置失败');
	}

	public function sendgoods() {
		if (! GetValue ( 'sender_sname' )) {
			$this->jumpUrl=U('Setting/ShippingAddress');
			$this->error('请先完善您的发货地址！');
		}
		else{
			$map['id']=$_GET['id'];
			$list=$this->dao->where($map)->find();

			if (!$list){
				$this->error('对不起，没有该订单信息！');
			}
			self::$Model=D("Orders_shippingbills");

			$express=self::$Model->where(array('order_id'=>$list['id']))->find();
			if($express){
				$this->expressSN=$express['ExpressSN'];
				$this->id=$express['id'];

			}
			$this->list=$list;
			$this->display ();
		}

	}
	public function dosendgoods(){
		//dump($_POST);
		$_POST['dateline']=time();
		if (empty($_POST['ExpressSN'])){
			$this->error('请输入快递单编号！');
		}
		self::$Model=D("Orders_shippingbills");

		if (self::$Model->create()){
			$id=self::$Model->add();
			if(!$id){
				$id=self::$Model->save();
			}
			//修改订单状态
			$map['id']=$_POST['order_id'];
			$data['orders_status']='3';
			$data['shipping_method']=$_POST['Express'];
			$this->dao->where($map)->save($data);
			$this->jumpUrl=U('Orders/dispBills',array('id'=>$_POST['order_id']));
			$this->success ( '添加成功！' );
		}
		else{
			$this->error(self::$Model->getError());
		}
	}

	public function dispBills(){
		$map['order_id']=$_GET['id'];
		self::$Model=D("Orders_ShippingBills");
		$list=self::$Model->where($map)->find();
		if (!$list){
			$this->error('没有找到该发货单信息！');
		}
		else{
			self::$Model=D("Billsprintsetting");
			$map1['Express']=$list['Express'];
			$this->blist=self::$Model->where($map1)->select();
			//$blist=self::$Model->where($map1)->find();
			//dump($blist);
			//echo self::$Model->getlastsql();
			$this->list=$list;
			$this->display();
		}
	}
	/**
	* 导出word文件
	*/
	public  function word(){
		$id=$_GET['id'];
		$this->orders();
		Vendor('MsDocGenerator');
		$doc = new clsMsDocGenerator();
		$doc->addParagraph('网站订单', array('text-align' => '"center"', 'font-size' => '16pt', 'font-weight' => 'bold'));
		//$doc->addParagraph("订单号:{$id}");
		$doc->addParagraph($this->fetch());
		$doc->output('sop订单'.$id);
	}
	/**
	* 导出excel文件
	*/
	/*public  function e2(){
	Vendor('MsXlsGenerator');
	$orders_all=$this->dao->select();
	$xls=array();
	$xls[0][0]="编号";
	$xls[0][1]="金额";
	$xls[0][2]="运输方式";
	$xls[0][3]="运费";
	$xls[0][4]="手续费";
	$xls[0][5]="保险金";
	$xls[0][6]="付款方式";
	$xls[0][7]="购买时间";
	$xls[0][8]="附加留言";
	$xls[0][9]="IP地址";
	$xls[0][10]="邮箱";
	$xls[0][11]="姓";
	$xls[0][12]="名";
	$xls[0][13]="地址";
	$xls[0][14]="电话";
	$xls[0][15]="邮编";
	$xls[0][16]="城市";
	$xls[0][17]="省份";
	$xls[0][18]="国家";
	$xls[0][19]="订单状态";
	$xls[0][20]="快递方式";
	$xls[0][21]="重量";
	$i=1;
	foreach ($orders_all as $o){
	$xls[$i][0]=$o['sn'];
	$xls[$i][1]=$o['orders_total'];
	$xls[$i][2]=$o['shipping_method'];
	$xls[$i][3]=$o['shippingmoney'];
	$xls[$i][4]=$o['paymoney'];
	$xls[$i][5]=$o['insurance'];
	$xls[$i][6]=$o['payment_module_code'];
	$xls[$i][7]=toDate($o['dateline']);
	$xls[$i][8]=$o['BuyNote'];
	$xls[$i][9]=$o['ip_address']."(".get_ip_area($o['ip_address']).")";
	$xls[$i][10]=$o['delivery_email'];
	$xls[$i][11]=$o['delivery_firstname'];
	$xls[$i][12]=$o['delivery_lastname'];
	$xls[$i][13]=$o['delivery_address'];
	$xls[$i][14]=$o['delivery_telephone'];
	$xls[$i][15]=$o['delivery_zip'];
	$xls[$i][16]=$o['delivery_city'];
	$xls[$i][17]=$o['delivery_state'];
	$xls[$i][18]=$o['delivery_country'];
	$xls[$i][19]=L('orders_status_'.$o['orders_status']);
	$xls[$i][20]=$o['shipping_method'];
	$xls[$i][21]=$o['total_weight'];
	$i++;
	}
	foreach ($xls as $k=>$v){
	foreach ($xls[$k] as $kk=>$vv){
	$xls[$k][$kk]=auto_charset($vv,'utf-8','gbk');
	}
	}
	Create_Excel_File("订单列表.xls",$xls);
	}*/
	public function excel(){
		Vendor('Excel.php-excel');
		$map['id']=array('in',$_REQUEST['id']);
		$orders=$this->dao->where($map)->select();
		$xls=array();
		$xls[0][0]="编号";
		$xls[0][1]="金额";
		$xls[0][2]="运输方式";
		$xls[0][3]="运费";
		$xls[0][4]="手续费";
		$xls[0][5]="保险金";
		$xls[0][6]="付款方式";
		$xls[0][7]="购买时间";
		$xls[0][8]="附加留言";
		$xls[0][9]="IP地址";
		$xls[0][10]="邮箱";
		$xls[0][11]="姓";
		$xls[0][12]="名";
		$xls[0][13]="地址";
		$xls[0][14]="电话";
		$xls[0][15]="邮编";
		$xls[0][16]="城市";
		$xls[0][17]="省份";
		$xls[0][18]="国家";
		$xls[0][19]="订单状态";
		$xls[0][20]="快递方式";
		$xls[0][21]="重量";
		$i=1;
		foreach ($orders as $o){
			$xls[$i][0]=$o['sn'];
			$xls[$i][1]=$o['orders_total'];
			$xls[$i][2]=$o['shipping_method'];
			$xls[$i][3]=$o['shippingmoney'];
			$xls[$i][4]=$o['paymoney'];
			$xls[$i][5]=$o['insurance'];
			$xls[$i][6]=$o['payment_module_code'];
			$xls[$i][7]=toDate($o['dateline']);
			$xls[$i][8]=$o['BuyNote'];
			$xls[$i][9]=$o['ip_address']."(".get_ip_area($o['ip_address']).")";
			$xls[$i][10]=$o['delivery_email'];
			$xls[$i][11]=$o['delivery_firstname'];
			$xls[$i][12]=$o['delivery_lastname'];
			$xls[$i][13]=$o['delivery_address'];
			$xls[$i][14]=$o['delivery_telephone'];
			$xls[$i][15]=$o['delivery_zip'];
			$xls[$i][16]=$o['delivery_city'];
			$xls[$i][17]=$o['delivery_state'];
			$xls[$i][18]=$o['delivery_country'];
			$xls[$i][19]=L('orders_status_'.$o['orders_status']);
			$xls[$i][20]=$o['shipping_method'];
			$xls[$i][21]=$o['total_weight'];
			$i++;
		}
		$xls_obj = new Excel_XML('UTF-8',false, 'SOP订单列表');
		$xls_obj->addArray($xls);
		$xls_obj->generateXML("SOP");
	}
	public  function export(){
		$map['id']=array('in',$_REQUEST['id']);
		$orders=$this->dao->where($map)->select();
		Vendor('MsDocGenerator');
		$doc = new clsMsDocGenerator();
		$doc->addParagraph('网站订单', array('text-align' => '"center"', 'font-size' => '16pt', 'font-weight' => 'bold'));
		$i=0;
		$count=count($orders);
		$this->assign('all_count',$count);
		foreach ($orders as $o){
			$this->assign('i',$i);
			$_GET['id']=$o['id'];
			$this->orders();
			$doc->addParagraph($this->fetch('Orders-word'));
			$i++;
			$doc->addParagraph("<br clear=all style='page-break-before:always;' />");
		}
		$doc->output('订单列表');
	}
}
?>