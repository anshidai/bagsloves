<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-24
*/ 
class CurrenciesModel extends Model{
	protected $_validate=array(

	array('name','require','货币名称必须填写!'),
	array('name','','货币名称已经存在!',0,'unique',1),

	);

	function get_usd_rate(){
		$rate=$this->where(array('symbol'=>'USD'))->getField('rate');
		return $rate;

	}
}
?>