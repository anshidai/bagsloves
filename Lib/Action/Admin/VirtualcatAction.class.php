<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-18
 */
class VirtualcatAction extends AdminCommAction {

	function _initialize(){
		parent::_initialize();
		$this->virtual_catelist=get_virtualcat_arr();
	}
	function index(){
		$this->display();
	}
	function doSortUpdate(){
		$j=0;
		foreach($_POST['sort'] as $k=>$v){
			if($_POST['sort'][$k]!=''){
				if(D('Virtual_cat')->where(array('id'=>$k))->data(array('sort'=>$v))->save()){
					$j++;
				}
			}
		}
		cleanCache();
		$this->success('共修改了'.$j.'个排序!');
	}
	function insert(){
		$_POST['filter']['cateid']=implode(',',$_POST['filter']['cateid']);
		$_POST['filter']['brandid']=implode(',',$_POST['filter']['brandid']);
		$_POST['filter']['type_id']=implode(',',$_POST['filter']['type_id']);
		$_POST['filter']=http_build_query(array_filter($_POST['filter']));
		$dao=D('Virtual_cat');
		if ($dao->create ()) {
			$dao->add ();
			$this->success ( '添加成功！' );
		} else {
			$this->error ( $dao->getError () );
		}
	}
	function edit(){
		$dao=D('Virtual_cat');
		$id=intval($_GET['id']);
		$list=$dao->find($id);
		parse_str($list['filter'], $list['filter']);
		$this->list=$list;
		$this->display();
	}
	function delete() {
		$id=$_REQUEST ['id'];
		if($id){
			$dao=D('Virtual_cat');
			$map ['id'] = array('in',$id);
			if($dao->where ( $map )->count ()){
				$dao->where ( $map )->delete ();
			}
		}
		$this->success ( "删除成功！" );
	}
	public function rename() {
		$data=$_POST;
		unset($data['id']);
		$dao=D('Virtual_cat');
		if($dao->create($data)){
			$dao->id=$_POST['id'];
			if($dao->save ()){
				$this->ajaxReturn($data,'修改成功',1);
			}else{
				$this->ajaxReturn($data,'修改失败',0);
			}
		}else {
			$this->ajaxReturn('',$dao->getError(),0);
		}

	}
	function update(){
		$_POST['filter']['cateid']=implode(',',$_POST['filter']['cateid']);
		$_POST['filter']['brandid']=implode(',',$_POST['filter']['brandid']);
		$_POST['filter']['type_id']=implode(',',$_POST['filter']['type_id']);
		$_POST['filter']=http_build_query(array_filter($_POST['filter']));
		$dao=D('Virtual_cat');
		if ($dao->create ()) {
			$dao->save ();
			$this->success ( '保存成功！' );
		} else {
			$this->error ( $dao->getError () );
		}
	}
}
?>