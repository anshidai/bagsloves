$(function(){
	
	/** 图片左右滚动 **/
	var oPic = $('#slider_pic').find('ul');
	var oImg = oPic.find('li');
	var oLen = oImg.length;
	var oLi = oImg.width();
	var prev = $("#prev");
	var next = $("#next");
	var step = 94;
	
	oPic.width(oLen*step); //计算总长度
	var iNow = 0;
	var iTimer = null;
	
	prev.click(function(){
		if(iNow>0){ 
			iNow--;
		}
		ClickScroll();
	});
	next.click(function(){
		if(iNow<oLen-3){
			iNow++
		}
		ClickScroll();
	});
	
	function ClickScroll()
	{
		iNow==0? prev.addClass('no_click'): prev.removeClass('no_click');
		iNow==oLen-3?next.addClass("no_click"):next.removeClass("no_click");
		oPic.animate({left:-iNow*step});
	}
	/** 图片左右滚动 end **/

	
	
	$("#continue_shopping").click(function(){
		$("#cart_ok").hide();
	});
	
	$("#add-cart").click(function(){
		var form = document.cart_quantity;
		$.post($(form).attr('action'),$(form).serialize(),function(data){
			if(!data.status){
				if(data.url != '') {
					alert(data.info);
					location.href = data.url;
					return;
				}
				jQuery('#cart_ok').hide();
				jQuery('#cart_alert').html(data.info).fadeIn(200).delay(3000).fadeOut(200);
			}else{
				
				location.href = '/index.php/Cart-disp.html';
				return;
				jQuery('#cart_alert').hide();
				jQuery('#cart_ok').find('.cart_ok_content').html(data.info).end().fadeIn(200);
				if(data.list) {
					var html = '<ul>';
					for(var i=0; i<data.list.length; i++) {
						html += '<li class="cart_box">';
						html += '<div class="cart_pro_img"><a href="'+data.list[i].url+'"><img src="'+data.list[i].smallimage+'"></a></div>';
						html += '<span class="cart_pro_name"><a href="'+data.list[i].url+'">'+data.list[i].name+'</a></span>';
						html += '<span class="cart_pro_property"><span>'+data.list[i].attr+'</span></span>';
						html += '<span class="cart_pro_piece">'+data.list[i].count+' item(s)</span>';
						html += '<span class="cart_pro_price FontColor">'+data.list[i].pricespe+'</span>';
						html += '</li>';
					}
					html += '</ul>';
					html += '<div class="cart_pro_btn"><a href="'+CartUrl+'"><span class="cart_view">View Cart ( <span class="cart_num">'+data.items_total+'</span> item)</span></a></div>';
					$("#cart_list").html(html).show();
					$("#cart_empty").hide();
				}
			}
		},'json');
		return false;
	});
	
	$("#go-cart").click(function(){
		var form = document.cart_quantity;
		if(isAjaxLogin){
			jQuery.post($(form).attr('action'),$(form).serialize(),function(data){
				if(!data.status){
					jQuery('#cart_ok').hide();
					jQuery('#cart_alert').html(data.info).fadeIn(200).delay(3000).fadeOut(200);
				}else{
					location.href=CartUrl;
				}
			},'json');
		}else{
			$(form).submit();
			return false;
		}
	});
	
	
    //zoom-img
    $(".jqzoom").imagezoom();
    $(".small-img li a").click(function() {
        $(this).parents("li").addClass("curr").siblings().removeClass("curr");
        $(".jqzoom").attr('src', $(this).find("img").attr("rel"));
        $(".jqzoom").attr('rel', $(this).find("img").attr("rel"));
    });

    $(".attr-color li a").click(function(){
        $(this).parents("li").addClass("curr").siblings().removeClass("curr");
        $(".jqzoom").attr('src', $(this).find("img").attr("rel"));
        $(".jqzoom").attr('rel', $(this).find("img").attr("rel"));
		$("#attr_color").val($(this).attr("attr"));
		$(".attr_selected").show().find("strong").html($(this).attr("title"));
		$("#shop-price").html($(this).find("span").html());

    });
    //zoom-img end
    
    var goods_number = $("#goods_number");
    $("#increase_btn").click(function() {
        goods_number.val(parseInt(goods_number.val()) +1);
        if(parseInt(goods_number.val()) <=0) {
            goods_number.val(1);
        }
    });
    $("#reduce_btn").click(function() {
        goods_number.val(parseInt(goods_number.val()) -1);
        if(parseInt(goods_number.val()) <=0) {
            goods_number.val(1);
        }
    });


    $("#comment_show").click(function(){
        showlayer("#comment-layer");
    });
    
    $("#comment_close").click(function(){
        $("#comment-layer").hide();    
    });

    rateStar();
});

function submitComment()
{  
    var quslity_rank = $("#quslity_rank").val();
    var email = $("#review_email").val();
    var products_id = $("#products_id").val();
    var comment_type = $("#comment_type").val();
    var review_content = $("#review_content").val();
	var email_reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
	
	if(email == '' || !email_reg.test(email)) {
		alert('Please fill in the email correctly.');
        return false;  
	}

    if(review_content == '') {
        alert('Please enter content.');
        return false;  
    } else if(review_content.lenght <10) {
        alert('Please enter content may not be less than 10 characters.');
        return false;  
    }
    $.ajax({
        type: 'POST',
        url: '/index.php/Pro-insert_ask.html',
        data: {
            products_id: products_id, 
            star: quslity_rank, 
            email: email, 
            type: comment_type, 
            title: $("#review_title").val(),
            name: $("#review_nickname").val(),
            content: review_content, 
        },
        success: function(data){
            if(data.status == '0') {
                alert(data.info);
                //location.replace(location.href);    
            }else {
                alert(data.info);  
				$("#comment-layer").hide();
            }
        },
        dataType:'json'
    });
}


function showlayer(obj)
{   
    
    var layer = $(obj);
    _windowWidth = $(document).width(),//获取当前窗口宽度
    _windowHeight = $(window).height(),//获取当前窗口高度
    _width = layer.outerWidth();//获取弹出层宽度
    _height = layer.outerHeight(),//获取弹出层高度
    _scrollTop = $(document).scrollTop();//滚动轴高度
    
    _posiLeft = (_windowWidth - _width)/2; 
    _posiTop = (_windowHeight - _height)/2 + _scrollTop;
    layer.css({
        "position": "absolute",
        "left": _posiLeft + "px",
        "top": _posiTop + "px",
        "display": "block",
        "z-index": "100"
    });//设置position
    
    /*
    $("#mask_layer").css({
        "width" :$(document.body).width()+"px",
        "height" :$(document.body).height()+"px",   
    });
    */  
}

//打分
function rateStar()
{
    var quslity_star = $('#quslity_star span');
    var quslity_status = false;
    quslity_star.each(function(i){
        $(this).mouseover(function(){
            if(!quslity_status) {
                quslity_star.removeClass('active-star');
                for(var j=0; j<=i; j++) {
                    quslity_star.eq(j).addClass('active-star');
                }
            }    
        });
        $(this).click(function(){
            $('#quslity_rank').val(quslity_star.eq(i).attr('value'));
            quslity_status = true; 
        });   
    });
    
	/*
    var value_star = $('#value_star span');
    var value_status = false;
    value_star.each(function(i){
        $(this).mouseover(function(){
            if(!value_status) {
                value_star.removeClass('active-star');
                for(var j=0; j<=i; j++) {
                    value_star.eq(j).addClass('active-star');
                }
            }    
        });
        $(this).click(function(){
            $('#value_rank').val(value_star.eq(i).attr('value'));
            value_status = true; 
        });   
    });
	
    var price_star = $('#price_star span');
    var price_status = false;
    price_star.each(function(i){
        $(this).mouseover(function(){
            if(!price_status) {
                price_star.removeClass('active-star');
                for(var j=0; j<=i; j++) {
                    price_star.eq(j).addClass('active-star');
                }
            }    
        });
        $(this).click(function(){
            $('#price_rank').val(price_star.eq(i).attr('value'));
            price_status = true; 
        });   
    }); 
	*/	
}