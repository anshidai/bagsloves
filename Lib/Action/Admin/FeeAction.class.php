<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class FeeAction extends AdminCommAction{

	function index(){
		$id=$_GET['id'];
		$this->payment=D('Payment')->find($id);
		$data['payment_id']=$id;
		$list=$this->dao->where($data)->find();
		if(!$list){
			$list['id']=$this->dao->add($data);
		}
		$this->list=$list;
		$this->display();
	}

}
?>