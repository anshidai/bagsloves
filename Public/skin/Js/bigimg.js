/*
渐变的弹出图片
*/
//--初始化变量--
var ns4 = document.layers;
var ns6 = document.getElementById && !document.all;
var ie4 = document.all;
var offsetX = 15;
var offsetY = 30;
function getViewportHeight() {
	if (window.innerHeight!=window.undefined) return window.innerHeight;
	if (document.compatMode=='CSS1Compat') return document.documentElement.clientHeight;
	if (document.body) return document.body.clientHeight;

	return window.undefined;
}
function getViewportWidth() {
	if (window.innerWidth!=window.undefined) return window.innerWidth;
	if (document.compatMode=='CSS1Compat') return document.documentElement.clientWidth;
	if (document.body) return document.body.clientWidth;

	return window.undefined;
}

function getScrollTop() {
	if (self.pageYOffset)
	{
		return self.pageYOffset;
	}
	else if (document.documentElement && document.documentElement.scrollTop)

	{
		return document.documentElement.scrollTop;
	}
	else if (document.body)
	{
		return document.body.scrollTop;
	}
}
function getScrollLeft() {
	if (self.pageXOffset)
	{
		return self.pageXOffset;
	}
	else if (document.documentElement && document.documentElement.scrollLeft)

	{
		return document.documentElement.scrollLeft;
	}
	else if (document.body)
	{
		return document.body.scrollLeft;
	}
}
function moveToMouseLoc(e)
{
	var scrollTop = getScrollTop();
	var scrollLeft = getScrollLeft();
	if(ns4||ns6)
	{
		x = e.pageX + scrollLeft;
		y = e.pageY - scrollTop;
	}
	else
	{
		x = event.clientX + scrollLeft;
		y = event.clientY;
	}

	if (x-scrollLeft > getViewportWidth() / 2) {
		x = x - document.getElementById("toolTipLayer").offsetWidth - 2 * offsetX;
	}

	if ((y+document.getElementById("toolTipLayer").offsetHeight+offsetY)>getViewportHeight()) {
		y = getViewportHeight()-document.getElementById("toolTipLayer").offsetHeight-offsetY;
	}
	$("#toolTipLayer").css({
		left:(x + offsetX)+'px',
		top:(y + offsetY + scrollTop)+'px'
	});
	return true;
}
(function($){
	$(function(){
		$('body').prepend("<style>.trans_msg{filter:alpha(opacity=100,enabled=1) revealTrans(duration=.2,transition=1) blendtrans(duration=.2);}</style>");
		$('body').append('<div id = "toolTipLayer" style="position:absolute;display:none;z-index:9999"></div>');
		if(ns4) document.captureEvents(Event.MOUSEMOVE);
		else
		{
			$('#toolTipLayer').hide();
		}
		document.onmousemove = moveToMouseLoc;
		$("a[altimg][altimg!='']").each(function(){
			$(this).mouseover(function(){
				
				var alt="<div align='center'>"+$('img',this).attr('alt')+"</div>";
				$('#toolTipLayer').html('<table style="border:1px solid #eee;background-color:#fff" cellspacing="0" cellpadding="1" class="trans_msg"><td><table style="border:none" cellspacing="2" cellpadding="3" ><td><font face="Arial" color="#777777" size="-2"><img id="loadimg" src="Public/images/ajaxloading.gif" border="0"/>' + alt + '</font></td></table></td></table>').show();
				
			
				$('#loadimg').replaceWith("<img src='"+$(this).attr('altimg')+"' width='350' />");

				


			}).mouseout(function(){
				$('#toolTipLayer').empty().hide();
			});
		});
	});
})(jQuery);