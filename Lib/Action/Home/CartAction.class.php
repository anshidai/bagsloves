<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-25
 */

class CartAction extends CommAction {
	function _initialize() {
		parent::_initialize();
		Session::set('back',null);
	}
	function add() {
		if ( $_POST ['count'] == 0) {
			$this->error ( "You did not select any product!" );
		}
		//强制登录
		/*if ($this->memberID <= 0 && GetValue('quickbuy')==0) {
		echo "<script>alert('Please Log on!');location.href='".U('MemberPublic/Login')."';</script>";
		die;
		}*/
		$dao = D ( "Cart" );
		self::$Model = D ( "Products" );
		$prolist = self::$Model->where ( "id=" . $_POST ['id'] )->find ();
		$attrlist = self::$Model->get_attrs ( $prolist ['cateid'], $_POST ['id'] );

		$model = array ();
		$i=0;
		//属性列表
		$attributes=array_keys($_POST['attr']);
		$no_select=array();
		
		//强制值不为空
		foreach ( $attrlist as $key => $value ) {
			if(($value['input_type']==0 || $value['input_type']==1) && in_array($value ['name'],$attributes) && $_POST ['attr'][$value ['name']]){ 
				$model [$i] ['name'] = $value ['name'];
				$attr_value=explode('__',$_POST ['attr'][$value ['name']]);
				$model [$i] ['value'] = $attr_value[0];
				$model [$i] ['attr_price'] = $attr_value[1];
				$i ++;
			}else{
				$no_select[]=$value ['name'];
			}
		}
		if (count($no_select)>0) {
			$this->error ( "Please select ".implode(',',$no_select).'!' );
		}
		$dao->add_item ( $this->sessionID, $_POST ['id'], $_POST ['count'], serialize ( $model ) );
		if($this->isAjax()){
			$item_count = itemCount();
			$total_count = TotalCount();
			$price_total = getprice_str(cart_total());
			$data = array(
				'status' => 1,
				'info' => "<em class='add_ok'></em><span id='shopping_data'>Shopping Cart <a>{$item_count}</a> items Total {$price_total}</span>",
				'number_total' => $item_count,
				'items_total' => $total_count,
				'price_total' => $price_total,
				'list' => $dao->cart_list($this->sessionID),
			);
			//$this->success('Shopping Cart <a>'.itemCount().'</a> items quantity <a>'.TotalCount().'</a>.<br/>Total '.getprice_str(cart_total()).'.');
			echo json_encode($data);exit;;
		}else{
			$this->redirect ( 'Cart/disp' );
		}
	}
	function disp() {
		$dao = D ( "Cart" );
		$this->list = $dao->display_contents ( $this->sessionID );//购物车列表
		$this->totalWeight = $dao->cart_total_weight( $this->sessionID );//总重量
		$this->itemTotal = $dao->get_item_totalcount( $this->sessionID );//总数量
		$this->itemCount = $dao->get_item_count($this->sessionID );//总类数
		$cartTotal = $dao->cart_total ( $this->sessionID );//产品总价格
		$this->cartTotal = getprice_str($cartTotal);
		$this->discount = $discount = $dao->discount($cartTotal);//打折信息
		$this->totalAmount = getprice_str($discount['price']);//全部总价
		$this->fee_readme=GetValue('fee_readme');//购物车说明

		$this->display ();
	}
	function delete() {
		$dao = D ( "Cart" );
		$dao->delete_item ( $this->sessionID, $_REQUEST ['id'] );
		$this->redirect ( 'Cart/disp' );
	}
	function save() {

		$dao = D ( "Cart" );
		for($row = 0; $row < count ( $_POST ['pid'] ); $row ++) {
			$dao->modify_quantity ( $this->sessionID, $_POST ['id'] [$row], $_POST ['count'] [$row]);
		}

		if(isset($_REQUEST['step']) && $_REQUEST['step']=='checkout' && !$_REQUEST['guest']){
			//最小金额
			$minimum_money=GetValue('minimum_money');
			if($minimum_money>0 && $dao->cart_total ( $this->sessionID )<$minimum_money){
				$this->error("Not be less than $minimum_money minimum!");
			}
			if ($this->memberID <= 0 && GetValue('quickbuy')==0) {
				Session::set('back',U('Cart/checked_payment'));
				$this->redirect ( 'MemberPublic/Login' );
			}
			$_SESSION['guest']=0;
			$this->redirect ( 'Cart/checked_payment' );

		}elseif($_REQUEST['guest']){//非强制登录
			$_SESSION['guest']=1;
			$this->redirect ( 'Cart/checked_payment');
		}else{
			$this->redirect ( 'Cart/disp' );
		}

	}

	function checked_payment(){
		if ($this->memberID <= 0  && GetValue('quickbuy')==0 && !$_SESSION['guest']) {
			Session::set('back',U('Cart/checked_payment'));
			$this->redirect ( 'MemberPublic/Login' );
		}
		if (! $this->memberShippingAddress && GetValue('quickbuy')==0  && !$_SESSION['guest']) {
			Session::set('back',U('Cart/checked_payment'));
			$this->redirect ( 'MemberIndex/ShippingAddress' );
		}elseif($_SESSION['guest']){
			$this->quickbuy=1;//不强制会员登录
		}else{
			$this->quickbuy=GetValue('quickbuy');//强制会员登录
		}

		//读取订单信息
		$dao = D ( "Cart" );
		$this->list = $dao->display_contents ( $this->sessionID );//购物车列表
		$this->totalWeight = $dao->cart_total_weight( $this->sessionID );//总重量
		$this->itemTotal = $dao->get_item_totalcount( $this->sessionID );//总数量
		$this->itemCount = $dao->get_item_count($this->sessionID );//总类数
		$cartTotal = $dao->cart_total ( $this->sessionID );//产品总价格
		$this->cartTotal = getprice_str($cartTotal);
		$this->discount = $discount = $dao->discount($cartTotal);//打折信息
		$this->totalAmount = getprice_str($discount['price']);//全部总价

		//已有收货地址
		//获取配送方式列表
		self::$Model=D("Shipping");
		$ShippingList=self::$Model->get_shipping_list($this->memberShippingAddress['country'],$this->memberShippingAddress['state'],$this->memberShippingAddress['city'],$weight);
		$this->ShippingList=$ShippingList;

		$this->country = D ( 'Region' )->where ( array('type'=>0) )->order('name asc')->select ();//国家列表

		//支付方式列表
		self::$Model = D ( "Payment" );
		$this->paymentlist = self::$Model->getlist ();


		$this->disp ();
	}
	public function checkout() {

		$cart_model = D ( "Cart" );
		if ($cart_model->get_item_count ( $this->sessionID ) < 1) {
			$this->jumpUrl=U('Index/index');
			$this->error("Your shopping cart does not have any products!");
		}

		if ($this->memberID <= 0  && GetValue('quickbuy')==0 && !$_SESSION['guest']) {
			Session::set('back',U('Cart/checked_payment'));
			$this->redirect ( 'MemberPublic/Login' );
		}
		if (! isset ( $_POST ['shipping_id'] ) || empty($_POST ['shipping_id'])) {
			$this->error ( 'Please select SHIPPING METHOD! ' );
		}
		if (! isset ( $_POST ['payment_module_code'] ) || empty($_POST ['payment_module_code'])) {
			$this->error ( 'Please select PAYMENT METHOD! ' );
		}

		if (!empty($_POST ['coupon'])) {
			$dao=D("Coupon");
			$coupon=$dao->validate($_POST['coupon']);
			if($coupon){
			}else{
				$this->error ( 'The coupon is invalid! ' );
			}
		}

		$orders_model = D ( "Orders" );
		$shipping_model = D ( "Shipping" );
		$shippingaddress_model = D ( "Shippingaddress" );
		$payment_model = D('Payment');
		if ($delivery_list=$orders_model->create ()) {
			$delivery=$_POST['delivery'];//0会员本身地址，1其它地址
			if($delivery==0){
				$member_model=D('Members');

				$delivery_list['member_id']=$this->memberID;
				$this->member_info=$member_info=$member_model->find($this->memberID);
				$delivery_list['delivery_email']=$member_info['email'];
				$delivery_list['delivery_firstname']=$member_info['firstname'];
				$delivery_list['delivery_lastname']=$member_info['lastname'];
				$member_info=$shippingaddress_model->get_shippingaddress($this->memberID);
				$delivery_list['delivery_address']=$member_info['address'];
				$delivery_list['delivery_country']=$member_info['country'];
				$delivery_list['delivery_state']=$member_info['state'];
				$delivery_list['delivery_city']=$member_info['city'];
				$delivery_list['delivery_zip']=$member_info['zip'];
				$delivery_list['delivery_telephone']=$member_info['telphone'];
			}
			$delivery_list['total_weight']=$cart_model->cart_total_weight($this->sessionID );
			$delivery_list['shippingmoney']=$shipping_model->get_shipping_fee($delivery_list["shipping_id"],$delivery_list['delivery_country'],$delivery_list['delivery_state'],$delivery_list['delivery_city'],$delivery_list['total_weight']);

			$delivery_list['shippingmoney']=$delivery_list['shippingmoney']['price'];
			//没有价格取保险金
			!$delivery_list['shippingmoney']?$delivery_list['shippingmoney']=$shipping_model->get_insure($delivery_list["shipping_id"]):'';
			$delivery_list['shipping_method']=$delivery_list['shipping_module_code']=$shipping_model->get_name($delivery_list["shipping_id"]);
			$products_total=$cart_model->cart_total ( $this->sessionID );//产品总价格
			$discount = $cart_model->discount($products_total);//打折信息
			$sum_total=round($discount['price'],2);//打折后价格
			$delivery_list['discount']=$discount['text'];
			$itemTotal = $cart_model->get_item_totalcount( $this->sessionID );//总数量
			$payment_id=$payment_model->get_id($delivery_list["payment_module_code"]);
			$fee=get_orders_Fees($sum_total,$itemTotal,$payment_id);
			//最小订单金额
			if($fee['minimum_money'] && $fee["total"]<=$fee['minimum_money']){
				$this->error("Not be less than ".$fee['minimum_money']." minimum!");
			}

			$delivery_list['paymoney']=$fee["paymoney"];
			$delivery_list['insurance']=$fee["insurance"];
			$delivery_list['orders_total']=$fee['total']+$delivery_list['shippingmoney'];//总价加上运费

			$delivery_list['products_total'] = $fee['products_total'];
			//判断手动和选择
			$delivery_list['delivery_country']=is_numeric($delivery_list['delivery_country'])?get_region_name($delivery_list['delivery_country']):$delivery_list['delivery_country'];
			$delivery_list['delivery_state']=is_numeric($delivery_list['delivery_state'])?get_region_name($delivery_list['delivery_state']):$delivery_list['delivery_state'];
			$delivery_list['delivery_city']=is_numeric($delivery_list['delivery_city'])?get_region_name($delivery_list['delivery_city']):$delivery_list['delivery_city'];
			//货币符号
			$delivery_list['currencies_code']=$_SESSION ['currency']['code']?$_SESSION ['currency']['code']:"$";

			//如果使用了优惠券
			if($coupon){
				$delivery_list['orders_total']-=$coupon['amount'];
				$delivery_list['coupon']=$coupon['amount'];//记录优惠了多少价格
			}
			//生成订单
			$orders_model->create ($delivery_list);//过滤
			if($orders_id = $orders_model->add ()){
				//保存优惠券使用者
				$dao=D("Coupon");
				$dao->where(array('coupon'=>$_POST['coupon']))->save(array('user'=>$delivery_list['delivery_lastname'].' '.$delivery_list['delivery_firstname'],'status'=>0));
				//处理orders_products表
				$list = $cart_model->display_contents ( $this->sessionID );
				$orders_products_model = D ( "Orders_products" );
				for($row=0;$count=count($list),$row<$count;$row++) {
					$data ['orders_id'] = $orders_id;
					$data ['products_model'] = serialize($list [$row] ['model']);
					$data ['products_id'] = $list [$row] ['pid'];
					$data ['products_name'] = $list [$row] ['name'];
					$data ['products_price'] = $list [$row] ['price'];
					$data ['products_pricespe'] = $list [$row] ['pricespe'];
					$data ['products_quantity'] = $list [$row] ['count'];
					$data ['products_total'] = $list [$row] ['total'];
					if($orders_products_model->create($data)){
						$orders_products_model->add ();
					}
				}

				//清除购物车
				$cart_model->clear_cart ( $this->sessionID );

				//发送邮件
				//邮件变量
				$this->itemTotal=$itemTotal;//总数量
				$this->totalWeight=$delivery_list['total_weight'];//总重量
				$this->orders_data=$delivery_list;//订单数据
				$this->list=$list;//购物车产品
				$this->cartTotal=getprice_str($products_total);//产品总价格
				$fee['insurance']>0 && $this->assign('insurance',getprice_str($fee['insurance']));
				$fee['paymoney']>0 && $this->assign('paymoney',getprice_str($fee['paymoney']));
				$this->discount=$discount;//打折
				$this->shippingPrice=getprice_str($delivery_list['shippingmoney']);//运费
				$this->totalAmount = getprice_str($delivery_list['orders_total']);//全部总价

				$this->this_script = "http://".$_SERVER['HTTP_HOST'];
				$sendto=array($delivery_list['delivery_email'],GetValue('mailcopyTo'));//抄送
				$body=$this->fetch("MailTpl:checkout");
				sendmail($sendto,GetValue('sitename')." - new order(SN:".$orders_model->sn.")!",$body)	;
				$this->redirect ( 'Cart/payment', array ('id' => $orders_id ) );
			}else {
				$this->error ( $orders_model->getError () );
			}

		} else {
			$this->error ( self::$Model->getError () );
		}
	}

	public function payment() {
		//如果是快速购物则强制登录
		if ($this->memberID <= 0  && GetValue('quickbuy')==0 && !$_SESSION['guest']) {
			Session::set('back',U('Cart/checked_payment'));
			$this->redirect ( 'MemberPublic/Login' );
		}
		$orders_id = $_GET ['id'];
		if (get_orders_status ( $orders_id ) == L ( "orders_status_2" )) {
			$this->error ( L ( "orders_error_paied" ) );
		}
		//读取支付代码
		self::$Model = D ( "Orders" );
		$list = self::$Model->where ( "id=" . $orders_id )->find ();
		if (! $list) {
			$this->redirect ( 'Index/index' );
		}
		$list['orders_total']=round($list['orders_total'],2);//四舍五入保留两位
        
		/**
		 * 在线支付
		 */
		$pname = $list ['payment_module_code'];
		self::$Model=D('Payment');
		$payment_title=self::$Model->where(array('name'=>$pname))->getField('title'); 
		//模板变量
		$this->title=ucwords($payment_title).' Payment';//标题
		$this->list=$list;
		import ( '@.ORG.Payment.' . $pname );
		if(class_exists($pname)){
			$p = new $pname ();
			$content=$p->create_form($list);//创建表单
			$this->content=$content;

			//用户说明
			$remark=GetValue($pname.'_desc');
			if($remark){
				$remark=str_replace(array('{sn}','{time}','{payname}','{total}','{go}','{admin_email}'),array($list['sn'],toDate($list['dateline']),$list['payment_module_code'],getprice_str($list['orders_total']),"<input type=\"button\" value=\"Click Here\" onclick=\"document.forms['pay_form'].submit();\" />",GetValue('email')),$remark);
			}
			$this->remark=$remark;
			$this->display ();
		}else{
			$this->error('Please select a payment method!');
		}
	}

	public function clear_cart(){
		self::$Model=D('Cart');
		self::$Model->clear_cart($this->sessionID);
		$this->success('Clear Cart Item Success!');
	}

}
?>