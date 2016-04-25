/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  18 January 2016 at 14:53:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function post_set_as_main(data) {


    if (data.action == 'set_main_contact_number_Mobile') {


        $("#Supplier_Main_Plain_Mobile_display").insertAfter("#display_telephones");
    } else if (data.action == 'set_main_contact_number_Telephone') {


        $("#Supplier_Main_Plain_Telephone_display").insertAfter("#display_telephones");

    }else if (data.action == 'set_main_delivery_address') {



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

