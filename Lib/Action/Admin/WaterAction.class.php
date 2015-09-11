<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class WaterAction extends AdminCommAction{
	function index(){
		$this->display();
	}
	function do_img(){
		header("Content-Type:text/html; charset=utf-8");
		import("ORG.Util.Image");
		$model=D('Products');
		$imagetype=$_REQUEST['imagetype'];
		switch ($imagetype){
			case 'big':
				$field='bigimage';
				break;
			case 'small':
				$field='smallimage';
				break;
		}
		$page=$_REQUEST['page'];
		$ImgWaterPath=__ROOT__.GetValue('ImgWaterPath');
		$ImgWaterPos=GetValue('ImgWaterPos');
		$ImageWaterAlpha=GetValue('ImageWaterAlpha')?GetValue('ImageWaterAlpha'):80;
		$start=$_REQUEST['start']?$_REQUEST['start']:0;
		$list=$model->limit("$start,$page")->select();
		$i=$start;
		if($list){
			foreach ($list as $v){
				$image=$v[$field];

				if(file_exists(__ROOT__.$image)){
					$i++;
					$this->do_it($image,$ImgWaterPath,$ImgWaterPos,$ImageWaterAlpha,$i);
				}
			}
			redirect(U('Water/do_img',array('imagetype'=>$imagetype,'page'=>$page,'start'=>$start+$page)));

		}else{
			echo "处理完毕!<br/>";
			flush();
			ob_flush();
		}
	}
	function do_it($image,$ImgWaterPath,$ImgWaterPos,$ImageWaterAlpha,$i){
		echo "正在处理第".$i."张图片:".$image."<br/>";
		flush();
		ob_flush();
		Image::water($image,$ImgWaterPath,$ImgWaterPos,'',$ImageWaterAlpha);
	}
}
?>