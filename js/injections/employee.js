/*Author: Raul Perusquia <raul@inikoo.com>
 Created:15 November 2015 at 17:11:04 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/



function show_user_fields() {
    $('#add_new_user_field').addClass('hide')
    $('#dont_add_new_user_field').removeClass('hide')
    $('#Staff_User_Handle_field ,#Staff_User_Password_field, #Staff_PIN_field,#Staff_Position_field').removeClass('hide')
    $('#Staff_User_Active_container,  #Staff_User_Handle_container ,#Staff_User_Password_container, #Staff_PIN_container,#Staff_Position_container').addClass('value')

    $('#Staff_User_Handle_validation').addClass('required')
    $('#Staff_User_Handle_container').attr('_required', 1)


    $(".value").each(function (index) {

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


function  terminate_employment(element) {




    $(element).find('i.fa').removeClass('fa-hand-scissors').addClass('fa-spinner fa-spin')

    var request = '/ar_edit_employees.php?tipo=terminate_employment&object=' + $(element).data('data').object + '&key=' + $(element).data('data').key


   console.log(request)



    $.getJSON(request, function (data) {
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
            $(element).find('i.fa').addClass('fa-hand-scissors').removeClass('fa-spinner fa-spin')

        }
    })
}