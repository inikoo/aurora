/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  28 April 2016 at 11:34:07 GMT+8, Lovina, Bali, Indonesia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function post_set_as_main(data) {


    if (data.action == 'set_main_contact_number_Mobile') {


        $("#Agent_Main_Plain_Mobile_display").insertAfter("#display_telephones");
    } else if (data.action == 'set_main_contact_number_Telephone') {


        $("#Agent_Main_Plain_Telephone_display").insertAfter("#display_telephones");

    }
}


function post_update_field(data) {

    if (data.value != undefined) {
        if (data.field == 'Agent_Main_Plain_Telephone') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_telephone_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#show_new_telephone_field').removeClass('hide')

            }
        } else if (data.field == 'Agent_Main_Plain_Mobile' || data.field == 'Agent_Main_Plain_FAX') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
            } else {
                $('#' + data.field + '_display').removeClass('hide')

            }
        } else if (data.field == 'Agent_Main_Plain_Email') {
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_email_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#' + 'Agent_Other_Email_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')
                $('#show_new_email_field').removeClass('hide')
            }
        }


        if (data.field_type != undefined) {
            if (data.field_type == 'Agent_Other_Email') {
                if (data.value != '') {
                    $('#' + data.field + '_mailto').html(data.formatted_email)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            } else if (data.field_type == 'Agent_Other_Telephone') {
                if (data.value != '') {
                    $('#' + data.field + '_display').find('span').html(data.formatted_value)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            }
        }

    }
}

function post_create_action(data) {

    var clone = $('#' + data.clone_from + '_display').clone()
    clone.prop('id', data.field + '_display').removeClass('hide');

    if (data.clone_from == 'Agent_Other_Email') {
        value = clone.find('.Agent_Other_Email_mailto').prop('id', data.field + '_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')

    } else if (data.clone_from == 'Agent_Other_Telephone') {
        clone.find('span').html(data.formatted_value)
    }


    $('#' + data.clone_from + '_display').before(clone)

}

