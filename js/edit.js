/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 17:50:46 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function open_edit_field(object, key, field) {

    var type = $('#' + field + '_container').attr('field_type')
    switch (type) {
    case 'string':
    case 'anything':
    case 'int_unsigned':
    case 'smallint_unsigned':
    case 'mediumint_unsigned':
    case 'int':
    case 'smallint':
    case 'mediumint':


        var offset = $('#' + field + '_label').position();

        $('#' + field + '_value').addClass('hide')
        $('#' + field + '_edit_button').addClass('hide')
       

        $('#' + field + '_reset_button').removeClass('hide')
        
        $('#' + field + '_save_button').removeClass('hide')

        $('#' + field).val($('#' + field + '_value').html())

        $('#' + field).removeClass('hide').offset({
            top: offset.top + 2,

        })

        $('#' + field + '_msg').html('').removeClass('success error')


        $('#' + field).focus()

        break;
    case 'option':

        var offset = $('#' + field + '_label').position();

        $('#' + field + '_value').addClass('hide')
        $('#' + field + '_edit_button').addClass('hide')
        $('#' + field + '_reset_button').removeClass('hide')
        $('#' + field + '_options').removeClass('hide')


        $('#' + field).val($('#' + field + '_value').html())

        $('#' + field + '_formated').removeClass('hide').offset({
            top: offset.top + 2,

        })

        $('#' + field + '_msg').html('').removeClass('success error')


        $('#' + field + '_save_button').removeClass('hide')

        $('#' + field + '_save_button').offset({
            top: offset.top + 10,
            left: $('#' + field + '_formated').position().left + $('#' + field + '_formated').outerWidth() + 5

        })



    default:

    }
    key_scope = {
        type: type,
        object: object,
        key: key,
        field: field
    };
}


function close_edit_field(field) {

    var type = $('#' + field + '_container').attr('field_type')


    switch (type) {
    case 'string':
    case 'anything':
    case 'int_unsigned':
    case 'smallint_unsigned':
    case 'mediumint_unsigned':
    case 'int':
    case 'smallint':
    case 'mediumint':
        $('#' + field + '_value').removeClass('hide')

        $('#' + field).addClass('hide')
        $('#' + field + '_edit_button').removeClass('hide')

        $('#' + field + '_reset_button').addClass('hide')
        $('#' + field + '_save_button').addClass('hide')
        $('#' + field + '_editor').removeClass('changed')

        break;
    case 'option':
        $('#' + field + '_value').removeClass('hide')
        $('#' + field + '_edit_button').removeClass('hide')
        $('#' + field + '_reset_button').addClass('hide')
        $('#' + field + '_options').addClass('hide')
        $('#' + field + '_formated').addClass('hide')
        $('#' + field + '_save_button').addClass('hide')

        break;

    default:

    }

    $('#' + field + '_editor').removeClass('invalid valid')


    if (!$('#' + field + '_msg').hasClass('success')) {
        $('#' + field + '_msg').html('').addClass('hide')
    }

    key_scope = false

}

function delayed_on_change_field(object, timeout) {
    var field = object.attr('id');

    var field_element = $('#' + field);
    var new_value = field_element.val()
    window.clearTimeout(object.data("timeout"));
    object.data("timeout", setTimeout(function() {


        on_changed_value(field, new_value, new_value)





    }, timeout));
}


function on_changed_value(field, new_value, new_label) {

    if (new_label != $('#' + field + '_value').html()) {
        var changed = true
        $("#" + field + '_editor').addClass('changed')
    } else {
        var changed = false
        $("#" + field + '_editor').removeClass('changed')

    }

    $('#' + field + '_editor').removeClass('invalid valid')


     $('#' + field + '_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')


    

    var validation = validate_field(field, new_value)
  
    $('#' + field + '_editor').addClass(validation.class)
    if (validation.class == 'waiting') {

       

    } else {

        $('#' + field + '_save_button').removeClass('fa-spinner fa-spin').addClass('fa-cloud')


        if (validation.class == 'invalid') {


            var msg = $('#' + field + '_' + validation.type + '_invalid_msg').html()
        } else {
            var msg = '';
        }
        $('#' + field + '_msg').html(msg)
    }

}




function select_option(field, value, label) {
    $('#' + field).val(value)
    $('#' + field + '_formated').val(label)
    $('#' + field + '_options li').removeClass('selected')
    $('#' + field + '_option_' + value).addClass('selected')

    on_changed_value(field, value, label)

}

function save_field(object, key, field) {

    var type = $('#' + field + '_container').attr('field_type')

    var field_element = $('#' + field);

    if ($("#" + field + '_editor').hasClass('invalid') || !$("#" + field + '_editor').hasClass('changed')) {
        return;
    }


    var value = field_element.val()


    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value
    $.getJSON(request, function(data) {
        if (data.state == 200) {

            $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')

            if (type == 'option') {
                $('#' + field + '_options li .current_mark').removeClass('current')
                $('#' + field + '_option_' + value + ' .current_mark').addClass('current')
                $('.' + field).html(data.formated_value)

            } else {
                $('.' + field).html(data.value)

            }


            close_edit_field(field)

        } else if (data.state == 400) {
            $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')

        }
    })


}

function hide_edit_field_msg(field) {

    $('#' + field + '_msg').html('').addClass('hide')

}
