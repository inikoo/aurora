/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  18 January 2016 at 14:53:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function post_set_as_main(data) {


    if (data.action == 'set_main_contact_number_Mobile') {
        console.log(data.action)

        $("#Customer_Main_Plain_Mobile_display").insertAfter("#display_telephones");
    } else if (data.action == 'set_main_contact_number_Telephone') {
        console.log(data.action)

        $("#Customer_Main_Plain_Telephone_display").insertAfter("#display_telephones");

    }

}


function post_update_field(data) {
    console.log(data)


    if (data.value != undefined) {
        if (data.field == 'Customer_Main_Plain_Mobile' || data.field == 'Customer_Main_Plain_Telephone' || data.field == 'Customer_Main_Plain_FAX') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
            } else {
                $('#' + data.field + '_display').removeClass('hide')

            }
        }
    }
}
