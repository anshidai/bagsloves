<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-10-19
*/ 
class RoleAction extends AdminCommAction{
	
	public function roleList(){
		//$dao=M("Role");
		$this->display();
	}	
	public function Delete(){
		$map['id']=$_GET['id'];		
		$i = $this->dao->where ( "pid=" . $_GET ['id'] )->count ();
		if ($i > 0) {
			$this->error ( "对不起，该类别下还有子分类，请先删除子分类！" );
		} else {
			$this->dao->where ( $map )->delete ();
			$this->success ( "删除类别成功！" );
		}
	}
	
	
	public function Node(){
		$this->roleID=$_GET['id'];
		$model=D('Access');
		$map['role_id']=$_GET['id'];
		$this->accesslist=$model->where($map)->select();
		$this->display();
	}
	public function setNode(){
		$model=D('Access');
		
		$roleID=$_POST['roleID'];
		$nodeID=$_POST['nodeID'];
		$map['role_id']=$roleID;
		$model->where($map)->delete();
		for($i=0;$i<count($nodeID);$i++){
			$data['role_id']=$roleID;
			$data['node_id']=$nodeID[$i];
			$model->add($data);
		}
		$this->success ( '数据更新成功！' );
	}
}
?>