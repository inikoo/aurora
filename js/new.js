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


        }

    });

    console.log(form_validation)


    //$('#' + object + '_controls').removeClass('waiting')
    $('#' + object + '_save_icon').addClass('fa-cloud')
    $('#' + object + '_save_icon').removeClass('fa-spinner fa-spin')


    $('#' + object + '_save').addClass(form_validation)
        $('#fields').attr('has_been_fully_validated',true);

    

}


function check_if_form_is_valid() {

 var object = $('#fields').attr('object');
    var valid = true;
    $(".value").each(function(index) {
        var field = $(this).attr('field')
        var value = $('#' + field).val()


if(!$("#" + field + '_validation').hasClass('valid')){
       console.log(field+' '+$("#" + field + '_validation').hasClass('valid'))

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

    }else{
            $('#' + object + '_save').removeClass('valid').addClass('invalid')

    }

}


function save_new_object(object) {

    validate_form(object)

    if ($('#' + object + '_save').hasClass('valid')) {

        $('#' + object + '_save_icon').removeClass('fa-cloud');
        $('#' + object + '_save_icon').addClass('fa-spinner fa-spin');

        var fields_data = {};
        var re = new RegExp('_', 'g');

        $(".value").each(

        function(index) {
            var field = $(this).attr('field')
            var value = fixedEncodeURIComponent($('#' + field).val())



console.log($(this).attr('id')+' '+field)



            fields_data[fixedEncodeURIComponent(field.replace(re, ' '))] = value

        });




        var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + $('#fields').attr('parent') + '&parent_key=' + $('#fields').attr('parent_key') + '&fields_data=' + JSON.stringify(fields_data)
console.log(request)
//return;
        $.getJSON(request, function(data) {
console.log(data)
            $('#' + object + '_save_icon').addClass('fa-cloud');
            $('#' + object + '_save_icon').removeClass('fa-spinner fa-spin');


            if (data.state == 200) {
                $('#' + object + '_save').addClass('hide');
                $('.results').removeClass('hide')
                $('#' + object + '_msg').html(data.msg).removeClass('hide').addClass('success');
                var request = $('#' + object + '_go_new').attr('request_template').replace('__key__', data.key);
                $('#' + object + '_go_new').attr('request', request)
            } else if (data.state == 400) {

                $('#' + object + '_msg').html(data.msg).removeClass('hide').addClass('error')

            }
        })


    }




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
