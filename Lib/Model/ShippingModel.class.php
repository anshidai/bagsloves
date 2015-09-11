<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2011-3-15
*/ 
class ShippingModel extends  Model{


	//运费列表
	function get_shipping_list($countyid,$stateid,$city,$weight){
		$ShippingList=$this->where("status=1")->select();
		foreach ($ShippingList as $k=>$v){
			$ShippingList[$k]['price']=$this->get_shipping_fee($v['id'],$countyid,$stateid,$city,$weight);
			$ShippingList[$k]['price']['price']=$ShippingList[$k]['price']['price']?$ShippingList[$k]['price']['price']:$v['insure'];
		}
		
		return $ShippingList;
	}
	function get_insure($id){
		$list=$this->find($id);
		return $list['insure'];
	}
	function get_name($id){
		$list=$this->find($id);
		return $list['name'];
	}
	//获取运费
	function get_shipping_fee($shippingid,$countyid,$stateid,$city,$weight){
		$fee=array();
		$dao=D("Shipping_area");
		if(!is_numeric($city)){
			$cityid=get_region_id($city);
		}else{
			$cityid=$city;
		}
		
		$list=$dao->where("shipping_id=".$shippingid)->select();
		if ($list){
			foreach ($list as $k=>$v){
				
				$config=unserialize ( $v["config"] );
				if(is_numeric($cityid) && $cityid && in_array ( $cityid,$config)) {
					return $this->shipping_fee($v,$weight);
				}elseif(is_numeric($stateid) && $stateid && in_array ( $stateid, $config )) {
					return $this->shipping_fee($v,$weight);
				}elseif(is_numeric($countyid) && $countyid && in_array ($countyid, $config )) {
					return $this->shipping_fee($v,$weight);
				}
			}
		}
		else{
			$price['price']=0;
			return $price;
		}
	}
	function shipping_fee($fee,$weight){
		$price=array();
		if ($weight<=1){
			$price['base_fee']=$fee["base_fee"];
			$price['price']=$price['base_fee']?$price['base_fee']:0;
		}
		else{
			$r=$fee["base_fee"]+($weight-1)*$fee["step_fee"];
			if ($r>=$fee["free_money"] && $fee["free_money"]){
				$price['price']=0;
			}
			else{
				$price['base_fee']=$fee["base_fee"];
				$price['step_fee']=($weight-1)*$fee["step_fee"];
				$price['price']=$r;
			}
		}
		return $price;
	}
	function get_shipping_method($id){
		$list=$this->field('shipping_method')->find($id);
		return $list['shipping_method'];
	}
}
?>