jQuery.fn.extend({
    editform: function(selectRadio, setType, step, callback){
        if (!selectRadio) return;
        setType ? null : setType = 'form';
        if (setType == 'form') {
            $(this).find('input,textarea,select').each(function(){
                if (this.type == 'radio' || this.type == 'checkbox') {
                    var n = this.name.replace('[]', '');
                    this.checked = typeof selectRadio[n] == 'string' ? this.value == selectRadio[n] : selectRadio[n] && $.inArray(this.value, selectRadio[n]) > -1;
                }
                else {
                    if (selectRadio[this.name]) {
                        this.value = selectRadio[this.name];
                    }
                }
            });
            return;
        }
        if (setType == 'info') {
            $(this).find('td').each(function(){
                if (this.name && selectRadio[this.name]) {
                    $(this).html(selectRadio[this.name]);
                }
            });
            return;
        }
        if (setType == 'row') {
            for (var i in selectRadio['data']) {
                var TmpStr = "<tr rowid='" + i + "'>";
                $.each(selectRadio["data"][i], function(j, l){
                    TmpStr += "<td>";
                    l ? TmpStr += l : TmpStr += "&nbsp;";
                    TmpStr += "</td>";
                });
                $(this).append(TmpStr);
                if ($.isFunction(step)) {
                    step();
                }
            }
            $.isFunction(callback) && callback();
            return;
        }
    }
});