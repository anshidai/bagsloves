(function(jQuery){
	jQuery(function(){
		jQuery('#coupon').change(function(){
			jQuery.post(jQuery('#coupon_url').val(),jQuery(this).serialize(),function(data){
					jQuery('#coupon').siblings('span').html(data);
			});
		});
		jQuery('form[name=cart]').submit(function(){
			if(jQuery('.o_address').is(':hidden')){
				jQuery('.o_address').empty();//防止验证
			}
			return Validator.Validate(this,2);
		});
		jQuery("[name=delivery]").click(function(){
			if(jQuery(this).attr("value")==1){
				jQuery(".o_address").show();
				jQuery("#shippingtype").hide();
				jQuery("#shippingmsg").show();
			}else{
				jQuery(".o_address").hide();
				jQuery("#shippingtype").show();

			}
		});
		jQuery('#selCountries,#selProvinces,#selCities').change(function(){
			if(jQuery(this).val()>0){
				jQuery.post(jQuery("#Shipping_area_url").val(),{
				'country':jQuery("#selCountries").val(),
				'state':jQuery("#selProvinces").val(),
				'city':jQuery("#selCities").val()
				},function(data){

					jQuery("#shippingmsg").hide();
					if(data.length>0){
						jQuery('#shippingtype').html(data).show();
					}else{
						jQuery("#shippingbox").html("There is no way in the area of distribution").show();
					}
				});
				jQuery('input:radio[name=shipping_id]:checked').trigger('click');
				jQuery('input:radio[name=payment_module_code]:checked').trigger('click');
			}
		});
		jQuery('input:radio[name=shipping_id],input:radio[name=payment_module_code],#coupon').live('change',function(){
			var shipping_id=jQuery('input:radio[name=shipping_id]:checked').val();
			var delivery=jQuery('input:radio[name=delivery]:checked').val();
			var payment=jQuery('input:radio[name=payment_module_code]:checked').attr('id');
			var coupon=jQuery('#coupon').val();

			var post={};
			if(shipping_id>0){
				post.shipping_id=shipping_id;
			}
			post.delivery=delivery;
			if(delivery>0){
				post.country=jQuery("#selCountries").val();
				post.state=jQuery("#selProvinces").val();
				post.city=jQuery("#selCities").val();
			}
			if(payment>0){
				post.payment=payment;
			}
			if(coupon.length>0){
				post.coupon=coupon;
			}
			
			jQuery.post(jQuery('#get_total_amount_url').val(),post,function(data){
				if(data.length>0){
					jQuery('#get_total_amount,#get_total_amount2').html(data).show();
				}
			});
		});
	});
})(jQuery);