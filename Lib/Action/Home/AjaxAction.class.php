<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2011-3-17
*/ 
class AjaxAction extends Action{
	function login(){
		Session::set('back',U('Cart/disp'));
		echo $this->fetch('Ajax:login');

	}
	function join(){
		Session::set('back',U('Cart/disp'));
		echo $this->fetch('Ajax:join');
	}
	function shippingaddress(){

		//获取国家列表
		$dao=D("Region");
		$this->Countries=$dao->where("type=0")->order('name asc')->select();

		$this->memberID=Cookie::get('memberID');
		if($this->memberID){
			$dao=D("Members");
			$this->memberInfo=$dao->where("id=".$this->memberID)->find();
		}

		Session::set('back',U('Cart/disp'));
		echo $this->fetch('Ajax:shippingaddress');
	}
	function coupon(){
		$dao=D("Coupon");
		$list=$dao->validate($_POST['coupon']);
		if($list){
			die('-'.getprice_str($list['amount']?$list['amount']:0));
		}else{
			die('<span style="color:red">*The coupon is invalid</span>');
		}
	}
	function getRegion(){
		$dao=D("Region");
		$map['pid']=$_REQUEST["pid"];
		$map['type']=$_REQUEST["type"];
		$list=$dao->where($map)->select();
		echo json_encode($list);
	}
	function get_shipping_list(){
		$dao=D("Cart");
		$weight=$dao->cart_total_weight(Cookie::get ( 'sessionID' ));

		$dao=D("Shipping");
		$ShippingList=$dao->get_shipping_list($_POST['country'],$_POST['state'],$_POST['city'],$weight);
		$this->ShippingList=$ShippingList;
		echo $this->fetch('Ajax:get_shipping_list');

	}
	function get_total_amount(){

		$dao = D ("Cart");
		$sessionID = Cookie::get ( 'sessionID' );

		$this->totalWeight = $weight = $dao->cart_total_weight( $sessionID );//总重量

		$cartTotal = $dao->cart_total ( $sessionID);//产品总价格

		$this->cartTotal = getprice_str($cartTotal);
		$this->itemTotal = $itemTotal = $dao->get_item_totalcount( $sessionID);//总数量
		$this->itemCount = $dao->get_item_count($sessionID );//总类数

		$this->discount = $discount = $dao->discount($cartTotal);//打折信息
		$totalAmonut=round($discount['price'],2);


		$shipping_id=$_POST['shipping_id'];
		$delivery=$_POST['delivery'];
		$shippingModel=D('Shipping');
		$member_id=Cookie::get('memberID');
		if($shipping_id && $delivery==0 && $member_id){//会员本身地址
			$memberShippingAddress=Cookie::get('memberShippingAddress');
			$shipping_price=$shippingModel->get_shipping_fee($shipping_id,$memberShippingAddress['country'],$memberShippingAddress['state'],$memberShippingAddress['city'],$weight);

		}else{//其它地址
			$shipping_price=$shippingModel->get_shipping_fee($shipping_id,$_POST['country'],$_POST['state'],$_POST['city'],$weight);
		}
		$shipping_price=$shipping_price['price'];
		//没有运费取保险金
		if(!$shipping_price){
			$insure=$shippingModel->get_insure($shipping_id);
			$shipping_price=$shipping_price?$shipping_price:$insure?$insure:0;
		}
		
		$payment=$_POST['payment'];
		if($payment){
			$fee=get_orders_Fees($totalAmonut,$itemTotal,$payment);
			$fee['insurance']>0 && $this->assign('insurance',getprice_str($fee['insurance']));
			$fee['paymoney']>0 && $this->assign('paymoney',getprice_str($fee['paymoney']));
			$totalAmonut=$fee['total'];//加上其它金额
		}
		
		//满金额免运费, 总金额大于免运费
		$free_shipping = GetValue('free_shipping');
		$this->free_shipping = $free_shipping;
		if($free_shipping && $totalAmonut>=$free_shipping) {
			$shipping_price = 0;
		}
		$this->shippingPrice = getprice_str($shipping_price);//运费
		
		$totalAmonut+=$shipping_price;//总价加上运费

		$cpdao=D("Coupon");
		$coupon=$cpdao->validate($_POST['coupon']);
		if($coupon){
			$totalAmonut-=$coupon['amount'];
			$this->coupon=getprice_str($coupon['amount']);
		}
		//读取订单信息
		$this->list = $dao->display_contents ( $sessionID );//购物车列表
		$this->totalAmount = getprice_str($totalAmonut);//全部总价
		
		if($this->free_shipping) {
			$this->actual_totalAmonut = $totalAmonut - $this->free_shipping;
		}
		echo $this->fetch('Ajax:get_total_amount');
	}



}
?>