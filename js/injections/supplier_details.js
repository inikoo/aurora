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

    if (data.value != undefined) {
        if (data.field == 'Supplier_Main_Plain_Telephone') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_telephone_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#show_new_telephone_field').removeClass('hide')

            }
        } else if (data.field == 'Supplier_Main_Plain_Mobile' || data.field == 'Supplier_Main_Plain_FAX') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
            } else {
                $('#' + data.field + '_display').removeClass('hide')

            }
        } else if (data.field == 'Supplier_Main_Plain_Email') {
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_email_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#' + 'Supplier_Other_Email_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')
                $('#show_new_email_field').removeClass('hide')
            }
        }


        if (data.field_type != undefined) {
            if (data.field_type == 'Supplier_Other_Email') {
                if (data.value != '') {
                    $('#' + data.field + '_mailto').html(data.formatted_email)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            } else if (data.field_type == 'Supplier_Other_Telephone') {
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

    if (data.clone_from == 'Supplier_Other_Email') {
        value = clone.find('.Supplier_Other_Email_mailto').prop('id', data.field + '_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')

    } else if (data.clone_from == 'Supplier_Other_Telephone') {
        clone.find('span').html(data.formatted_value)
    }


    $('#' + data.clone_from + '_display').before(clone)

}

function unlink_agent(element) {



    if ($(element).hasClass('disabled')) {
        return
    }



    if (!$(element).find('i.fa').removeClass('chain-broken')) return;

    $(element).find('i.fa').removeClass('fa-chain-broken').addClass('fa-spinner fa-spin')
    
   // console.log( $(element).data('data'))

    var request = '/ar_edit.php?tipo=edit_field&object=' + $(element).data('data').object + '&key=' + $(element).data('data').key+'&field=unlink_agent&value='+$(element).data('data').agent_key
   
  // console.log(request)
    $.getJSON(request, function(data) {
        if (data.state == 200) {

            console.log(data)
           
            if (data.request != undefined) {
                change_view(data.request)
            } else {
                change_view(state.request, {
                    'reload_showcase': 1
                })
            }

        } else if (data.state == 400) {
            $(element).find('i.fa').addClass('fa-chain-broken').removeClass('fa-spinner fa-spin')

        }


    })


}

