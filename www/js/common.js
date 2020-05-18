function Common_fce() {
    this.show_div = function(button, id_prefix) {
        var id = $(button).attr('data-id');
        $('#' + id_prefix + id).removeClass('hidden');
        $(button).addClass('hidden');            
    };
}

function Forms_fce() {
    this.refill_forms = function(domlist, data) {
        //data = asoc. pole převedené do JSON a zakódované v BASE 64
        data = JSON.parse(atob(data.trim()));
        var inst = this;
        $(domlist).each(function(k, v){
            var name = $(v).attr('name');            
            if (data[name] !== undefined) {
                switch($(v).prop("tagName")) {
                    case 'INPUT':
                        $(v).val(data[name]);
                        break;
                    case 'SELECT':
                        inst.set_select($(v), data[name]);
                        break;
                }
            }                
        });            
    };
    
    this.set_select = function(dom, value) {
        var opts = $(dom).find('option');
        $(opts).removeAttr('selected');
        $(opts).each(function(k, v) {
            if ($(v).attr('value') === value) {
                $(v).attr('selected', '');
            }            
        });

    };        
}

