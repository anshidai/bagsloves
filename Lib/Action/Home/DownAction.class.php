<?php
/**
 * 下载
 *
 */
class DownAction extends CommAction {
	public function index(){	
		$dao=D('Down');
		$this->disp_text='Download list';
		$dao->_list($this->view,$map,'id',false);
		$this->display();
	} 
	
}


?>