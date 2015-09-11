<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class WidgetsAction extends AdminCommAction{
	
	function index(){
		$this->assign('list',$this->_parseWidgets());
		$this->display();
	}
	function _parseWidgets(){
		$widget_path=LIB_PATH.'Widgets/';
		$dirs=glob($widget_path.'*',GLOB_ONLYDIR);
		if($dirs){
			$xml=array();
			foreach ($dirs as $key=>$row){
				$info_file=$row.'/info.php';
				if(file_exists($info_file)){
					$xml[basename($row)]['info']=include($info_file);
				}
			}
			file_put_contents($widget_path.'widgets.xml',xml_encode($xml));
			return $xml;
		}
	}
}
?>