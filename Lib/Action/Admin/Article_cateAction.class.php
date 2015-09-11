<?php
/**
  * @author nanze
  * @link 
  * @todo 文章类别管理
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-18
*/ 
class Article_cateAction extends AdminCommAction{
	public function catelist(){
		$list=get_artcate_arr();
		$this->assign('list',$list);
		$this->display();
	}

	public function add(){

		$this->cateoption=$this->dao->cate_option();
		$this->display();
	}
	public function edit(){
		$list=$this->dao->where(array('id'=>$_REQUEST['id']))->find();
		$this->list=$list;
		if($list['pid']){
			$this->cateoption=$this->dao->cate_option(0,0,$list['pid']);
		}else{
			$this->cateoption=$this->dao->cate_option();
		}
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
					$this->success('新增类别成功!');
				}else{
					$this->error('新增类别失败!');
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
				if($this->dao->id==$this->dao->pid){
					$this->error('上级类别不能为自已!');
				}
				$children=$this->dao->field('id')->where(array('pid'=>$this->dao->id))->select();
				$children=array_map('reset',$children);

				if(in_array($this->dao->pid,$children)){
					$this->error('上级类别不能为自已子类!');
				}
				if(false !== $this->dao->save()){
					$this->success('修改类别成功!');
				}else{
					$this->error('修改类别失败!');
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