/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function toggle_options(field) {
    if ($('#' + field + '_options').hasClass('hide')) $('#' + field + '_options').removeClass('hide')
    else $('#' + field + '_options').addClass('hide')

}


function show_options(field) {
    $('#' + field + '_options').removeClass('hide')
}

function hide_options(field) {
    $('#' + field + '_options').addClass('hide')
}

function get_form_validation_state(submitting) {

    form_validation = 'valid';

    $(".value").each(function(index) {

        var field = $(this).attr('field')





        if ($('#' + field + '_field').hasClass('invalid')) {
            component_validation = 'invalid'
        } else if ($('#' + field + '_field').hasClass('valid')) {
            component_validation = 'valid'
        } else {
            component_validation = 'potentially_valid'
        }

        //if (component_validation == 'invalid' || component_validation == 'potentially_valid') 
        if (component_validation == 'invalid') {
            form_validation = 'invalid';
        }

        if (form_validation == 'invalid') {
            return;
        }

        if (component_validation == 'potentially_valid') {
            form_validation = 'potentially_valid';
        }



    });

    return form_validation

}




function process_form_validation(validation, submitting) {

    if (submitting && validation == 'potentially_valid') {
        validation = 'invalid'
    }
    $('#fields').removeClass('invalid valid potentially_valid').addClass(validation)

    //console.log(validation)
}


function check_if_form_is_valid() {



    var object = $('#fields').attr('object');
    var valid = true;
    $(".value").each(function(index) {
        var field = $(this).attr('field')
        var value = $('#' + field).val()

        //console.log(field + ' ' + $("#" + field).hasClass('valid') + ' ' + $("#" + field).hasClass('potentially_valid'))
        if (!$("#" + field + '_validation').hasClass('valid')) {


            valid = false;
        }


    });


    if (valid) {
        $('#' + object + '_save').addClass('valid').removeClass('invalid')

    } else {
        $('#' + object + '_save').removeClass('valid').addClass('invalid')

    }

}

function save_new_object(object, form_type) {

    var form_validation = get_form_validation_state()
    process_form_validation(form_validation, true)



    if ($('#fields').hasClass('valid')) {


        $('#fields').removeClass('valid').addClass('waiting')
        $('#' + object + '_save_icon').removeClass('fa-cloud');
        $('#' + object + '_save_icon').addClass('fa-spinner fa-spin');
        $('#save_label').addClass('hide')
        $('#saving_label').removeClass('hide')

        var fields_data = {};
        var re = new RegExp('_', 'g');

        var form_data = new FormData();

        $(".value").each(

        function(index) {
            var field = $(this).attr('field')
            var field_type = $(this).attr('field_type')

            if (field_type == 'time') {
                value = clean_time($('#' + field).val())
            } else if (field_type == 'password' || field_type == 'password_with_confirmation' || field_type == 'password_with_confirmation_paranoid' || field_type == 'pin' || field_type == 'pin_with_confirmation' || field_type == 'pin_with_confirmation_paranoid') {
                value = sha256_digest($('#' + field).val())
            } else if (field_type == 'attachment') {
                form_data.append("file", $('#' + field).prop("files")[0])
                value = ''
            } else {
                var value = $('#' + field).val()
            }
            //console.log($(this).attr('id') + ' ' + field)
            fields_data[field.replace(re, ' ')] = value
        });



        if (form_type == 'setup') {
            save_setup(object, fields_data)
            return;
        }

        // used only for debug
        var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + $('#fields').attr('parent') + '&parent_key=' + $('#fields').attr('parent_key') + '&fields_data=' + JSON.stringify(fields_data)
        console.log(request)
        //=====
        form_data.append("tipo", (form_type != '' ? form_type : 'new_object'))
        form_data.append("object", object)
        form_data.append("parent", $('#fields').attr('parent'))
        form_data.append("parent_key", $('#fields').attr('parent_key'))
        form_data.append("fields_data", JSON.stringify(fields_data))

        var request = $.ajax({

            url: "/ar_edit.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })

        request.done(function(data) {


            console.log(data)
            $('#' + object + '_save_icon').addClass('fa-cloud');
            $('#' + object + '_save_icon').removeClass('fa-spinner fa-spin');
            $('#save_label').removeClass('hide')
            $('#saving_label').addClass('hide')
            $('#fields').removeClass('waiting');
            if (data.state == 200) {
                $('#result').html(data.pcard).removeClass('hide')

                $('#fields').addClass('hide')




                for (var field in data.updated_data) {
                    $('.' + field).html(data.updated_data[field])
                }

                post_new_actions(object, data)

            } else if (data.state == 400) {
                $('#fields').addClass('error');
                $('#' + object + '_msg').html(data.msg).removeClass('hide')

            }

        })

        request.fail(function(jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)
            $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
            $('#fields').removeClass('waiting').addClass('valid')
            $('#save_label').removeClass('hide')
            $('#saving_label').addClass('hide')

            $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


        });






    }




}

function post_new_actions(object, data) {

    switch (object) {
    case 'Timesheet_Record':

        rows.fetch({
            reset: true
        });
        break;


    default:

    }


}

function save_inline_new_object(trigger) {


    var object = $('#inline_new_object').attr('object')
    var parent = $('#inline_new_object').attr('parent')
    var parent_key = $('#inline_new_object').attr('parent_key')
    var field = $('#inline_new_object').attr('field')
    var field_edit = $('#' + field + '_container').attr('field_type')




    if (!$('#inline_new_object').hasClass('valid')) {

        return;
    }

    $('#inline_new_object').removeClass('valid ')
    $('#' + object + '_save').removeClass('fa-cloud').addClass('fa-spinner fa-spin')


    var fields_data = {};
    var re = new RegExp('_', 'g');

    if (field_edit == 'time') {
        value = clean_time($('#' + field).val())


        value = $('#' + field + '_date').val() + ' ' + value
        // value = fixedEncodeURIComponent(value)
    } else {
        var value = $('#' + field).val()
        //var value = fixedEncodeURIComponent($('#' + field).val())
    }


    fields_data[field.replace(re, ' ')] = value
    //var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + parent + '&parent_key=' + parent_key + '&fields_data=' + JSON.stringify(fields_data)
    //console.log(request)
    var form_data = new FormData();
    form_data.append("tipo", 'new_object')
    form_data.append("object", object)
    form_data.append("parent", parent)
    form_data.append("parent_key", parent_key)
    form_data.append("fields_data", JSON.stringify(fields_data))

    var request = $.ajax({
        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'
    })

    request.done(function(data) {



        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        //console.log(data)
        if (data.state == 200) {


            $('#inline_new_object_msg').html(data.msg).addClass('success')
            $('#inline_new_object').addClass('hide')
            $('#icon_' + trigger).addClass('fa-plus')
            $('#icon_' + trigger).removeClass('fa-times')
            $('#' + field).val('').attr('has_been_valid', 0)
            $('#inline_new_object').addClass('potentially_valid')


            for (var updated_fields in data.updated_data) {
                $('.' + updated_fields).html(data.updated_data[updated_fields])
            }

            rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
            rows.fetch({
                reset: true
            });




        } else if (data.state == 400) {
            $('#inline_new_object_msg').removeClass('invalid valid potentially_valid')
            $('#inline_new_object_msg').html(data.msg).addClass('invalid')
            $('#inline_new_object').addClass('invalid')


        }
    })

    request.fail(function(jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)
        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


    });


}

function toggle_inline_new_object_form(trigger) {

    var field = $('#inline_new_object').attr('field')
    var field_edit = $('#' + field + '_container').attr('field_type')

    $('#inline_new_object_msg').html('').removeClass('error success')

    if ($('#icon_' + trigger).hasClass('fa-plus')) {
        $('#inline_new_object').removeClass('hide')
        $('#icon_' + trigger).removeClass('fa-plus').addClass('fa-times')



        $('#' + field).val('')

    } else {
        $('#inline_new_object').addClass('hide')
        $('#icon_' + trigger).addClass('fa-plus').removeClass('fa-times')

    }

}


function clone_it() {
    $('#result').html('')

    $('#fields').removeClass('hide');
    $('#result').addClass('hide');

}


function change_to_new_object_view() {
    var object = $('#fields').attr('object');
    request = $('#' + object + '_go_new').attr('request');
    change_view(request);
}


function reset_controls() {
    var object = $('#fields').attr('object');

    $('#' + object + '_save').removeClass('hide');
    $('.results').addClass('hide')
    $('#' + object + '_msg').html('').addClass('hide').removeClass('success');
    $('#' + object + '_go_new').attr('request', '')
}


function update_new_address_fields(field, country_code, hide_recipient_fields, arg) {

    var request = '/ar_address.php?tipo=fields_data&country_code=' + country_code

    $.getJSON(request, function(data) {


        if (data.state == 200) {
            for (var key in data.fields) {
                var field_tr = $('#' + field + '_' + key + '_field')
                var container_tr = $('#' + field + '_' + key + '_container')
                var field_data = data.fields[key]

                field_tr.find('.label').html(field_data.label)
                console.log(field_data)
                if (field_data.required) {
                    //console.log('xxx #' + field + '_' + key + '_container')
                    container_tr.attr('_required', 1);
                    field_tr.find('.fa-asterisk').addClass('required')

                } else {
                    container_tr.attr('_required', 0);
                    field_tr.find('.fa-asterisk').removeClass('required')

                }


                if (!field_data.render || (hide_recipient_fields && (key == 'recipient' || key == 'organization'))) {
                    field_tr.addClass('hide')
                } else {
                    field_tr.removeClass('hide')
                }
                field_tr.insertBefore('#' + field + '_country_field')


            }

            $(".address_value").each(function(index) {



                var field = $(this).attr('field')
                // console.log(field)
                var value = $('#' + field).val()

                var field_data = $('#' + field + '_container')

                //console.log('#' + field + '_container')
                var type = field_data.attr('field_type')
                var server_validation = field_data.attr('server_validation')
                var parent = field_data.attr('parent')
                var parent_key = field_data.attr('parent_key')
                var _object = field_data.attr('object')
                var key = field_data.attr('key')


                if (field_data.attr('_required') == 1) {
                    var required = true
                } else {
                    var required = false
                }

                var validation = validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)
                //console.log(validation)
                if (arg == 'init') {

                    if (validation.class == 'invalid' && value == '') {
                        validation.class = 'potentially_valid'
                    }
                }

                $('#' + field + '_field').removeClass('invalid potentially_valid valid').addClass(validation.class)



            });



        } else if (data.state == 400) {


        }
    })

}
