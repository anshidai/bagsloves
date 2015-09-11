<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class BrandModel extends Model{
	protected $_validate=array(

	array('name','require','品牌名称必须填写!'),
	array('name','','品牌名称已经存在!',0,'unique',1),

	);
}

?>