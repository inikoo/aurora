/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  1 March 2018 at 14:32:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/




function toggle_list_subscription(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off').next('span').addClass('discreet')

    }else if(icon.hasClass('fa-toggle-off')){
        icon.removeClass('fa-toggle-off').addClass('fa-toggle-on').next('span').removeClass('discreet')
    }

    var form_validation = get_form_validation_state()
    process_form_validation(form_validation)


}



function toggle_list_elements(element){


    if( $(element).closest('tr').hasClass('super_discreet')){
        return
    }


    var icon=$(element).find('i')



    var number_elements=($(element).closest('td').find('i').length/2)

    if(icon.hasClass('fa-check-square')){



        if( $(element).closest('td').find('i.fa-check-square').length-number_elements<2){
            return
        }


        icon.removeClass('fa-check-square').addClass('fa-square').next('span').addClass('discreet')

    }else if(icon.hasClass('fa-square')){
        icon.removeClass('fa-square').addClass('fa-check-square').next('span').removeClass('discreet')
    }

    var form_validation = get_form_validation_state()
    process_form_validation(form_validation)

}







function toggle_list_with(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-random')){

        icon.removeClass('fa-random').addClass('fa-toggle-on')

    }else if(icon.hasClass('fa-toggle-on')){
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')

    }else if(icon.hasClass('fa-toggle-off')){

        icon.removeClass('fa-toggle-off').addClass('fa-random')
    }

    var form_validation = get_form_validation_state()
    process_form_validation(form_validation)

}


function post_process_new_customer_list_form_validation(){

    //console.log('caca')

     var original_customer_list_checksum='3a4f6614d714c0b2a430815c430d6d259df7cd59642280894d3b1335d0bb3e8f';
    var fields_data = {};
    var re = new RegExp('_', 'g');

    $(".value").each(function (index) {

        var field = $(this).attr('field')
        var field_type = $(this).attr('field_type')

        if(field=='List_Name') return 1;
        if(field=='List_Type') return 1;




        if (field_type == 'time') {
            value = clean_time($('#' + field).val())
        }else if (field_type == 'date' || field_type == 'date_interval') {
            if($('#' + field).val()!='') {
                value = $('#' + field).val() + ' ' + $('#' + field + '_time').val()
            }else{
                value=''
            }
        } else if (field_type == 'password' || field_type == 'password_with_confirmation' || field_type == 'password_with_confirmation_paranoid' || field_type == 'pin' || field_type == 'pin_with_confirmation' || field_type == 'pin_with_confirmation_paranoid') {
            value = sha256_digest($('#' + field).val())
        } else if (field_type == 'attachment') {
            form_data.append("file", $('#' + field).prop("files")[0])
            value = ''
        } else if (field_type == 'country_select') {
            value = $('#' + field).countrySelect("getSelectedCountryData").code

        } else if (field_type == 'telephone') {
            value = $('#' + field).intlTelInput("getNumber");

        } else if (field_type == 'subscription') {
            var icon = $(this).find('i')
            if (icon.hasClass('fa-toggle-on')) {
                value = 'Yes'

            } else {
                value = 'No'
            }

        }  else if (field_type == 'elements') {
            var icon = $(this).find('i')
            if (icon.hasClass('fa-check-square')) {
                value = 'Yes'

            } else {
                value = 'No'
            }

        } else if (field_type == 'with_field') {
            var icon = $(this).find('i')
            if (icon.hasClass('fa-toggle-on')) {
                value = 'Yes'

            }else if (icon.hasClass('fa-toggle-off')) {
                value = 'No'

            } else {
                value = ''
            }
        } else {
            var value = $('#' + field).val()
        }


            fields_data[field.replace(re, ' ')] = value






    });


  //  console.log(fields_data)

if(fields_data['Assets']!='' ||  fields_data['Ordered Date From']!='' || fields_data['Ordered Date To']!=''){
        $('#Order_State_field').removeClass('super_discreet')
}else
    {
        $('#Order_State_field').addClass('super_discreet')

}

    var checksum=sha256_digest(JSON.stringify(fields_data));

  //  console.log(checksum)

    $('.calculate_number_list_items').removeClass('hide')
    $('.calculated_number_list_items').addClass('hide')

    if( checksum==original_customer_list_checksum){
        $('.calculate_number_list_items').addClass('super_discreet')

    }else{

        $('.calculate_number_list_items').removeClass('super_discreet')
    }




}


function estimate_number_list_items(){


    $('.calculating_number_list_items').removeClass('hide')
    $('.calculate_number_list_items').addClass('hide')
    $('.calculated_number_list_items').addClass('hide')

    var form_data = new FormData();

    var fields_data = {};
    var re = new RegExp('_', 'g');



    $(".value").each(function (index) {

        var field = $(this).attr('field')
        var field_type = $(this).attr('field_type')

        if(field=='List_Name') return 1;
        if(field=='List_Type') return 1;


        if (field_type == 'time') {
            value = clean_time($('#' + field).val())
        } else if (field_type == 'date' || field_type == 'date_interval') {


            if($('#' + field+'_value').val()!='') {
                value = $('#' + field+'_value').val() + ' ' + $('#' + field + '_time').val()
            }else{
                value=''
            }





        } else if (field_type == 'password' || field_type == 'password_with_confirmation' || field_type == 'password_with_confirmation_paranoid' || field_type == 'pin' || field_type == 'pin_with_confirmation' || field_type == 'pin_with_confirmation_paranoid') {
            value = sha256_digest($('#' + field).val())
        } else if (field_type == 'attachment') {
            form_data.append("file", $('#' + field).prop("files")[0])
            value = ''
        } else if (field_type == 'country_select') {
            value = $('#' + field).countrySelect("getSelectedCountryData").code

        } else if (field_type == 'telephone') {
            value = $('#' + field).intlTelInput("getNumber");

        } else if (field_type == 'subscription') {
            var icon = $(this).find('i')
            if (icon.hasClass('fa-toggle-on')) {
                value = 'Yes'

            } else {
                value = 'No'
            }

        }  else if (field_type == 'elements') {
            var icon = $(this).find('i')
            if (icon.hasClass('fa-check-square')) {
                value = 'Yes'

            } else {
                value = 'No'
            }

        } else if (field_type == 'with_field') {
            var icon = $(this).find('i')
            if (icon.hasClass('fa-toggle-on')) {
                value = 'Yes'

            }else if (icon.hasClass('fa-toggle-off')) {
                value = 'No'

            } else {
                value = ''
            }
        } else {
            var value = $('#' + field).val()
        }


        fields_data[field.replace(re, ' ')] = value





    });


    // used only for debug
    var request = '/ar_lists.php?tipo=estimate_number_list_items&object=' + $('#fields').attr('object') + '&parent_key=' + $('#fields').attr('parent_key') + '&fields_data=' + JSON.stringify(fields_data)
    console.log(request)


    //return;
    //=====
    form_data.append("tipo",'estimate_number_list_items')
    form_data.append("object", $('#fields').attr('object'))
    form_data.append("parent_key", $('#fields').attr('parent_key'))
    form_data.append("fields_data", JSON.stringify(fields_data))

    var request = $.ajax({

        url: "/ar_lists.php" , data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {

        $('.calculate_number_list_items').addClass('hide')
        $('.calculating_number_list_items').addClass('hide')



        $('.calculated_number_list_items').removeClass('hide').html(data.text)


    })

    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}


