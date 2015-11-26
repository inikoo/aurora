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

function validate_form(object) {


    //  $('#' + object + '_controls').removeClass('invalid valid')
    //  $('#' + object + '_controls').addClass('waiting')
    $('#' + object + '_save_icon').removeClass('fa-cloud')
    $('#' + object + '_save_icon').addClass('fa-spinner fa-spin')

    $('#' + object + '_save').removeClass('invalid valid')


    var form_validation = 'valid';

    $(".value").each(function(index) {
        var field = $(this).attr('field')
        var value = $('#' + field).val()

        if ($('#' + field + '_validation').hasClass('invalid')) {
            form_validation = 'invalid'

        } else if ($('#' + field + '_validation').hasClass('potentially_valid')) {

            $('#' + field + '_validation').addClass('invalid').removeClass('potentially_valid')

            form_validation = 'invalid'

            var validation = validate_field(field, value)

            if ($('#' + field + '_' + validation.type + '_invalid_msg').length) {
                var msg = $('#' + field + '_' + validation.type + '_invalid_msg').html()
            } else {
                var msg = $('#invalid_msg').html()
            }
            $('#' + field + '_msg').html(msg)
            $('#' + field + '_msg').removeClass('hide').addClass('invalid')

            //console.log(field+' '+'invalid')

        } else {

            $('#' + field + '_validation').removeClass('invalid')

            var validation = validate_field(field, value)


            if (validation.class == 'invalid' || validation.class == 'potentially_valid') {




                form_validation = 'invalid'
                $('#' + field + '_validation').addClass('invalid').removeClass('potentially_valid')

                if ($('#' + field + '_' + validation.type + '_invalid_msg').length) {
                    var msg = $('#' + field + '_' + validation.type + '_invalid_msg').html()
                } else {
                    var msg = $('#invalid_msg').html()
                }
                $('#' + field + '_msg').html(msg)
                $('#' + field + '_msg').removeClass('hide').addClass('invalid')


            } else {

            }
            //console.log(field+' '+validation.class)

        }

    });

    //  console.log('xxx>'+form_validation+'<<<')

    //$('#' + object + '_controls').removeClass('waiting')
    $('#' + object + '_save_icon').addClass('fa-cloud')
    $('#' + object + '_save_icon').removeClass('fa-spinner fa-spin')
    $('#' + object + '_save').addClass(form_validation)

    $('#fields').attr('has_been_fully_validated', true);


    //console.log('#' + object + '_save')
}


function check_if_form_is_valid() {

    var object = $('#fields').attr('object');
    var valid = true;
    $(".value").each(function(index) {
        var field = $(this).attr('field')
        var value = $('#' + field).val()


        if (!$("#" + field + '_validation').hasClass('valid')) {
            console.log(field + ' ' + $("#" + field + '_validation').hasClass('valid'))

            valid = false;


            //  return false
        }


        //    var validation = validate_field(field, value)
        //     if (validation.class == 'waiting') {
        //       return true
        // }
        // if (validation.class != 'valid') {
        //     valid = false;
        // }
    });

    if (valid) {
        $('#' + object + '_save').addClass('valid').removeClass('invalid')

    } else {
        $('#' + object + '_save').removeClass('valid').addClass('invalid')

    }

}


function save_new_object(object) {

    validate_form(object)

    //return
    console.log($('#' + object + '_save').hasClass('valid'))

    if ($('#' + object + '_save').hasClass('valid')) {

        $('#' + object + '_save_icon').removeClass('fa-cloud');
        $('#' + object + '_save_icon').addClass('fa-spinner fa-spin');

        var fields_data = {};
        var re = new RegExp('_', 'g');

        $(".value").each(

        function(index) {
            var field = $(this).attr('field')
            var value = fixedEncodeURIComponent($('#' + field).val())



            console.log($(this).attr('id') + ' ' + field)



            fields_data[fixedEncodeURIComponent(field.replace(re, ' '))] = value

        });




        var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + $('#fields').attr('parent') + '&parent_key=' + $('#fields').attr('parent_key') + '&fields_data=' + JSON.stringify(fields_data)
        console.log(request)
        //return;
        $.getJSON(request, function(data) {
            // console.log(data)
            $('#' + object + '_save_icon').addClass('fa-cloud');
            $('#' + object + '_save_icon').removeClass('fa-spinner fa-spin');


            if (data.state == 200) {
                $('#result').html(data.pcard)

                $('#fields').addClass('hide');
                $('#result').removeClass('hide');






            } else if (data.state == 400) {

                $('#' + object + '_msg').html(data.msg).removeClass('hide').addClass('error')

            }
        })


    }




}


function save_inline_new_object(trigger) {


    var object = $('#inline_new_object').attr('object')
    var parent = $('#inline_new_object').attr('parent')
    var parent_key = $('#inline_new_object').attr('parent_key')
    var field = $('#inline_new_object').attr('field')
    var field_edit = $('#' + field + '_container').attr('field_type')




    if (!$('#' + field + '_editor').hasClass('valid')) {

        return;
    }

    $('#' + field + '_editor').removeClass('valid ')
    $('#' + object + '_save').removeClass('fa-cloud').addClass('fa-spinner fa-spin')


    var fields_data = {};
    var re = new RegExp('_', 'g');

    if (field_edit == 'time') {
        value = clean_time($('#' + field).val())


        value = $('#' + field + '_date').val() + ' ' + value
        value = fixedEncodeURIComponent(value)
    } else {
        var value = fixedEncodeURIComponent($('#' + field).val())
    }


    fields_data[fixedEncodeURIComponent(field.replace(re, ' '))] = value
    var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + parent + '&parent_key=' + parent_key + '&fields_data=' + JSON.stringify(fields_data)
    //console.log(request)
    $.getJSON(request, function(data) {
        // console.log(data)
        $('#' + field + '_editor').removeClass('valid ')
        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        if (data.state == 200) {


            $('#inline_new_object_msg').html(data.msg).addClass('success')
            $('#inline_new_object').addClass('hide')
            $('#inline_new_object').addClass('hide')
            $('#icon_' + trigger).addClass('fa-plus')
            $('#icon_' + trigger).removeClass('fa-times')
            $('#' + field).val('').attr('has_been_valid', 0)
            $('#' + field + '_editor').removeClass('valid invalid')
            $('#' + field + '_validation').removeClass('valid invalid')

            var updated_fields;
            for (updated_fields in data.updated_data) {
                $('.' + updated_fields).html(data.updated_data[updated_fields])
            }

            rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
            rows.fetch({
                reset: true
            });




        } else if (data.state == 400) {
            $('#inline_new_object_msg').html(data.msg).addClass('error')
            $('#' + field + '_editor').removeClass('invalid').addClass('invalid')
            $('#' + field + '_validation').removeClass('invalid').addClass('invalid')


        }
    })


}


function toggle_inline_new_object_form(trigger) {

    var field = $('#inline_new_object').attr('field')
    var field_edit = $('#' + field + '_container').attr('field_type')

    $('#inline_new_object_msg').html('').removeClass('error success')

    if ($('#icon_' + trigger).hasClass('fa-plus')) {
        $('#inline_new_object').removeClass('hide')
        $('#icon_' + trigger).removeClass('fa-plus').addClass('fa-times')



        if (field_edit == 'time') {
            var d = new Date();
            var time = addZero2dateComponent(d.getHours()) + ':' + addZero2dateComponent(d.getMinutes())
            $('#' + field).val(time)
            on_changed_value(field, time)
        } else {
            $('#{$data.field_id}').val('')

        }


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
