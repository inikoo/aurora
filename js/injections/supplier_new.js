/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  22 April 2016 at 15:51:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function show_user_fields() {
    $('#add_new_user_field').addClass('hide')
    $('#dont_add_new_user_field').removeClass('hide')
    $('#Staff_User_Handle_field ,#Staff_User_Password_field, #Staff_PIN_field,#Staff_Position_field').removeClass('hide')
    $('#Staff_User_Active_container,  #Staff_User_Handle_container ,#Staff_User_Password_container, #Staff_PIN_container,#Staff_Position_container').addClass('value')

    $('#Staff_User_Handle_validation').addClass('required')
    $('#Staff_User_Handle_container').attr('_required', 1)



    $(".value").each(function(index) {

        if ($(this).hasClass('address_value')) {
            return;
        }

        var field = $(this).attr('field')
        //console.log(field)
        var value = $('#' + field).val()

        var field_data = $('#' + field + '_container')
        var type = field_data.attr('field_type')
        var required = field_data.attr('_required')
        var server_validation = field_data.attr('server_validation')
        var parent = field_data.attr('parent')
        var parent_key = field_data.attr('parent_key')
        var _object = field_data.attr('object')
        var key = field_data.attr('key')


        var validation = validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)



        if (validation.class == 'invalid' && value == '') {
            validation.class = 'potentially_valid'
        }

        $('#' + field + '_field').removeClass('invalid potentially_valid valid').addClass(validation.class)



    });

    var form_validation = get_form_validation_state()
    process_form_validation(form_validation)




    $('#Staff_User_Handle').val($('#Staff_Alias').val())
    on_changed_value('Staff_User_Handle', $('#Staff_Alias').val())

}

function hide_user_fields() {
    $('#add_new_user_field').removeClass('hide')
    $('#dont_add_new_user_field').addClass('hide')
    $('#Staff_User_Handle_field ,#Staff_User_Password_field, #Staff_User_PIN_field,#Staff_Position_field').addClass('hide')
    $('#Staff_User_Active_container,#Staff_User_Handle_container ,#Staff_User_Password_container, #Staff_User_PIN_container,#Staff_Position_container').removeClass('value')

    $('#Staff_User_Handle').val('')
    on_changed_value('Staff_User_Handle', '')


}


function post_update_related_fields(country_data) {


    if ($('#Supplier_Products_Origin_Country_Code').attr('has_been_changed') == 0) {
        $('#Supplier_Products_Origin_Country_Code').countrySelect("selectCountry", country_data.iso2);
    }
    // console.log(country_data)
    if ($('#Supplier_Default_Currency_Code').attr('has_been_changed') == 0) {

        $('#Supplier_Default_Currency_Code').countrySelect("selectCountryfromCode", country_data.currency);
    }
}
