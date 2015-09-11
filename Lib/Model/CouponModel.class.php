<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class CouponModel extends Model{
	protected $_validate=array(

	array('name','require','优惠券名称必须填写!')

	);
	function validate($coupon){
		$map['coupon']=trim($coupon);
		$map['status']=1;
		$map['start_time']=array('elt',time());
		$map['expire_time']=array('egt',time());
		$list=$this->where($map)->find();
		return $list;
	}
}

?>