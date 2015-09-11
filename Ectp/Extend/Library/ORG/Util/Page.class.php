<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class Page extends Think {
	// 起始行数
	protected $firstRow	;
	// 列表每页显示行数
	protected $listRows	;
	// 页数跳转时要带的参数
	public $parameter  ;
	// 分页总页面数
	protected $totalPages  ;
	// 总行数
	protected $totalRows  ;
	// 当前页数
	protected $nowPage    ;
	// 分页的栏的总页数
	protected $coolPages   ;
	// 分页栏每页显示的页数
	protected $rollPage   ;
	// 分页显示定制
	// protected $config  =	array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
	protected $config  =	array('header'=>'Records','prev'=>'prev','next'=>'next','first'=>'first','last'=>'last','theme'=>' %totalRow% %header% %nowPage%/%totalPage%  %upPage% %downPage%     %linkPage%  ');

	/**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
	public function __construct($totalRows,$listRows,$parameter='') {
		$this->totalRows = $totalRows;
		$this->parameter = $parameter;
		$this->rollPage = C('PAGE_ROLLPAGE');
		$this->listRows = !empty($listRows)?$listRows:C('PAGE_LISTROWS');
		$this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
		$this->coolPages  = ceil($this->totalPages/$this->rollPage);
		$this->nowPage  = str_replace('.html','',!empty($_GET[C('VAR_PAGE')])?$_GET[C('VAR_PAGE')]:1);
		if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
			$this->nowPage = $this->totalPages;
		}
		$this->firstRow = $this->listRows*($this->nowPage-1);
	}

	public function setConfig($name,$value) {
		if(isset($this->config[$name])) {
			$this->config[$name]    =   $value;
		}
	}

	/**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
	public function show() {
		$strpath=C('URL_PATHINFO_DEPR');
		if(0 == $this->totalRows) return '';
		$p = C('VAR_PAGE');
		$nowCoolPage      = ceil($this->nowPage/$this->rollPage);
		 
		$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
		
		$parse = parse_url($url);
		if(isset($parse['query'])) {
			parse_str($parse['query'],$params);
			unset($params[$p]);
			$url   =  $parse['path'].'?'.http_build_query($params);
		}
		
		//上下翻页字符串
		$upRow   = $this->nowPage-1;
		$downRow = $this->nowPage+1;
		if($strpath=='-'){
			$strurl=explode('-p-',$url);
		}elseif($strpath=='_'){
			$strurl=explode('_p_',$url);
		}else{
			$strurl=explode('/p/',$url);
		}

		$url=$strurl['0'];
		
		$url=str_replace('?','',$url);
		$url=str_replace('.html','',$url);
		
		$url=auto_charset($url,'gbk','utf-8'); 
		$url=rtrim($url,'-');
		if ($upRow>0){
			$upPage="[<a href='".$url.$strpath.$p.$strpath."$upRow.html'>".$this->config['prev']."</a>]";
		}else{
			$upPage="";
		}

		if ($downRow <= $this->totalPages){
			$downPage="[<a href='".$url.$strpath.$p.$strpath."$downRow.html'>".$this->config['next']."</a>]";
		}else{
			$downPage="";
		}
		// << < > >>
		if($nowCoolPage == 1){
			$theFirst = "";
		}else{
			$preRow =  $this->nowPage-$this->rollPage;
			$theFirst = "[<a href='".$url.$strpath.$p.$strpath."1.html' >".$this->config['first']."</a>]";
		}
		if($nowCoolPage == $this->coolPages){
			$nextPage = "";
			$theEnd="";
		}else{
			$nextRow = $this->nowPage+$this->rollPage;
			$theEndRow = $this->totalPages;
			$theEnd = "[<a href='".$url.$strpath.$p.$strpath."$theEndRow.html' >".$this->config['last']."</a>]";
		}
		
		$linkPage="";
		// 1 2 3 4 5
		for($i=1;$i<=$this->totalPages;$i++){
			$page=$i;
				if($page<=$this->totalPages){
					$linkPage .= "&nbsp;<a href='".$url.$strpath.$p.$strpath."$page.html'>&nbsp;".$page."&nbsp;</a>";
				}
		}
		//下拉框形式
		$linkPage .= "<select id='spage'>";
		for($i=1;$i<=$this->totalPages;$i++){
			$page=$i;
				if($page<=$this->totalPages){
					if($i==$this->nowPage){
					$linkPage .="<option value='".$url.$strpath.$p.$strpath."$page.html' selected>&nbsp;".$page."&nbsp;</option>";
					}
					else{
						$linkPage .="<option value='".$url.$strpath.$p.$strpath."$page.html'>&nbsp;".$page."&nbsp;</option>";
					}
				}
		}
		$linkPage .= "</select>";
		$pageStr	 =	 str_replace(
		array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%linkPage%','%end%'),
		array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$linkPage,$theEnd),$this->config['theme']);
		return 'total'.$pageStr;
	}

}
?>