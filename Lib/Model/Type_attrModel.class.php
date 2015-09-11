<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-12-1
*/ 
class Type_attrModel extends Model{
	
	function getAttrs($typeid){
		$attr=$this->where("type_id=".$typeid." and status=1")->order("sort desc")->select();
		for ($row=0;$row<count($attr);$row++){
			$attr[$row]['values']=explode(chr(13),$attr[$row]['values']);
			foreach ($attr[$row]['values'] as $k=>$v){
				$attr[$row]['values'][$k]=str_replace("\n","",$v);
			}
		}
		return $attr;
	}
	function getAllAttrs($typeid){
		$attr=$this->where("status=1")->order("sort desc")->select();
		for ($row=0;$row<count($attr);$row++){
			$attr[$row]['values']=explode(chr(13),$attr[$row]['values']);
			foreach ($attr[$row]['values'] as $k=>$v){
				$attr[$row]['values'][$k]=str_replace("\n","",$v);
			}
		}
		return $attr;
	}

}
?>