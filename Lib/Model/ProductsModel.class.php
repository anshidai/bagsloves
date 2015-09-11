<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-23
 */
class ProductsModel extends Model {



	protected $_validate = array (
	//array ('name', 'checknamelen', 'Products name too long!', self::EXISTS_VAILIDATE, 'callback' ),

	array('cateid','checkcateid','必须选择类别ID',self::EXISTS_VAILIDATE, 'callback'),
	array('name','require','产品名称必须填写!'),
	array('name','','产品名称已经存在!',0,'unique',1),
	);
	protected $_auto=array(
	array('dateline','time',3,'function'),
	);
	protected function checknamelen(){
		if (strlen($_REQUEST['name'])>=60){
			return false;
		}
		else{
			return true;
		}
	}
	protected function checkcateid($cateid){
		if (!$cateid){
			return false;
		}
		else{
			return true;
		}
	}
	public function rand($list){
		$tmp=array();
		$r=array();
		$count=count($list);
		while(count($tmp)<$count){
			$tmp[]=mt_rand(0,$count-1);
			$tmp=array_unique($tmp);
		}
		while(list(,$k)=each($tmp)){
			$r[]=$list[$k];
		}
		return $r;
	}
	public function bough_other($id){
		$order_products_dao=D('Orders_products');
		$list=$order_products_dao->query("SELECT * from __TABLE__ where orders_id in (select orders_id from __TABLE__ where products_id=$id) and products_id !=$id");
		return $list;
		
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
			$voList = $this->where($map)->order("`$order` $sort,`id` desc")->limit($p->firstRow .','.$p->listRows)->select();

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

	public function viewcounts($pid){
		$data['viewcount']=array('exp',"viewcount+1");
		$this->where('id='.$pid)->save($data);
	}


	public function get_attrs($cateid,$pid){
		$dao=D("Cate");
		$typeid=$dao->field("type_id")->where("id=".$cateid)->select();
		$dao=D("Type_attr");
		$attr = $dao->where ( "type_id=" . $typeid [0] ['type_id'] . " and status=1" )->order ( "sort desc" )->select ();

		$dao = D ( "Products_attr" );
		$attrs=array();
		for($row = 0; $row < count ( $attr ); $row ++) {
			$map1 ['products_id'] = $pid;
			$map1 ['attr_id'] = $attr [$row] ['id'];
			$attr_value=$dao->where ( $map1 )->group('attr_value')->order('sort asc')->select ();

			if($attr_value){

				switch($attr[$row]['input_type']){
					case 0:
						$attrs [$row]=$attr [$row];
						$attrs [$row] ['attrs'] = $attr_value;
						$attrs [$row] ['values'] = $attr_value[0]['attr_value'];
						break;
					case 1:
						$attrs [$row]=$attr [$row];
						$attrs [$row] ['attrs'] = $attr_value;
						$attrs [$row] ['values'] = explode ( chr ( 13 ), $attr [$row] ['values'] );
						foreach ($attrs[$row]['values'] as $k=>$v){
							$attrs[$row]['values'][$k]=str_replace("\n","",$v);
						}

						$attrs [$row] ['values_count'] = count($attrs [$row] ['attrs']);
						break;
					case 3:
						$attrs [$row]=$attr [$row];
						$attrs [$row] ['attrs'] = $attr_value;
						$attrs [$row] ['values'] = explode ( ',', $attr_value[0]['attr_value'] );
						foreach ($attrs[$row]['values'] as $k=>$v){
							$attrs[$row]['values'][$k]=$v;

						}
						$attrs [$row] ['values_count'] = count($attrs [$row] ['attrs']);

						break;

				}
			}
		}
		return $attrs;
	}
	//获取产品价格
	public function get_weight($pid){
		$list=$this->where("id=".$pid)->find();
		if ($list){
			return $list["weight"];
		}
		else{
			return 0;
		}
	}
	//计算属性价格
	public function model_attr_price($model){
		$price=0;
		foreach ((array)$model as $value){
			$price+=$value['attr_price'];
		}
		return $price;
	}
	//获取产品价格明细
	public function getpriceInfo($pid,$count,$model){
		$list=$this->where("id=".$pid)->find();
		if (!$list){
			return null;
		}
		else{
			$model_price=$this->model_attr_price($model);
			$list['price']=(float)(VipPrice($list['price'])+$model_price);
			$list['pricespe']=(float)(VipPrice($list['pricespe'])+$model_price);
			$list['count']=$count;
			$ginfo=get_members_group(Session::get('memberID'));
			$list['discount']=$ginfo['discount']?$ginfo['discount']:1;
			$list['price_total']=$list['price']*$count;
			$list['pricespe_total']=$list['pricespe']*$count;
			//优惠活动,1为批发
			if(in_array($list['activity'],array('1'))){
				switch ($list['activity']){
					case 1:
						$PL_model=D('Products_lot');
						$data=$PL_model->where(array("product_id"=>$pid))->select();
						$maxcount=$PL_model->where("product_id={$pid}")->order("maxcount desc")->find();
						foreach ($data as $k=>$v){
							if($count>=$v['mincount'] && $count<=$v['maxcount']){
								$list['pricespe']=$list['price']=(float)$v['price'];
								$list['price_total']=$list['price']*$count;
								$list['pricespe_total']=$list['pricespe']*$count;
								break 2;
							}elseif($count>=$maxcount['maxcount']){
								$list['pricespe']=$list['price']=(float)$maxcount['price'];
								$list['price_total']=$list['price']*$count;
								$list['pricespe_total']=$list['pricespe']*$count;
								break 2;
							}
						}
						break;
				}
			}
			if ($list['price_total']<=$list['pricespe_total'] && $list['price_total']){
				$list['total']=$list['price_total'];
			}
			else{
				$list['total']=$list['pricespe_total'];
			}
			if ($discount>0){
				$list['total']=$list['total']*$discount;
			}

			return $list;
		}
	}
}
?>