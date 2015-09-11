(function($){

	jQuery.extend(Boxy,{
		login:function(url,mid){
			var mid=Boolean(parseInt(mid));
			if(!mid){
				Boxy.load(url);
				return false;
			}
			return true;
		},
		dologin:function(form){
			if(!Validator.Validate(form,2)){
				return false;
			}
			jQuery.post($(form).attr('action'),$(form).serialize(),function(data){
				if(!data.status && data.info=='noaddress'){
					Boxy.shippingaddress();
				}else if(!data.status){
					alert(data.info);
				}else{
					jQuery("#cartform").submit();
				}
			},'json');
			return false;
		},
		join:function(url){
			Boxy.load(url);
			return false;
		},
		dojoin:function(form){
			if(!Validator.Validate(form,2)){
				return false;
			}
			jQuery.post($(form).attr('action'),$(form).serialize(),function(data){
				if(!data.status){
					alert(data.info);
				}else{
					Boxy.shippingaddress(ajaxshippingaddress);
				}
			},'json');
			return false;
		},
		shippingaddress:function(url){
			Boxy.load(url);
			return false;
		},
		doShippingAddress:function(form){
			if(!Validator.Validate(form,2)){
				return false;
			}
			jQuery.post($(form).attr('action'),$(form).serialize(),function(data){
				if(!data.status){
					alert(data.info);
				}else{
					send(1);
				}
			},'json');
			return false;
		},
		direct:function(){
			jQuery("#cartform").append("<input type='hidden' name='guest' value='1' />");
			jQuery("#cartform").submit();
		}
	});
})(jQuery);