/*Author: Raul Perusquia <raul@inikoo.com>
 Created:3 :00 pm Thursday, 2 July 2020 (MYT), Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo
 Version 3.0*/


function toggle_show_production_parts_in_inventory(element) {

    let show_production_parts='';

    const icon = $(element).find('i')

    if (icon.hasClass('fa-toggle-on')) {
        show_production_parts='No'
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')
    }else{
        show_production_parts='Yes'
        icon.removeClass('fa-toggle-off').addClass('fa-toggle-on')

    }


    let parameters = JSON.parse(rows.parameters);




    parameters.show_production = show_production_parts;

    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
    rows.fetch({
        reset: true
    });
    get_elements_numbers(rows.tab, rows.parameters)






}

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

