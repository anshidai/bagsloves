<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-24
 */
class ArticleAction extends CommAction {
	public function _empty(){
		
		if(is_numeric(ACTION_NAME)){//单独文章
			$_REQUEST['id']=ACTION_NAME;
		}
		$this->index();
	}
	/**
	 * 文章新闻
	 *
	 */
	function index(){
		$model=D('Article');
		switch (true){
			case isset($_REQUEST['id'])://单独文章
				$list=$model->find(intval($_REQUEST['id']));
				if(!empty($list['href'])){
					redirect($list['href']);
					die;
				}
				//副标题
				if(empty($list['title2'])){
					$list['title2']=$list['title'];
				}
				if(!$list){
					parent::_empty();
					return;
				}
				if($next=$model->where('pid='.$list['pid'].' and id>'.$list['id'])->getField('id')){
					$this->next=U('Article/'.$next);
				}
				
				if($prev=$model->where('pid='.$list['pid'].' and id<'.$list['id'])->getField('id')){
					$this->prev=U('Article/'.$prev);
				}
				if($list['pid']){
					$this->nav2=get_cate_nav($list['pid']);
				}
				$this->pagetitle=$list['title'];
				$this->pagekeywords=$list['keywords'];
				$this->pagedesc=$list['description'];
				$this->assign($list);
				$this->display ('Article:index');
				break;
			case isset($_REQUEST['pid'])://文章列表
				$map['pid']=intval($_REQUEST['pid']);
				$model->_list($this->view,$map,'id',false);
				$model=D('Article_cate');
				//seo
				$cate=$model->where(array('id'=>intval($_REQUEST['pid'])))->find();
				//副标题
				if(empty($cate['name2'])){
					$cate['name2']=$cate['name'];
				}
				$this->pagetitle=$cate['name'];
				$this->pagekeywords=$cate['keywords'];
				$this->pagedesc=$cate['description'];
				$this->disp_text=$cate['name'];
				$this->display ('Article:Article_list');
				break;
			default:
				parent::_empty();
				return;
		}


	}
}
?>