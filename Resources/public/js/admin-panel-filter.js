$(function(){
    $('#admin-filter input[type=datetime]').each(function(){
	$(this).attr('type','text');
    });
    $('#admin-filter input[type=date]').each(function(){
	$(this).attr('type','text');
    });
	
    var $img_add = $('<img class="zk2-filter-img img_add" src="/bundles/zk2adminpanel/images/new.png" title="Add fliter" alt="Add fliter" />');
    var $img_del = $('<img class="zk2-filter-img img_del" src="/bundles/zk2adminpanel/images/delete.png" title="Delete fliter" alt="Delete fliter" />');
    var $td = $('#filter_table td');
    $td.find('.zk2-field-filter:first').css('margin-left','80px');
    $td.find('.zk2-field-filter:not(:first)').append($img_del).filter(function(){
        return $(this).find('input,select').eq(2).val() == '' &&
               ( !$(this).find('select').eq(1).val().match("NULL") );
    }).hide();
    $td.filter(function(){return $(this).find('.zk2-field-filter').length > 1}).find('.zk2-field-filter:first').append($img_add);
    
    $('.img_add').click(function(e){
        e.preventDefault();
        var $thisTd = $(this).parent().parent();
        $thisTd.find('.zk2-field-filter:hidden:first').show();
        if( $thisTd.find('.zk2-field-filter:hidden').length == 0 ) {
            $(this).hide();
        }
    });
    
    $('.img_del').click(function(e){
        e.preventDefault();
        $(this).parent().find('.sf_filter_value').val('');
        $(this).parent().hide();
        $(this).parent().parent().find('.zk2-field-filter:first .img_add').show();
    });
});