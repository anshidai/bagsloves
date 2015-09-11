<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2011-3-15
*/ 
class ShippingAction extends AdminCommAction{
	function index(){
		$this->list=$this->dao->select();
		$this->display();
	}
}
?>