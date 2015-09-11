<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class AdAction extends AdminCommAction{
	function _initialize()
	{
		parent::_initialize();
		$map['type']=array('not in',"'txt','img','flash','code',''");
		$this->type=$this->dao->field('type')->group('type')->where($map)->select();
	}
	function adlist(){
		$this->type=$this->dao->field('type')->group('type')->select();
		if(isset($_REQUEST['type'])){
			$map['type']=$_REQUEST['type'];
		}
		$this->sort="id desc";
		$this->_list($map);
		$this->display();
	}
	public function Insert() {
		if(!$_POST['type'] && $_POST['type2']){
			$_POST['type']=$_POST['type2'];
		}
		$return[]=array( 
		'text'=>'继续添加广告',
		'link'=>U('Ad/add')
		);
		
		$this->assign('return',$return);
		parent::Insert();
	}
	public function Update() {
		if(!$_POST['type'] && $_POST['type2']){
			$_POST['type']=$_POST['type2'];
		}
		$return[]=array( 
		'text'=>'添加新广告',
		'link'=>U('Ad/add')
		);
		$return[]=array( 
		'text'=>'继续编辑该广告',
		'link'=>U('Ad/edit',array('id'=>$_POST['id']))
		);
		$this->assign('return',$return);
		parent::Update();
	}
	function Delete() {
		$id=$_REQUEST ['id'];
		if($id){
			$map ['id'] = array('in',$id);
			if($list=$this->dao->where ( $map )->select ()){
				foreach($list as $k=>$v){
					$img=auto_charset($v['img_url'],'utf-8','gbk');
					if(file_exists($img)){
						unlink($img);
					}
				}
				$this->dao->where ( $map )->delete ();
			}
		}
		$this->success ( "删除成功！" );
	}
}
?>