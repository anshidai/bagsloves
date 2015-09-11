function set_shipping(sel,country,state,weight,total,url){
	var shipping_mod=jQuery("input[type=radio][checked]").val();
	jQuery.getJSON(url,{code:shipping_mod,country:country,state:state,weight:weight,total:total},
			function(data){			
			
			jQuery("#shipping_money_text").html(data.Shipping_money_str);
			
			jQuery("#pay_fee_str2").html(data.paymoney_str);
			jQuery("#total_text").html(data.procuts_total_str);
			jQuery("#shippingfee").val(data.Shipping_money);
			jQuery("#paymentfee").val(data.paymoney);
			jQuery("#insurance").val(data.insurance);
			jQuery("#total").val(data.procuts_total);
			
		}
			
		);
	
}