<?php
/**
 * 查找
 *
 */
class SearchAction extends CommAction {
	public function index(){
		$type=$_REQUEST['type'];
		$key=$_REQUEST['key'];

		if (!get_magic_quotes_gpc()) {
			$key = addslashes($key);
		}
		$sql=array();
		if(!empty($key) && !empty($type)){
			switch (true){
				case $type=='name':
					$sql['name']=array('like',"%$key%");//名称模糊查找
					break;
				case $type=='id':
					$sql['id']=intval($key);//名称模糊查找
					break;
				case $type=='brand':
					$sql['brand']=$key;//品牌查找
					break;
				case $type=='cateid':
					$sql['cateid']=$key;//类别查找
					break;
				case $type=='pricelt':
					$sql['price']=array('lt',$key);//价格小于
					break;
				case $type=='pricegt':
					$sql['price']=array('gt',$key);//价格大于
					break;
			}
		}elseif(!empty($key)){
			$sql['name']=array('like',"%$key%");//名称模糊查找
			$sql['serial']=array('like',"%$key%");//名称模糊查找
			$sql['id']=intval($key);
			$sql['_logic']='or';
		}
		$map['isdown']=array('neq','1');
		parent::$Model=D("Products");
		if(isset($_REQUEST['key'])){
			$_SESSION['map']=$sql;
		}elseif(isset($_REQUEST['map'])){
			$sql=$_SESSION['map'];
		}
		$this->disp_text="Search";
		parent::$Model->_list($this->view,$sql,'sort',false);
		$this->display();
	}



}
?>