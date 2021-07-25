/*Author: Raul Perusquia <raul@inikoo.com>
 Created:   25 July 2021  17:00 Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/

function toggle_location_picking_pipeline(element) {

    let icon = $(element).find('i');
    if(icon.hasClass('wait')){
        return;
    }

    let field = $(element).data('field');


    let value = $(element).data('value');
    let original_icon='fa-toggle-on';
    if(icon.hasClass('fa-toggle-off')){
        original_icon='fa-toggle-off';
    }

    icon.removeClass('fa-toggle-off fa-toggle-on').addClass('fa-spinner fa-spin wait');


    let ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field');
    ajaxData.append("object", 'Location');
    ajaxData.append("key", $('#fields').attr('key'));
    ajaxData.append("field", field);
    ajaxData.append("value", value);

    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {
            icon.removeClass('fa-spinner fa-spin wait');


            if (data.state === 200) {


                const medata=data['update_metadata'];
                icon.addClass(medata['icon']);
                $(element).data('value',medata['value']);
                if(medata['value']){
                    $(element).find('span').removeClass('discreet');
                }else{
                    $(element).find('span').addClass('discreet');

                }

                let key;
                for (key in medata['class_html']) {
                    $('.' + key).html(medata['class_html'][key])
                }




            } else {
                icon.addClass(original_icon);
                swal({
                     text: data.msg, confirmButtonText: "OK"
                });
            }


        }, error: function () {
            icon.removeClass(original_icon).removeClass('fa-spinner fa-spin wait');

        }
    });


}
