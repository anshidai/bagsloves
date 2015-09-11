<?php
class IndexAction extends RpcCommAction   {

	
	//返回订单列表
	public function getOrderInfo($sessionid,$type,$start,$shownum){
		$this->CheckSession($sessionid);
		$map['orders_status']= array('like','%'); ;
		if($type!="") 
		{
			$map['orders_status']=$type;
		}
		$dao = D("Orders");
		$orderList = $dao->where ( $map )->limit ($start. ',' .$shownum )->order('id desc')->select ();
		$sql = $dao->getlastsql();
		if($orderList){
			$res["code"]=0;
			$res["msg"]='订单列表';
			$res["sql"]= $sql;
			$res["orderList"]=$orderList;
		}else{
			$res["code"]=1;
			$res["msg"]='找不到该记录';
		}
		return $res;
	}
	
	//返回指定订单
	public function getOrderByOrderId($sessionid,$type,$orderSN){
		$this->CheckSession($sessionid);
		$map['orders_status']=$type;
		$map['sn']=$orderSN;
		$dao = D("Orders");
		$orderItem = $dao->where ( $map )->find ();
	
		if($orderItem){
			$res["code"]=0;
			$res["msg"]='订单项';
			$res["orderItem"]=$orderItem;
		}else{
			$res["code"]=1;
			$res["msg"]='找不到该记录';
		}
		return $res;
	
	}
	
	//返回指定订单的详细信息
	public function getOrderInfoByOrderId($sessionid,$type,$orderSN){
		$this->CheckSession($sessionid);
		if($type!="") 
		{
			$map['orders_status']=$type;
		}
		$map['sn']=$orderSN;
		$billdata['order_sn']=$orderSN;
		$dao = D("Orders");
		$orderItem = $dao->where ( $map )->find ();
		$orderId = $orderItem["id"];
		///发货号
		$bill=M("orders_shippingbills");
		$billinfo=$bill->where($billdata)->find();
		$expresssn=$billinfo['ExpressSN'];
		////收货地址
		$shippingInfo=$dao->find($orderId);
	//	
		//产品信息
		$list=$dao->query("select a.*,b.bigimage,b.serial,if(a.products_pricespe,a.products_pricespe,a.products_price) as price from ".C('DB_PREFIX')."orders_products a left join ".C('DB_PREFIX')."products b on a.products_id = b.id where a.orders_id='".$orderId."'");
		$listSql = $dao->getlastsql();
		//产品总价
		$orders_products_Model=D('Orders_products');
		$products_total=$orders_products_Model->where("orders_id=".$orderId)->sum('products_total');
	
		//产品总数
		$products_sum=$orders_products_Model->where("orders_id=".$orderId)->sum('products_quantity');
	
		foreach ($list as $k=>$v){
			$list[$k]['products_model']=unserialize($v['products_model']);
		}
	
		//订单产品总重量
		$orders_total=$shippingInfo['orders_total'];
		$total_weight=$shippingInfo['total_weight'];
	
		if($orderItem){
			$res["code"]=0;
			$res["msg"]='订单项';
			$res["orderItem"]=$orderItem; 
			//$res["deliveryAddress"]=$shippingInfo;
			$res["proDetail"]=$list;
			$res["products_total"]=$products_total;
			$res["orders_total"]=$orders_total;
			$res["ExpressSN"]=$expresssn;
		}else{
			$res["code"]=1;
			$res["msg"]='找不到该记录';
		}
		return $res;
	
	}
	
	public function ModifyOrderStatus($Sessionid,$Orderid,$Status,$Billsn,$Shoppingmethod){
		$this->CheckSession($sessionid);
		$data['sn']=$Orderid;
		$bdata['order_sn']=$Orderid;
		$map=$data;
		$data['orders_status']=$Status;
		$data['shipping_method']=$Shoppingmethod;
		$dao = D("Orders");
		$orderInfo = $dao->where ( $map )->find();
		//发货信息
		$billdata['order_sn']=$Orderid;
		$billdata['order_id']=$orderInfo['id'];
		$billdata['ExpressSN']=$Billsn;
		$billdata['delivery_name']=$orderInfo['delivery_firstname']+$orderInfo['delivery_lastname'];
		$billdata['delivery_city']=$orderInfo['delivery_city'];
		$billdata['delivery_state']=$orderInfo['delivery_state'];
		$billdata['delivery_country']=$orderInfo['delivery_country'];
		$billdata['delivery_zip']=$orderInfo['delivery_zip'];
		$billdata['delivery_telephone']=$orderInfo['delivery_telephone'];
		$billdata['delivery_address']=$orderInfo['delivery_address'];
		$billdata['Express']=$orderInfo['shipping_method'];
		
		$billdata['sender_sname'] = GetValue('sender_sname');
		$billdata['sender_address'] = GetValue('sender_address');
		$billdata['sender_city'] = GetValue('sender_city');
		$billdata['sender_region'] = GetValue('sender_region');
		$billdata['sender_country'] = GetValue('sender_country');
		$billdata['sender_zip'] = GetValue('sender_zip');
		$billdata['sender_tel'] = GetValue('sender_tel');
		
		
		
		if($orderInfo){
			$orderStatus = $dao->where ( $map )->save ($data);
		    $Tbill=M("orders_shippingbills");
		    if($Billsn != false && $Billsn !="" &&$Billsn !=" " && $Billsn !="null"){
		        $billStatus=$Tbill->where($bdata)->save($billdata);
		        if($billStatus == false)
		        {
		    	   $billStatus=$Tbill->data($billdata)->add();
		        }
		    }
		    
			if($orderStatus !== false && $billStatus !== false){
				$res["code"]=0;
				$res["Msg"]='修改订单成功';
				$res["OrderInfo"]=$orderInfo;
                $res["Billsn"]=$Billsn;
			}else{
				$res["code"]=1;
				$res["Msg"]='修改订单状态失败';
				$res["OrderInfo"]=$orderInfo;
			}
		}else{
			$res["code"]=2;
			$res["Msg"]='找不到该记录';
			$res["OrderInfo"]=null;
		}
		
		return $res;
	}
	public function getSenderInfo($Sessionid){
		$this->CheckSession($sessionid);
		$array['sender_sname'] = GetValue('sender_sname');
		$array['sender_address'] = GetValue('sender_address');
		$array['sender_city'] = GetValue('sender_city');
		$array['sender_region'] = GetValue('sender_region');
		$array['sender_country'] = GetValue('sender_country');
		$array['sender_zip'] = GetValue('sender_zip');
		$array['sender_tel'] = GetValue('sender_tel');
		return $array;
	}
	
	
	//参数是数组
	public function paramArray($array){
		return $array["username"];
	}
		
	//测试
	public function helloWorld($str){
		return "hello    ".$str;
	}
	//返回数组
	public function returnArray(){
		return array("key_a"=>"value_aaa","key_b"=>"value_bbb","key_c"=>"value_ccc");
	}
}
?>