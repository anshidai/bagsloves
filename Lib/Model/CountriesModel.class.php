<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-26
*/ 
class  CountriesModel extends Model{
	public function getlist(){
		return $this->order("countries_name")->select();
	}
}
?>