<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-17
*/ 
function test ($con)
{
  echo $con;
}
add_filter('Say','test');

?>