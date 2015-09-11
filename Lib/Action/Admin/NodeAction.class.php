<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-16
 */
class NodeAction extends AdminCommAction {
	
	public function nodelist() {
		if($this->isPost()){
			$access=$_POST['access'];
			SetValue('accessmode',$access);
			cleanCache ();
			$this->success ( "修改成功！" );
		}
		$this->access=GetValue('accessmode')?GetValue('accessmode'):'module';
		$this->display ();
	}
	
	public function Delete() {
		$map ['id'] = $_GET ['id'];
		$i = $this->dao->where ( "pid=" . $_GET ['id'] )->count ();
		if ($i > 0) {
			$this->error ( "对不起，该类别下还有子分类，请先删除子分类！" );
		} else {
			$this->dao->where ( $map )->delete ();
			$this->success ( "删除类别成功！" );
		}
	}
	
	
}
?>