function common_fce() {
    return {
        show_div: function(button, id_prefix) {
            var id = $(button).attr('data-id');
            $('#' + id_prefix + id).removeClass('hidden');
            $(button).addClass('hidden');            
        }
    };
}

