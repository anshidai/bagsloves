<?php

class CouponAction extends AdminCommAction{
	public function index(){
		if(isset($_GET['coupon'])){
				$map['coupon']=$_GET['coupon'];
		}
		$this->sort="id desc";
		$this->_list($map);
		$this->display();
	}

	/**
	 * 生成优惠券
	 *
	 */
	public function insert(){
		$num=$_POST['num']?$_POST['num']:10;
		if($data=$this->dao->create()){
			$data['start_time']=strtotime($_POST['start_time']);
			$data['expire_time']=strtotime($_POST['expire_time']);
			$data['dateline']=time();
			for($i=0;$i<$num;$i++){
				$data['coupon']=strtoupper(uniqid ( 'CP' ));
				$this->dao->add($data);
			}
			$this->success('生成成功'.$num.'个优惠券!');
		}else{
			$this->error($this->dao->getError());
		}

	}
	public function update(){
		if($this->dao->create()){
			$this->dao->start_time=strtotime($_POST['start_time']);
			$this->dao->expire_time=strtotime($_POST['expire_time']);
			$this->dao->dateline=time();
			$this->dao->save();
			$this->success('保存成功!');
		}else{
			$this->error($this->dao->getError());
		}
	}
}
?>