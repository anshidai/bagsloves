<?php

header("Content-type: text/html; charset=utf-8");
set_time_limit(0);

ini_set('display_errors', 1);
error_reporting(E_ALL);

class SpiderAction extends Action {
    
    public $cate = array(
        107 => '387',
        79 => '388',
        21 => '389',
        23 => '390',
        24 => '391',
        25 => '392',
        28 => '393',
        20 => '394',
        18 => '395',
        30 => '396',
        22 => '397',
        19 => '398',
        29 => '399',
        27 => '400',
        26 => '401',
        3 => '410',
        12 => '409',
        16 => '408',
        106 => '407',
        95 => '406',
        80 => '405',
        116 => '404',
        111 => '403',
        11 => '411',
        45 => '414',
        110 => '413',
        50 => '417',
        112 => '416',
        90 => '419',
        54 => '426',
    );
    
    
    public function reset()
    {
        $model = M('CjDetail');
        
        $where = " status=1 AND site_name='2015destinationbags' AND is_use=0 ";
        
        $count = $model->where($where)->count();

        for($id=1; $id<=$count; $id++) {
            $info = $model->field('goods_id,related_id,attachment')->where($where." AND id={$id}")->find();
            if(empty($info)) {
                continue;
            }
            if(empty($info['related_id'])) {
                $model->where("goods_id='{$info['goods_id']}' AND site_name='2015destinationbags'")->save(array('attachment_merge'=>$info['attachment'], 'is_use'=>2));
            }else {
                $list = $model->field('goods_id,related_id,attachment')->where("goods_id in({$info['related_id']})")->select();
                if($list) {
                    $attachment = array();
                    foreach($list as $k=>$val) {
                        $attachment[$k] = $val['attachment'];
                    }
                    $attachment = array_unique($attachment);
                    
                    $model->where("goods_id in({$info['related_id']}) AND site_name='2015destinationbags'")->save(array('is_use'=>1));

                    $model->where("goods_id='{$info['goods_id']}' AND site_name='2015destinationbags'")->save(array('attachment_merge'=>implode('||', $attachment), 'is_use'=>2));
                }    
            }
            echo "compelte {$id}\n<br>";
        }
        
    }
    
    
    public function http_down_img($img_url)
    {
        $path_parts = pathinfo($img_url);
        
        $subdir = date('Ymd');
        $imgPath = "./Public/Uploads/Products/{$subdir}/";
        $milliSecond = substr(md5($path_parts['basename'].date()), 0, 13);
        
        if(!is_dir($imgPath)) {
            @mkdir($imgPath, 0777);
        }
        
        $get_file = @file_get_contents($img_url);
        
        $rndFileName = $imgPath.$milliSecond.".".$path_parts['extension'];
        
        if($get_file) {
            $fp = @fopen($rndFileName, "w");
            @fwrite($fp, $get_file);
            @fclose($fp);
        }
        unset($get_file);
        return $rndFileName;
    }
    
    
    public function ceate_thumb($image_path, $width = 160, $height = 160)
    {
        //$thumbSuffix = "_thumb";

        import("ORG.Util.Image");
        $Img = new Image();//实例化图片类对象
        $image_info = $Img::getImageInfo($image_path);//获取图片信息
        $thumb_name = str_replace(end(explode('/', $image_path)), 'thumb_'.end(explode('/', $image_path)), $image_path);

        $thumb_type = $image_info['type'];
        $Img::thumb($image_path, $thumb_name, $thumb_type, $width, $height);
        return $thumb_name;
    }
    
    
    public function init()
    {
        $width = GetValue('ImgThumbW');
        $height = GetValue('ImgThumbH');
        
        $max_id = M('CjDetail')->max('id');
        for($i=1; $i<=$max_id; $i++) {
            $info = M('CjDetail')->where("id={$i} AND status=1 and site_name='2015destinationbags'")->find();
            if(empty($info) || $info['is_push'] == '1') continue;

            $related_ids = $info['related_id']? $info['related_id']: $info['goods_id'];
            $relate_goods = M('CjDetail')->where("goods_id in($related_ids) AND status=1 and site_name='2015destinationbags' AND is_push=0")->order('id')->select();
            if($relate_goods) {
                $insert_id = array();
                foreach($relate_goods as $k=>$val) {
                    $data = array();
                    $data['name'] = $val['goods_name'];
                    $data['price'] = $val['market_price'];
                    $data['pricespe'] = $val['shop_price'];
                    $data['cateid'] = $this->cate[$val['cat_id']];
                    $data['remark'] = $val['desc'];
                    $data['dateline'] = time();
                    $data['cj_url'] = $val['url'];
                    $data['brandid'] = 0;
                    $data['costprice'] = 0;
                    $data['provider'] = 0;
                    $insert_id[] = $this->_add($val['id'], $val['attr'], $val['select_color'], $val['attachment'], $data);
                }
                //插入商品关联关系
                $this->add_related($insert_id);
            }
        }

    }
    
    public function add_related($goods_ids)
    {
        if(empty($goods_ids) || count($goods_ids) == 1) return false;
        
        for($i=0; $i<count($goods_ids); $i++) {
            $curr_id = $goods_ids[$i];
            $tmp_goods_ids = $goods_ids;
            $key = array_search($curr_id, $tmp_goods_ids);
            
            if($key !== false) {
                array_splice($tmp_goods_ids, $key, 1);
            }
            
            for($j=0; $j<count($tmp_goods_ids); $j++) {
                M('ProductsRelated')->add(array('products_id'=>$curr_id, 'related_products_id'=>$tmp_goods_ids[$j]));
            }
        }
    }
    
    public function upload_color_img()
    {

        $width = GetValue('ImgThumbW');
        $height = GetValue('ImgThumbH');
        
        $list = D('Products')->field('id,cateid,name,bigimage,smallimage')->select();
        foreach($list as $k=>$val) {
            $count = D('ProductsAttr')->where("products_id={$val['id']} AND attr_id=3")->count();
            if(!$count) {
                $img = 'http://www.bagsloves.com/'.str_replace('./', '', $val['bigimage']);
                $colorname = substr($val['name'], stripos($val['name'], 'large'), -1);
                $colorname = explode(' ', $colorname);
                $color = trim($colorname[1]);
                $arr_color = array(
                            'Yellow',
                            'White',
                            'Silver',
                            'Red',
                            'Purple',
                            'Pink',
                            'Orange',
                            'Multicolour',
                            'Ivory',
                            'Grey',
                            'Green',
                            'Gold',
                            'Brown',
                            'Blue',
                            'Black',
                            'Apricot',
                            'With',
                            'purses',
                            'Tassel',
                            'Stud',
                            'Striped',
                            'Scarf',
                            'Others',
                            'Necklaces',
                            'Lock',
                            'Flap',
                            'Close',
                            'Earrings',
                            'Double',
                            'Zipper',
                            'Bracelets',
                            'Vanilla',
                            'Khaki',
                            'Chocolate',
                            'Bronze',
                            'Rose',
                            'Golden',
                            'Coffee',
                            'Navy',
                            'Fuchsia',
                            'Red Black',
                            'Brown White',
                            'Brown Coffee',
                            'Brown Apricot',
                            'Black Navy',
                            'Camel',
                            'Beige'
                );
                
                $color = '';
                for($i=0; $i<count($arr_color); $i++) {
                    if(stripos(strtolower($val['name']), strtolower($arr_color[$i]))) {
                        $color = $arr_color[$i];
                        break;
                    }    
                }
                
                if(!in_array($color, $arr_color)) {
                    file_put_contents('/home/libaoan/no_find_color_3.log', "{$val['id']}\t{$val['name']}\n", FILE_APPEND);
                    //file_put_contents('E:/no_find_color.log', "{$val['id']}\t{$val['name']}\n", FILE_APPEND);
                    continue;
                }
                $color_data['img_url'] = $this->http_down_img($img);
                $color_data['thumb_url'] = $this->ceate_thumb($color_data['img_url'], $width, $height);
                $color_data['products_id'] = $val['id'];
                $color_data['attr_id'] = 3;
                $color_data['attr_value'] = $color;
                $color_data['dateline'] = time();
                $color_data['sort'] = 0;
                M('ProductsAttr')->add($color_data);
                
                file_put_contents('/home/libaoan/find_color_3.log', "{$val['id']}\t{$val['name']}\n", FILE_APPEND);
                //file_put_contents('E:/find_color.log', "{$val['id']}\t{$val['name']}\n", FILE_APPEND);
                
                
           }
        }
            
    }
    
    public function _add($id, $attr, $curr_color, $attachment, $data)
    {
        if(empty($id) || empty($data) || !is_array($data)) return false;
        
        $width = GetValue('ImgThumbW');
        $height = GetValue('ImgThumbH');

        //添加商品图册
        if($goods_id = M('Products')->add($data)) {
            $attachment = explode('||', $attachment);
            for($i=0; $i<count($attachment); $i++) {
                $img[$i]['img_url'] = $this->http_down_img($attachment[$i]);
                $img[$i]['thumb_url'] = $this->ceate_thumb($img[$i]['img_url'], $width, $height);
                $img[$i]['img_original'] = $img[$i]['thumb_url'];
                $img[$i]['img_desc'] = end(pathinfo($img[$i]['img_url']));
            }
            foreach($img as $thumb) {
                M('ProductsGallery')->add(array('pid'=>$goods_id, 'img_url'=>$thumb['img_url'], 'thumb_url'=>$thumb['thumb_url'], 'img_desc'=>$thumb['img_desc'], 'img_original'=>$thumb['img_original']));
            }
            $serial = 'B0000'.$goods_id;
            M('Products')->where("id='{$goods_id}'")->save(array('serial'=>$serial, 'bigimage'=>$img[0]['img_url'], 'smallimage'=>$img[0]['thumb_url']));
            
            $attr_list = unserialize($attr);
            if(!empty($attr_list) && is_array($attr_list)) {
                //添加属性-尺寸
                if($attr_list['size']) {
                    $size_data['products_id'] = $goods_id;
                    $size_data['attr_id'] = 2;
                    $size_data['attr_value'] = $attr_list['size'];
                    $size_data['dateline'] = time();
                    $size_data['sort'] = 0;
                    M('ProductsAttr')->add($size_data);
                }
            }
            
            //添加属性-颜色
            if($curr_color) {
                $color = unserialize($curr_color);
                $color['img'] = str_replace('_0.', '.', $color['img']);
                $color_data['img_url'] = $this->http_down_img($color['img']);
                $color_data['thumb_url'] = $this->ceate_thumb($color_data['img_url'], $width, $height);
                $color_data['products_id'] = $goods_id;
                $color_data['attr_id'] = 3;
                $color_data['attr_value'] = $color['color'];
                $color_data['dateline'] = time();
                $color_data['sort'] = 0;
                M('ProductsAttr')->add($color_data);
            }
            
            M('CjDetail')->where("id='{$id}'")->save(array('is_push'=>1));
            echo "compelte {$id}\n<br>";
            unset($data,$attr_list,$size_data,$color_data,$img);
            usleep(500000);
            return $goods_id;
        }
        return false;
    }
    
    public function init_bak()
    {
        $width = GetValue('ImgThumbW');
        $height = GetValue('ImgThumbH');
        
        $list = M('CjDetail')->where("status=1 and site_name='2015destinationbags' AND is_use=2 AND is_push=0")->order('id')->select();
        //$list = M('CjDetail')->where("goods_id in(6340,6341) AND is_use=2 AND is_push=0")->order('id')->limit(30)->select();
        foreach($list as $k=>$val) {
            $data = array();
            $data['name'] = $val['goods_name'];
            $data['price'] = $val['market_price'];
            $data['pricespe'] = $val['shop_price'];
            $data['cateid'] = $this->cate[$val['cat_id']];
            $data['remark'] = $val['desc'];
            $data['dateline'] = time();
            $data['cj_url'] = $val['url'];
            $data['brandid'] = 0;
            $data['costprice'] = 0;
            $data['provider'] = 0;

            //添加商品图册
            if($goods_id = M('Products')->add($data)) {
                $attachment = explode('||', $val['attachment_merge']);
                for($i=0; $i<count($attachment); $i++) {
                    $img[$i]['img_url'] = $this->http_down_img($attachment[$i]);
                    $img[$i]['thumb_url'] = $this->ceate_thumb($img[$i]['img_url'], $width, $height);
                    $img[$i]['img_original'] = $img[$i]['thumb_url'];
                    $img[$i]['img_desc'] = end(pathinfo($img[$i]['img_url']));
                }
                foreach($img as $thumb) {
                    M('ProductsGallery')->add(array('pid'=>$goods_id, 'img_url'=>$thumb['img_url'], 'thumb_url'=>$thumb['thumb_url'], 'img_desc'=>$thumb['img_desc'], 'img_original'=>$thumb['img_original']));
                }
                $serial = 'B0000'.$goods_id;
                M('Products')->where("id='{$goods_id}'")->save(array('serial'=>$serial, 'bigimage'=>$img[0]['img_url'], 'smallimage'=>$img[0]['thumb_url']));
            }

            $attr_list = unserialize($val['attr']);
            if(!empty($attr_list) && is_array($attr_list)) {
                
                //添加属性-尺寸
                if($attr_list['size']) {
                    $size_data['products_id'] = $goods_id;
                    $size_data['attr_id'] = 2;
                    $size_data['attr_value'] = $attr_list['size'];
                    $size_data['dateline'] = time();
                    $size_data['sort'] = 0;
                    M('ProductsAttr')->add($size_data);
                }
                
                //添加属性-颜色
                if($attr_list['color']) {
                    foreach($attr_list['color'] as $color) {
                        $color['img'] = str_replace('_0.', '.', $color['img']);
                        $color_data['img_url'] = $this->http_down_img($color['img']);
                        $color_data['thumb_url'] = $this->ceate_thumb($color_data['img_url'], $width, $height);
                        $color_data['products_id'] = $goods_id;
                        $color_data['attr_id'] = 3;
                        $color_data['attr_value'] = $color['color'];
                        $color_data['dateline'] = time();
                        $color_data['sort'] = 0;
                        M('ProductsAttr')->add($color_data);
                    }
                }
            }
            M('CjDetail')->where("id='{$val['id']}'")->save(array('is_push'=>1));
            echo "compelte {$val['id']}\n<br>";
            unset($data,$attr_list,$size_data,$color_data,$img);
            usleep(500000);
            //sleep(1);
            //exit;
        }
    }
    
    
}


