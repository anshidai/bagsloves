<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-23
 */
class Virtual_catModel extends Model {
	protected $_validate=array(

	array('name','require','类别名必须填写!'),
	array('name','','类别名已经存在!',0,'unique',1),

	);
	
}
?>