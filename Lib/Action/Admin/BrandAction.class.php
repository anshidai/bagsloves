<?php
/**
  * @author nanze
  * @link 
  * @todo 品牌管理
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-18
*/ 
class BrandAction extends AdminCommAction{
	public function brandlist(){
		$this->sort="id desc";
		$this->_list();
		$this->display();
	}
	function Delete() {
		$id=$_REQUEST ['id'];
		$map ['id'] = array('in',$id);
		
		if($list=$this->dao->where ( $map )->select ()){
			$img=auto_charset($list['imgurl'],'utf-8','gbk');
			if(file_exists($img)){
				unlink($img);
			}
			$this->dao->where ( $map )->delete ();
		}
		$this->success ( "删除成功！" );
	}
}
?>