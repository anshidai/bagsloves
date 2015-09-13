$(function(){
	
	$("#continue_shopping").click(function(){
		$("#cart_ok").hide();
	});
	
	$("#add-cart").click(function(){
		var form = document.cart_quantity;
		$.post($(form).attr('action'),$(form).serialize(),function(data){
			console.log(data.list)
			if(!data.status){
				jQuery('#cart_ok').hide();
				jQuery('#cart_alert').html(data.info).fadeIn(200).delay(3000).fadeOut(200);
			}else{
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
					html += '<div class="cart_pro_btn"><a href="'+CartUrl+'"><span class="cart_view">View Cart ( <span class="cart_num">'+data.list.length+'</span> item)</span></a></div>';
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
}