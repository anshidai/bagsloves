<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class ArticleModel extends Model{
	protected $_validate=array(

	array('title','require','文章标题必须填写!'),
	//array('title','','文章标题已经存在!',0,'unique',1),
	
	array ('title', 'uniqueTitle', '文章标题已经存在!', 0, 'callback',1 )
	);
	protected function uniqueTitle(){
		$list=$this->where("title='".$_POST ['title'] ."'")->find();
		if ($list){
			return false;
		}
		else{
			return true;
		}
	}
	/**
	 * 分页
	 *
	 * @param Model $model
	 * @param Array $map
	 * @param String $sortBy
	 * @param Boolean $asc
	 */
	public function _list($view,$map,$sortBy='',$asc=true)
	{
		//排序字段 默认为主键名
		if(isset($_REQUEST['order'])) {
			$order = $_REQUEST['order'];
		}else {
			$order = !empty($sortBy)? $sortBy: $model->getPk();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if(isset($_REQUEST['sort'])) {
			$sort = $_REQUEST['sort']?'asc':'desc';
		}else {
			$sort = $asc?'asc':'desc';
		}
		//取得满足条件的记录数
		if(!empty($_SESSION['map']) && 'Search'==MODULE_NAME){
			$map=$_SESSION['map'];
		}
		$count= $this->where($map)->count();
		$pages=array(
		'totalRows'=>0,//总记录
		'totalPages'=>0,//总页数
		'startRow'=>0,//开始行
		'endRow'=>1,//结束行
		'list'=>null,//当前数据
		"page"=>null//表示字符串
		);
		if($count>0) {
			import("ORG.Util.Page");
			//创建分页数量

			$p   = new Page($count,20);
			//分页查询数据
			$voList = $this->where($map)->order("`".$order."` ". $sort)->limit($p->firstRow .','.$p->listRows)->select();
			//分页显示
			$page = $p->show();
			$pages['totalRows']=$p->totalRows;
			$pages['totalPages']=$p->totalPages;
			$pages['startRow']=$p->firstRow+1;
			$pages['endRow']=($p->nowPage>1?$p->nowPage-1:1)*$p->listRows;
			$pages['list']=$voList;
			$pages['page']=$page;
		}
		$view->assign($pages);
		return null ;
	}
}

?>