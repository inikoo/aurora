/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  29-07-2019 19:45:19 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/




function toggle_mailshot_scope(element){


    $('#Type_field').addClass('valid')

    if(!$(element).hasClass('selected')){

        $(element).closest('.button_radio_options').find('.button').removeClass('selected')
        $(element).addClass('selected')



        switch ($(element).attr('field')){
            case 'Customer_List':
                $('#List_field').removeClass('hide')
                $('#Asset_field').addClass('hide')
                $('#Scope_Type_field').addClass('hide')

                $('#Scope_Type_field').addClass('valid')

                break;
            case 'Product_Category':
                $('#List_field').addClass('hide')
                $('#Asset_field').removeClass('hide')

                if($('#Scope_Type_field').hasClass('activated')){
                    $('#Scope_Type_field').removeClass('hide')

                }

                $('#Scope_Type_field').removeClass('valid').find('.value').removeClass('selected')


                break;
            default:

        }
    }










}




function toggle_mailshot_scope_type(element){


    $('#Scope_Type_field').addClass('valid')


    if(!$(element).hasClass('selected')){

        $(element).closest('.button_radio_options').find('.button').removeClass('selected')
        $(element).addClass('selected')
    }







}




function toggle_new_category_deal_entitled_to(element) {


    if (!$(element).hasClass('selected')) {


        var tr = $(element).closest('tr')

        tr.find('.button').removeClass('selected')

        $(element).addClass('selected')

        if ($(element).attr('id') == 'Entitled_To_Anyone_field') {

            $('#Deal_Voucher_Auto_Code_field').addClass('hide')
            $('#Deal_Voucher_Code_field').addClass('hide')

        } else if ($(element).attr('id') == 'Entitled_To_Voucher_field') {
            $('#Deal_Voucher_Auto_Code_field').removeClass('hide')

            $('#Deal_Voucher_Code').removeClass('hide')

            if($('#toggle_voucher_auto_code_icon').hasClass('fa-toggle-on')){

                $('#Deal_Voucher_Code_field').addClass('hide')
                $('#Deal_Voucher_Auto_Code_field').removeClass('hide')

            }else{
                $('#Deal_Voucher_Code_field').removeClass('hide')
                $('#Deal_Voucher_Auto_Code_field').addClass('hide')
            }



        }


        $('.deal_type_title').removeClass('hide')
        $('#Type_field').removeClass('hide')
       // $('#Percentage_Off_field').removeClass('hide')


    }


}

function toggle_new_deal_entitled_to(element){


    if(!$(element).hasClass('selected')){

        $(element).closest('.button_radio_options').find('.button').removeClass('selected')
        $(element).addClass('selected')




    }


    console.log($(element).attr('field'))


    $('#_Customer_Selected_field').addClass('hide')

    switch ($(element).attr('field')){
        case 'Entitled_To_Customer':
            $('#Customer_field').removeClass('hide')
            $('#customer_list_field').addClass('hide')



            break;
        case 'Entitled_To_Customer_List':
            $('#customer_list_field').removeClass('hide')
            $('#Customer_field').addClass('hide')

            break;
        default:
            $('#customer_list_field').addClass('hide')
            $('#Customer_field').addClass('hide')
    }

   // var form_validation = get_form_validation_state()
   // process_form_validation(form_validation)


}



function toggle_new_deal_extra_trigger(element){



    if(!$(element).hasClass('selected')){

        $(element).closest('.button_radio_options').find('.button').removeClass('selected')
        $(element).addClass('selected')
    }


    console.log($(element).attr('field'))

    switch ($(element).attr('field')){
        case 'Trigger_Extra_Amount_Net':

            $('#Trigger_Extra_Amount_Net_field').removeClass('hide')
            $('#Trigger_Extra_Amount_Net').removeClass('hide')

            break;
        default:

    }

    // var form_validation = get_form_validation_state()
    // process_form_validation(form_validation)


}

function show_extra_term(){
    $('#Extra_Terms_field').removeClass('hide')
    $('#add_extra_term_field').addClass('hide')

}

function toggle_new_deal_trigger(element){



    if(!$(element).hasClass('selected')){

        $(element).closest('.button_radio_options').find('.button').removeClass('selected')
        $(element).addClass('selected')
    }


    console.log($(element).attr('field'))

    switch ($(element).attr('field')){
        case 'Product_Category':
            set_voucher_code_as_auto()
            $('#Asset_field').addClass('hide')

            break;
        case 'Trigger_Asset':
            $('#Deal_Voucher_Auto_Code_field').addClass('hide')
            $('#Deal_Voucher_Code_field').addClass('hide')
            $('#Asset_field').removeClass('hide')
            break;
        default:

    }


    $('#add_extra_term_field').removeClass('hide')

    // var form_validation = get_form_validation_state()
    // process_form_validation(form_validation)


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





function set_voucher_code_as_auto(){

    var icon=$('#Deal_Voucher_Auto_Code_container').find('i')
    icon.removeClass('fa-toggle-off').addClass('fa-toggle-on')

    $('#Deal_Voucher_Auto_Code_field').removeClass('hide')
    $('#Deal_Voucher_Code_field').addClass('hide')


    // var form_validation = get_form_validation_state()
    // process_form_validation(form_validation)

}



function toggle_voucher_auto_code(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')

        $('#Deal_Voucher_Auto_Code_field').addClass('hide')
        $('#Deal_Voucher_Code_field').removeClass('hide')
        $('#Deal_Voucher_Code').removeClass('hide')

        update_field({field:'Deal_Voucher_Code',render:true,required:true})

    }

   // var form_validation = get_form_validation_state()
   // process_form_validation(form_validation)

}


function post_process_new_customer_list_form_validation(){

    console.log('caca')

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

        $('.calculated_number_list_items').removeClass('hide').html(data.text)


    })

    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}

function on_change_customer_list(element) {

console.log(element)

    console.log($(element).attr('formatted_value'))

    $('#Customer_field').addClass('hide')
    $('#_Customer_Selected_field').removeClass('hide')

    $('#_Customer_Selected_container').html($(element).attr('formatted_value'))
    $('#_Customer_Selected_formatted_value').html($(element).attr('formatted_value'))





}

function select_other_customer(){

console.log($('#Customer_Select_value'))


    $('#Customer_field').removeClass('hide')
    $('#_Customer_Selected_field').addClass('hide')
    $('#Customer_container').find('input.Customer_Select_value').val('')

    $('#_Customer_Selected_container').html('')
    $('#_Customer_Selected_formatted_value').html('')
}

function toggle_category_deal_type(element){

    $('#Trigger_Extra_Items_Amount_Net').removeClass('hide')
    $('#Amount_Off').removeClass('hide')



    if(!$(element).hasClass('selected')){
        $(element).closest('.button_radio_options').find('.button').removeClass('selected')
        $(element).addClass('selected')


        $('#Percentage_field').addClass('hide')
        $('#Buy_n_get_n_free_field').addClass('hide')
        $('#Buy_n_n_free_field').addClass('hide')
        $('#Trigger_Extra_Items_Amount_Net_field').addClass('hide')
        $('#Amount_Off_field').addClass('hide')

        $('#Allowance_Type').val($(element).attr('field'))





        if ($(element).attr('field') == 'Deal_Type_Percentage_Off') {

            $('#Percentage_field').removeClass('hide')


        }else if ($(element).attr('field') == 'Deal_Type_Amount_Off') {

            $('#Trigger_Extra_Items_Amount_Net_field').removeClass('hide')
            $('#Amount_Off_field').removeClass('hide')



        } else if ($(element).attr('field') == 'Deal_Type_Buy_n_get_n_free') {
            $('#Percentage_field').addClass('hide')
            $('#Buy_n_get_n_free_field').removeClass('hide')
            $('#Buy_n_n_free_field').addClass('hide')


        }else if ($(element).attr('field') == 'Deal_Type_Buy_n_pay_n') {
            $('#Percentage_field').addClass('hide')
            $('#Buy_n_get_n_free_field').addClass('hide')
            $('#Buy_n_n_free_field').removeClass('hide')


        }


    }
    console.log('xx')
    $('#Deal_Component_controls').removeClass('hide')


}


