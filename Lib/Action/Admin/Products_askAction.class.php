<?php
/**
  * @author nanze
  * @link 
  * @todo  产品问答
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-18
*/ 
class Products_askAction extends AdminCommAction{

	public function index(){
		$this->type=$this->dao->field('type')->group('type')->select();
		if(isset($_REQUEST['type'])){
			$map['type']=$_REQUEST['type'];
		}
		$this->sort="id desc";
		$this->_list($map);
		$this->display();
	}
	public function edit(){
		$id=$_GET['id'];
		$list=$this->dao->find($id);
		if($list['products_id']){
			$pro_model=D('Products');
			$data=$pro_model->find($list['products_id']);
			$list['pro']=$data;
		}
		$this->assign('list',$list);
		$this->display();
	}
}
?>