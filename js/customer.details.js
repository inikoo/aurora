/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  18 January 2016 at 14:53:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function post_set_as_main(data) {


    if (data.action == 'set_main_contact_number_Mobile') {
     

        $("#Customer_Main_Plain_Mobile_display").insertAfter("#display_telephones");
    } else if (data.action == 'set_main_contact_number_Telephone') {
       

        $("#Customer_Main_Plain_Telephone_display").insertAfter("#display_telephones");

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
      $('#Customer_Delivery_Address_country_select').trigger("country-change",'init');

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
