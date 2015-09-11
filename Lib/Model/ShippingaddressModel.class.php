<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-26
*/ 
class ShippingaddressModel extends Model{
	function get_shippingaddress($memberid){
		$list=$this->where(array('id'=>$memberid))->find();
		return $list;
	}
}
?>