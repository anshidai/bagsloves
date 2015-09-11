<?php
import('ViewModel');
class Pro_attrViewModel extends ViewModel {
	protected $viewFields = array(
	'Products'=>array (
	0 => 'id',
	1 => 'cateid',
	2 => 'name',
	3 => 'serial',
	4 => 'price',
	5 => 'pricespe',
	6 => 'weight',
	7 => 'bigimage',
	8 => 'smallimage',
	9 => 'remark',
	10 => 'isnew',
	11 => 'ishot',
	12 => 'isrec',
	13 => 'isprice',
	14 => 'isdown',
	15 => 'dateline',
	16 => 'sort',
	17 => 'brandid',
	18 => 'viewcount',
	19 => 'points',
	20 => 'costprice',
	21 => 'provider',
	22 => 'stock',
	23 => 'pagetitle',
	24 => 'pagekey',
	25 => 'pagedec',
	26 => 'picprefix',
	'_type'=>'LEFT',
	),
	'Products_attr'=>array (
	'id'=>'aid',
	1 => 'products_id',
	2 => 'attr_id',
	3 => 'attr_value',
	4 => 'img_url',
	5 => 'attr_price',
	6 => 'sort',
	'dateline' => 'a_dateline',
	'_on'=>'Products.id=Products_attr.products_id'),
	);
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
		if(isset($_REQUEST['pr']) && isset($_REQUEST['pr2'])){
			$map['pricespe']=array('between',"{$_REQUEST['pr']},{$_REQUEST['pr2']}");
		}elseif(isset($_REQUEST['pr']) && !isset($_REQUEST['pr2'])){
			$map['pricespe']=array('gt',$_REQUEST['pr']);
		}
		
		!empty($_REQUEST['type']) && $map[$_REQUEST['type']]=1;

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

			if('Search' == MODULE_NAME){
				$pro_num=GetValue('search_num');
			}elseif(strpos($_SERVER ['REQUEST_URI'],'cid-')>0){
				$pro_num=GetValue('cate_num');
			}elseif('Pro' == MODULE_NAME){
				$pro_num=GetValue('pro_num');
			}
			if(!$pro_num){
				$pro_num=21;
			}
			$p   = new Page($count,$pro_num);

			//分页查询数据
			$voList = $this->where($map)->order("`Products`.`$order` $sort,`id` desc")->limit($p->firstRow .','.$p->listRows)->select();

			//分页显示
			$page = $p->show();
			$pages['totalRows']=$p->totalRows;
			$pages['totalPages']=$p->totalPages;
			$pages['startRow']=$p->firstRow+1;
			$pages['endRow']=($p->nowPage>1?$p->nowPage:1)*$p->listRows;
			$pages['endRow']=$pages['endRow']>$pages['totalRows']?$pages['totalRows']:$pages['endRow'];
			$pages['list']=$voList;
			$pages['page']=$page;
		}
		$view->assign($pages);
		return null ;
	}
}
?>