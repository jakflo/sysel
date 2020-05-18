$(function(){
    
    forms_fce.refill_forms($('input.itemlist_filter, select.itemlist_filter'), $('#form_save').text());
    
    $('button.order_by_button').click(function(){
        var name = $(this).attr('data-name');
        $('button.order_by_button').removeClass('marked');
        $(this).addClass('marked');
        $("input[name='order_by']").val(name);
        $('#curr_page').val('1');
        $('#filter_form').submit();
    });
    
    $('.itemlist_filter').change(function() {
        $('#curr_page').val('1');
    });
});


