//购物车
$(".header_cart").hover(function(){
	var $this=$(this),
		$lang=$this.attr('lang');
		$note=$this.find(".cart_note");
		
	$this.addClass("header_active");
	$note.show();
	if(!$note.html()){
		$.ajax({
			url:"/static/theme/t010/lang/"+$lang+"/inc/header_cart.php",
			async:false,
			type:'get',
			dataType:'html',
			success:function(result){
				if(result){
					$note.html(result);
				}
			}
		});
	}
}, function(){
	$(this).removeClass("header_active").find(".cart_note").hide();
});