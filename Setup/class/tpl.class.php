<?php
// tpl类，用于模板
class tpl
{
	var $filename; //模板文件
	var $content; //返回内容

	//初始化模板文件，将所有内容读入
	function tpl($tplfilename)
	{
		$this->filename=$tplfilename;
		$fd = fopen( $this->filename, "r" );
		$this->content = fread($fd, filesize($this->filename));
		$this->contentnull = '';//如果为空则以这个变量的内容替换
		fclose( $fd );
	}

	//替换标志位内容
	function tplsign($key,$value)
	{
		$this->content=str_replace("{".$key."}",$value,$this->content);
	}

	//替换标志块内容
	function tplblocksign($block_name,$values)
	{
		//获得替换块的子模板
		if(is_array($values)){
			@ereg("{".$block_name."}.*{/".$block_name."}",$this->content,$regs);
			$str_block=substr($regs[0],2+strlen($block_name),-(strlen($block_name)+3));
			$str_replace="";
			$block_replace="";
			foreach($values as $subarr)
			{
				$str_replace=$str_block;
				while ( list( $key, $val ) = each( $subarr ) )
				{
					$str_replace=str_replace("{".$key."}",$val,$str_replace);
				}
				$block_replace.=$str_replace;
			}
			$this->content=@ereg_replace ("{".$block_name."}.*{/".$block_name."}",$block_replace,$this->content);
		}else{
			$this->content=@ereg_replace ("{".$block_name."}.*{/".$block_name."}",$this->contentnull,$this->content);
		}
	}

	//替换果否形式的标志块内容
	function tplissign($block_name,$values)
	{
		//获得替换块的子模板
		//不完善,还需要对多个相同的标签替换
		//以后再完成
		$arrayconents=explode("{".$block_name."}",$this->content);
		foreach ($arrayconents as $arrayconent){
			$arrayconent="{".$block_name."}".$arrayconent;
			@ereg("{".$block_name."}.*{/".$block_name."}",$arrayconent,$regs);
			$str_block=substr($regs[0],2+strlen($block_name),-(strlen($block_name)+3));
			if($values){
				$this->content=str_replace ("{".$block_name."}".$str_block."{/".$block_name."}",$str_block,$this->content);
			}else{
				$this->content=str_replace ("{".$block_name."}".$str_block."{/".$block_name."}",$this->contentnull,$this->content);
			}
			unset($regs);
		}
	}

	//替换果否形式的标志块内容
	function tpllangsign($lang)
	{
		//获得替换块的子模板
		$cont=$this->content;
		foreach ($lang as $k=>$l){
			$cont=str_replace("{lang.".$k."}",$l,$cont);
		}
		$this->content=$cont;
	}	
	
	//输出模板内容
	function tplshow()
	{
		echo($this->content);
	}

	//输出模板内容
	function tplreturn()
	{
		return $this->content;
	}

}
?>