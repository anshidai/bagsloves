<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-25
 */
class CartModel extends Model {

	public $memberID;
	function _initialize() {
		parent::_initialize();
		$this->memberID=Cookie::get('memberID')?Cookie::get('memberID'):0;//会员帐号

	}

	public function discount($price){
		$discount_enable=GetValue('discount_enable');
		//判断是否有折扣
		$discount=1;
		$r=array();
		if($discount_enable){
			$model=D('Discount');
			$list=$model->select();
			foreach ($list as $k=>$v){
				if($price>=$v['minmoney'] && $price <=$v['maxmoney']){
					$discount=$v['discount'];
					break;
				}
			}
		}
		if($discount<1){
			$r['price']=round($price*$discount,2);//保留两位
			$r['text']=getprice_str($price).'*<Span style="color:red;font-weight:bold;">'.($discount*100).'%</span>='.getprice_str($r['price']);
		}else{
			$r['price']=$price;
			$r['text']="";
		}
		return $r;
	}
	// 查验物品
	public function check_item($sessionID, $pid,$model) {
		$list = $this->where ( "session_id='" . $sessionID . "' and pid=" . $pid." and model='".$model."'" )->find ();
		if (! $list) {
			return 0;
		} else {
			//返回产品数量
			return count($list);
		}
	}
	//添加购物车
	public function add_item($sessionID, $pid, $count,$model) {
		$qty = $this->check_item ( $sessionID, $pid,$model );

		if ($qty) {
			$data ['count'] = array ('exp', 'count+' . $count );
			$data['dateline']=time();
			$this->where ( "session_id='" . $sessionID . "' and pid=" . $pid." and model='".$model."'" )->save ( $data );
		} else {
			$data['pid']=$pid;
			$data['uid']=$this->memberID;//不是会员为0
			$data['session_id']=$sessionID;
			$data['count']=$count;
			$data['model']=$model;
			$data['dateline']=time();

			$this->add($data);
		}

	}
	// 删除购物车中的内容
	public function delete_item($sessionID, $id){
		if($id){
			$map['session_id']=$sessionID;
			$map['id']=array('in',$id);
			$this->where ($map)->delete();
		}
	}
	//修改物品数量
	public function modify_quantity($sessionID, $id,$count,$model){
		$data['count']=$count;
		//$data['model']=$model;
		$data['dateline']=time();
		$this->where ( "session_id='" . $sessionID . "' and id=" . $id )->save($data);
	}
	//获取产品种类数
	public function get_item_count($sessionID){

		return $this->where ( "session_id='" . $sessionID . "'"  )->count();
	}
	//获取产品总数
	public function get_item_totalcount($sessionID){
		return $this->where ( "session_id='" . $sessionID . "'"  )->sum('count');
	}
	//清空购物车
	public function clear_cart($sessionID){
		$this->where ( "session_id='" . $sessionID . "'"  )->delete();
	}
	//获取总价格
	public function cart_total($sessionID){
		$total=0;
		$dao=D("Products");
		$list=$this->where( "session_id='" . $sessionID . "'")->select();
		for ($row=0;$row<count($list);$row++){
			$data=$dao->getpriceInfo($list[$row]['pid'],$list[$row]['count'],unserialize($list[$row]['model']));
			$total=$total+$data['total'];
		}
		return $total;
	}
	//获取总重量
	public function cart_total_weight($sessionID){
		$total = 0;
		$dao=D("Products");
		$list = $this->where ( "session_id='" . $sessionID . "'" )->select ();
		for($row = 0; $row < count ( $list ); $row ++) {
			$weight = $dao->get_weight($list[$row]["pid"])*$list [$row] ['count'] ;
			$total = $total + $weight;
		}
		return $total;

	}
	//获取购物车明细
	public function display_contents($sessionID){
		$list = $this->where (  "session_id='" . $sessionID."'")->select ();
		if (!$list){
			return null;
		}
		$dao = D ( "Products" );
		for($row = 0; $row < count ( $list ); $row ++) {

			$list[$row]['model']= unserialize($list[$row]['model']) ;
			$data = $dao->getpriceInfo ( $list [$row] ['pid'], $list [$row] ['count'],$list[$row]['model'] );

			$list[$row]['price']=$data['price'];
			$list[$row]['serial']=$data['serial'];//
			//$list[$row]['pid']=$data['id'];
			$list[$row]['name']=$data['name'];
			$list[$row]['bigimage']=$data['bigimage'];
			$list[$row]['smallimage']=$data['smallimage'];
			//$list[$row]['model2']= $dao->get_attrs($dao->where(array('id'=>$list[$row]['pid']))->getField('cateid'),$list[$row]['pid']);
			//$list[$row]['model']= $list['model'] ;
			$list[$row]['pricespe']=$data['pricespe'];
			$list[$row]['count']=$data['count'];
			$list[$row]['discount']=$data['discount'];
			$list[$row]['price_total']=$data['price_total'];
			$list[$row]['pricespe_total']=$data['pricespe_total'];
			$list[$row]['total']=$data['total'];
			$list[$row]['costprice']=$data['costprice'];
			$list[$row]['provider']=$data['provider'];
		}

		return $list;
	}
	
	public function cart_list($sessionID)
	{
		$list = $this->where("session_id='" . $sessionID."'")->select();
		if(empty($list)) return array();
		
		$data = array();
		
		$dao = D("Products");
		foreach($list as $k=>$val) {
			$product_info = $dao->getpriceInfo($val['pid'], $val['count'], $val['model']);
			
			if(!empty($val['model'])) {
				$attr_tmp = unserialize($val['model']);
				foreach($attr_tmp as $vo) {
					$row['attr'] .= "{$vo['name']}: {$vo['value']} &nbsp;&nbsp;";
				}
			}
			$row['id'] = $product_info['id'];
			$row['cateid'] = $product_info['cateid'];
			$row['name'] = $product_info['name'];
			$row['url'] = build_url($product_info,'pro_url');
			$row['price'] = $product_info['price'];
			$row['pricespe'] = getprice($product_info['price'],$product_info['pricespe'],false);
			$row['bigimage'] = build_url($product_info,'pro_bigimage');
			$row['smallimage'] = build_url($product_info,'pro_smallimage');
			$row['count'] = $product_info['count'];
			$row['price_total'] = $product_info['price_total'];
			$row['pricespe_total'] = $product_info['pricespe_total'];
			$row['total'] = $product_info['total'];
			$row['costprice'] = $product_info['costprice'];
			$row['provider'] = $product_info['provider'];
			$data[] = $row;
		}
		return $data;
	}
	
}
?>