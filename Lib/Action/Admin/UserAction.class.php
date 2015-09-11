<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-16
 */
class UserAction extends AdminCommAction {
	var $dao;
	
	function userlist() {
		$this->list = $this->dao->select ();
		$this->display ();
	}
	
	public function Insert() {
		if ($_POST ['password'] != $_POST ['repassword']) {
			$this->error ( '两次输入的密码不同！' );
		}
		if ($this->dao->create ()) {
			$id = $this->dao->add ();
			$this->success ( '添加成功！' );
		} else {
			$this->error ( $this->dao->getError () );
		}
	}
	
	
	
	public function UpdatePassword(){
		$map ['id'] = $_POST ['id'];
		$data ['password'] = md5 ( $_POST ['password'] );
		$list = $this->dao->where ( $map )->save ( $data );
		if ($list !== false) {
			$this->success ( '数据更新成功！' );
		} else {
			$this->error ( "没有更新任何数据!" );
		}
	}
	public function Role(){
		$this->userID=$_GET['id'];
		$model=D('Role_user');
		$map['user_id']=$_GET['id'];
		$this->list=$model->where($map)->select();
		$this->display();
	}
	public function setRole(){
		$model=D('Role_user');
		$map ['user_id'] = $_POST ['userID'];
		$model->where($map)->delete();
		$roleID=$_POST['roleID'];
		for($i=0;$i<count($roleID);$i++){
			$data['user_id']=$_POST ['userID'];
			$data['role_id']=$roleID[$i];
			$model->add($data);
		}
		$this->success ( '数据更新成功！' );
		
	}
}
?>