//+---------------------------------------------------
//|	快速方法获得当前的对象
//+---------------------------------------------------
function $(objectId){
	return reobj=(typeof(objectId)=="object") ? objectId : document.getElementById(objectId);
}

//+---------------------------------------------------
//|	显示或隐藏一个对象
//+---------------------------------------------------
function display(objectId){
	
		if($(objectId).style.display=='none'){
			$(objectId).style.display='block';
		}else{
			$(objectId).style.display='none';
		}
}

//+---------------------------------------------------
//|	跳转到一个安装步骤里去
//+---------------------------------------------------
function gostepurl(step){
	location.href = "index.php?action=show&step="+step;
}

//+---------------------------------------------------
//|	提交表单,以GET的形式,查看表单的内容就可以理解
//+---------------------------------------------------
function godourl(step){
	$('mainform').submit();
}

//+---------------------------------------------------
//|	点击退出按键后提示
//+---------------------------------------------------
function cancal(){
	if(notice('你是否在退出安装的同时把安装文件删除?\r点[确定]删除安装文件,点[取消]退出安装')){
		location.href = "index.php?action=del";
	}else{
		location.href = "index.php?action=out";
	}
}

//+---------------------------------------------------
//|	confirm提示
//+---------------------------------------------------
function notice(content){
	if(!confirm(content)){
		return false;
	}else{
		return true;
	}
}