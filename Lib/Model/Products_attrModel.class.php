<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-12-2
*/ 
class Products_attrModel extends Model{
	public function del_attrs($products_id){
		if($products_id){
			$map['products_id']=array('in',$products_id);
			$list=$this->where($map)->select();
			if($list){
				foreach ($list as $k=>$v){
					$v['img_url']=auto_charset($v['img_url'],'utf-8','gbk');
					if(file_exists($v['img_url'])){
						unlink($v['img_url']);
					}
				}
			}
			$this->where ($map)->delete ();
		}

	}
	
	public function get_attrs($cateid, $pid){
		
		$dao=D("Cate");
		$typeid=$dao->field("type_id")->where("id=".$cateid)->select();
		$dao=D("Type_attr");
		$attr = $dao->where ( "type_id=" . $typeid [0] ['type_id'] . " and status=1" )->order ( "sort desc" )->select ();

		$attrs=array();
		for($row = 0; $row < count ( $attr ); $row ++) {
			$map1 ['products_id'] = $pid;
			$map1 ['attr_id'] = $attr [$row] ['id'];
			$attr_value=$this->where ( $map1 )->group('attr_value')->order('sort asc')->select ();

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
	
	
}
?>