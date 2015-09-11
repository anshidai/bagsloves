<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class UserModel extends Model{
	protected $_auto=array(
	array('password','md5',1,'function'),
	);
}
?>