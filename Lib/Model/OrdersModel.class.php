<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-29
*/ 
class OrdersModel extends Model{
protected $_auto=array(
	array('dateline','time',1,'function'),
	array('ip_address','get_client_ip',1,'function'),
	array('sn','get_sn',1,'function'),
	);
	public function get_order($id){
		return $this->where("id=".$id)->find();
	}
}

?>