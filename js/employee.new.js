/*Author: Raul Perusquia <raul@inikoo.com>
 Created:15 November 2015 at 17:11:04 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function show_user_fields() {
    $('#add_new_user_field').addClass('hide')
    $('#dont_add_new_user_field').removeClass('hide')
    $('#Staff_User_Handle_field ,#Staff_User_Password_field, #Staff_User_PIN_field').removeClass('hide')
    $('#Staff_User_Active_container,  #Staff_User_Handle_container ,#Staff_User_Password_container, #Staff_User_PIN_container').addClass('value')

    $('#Staff_User_Handle').val($('#Staff_Alias').val())
    on_changed_value('Staff_User_Handle',$('#Staff_Alias').val())

}

function hide_user_fields() {
    $('#add_new_user_field').removeClass('hide')
    $('#dont_add_new_user_field').addClass('hide')
    $('#Staff_User_Handle_field ,#Staff_User_Password_field, #Staff_User_PIN_field').addClass('hide')
    $('#Staff_User_Active_container,#Staff_User_Handle_container ,#Staff_User_Password_container, #Staff_User_PIN_container').removeClass('value')

   $('#Staff_User_Handle').val('')
    on_changed_value('Staff_User_Handle','')


}
