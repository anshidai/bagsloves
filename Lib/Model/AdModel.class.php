<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class AdModel extends Model{

	protected $_auto=array(
	array('content','get_text',3,'callback')
	);
	public function get_text(){
		if($_POST['type']=='txt'){
			$_POST['content']=$this->StripHTML($_POST['content']);
		}
		return $_POST['content'];
	}

	function StripHTML($string){
		$pattern=array ("'<script[^>]*?>.*?</script>'si", "'<style[^>]*?>.*?</style>'si",  "'<[\/\!]*?[^<>]*?>'si",  "'([\r\n])[\s]+'",  "'&(quot|#34);'i",  "'&(amp|#38);'i",  "'&(lt|#60);'i",  "'&(gt|#62);'i",  "'&(nbsp|#160);'i",  "'&(iexcl|#161);'i",  "'&(cent|#162);'i",  "'&(pound|#163);'i",  "'&(copy|#169);'i",  "'&#(\d+);'e");
		$replace=array ("", "", "\\1", "", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), "chr(\\1)");
		return preg_replace ($pattern, $replace, $string);
	}
}
?>