<?php
/**
  * @author nanze
  * @link 
  * @todo 文章管理
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-18
*/ 
class ArticleAction extends AdminCommAction{
	public function articlelist(){
		$map=array();

		if(!empty($_REQUEST['pid'])){
			$map['pid']=$_REQUEST['pid'];
			$this->pid=$map['pid'];
		}
		if(!empty($_REQUEST['key'])){
			$map['title']=array('like','%'.auto_charset($_REQUEST['key'],'gbk','utf-8').'%');
		}

		$this->cateoption=D('Article_cate')->cate_option(0,0,$_REQUEST['pid']);
		$this->sort='pid desc';
		$this->_list($map);
		$list=$this->__get('list');
		$article_cate_model=D('Article_cate');
		foreach ($list as $k=>$v){
			$list[$k]['catename']=$article_cate_model->where(array('id'=>$v['pid']))->getField('name');
		}
		$this->assign('list',$list);
		$this->display();
	}
	public function add(){
		$this->cateoption=D('Article_cate')->cate_option();
		$this->display();
	}
	public function edit(){

		$list=$this->dao->where(array('id'=>$_REQUEST['id']))->find();
		$this->cateoption=D('Article_cate')->cate_option();
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

			if(false !== $this->dao->create()){
				$this->dao->dateline=time();
				if(false !== $this->dao->add()){
					$this->success('新增文章成功!');
				}else{
					$this->error('新增文章失败!');
				}
			}else{
				$this->error($this->dao->getError());
			}
		}

	}
	public function update(){
		if($this->isPost()){
			if(false !== $this->dao->create()){
				$this->dao->dateline=time();
				if(false !== $this->dao->save()){
					$this->success('修改文章成功!');
				}else{
					$this->error('修改文章失败!');
				}
			}else{
				$this->error($this->dao->getError());
			}
		}

	}
	public function delete() {
		$map ['id'] = array('in',$_REQUEST ['id']);
		$list=$this->dao->where ($map)->select();
		if($list){
			
			if(false !== $this->dao->where ($map)->delete()){
				/**
				 * 删除相关图片
				 */
				foreach ($list as $value) {
					$img=iconv('utf-8','gbk',$value['img_url']);
					if(file_exists($img)){
						unlink($img);
					}
				}
			}
		}
		$this->success("删除成功!");
	}
}
?>