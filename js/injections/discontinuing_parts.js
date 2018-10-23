/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 October 2018 at 15:33:32 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


function set_discontinuing_part_as_active(element, part_sku) {

    if ($(element).hasClass('wait')) {
        return;
    }


    if ($(element).hasClass('fa-skull')) {

        $(element).removeClass('fa-skull').addClass('wait fa-spinner fa-spin')
        var value = 'In Use';
        var old_icon = 'fa-skull';

    } else if ($(element).hasClass('fa-skull')) {
        $(element).removeClass('fa-box').addClass('wait fa-spinner fa-spin')
        var value = 'Discontinued';
        var old_icon = 'fa-box';
    }else{
        return;
    }


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'Part')
    ajaxData.append("key", part_sku)
    ajaxData.append("field", 'Part_Status')
    ajaxData.append("value", value)


    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            console.log(data)

            if (data.state == '200') {
                //$(element).addClass('fa-unlink very_discreet wait').removeClass('fa-link')

                switch (data.value) {
                    case 'In Use':
                        $(element).removeClass(' fa-spinner fa-spin').addClass('fa-box super_discreet')

                        break;
                    case 'Discontinuing':
                        $(element).removeClass(' fa-spinner fa-spin').addClass('fa-skull super_discreet')

                        break;
                    case 'Discontinued':
                        $(element).removeClass(' fa-spinner fa-spin').addClass('fa-tombstone super_discreet')

                        break;

                }


            } else if (data.state == '400') {
                $(element).removeClass('wait fa-spinner fa-spin').addClass(old_icon)

                swal(data.msg);
            }


        }, error: function () {

        }
    });


}
