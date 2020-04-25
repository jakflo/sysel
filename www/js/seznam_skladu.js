$(function(){
    $('.rename_butt').click(function(){
        var id = $(this).attr('data-wid');
        $('#rename_div_' + id).removeClass('hidden');
        $(this).addClass('hidden');
    });
    
});


