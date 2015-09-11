<?php
/**
  * @author nanze
  * @link 
  * @todo 文章管理
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-18
*/ 
class DownAction extends AdminCommAction{
	public function downlist(){
		$Map=array();
		if(!empty($_REQUEST['title'])){
			$Map['title']=array('like','%'.$_REQUEST['title'].'%');
		}
		
		$this->sort='pid desc';
		$this->_list($Map);
		$list=$this->__get('list');
		$Down_cate_model=D('Down_cate');
		foreach ($list as $k=>$v){
			$list[$k]['catename']=$Down_cate_model->where(array('id'=>$v['pid']))->getField('name');
		}
		$this->assign('list',$list);

		$this->display();
	}
	public function add(){
		$this->cateoption=D('Down_cate')->cate_option();
		$this->display();
	}
	public function edit(){

		$list=$this->dao->where(array('id'=>$_REQUEST['id']))->find();
		if($list['pid']){
			$this->cateoption=D('Down_cate')->cate_option(0,0,$list['pid']);
		}else{
			$this->cateoption=D('Down_cate')->cate_option();
		}
		$this->list=$list;
		$this->display();
	}
	public function status(){
		$map['id']=intval($_REQUEST['id']);
		$status=$this->dao->where($map)->getField('status');
		if($status==1){
			$status=0;
			$data['img_url']=__ROOT__.'/Tpl/default/Public/images/mod_0.gif';
		}else{
			$status=1;
			$data['img_url']=__ROOT__.'/Tpl/default/Public/images/mod_1.gif';
		}
		$this->dao->where($map)->data(array('status'=>$status))->save();
		$this->ajaxReturn($data,'保存成功',1);

	}
	public function insert(){
		if($this->isPost()){

			if(false !== $data=$this->dao->create()){
				$this->dao->dateline=time();
				for($i=0;$i<count($_POST['imgurl']);$i++){
					if (! empty ( $_POST ['imgurl'][$i] )) {
						$data ['file_url'] = $_POST ['imgurl'] [$i];//上传新文件
					}
				}
				if(false !== $this->dao->add($data)){
					$this->success('新增下载成功!');
				}else{
					$this->error('新增下载失败!');
				}
			}else{
				$this->error($this->dao->getError());
			}
		}

	}
	public function update(){
		if($this->isPost()){
			if(false !== $data=$this->dao->create()){
				$this->dao->dateline=time();
				for($i=0;$i<count($_POST['imgurl']);$i++){
					if (! empty ( $_POST ['imgurl'][$i] )) {
						$data ['file_url'] = $_POST ['imgurl'] [$i];//上传新文件
					}
				}
				if(false !== $this->dao->save($data)){
					$this->success('修改下载成功!');
				}else{
					$this->error('修改下载失败!');
				}
			}else{
				$this->error($this->dao->getError());
			}
		}

	}
	public function delete() {
		$map ['id'] = array('in',$_REQUEST ['id']);
		$list=$this->dao->where ($map)->select();
		if(false != $list){
			if(false !== $this->dao->where ($map)->delete()){
				/**
				 * 删除相关文件
				 */
				foreach ($list as $value) {
					$file=iconv('utf-8','gbk',$value['file_url']);
					if(file_exists($file)){
						unlink($file);
					}
				}
				$this->success("删除成功!");
			}else{
				$this->error('删除失败!');
			}
		}else{
			$this->error('请选择删除项!');
		}

	}
}
?>