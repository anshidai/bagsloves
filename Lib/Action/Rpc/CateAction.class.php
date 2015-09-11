<?php
class CateAction extends RpcCommAction   {

	public function catelist(){
		$list=get_catetree_arr();
		if($list){
			$res["code"]=0;
			$res["catelist"]=$list;
		}else{
			$res["code"]=1;
			$res["Msg"]='查询失败';
			$res["catelist"]=null;
		}
		return $res;

	}

	public function brandlist(){
		$CateModel=D('brand');
		$list=$CateModel->select();
		if($list){
			$res["code"]=0;
			$res["brandlist"]=$list;
		}else{
			$res["code"]=1;
			$res["Msg"]='查询失败';
			$res["brandlist"]=null;
		}
		return $res;
	}

	public function typelist(){
		$CateModel=D('type_cate');
		$list=$CateModel->select();
		if($list){
			$res["code"]=0;
			$res["typelist"]=$list;
		}else{
			$res["code"]=1;
			$res["Msg"]='查询失败';
			$res["typelist"]=null;
		}
		return $res;
	}

	//添加类别
	public function addcate($cateinfo) {
		$model=M('Cate');
		if ($model->create($cateinfo)) {
			$id=$model->add();
			if($id){
				$res["code"]=0;
				$res["Msg"]='添加成功';
			}else{
				$res["code"]=1;
				$res["Msg"]='添加失败';
			}
		} else {
			$res["code"]=2;
			$res["Msg"]=$model->getError ();
		}
		cleanCache ();
		return $res;
	}
	//保存类别
	public function savecate($id,$cateinfo) {
		$model=M('Cate');
		$array["id"]=$id;
		$count=$model->where($array)->save($cateinfo);
		if($count){
			$res["code"]=0;
			$res["Msg"]='保存成功';
		}else{
			$res["code"]=1;
			$res["Msg"]='保存失败';
		}
		cleanCache ();
		return $res;
	}

	//删除类别
	public function delcate($id) {
		$model=M('Cate');
		$promodel=M('products');
		$array["id"]=$id;
		$catecount=$model->where("pid=".$id)->count();
		$procount=$promodel->where("cateid=".$id)->count();
		if($catecount<1 && $procount<1 ){
			$count=$model->where($array)->delete();
			if($count){
				$res["code"]=0;
				$res["Msg"]='删除成功';
			}else{
				$res["code"]=1;
				$res["Msg"]='删除失败';
			}
		}else {
			$res["code"]=2;
			$res["Msg"]='该类别正在使用，删除失败';
		}
		cleanCache ();
		return $res;
	}
	public function uploadFile($content,$ext=".jpg"){
		$savePath="./Public/Uploads/Rpc/Cate/".toDate(time(),'Ymd');
		if (! file_exists ( $savePath )) {
			mk_dir ( $savePath );
		}
		$savePath.="/".uniqid().$ext;
		if(file_put_contents($savePath,$content)){
			$res['code']=0;
			$res["Msg"]='上传成功';
			$res['path']=$savePath;
		}else{
			$res['code']=1;
			$res["Msg"]='上传失败';
		}
		return $res;
	
	}
}
?>