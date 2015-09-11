<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2011-1-17
*/ 
class Orders_shippingbillsModel extends Model{
	protected $_validate = array (
	array('order_id','','订单发货单已经存在!',0,'unique',1),
	);
}
?>