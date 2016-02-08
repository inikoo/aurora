/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  6 February 2016 at 13:19:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function post_set_as_main(data) {


    if (data.action == 'set_main_contact_number_Mobile') {


        $("#Supplier_Main_Plain_Mobile_display").insertAfter("#display_telephones");
    } else if (data.action == 'set_main_contact_number_Telephone') {


        $("#Supplier_Main_Plain_Telephone_display").insertAfter("#display_telephones");

    } 
}


function post_update_field(data) {
    console.log(data)


    if (data.value != undefined) {
        if (data.field == 'Supplier_Main_Plain_Mobile' || data.field == 'Supplier_Main_Plain_Telephone' || data.field == 'Supplier_Main_Plain_FAX') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
            } else {
                $('#' + data.field + '_display').removeClass('hide')

            }
        }
    }
}
