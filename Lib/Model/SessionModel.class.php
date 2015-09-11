<?php
/**
  * @author nanze
  * @link 
  * @todo 
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-23
*/ 
class SessionModel extends Model{
	
	public function AddSession($session_id,$authInfoid){
		$data["sessionid"] = $session_id;
		$data["adminid"] = $authInfoid;
		$this->add($data);
	}

	public function GetSession($sessionid){
		$map["sessionid"]= $sessionid;
		if($list=$this->where($map)->find()){
			return $list;
		}else {
			return  false;
		}
	}
	public function GetAdminId($sessionid){
		$map["sessionid"]= $sessionid;
		if($list=$this->where($map)->find()){
			return $list['adminid'];
		}else {
			return  '';
		}
	}
	
}
?>