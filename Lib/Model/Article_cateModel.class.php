<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class Article_cateModel extends Model{
	protected $_validate=array(

	array('name','require','类别名称必须填写!'),
	array('name','','类别名称已经存在!',0,'unique',1),

	);
	public function get_article_by_cate($name,$limit){
		$article=$this->query("select * from ".C('DB_PREFIX')."article_cate a left join ".C('DB_PREFIX')."article b on a.pid=b.id where a.name='".$name."' order by b.id desc limit ".$limit);
		return $article;
	}

	
	/**
	 * 生成类别
	 *
	 */

	public function cate_option($id=0,$i=0,$selected=null)
	{

		$cate = $this->where(array('pid'=>$id))->order('id asc')->select();
		if(false == $cate) return null;

		$option='';
		if($i==0){
			$option.="<option value=''>-请选择-</option>";
		}else{
			$str='|';
			$str.=str_repeat('-',$i*2);
		}
		foreach ($cate as $v){
			if(!is_null($selected) && $selected==$v['id']){
				$option.="<option value='{$v['id']}' selected='selected'>".$str.$v['name']."</option>";
			}else{
				$option.="<option value='{$v['id']}'>".$str.$v['name']."</option>";
			}
			$option.=$this->cate_option($v['id'],$i+1);
		}
		return $option;
	}
}

?>