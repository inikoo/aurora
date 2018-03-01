/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  18 January 2016 at 14:53:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function post_set_as_main(data) {


    if (data.action == 'set_main_contact_number_Mobile') {


        $("#Customer_Main_Plain_Mobile_display").insertAfter("#display_telephones");
    } else if (data.action == 'set_main_contact_number_Telephone') {


        $("#Customer_Main_Plain_Telephone_display").insertAfter("#display_telephones");

    } else if (data.action == 'set_main_delivery_address') {


        var address_fields = jQuery.parseJSON(data.value)

        $('#Customer_Delivery_Address_recipient  input ').val(decodeEntities(address_fields['Address Recipient']))
        $('#Customer_Delivery_Address_organization  input ').val(decodeEntities(address_fields['Address Organization']))
        $('#Customer_Delivery_Address_addressLine1  input ').val(decodeEntities(address_fields['Address Line 1']))
        $('#Customer_Delivery_Address_addressLine2  input ').val(decodeEntities(address_fields['Address Line 2']))
        $('#Customer_Delivery_Address_sortingCode  input ').val(decodeEntities(address_fields['Address Sorting Code']))
        $('#Customer_Delivery_Address_postalCode  input ').val(decodeEntities(address_fields['Address Postal Code']))
        $('#Customer_Delivery_Address_dependentLocality  input ').val(decodeEntities(address_fields['Address Dependent Locality']))
        $('#Customer_Delivery_Address_locality  input ').val(decodeEntities(address_fields['Address Locality']))
        $('#Customer_Delivery_Address_administrativeArea  input ').val(decodeEntities(address_fields['Address Administrative Area']))

        $('#Customer_Delivery_Address_country_select').intlTelInput("setCountry", address_fields['Address Country 2 Alpha Code'].toLowerCase());
        $('#Customer_Delivery_Address_country_select').trigger("country-change", 'init');

    }

}


function post_update_field(data) {

    if (data.value != undefined) {
        if (data.field == 'Customer_Main_Plain_Telephone') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_telephone_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#show_new_telephone_field').removeClass('hide')

            }
        } else if (data.field == 'Customer_Main_Plain_Mobile' || data.field == 'Customer_Main_Plain_FAX') {
            console.log(data.field + ' --> ' + data.value)
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
            } else {
                $('#' + data.field + '_display').removeClass('hide')

            }
        } else if (data.field == 'Customer_Main_Plain_Email') {
            if (data.value == '') {
                $('#' + data.field + '_display').addClass('hide')
                $('#show_new_email_field').addClass('hide')

            } else {
                $('#' + data.field + '_display').removeClass('hide')
                $('#' + 'Customer_Other_Email_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')
                $('#show_new_email_field').removeClass('hide')
            }
        }


        if (data.field_type != undefined) {
            if (data.field_type == 'Customer_Other_Email') {
                if (data.value != '') {
                    $('#' + data.field + '_mailto').html(data.formatted_email)
                } else {
                    $('#' + data.field + '_display').addClass('hide')

                }
            } else if (data.field_type == 'Customer_Other_Telephone') {
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

    if (data.clone_from == 'Customer_Other_Email') {
        value = clone.find('.Customer_Other_Email_mailto').prop('id', data.field + '_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')

    } else if (data.clone_from == 'Customer_Other_Telephone') {
        clone.find('span').html(data.formatted_value)
    }


    $('#' + data.clone_from + '_display').before(clone)

}

function toggle_subscription(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        var value='No'
    }else if(icon.hasClass('fa-toggle-off')){
        var value='Yes'
    }else{

        return
    }
    icon.removeClass('fa-toggle-on fa-toggle-off').addClass(' fa-spinner fa-spin')




    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'Customer')

    ajaxData.append("key", $('#fields').attr('key'))
    ajaxData.append("field", $(element).attr('field'))

    ajaxData.append("value", value)


    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {


                if(value=='Yes'){
                    icon.addClass('fa-toggle-on').removeClass(' fa-spinner fa-spin')
                    icon.next('span').removeClass('discreet')
                    $('.'+$(element).attr('field')).removeClass('error discreet')

                }else{
                    icon.addClass('fa-toggle-off').removeClass(' fa-spinner fa-spin')
                    icon.next('span').addClass('discreet')
                    $('.'+$(element).attr('field')).addClass('error discreet')

                }

              //  $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });


}

function toggle_subscription_from_new(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off').next('span').addClass('discreet')

    }else if(icon.hasClass('fa-toggle-off')){
        icon.removeClass('fa-toggle-off').addClass('fa-toggle-on').next('span').removeClass('discreet')
    }





}





