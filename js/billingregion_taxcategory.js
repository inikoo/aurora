/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 January 2016 at 10:14:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


$('#report_settings').click(

function() {

    if ($('#report_config').hasClass('hide')) {

        $('#report_config').removeClass('hide')
        var position = $("#report_settings").position()
        console.log(position)
        $("#report_config").css('left', position.left - $('#report_config').width());
        $("#report_config").css('top', position.top + $("#report_settings").height());

    } else {
        $('#report_config').addClass('hide')

    }
})


function toggle_store_field(key) {

    var field_element = $('#field_store_' + key)

    if (field_element.hasClass('fa-check-square-o')) {
        field_element.removeClass('fa-check-square-o')
        field_element.addClass('fa-square-o')


    } else {
        field_element.addClass('fa-check-square-o')
        field_element.removeClass('fa-square-o')
    }
    if (setting_changed()) {
        
        $('#report_config_save').removeClass('disabled').addClass('valid save')
        $('#report_config_save_label').removeClass('hide')
    } else {
        
        $('#report_config_save').addClass('disabled').removeClass('valid save')
        $('#report_config_save_label').addClass('hide')


    }

}



function setting_changed() {

    var fields = []
    var changed = false;
    $('#report_config .field_store i').each(function(index, obj) {

        if ($(obj).hasClass('fa-check-square-o') && $(obj).attr('original_val') != 'fa-check-square-o') {
            changed = true
            return false
        } else if ($(obj).hasClass('fa-square-o') && $(obj).attr('original_val') != 'fa-square-o') {
            changed = true
            return false
        }

    });
    return changed;
}


function apply_changes() {

    if ($('#report_config_save').hasClass('disabled')) {
        return;
    }
    var excluded_stores = [];


    $('#report_config .field_store i').each(function(index, obj) {
        if ($(obj).hasClass('fa-square-o')) {
        excluded_stores.push($(obj).attr('key'))
         $(obj).attr('original_val','fa-square-o')
        }else{
        $(obj).attr('original_val','fa-check-square-o')
        }
    });
 $('#report_config_save').addClass('disabled').removeClass('valid save')
        $('#report_config_save_label').addClass('hide')
        
 $('#report_config').addClass('hide')

    var parameters = jQuery.parseJSON(rows.parameters);
    parameters['excluded_stores'] = excluded_stores
    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters

    rows.fetch({
        reset: true
    });


}
