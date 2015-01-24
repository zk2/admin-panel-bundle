$(function(){
    $('#admin-filter input[type=datetime]').each(function(){
	$(this).attr('type','text');
    });
    $('#admin-filter input[type=date]').each(function(){
	$(this).attr('type','text');
    });
	
    var $img_add = $('<img class="zk-filter-img img_add" src="/bundles/zkadminpanel/images/new.png" title="Add fliter" alt="Add fliter" />');
    var $img_del = $('<img class="zk-filter-img img_del" src="/bundles/zkadminpanel/images/delete.png" title="Delete fliter" alt="Delete fliter" />');
    var $td = $('#filter_table td');
    $td.find('.zk-field-filter:first').css('margin-left','80px');
    $td.find('.zk-field-filter:not(:first)').append($img_del).filter(function(){
        return $(this).find('input,select').eq(2).val() == '' &&
               ( !$(this).find('select').eq(1).val().match("NULL") );
    }).hide();
    $td.filter(function(){return $(this).find('.zk-field-filter').length > 1}).find('.zk-field-filter:first').append($img_add);
    
    $('.img_add').click(function(e){
        e.preventDefault();
        var $thisTd = $(this).parent().parent();
        $thisTd.find('.zk-field-filter:hidden:first').show();
        if( $thisTd.find('.zk-field-filter:hidden').length == 0 ) {
            $(this).hide();
        }
    });
    
    $('.img_del').click(function(e){
        e.preventDefault();
        $(this).parent().find('.sf_filter_value').val('');
        $(this).parent().hide();
        $(this).parent().parent().find('.zk-field-filter:first .img_add').show();
    });
});