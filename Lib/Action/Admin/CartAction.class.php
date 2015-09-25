<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-30
*/ 
class CartAction extends AdminCommAction{
	
	function cartlist()
	{
		$map=array();
		if(!empty($_REQUEST['kwd'])){
			$map['session_id']=array('like','%'.$_REQUEST['kwd'].'%');
		}
		$this->sort="id desc";
		$this->kwd=$_REQUEST['kwd'];
		$this->_list ($map);
		
		$this->display ();
	}
	
	
	
}