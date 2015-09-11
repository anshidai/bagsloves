<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-24
*/ 
class  CurrenciesAction extends AdminCommAction{
	function currencieslist(){
		$this->_list();
		$this->display();
	}
}
?>