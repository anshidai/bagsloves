function loadRegion(sel,type_id,selName,url){
	//alert(jQuery("#"+sel).val());
	jQuery("#"+selName+" option").each(function(){
		jQuery(this).remove();
	});
	jQuery("<option value=0>-please select-</option>").appendTo(jQuery("#"+selName));
	if(jQuery("#"+sel).val()==0){
		return;
	}
	jQuery.getJSON(url,{pid:jQuery("#"+sel).val(),type:type_id},
	function(data){
		if(data){
			jQuery.each(data,function(idx,item){
				if(idx==0){
					return true;//同countinue，返回false同break
				}

				//jQuery(new Option(item.name,item.id)).appendTo(jQuery("#"+selName));
				jQuery("<option value="+item.id+">"+item.name+"</option>").appendTo(jQuery("#"+selName));

			});
		}else{
			jQuery("<option value='0'>All Province</option>").appendTo(jQuery("#"+selName));
		}

	}

	);
}
function addconfig(){
	var city=jQuery("#selCities").val();
	var Provinces=jQuery("#selProvinces").val();
	var Countries=jQuery("#selCountries").val();
	if(city>0){
		var chboxtext="<input type='checkbox' name='config[]' value='"+city+"' checked />"+jQuery("#selCities").find('option:selected').text();
	}
	else{
		if(Provinces>0){
			var chboxtext="<input type='checkbox' name='config[]' value='"+Provinces+"' checked />"+jQuery("#selProvinces").find('option:selected').text();
		}
		else{
			if(Countries>0){
				var chboxtext="<input type='checkbox' name='config[]' value='"+Countries+"' checked />"+jQuery("#selCountries").find('option:selected').text();
			}
		}
	}

	jQuery("#configtext").append(chboxtext);
}