/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2015 at 12:20:32 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function validate_field(field, new_value) {

    var field_data = $('#' + field + '_container')

    var type = field_data.attr('field_type')

    var validation = client_validation(type, new_value)

    if (validation.class == 'valid' && field_data.attr('server_validation')) {


        var validation = {
            class: 'waiting',
            type: ''
        }

        server_validation(field_data.attr('server_validation'), field_data.attr('parent'), field_data.attr('parent_key'), field_data.attr('object'), field_data.attr('key'), field, new_value)
    }



    return validation;
}


function client_validation(type, value) {

    var valid_state = {
        class: 'valid',
        type: ''
    }

    switch (type) {
    case 'smallint_unsigned':

        if (!$.isNumeric(value)) {
            return {
                class: 'invalid',
                type: 'not_integer'
            }
        }

        if (value > 65535) {
            return {
                class: 'invalid',
                type: 'too_big'
            }
        }

        if (value < 0) {
            return {
                class: 'invalid',
                type: 'negative'
            }
        }

        if (Math.floor(value) != value) {
            return {
                class: 'invalid',
                type: 'not_integer'
            }
        }


        break;
    case 'int_unsigned':


        if (!$.isNumeric(value)) {
            return {
                class: 'invalid',
                type: 'not_integer'
            }
        }

        if (value > 4294967295) {
            return {
                class: 'invalid',
                type: 'too_big'
            }
        }

        if (value < 0) {
            return {
                class: 'invalid',
                type: 'negative'
            }
        }

        if (Math.floor(value) != value) {
            return {
                class: 'invalid',
                type: 'not_integer'
            }
        }






        break;
    case 'string':

        if (value == '') {
            return {
                class: 'invalid',
                type: ''
            }
        }

        break;
        
    case'date':
   
     if (value =='') {
      
            return {
                class: 'invalid',
                type: 'invalid'
            }
        }
    break;    

    case 'email':

        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,63})?$/


        if (!emailReg.test(value)) {
            return {
                class: 'invalid',
                type: ''
            }
        }

        break;



    default:

    }



    return valid_state;
}

function server_validation(tipo, parent, parent_key, object, key, field, value) {

    var request = '/ar_validation.php?tipo=' + tipo + '&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value

    $.getJSON(request, function(data) {

        if (!$('#' + field + '_value').hasClass('hide')) {
            return;
        }

        if (data.state == 200) {

            var validation = data.validation
            var msg = data.msg

        } else {
            var validation = 'invalid'
            var msg = "Error, can't verify value on server"
            console.log(data.msg)
        }

        $('#' + field + '_save_button').removeClass('fa-spinner fa-spin').addClass('fa-cloud')


        if (validation == 'valid') {

            var msg = '';
        }
        $('#' + field + '_msg').html(msg)
        $('#' + field + '_editor').addClass(validation)


    })


}
