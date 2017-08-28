/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2017 at 12:23:41 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/



function copy_max_refundable_amount() {

    if(!$('#payment_refund_dialog .lower_fields').hasClass('hide')){
        $('#payment_refund_amount').val($('#payment_refund_max_amount').val());
        validate_refund_form()
    }


}

function open_refund_dialog(element, payment_key) {


    var settings = $(element).data('settings')

    $('#payment_refund_dialog').removeClass('hide').data('settings',settings)

    $('#payment_refund_dialog .payment_reference').html(settings.reference)
    $('#payment_refund_dialog .payment_refundable_amount').html(settings.amount_formatted)
    $('#payment_refund_max_amount').val(settings.amount)
    $('#payment_refund_payment_key').val(payment_key)

    if (settings.can_refund_online) {
        $('#payment_refund_dialog .refund_submit_type').removeClass('hide')
        $('#payment_refund_submit_type').val('')

    } else {
        $('#payment_refund_dialog .submit_type').removeClass('hide')
        $('#payment_refund_submit_type').val('Manual')

        $('#payment_refund_dialog .reference').removeClass('hide')



    }

}


$(document).on('input propertychange', '#payment_refund_dialog input', function (evt) {
    validate_refund_form();
})



function validate_refund_form(){

    var filled=true;
    var error=false;

    if($('#payment_refund_refund_type').val()==''){
        filled=false;

    }
    if($('#payment_refund_submit_type').val()==''){
        filled=false;

    }

    if($('#payment_refund_reference').val()=='' && $('#payment_refund_submit_type').val()=='Manual' ){
        filled=false;

    }




    if($('#payment_refund_amount').val()==''){
        filled=false;

    }else{

        if( (validate_number($('#payment_refund_amount').val(), 0, $('#payment_refund_max_amount').val())  || $('#payment_refund_amount').val()==0  ) && $('#payment_refund_amount').val()!='' ){
            error=true;
            $('#payment_refund_amount').addClass('error')

        }else{
            $('#payment_refund_amount').removeClass('error')

            error=false;
        }



    }

    if(error){
        $('#payment_refund_dialog .save').removeClass('valid changed').addClass('error')
        return;
    }else{
        $('#payment_refund_dialog .save').removeClass('error')

    }


    if(!filled){
        $('#payment_refund_dialog .save').removeClass('valid changed')

    }else{
        $('#payment_refund_dialog .save').addClass('valid changed')

    }

}


function payment_refund_credit_selected() {
    $('#payment_refund_dialog  .select_credit').addClass('selected').removeClass('no_selected')
    $('#payment_refund_dialog  .select_refund').removeClass('selected').addClass('no_selected')
    $('#payment_refund_refund_type').val('Credit')
    $('#payment_refund_submit_type').val('Credit')



    $('#payment_refund_dialog .fields').removeClass('hide')


    $('#payment_refund_dialog .fields').addClass('hide')

    $('#payment_refund_dialog .lower_fields').removeClass('hide')
        $('#payment_refund_dialog .reference').addClass('hide')


    $('#payment_refund_dialog  .submit_type').removeClass('selected no_selected')



}


function payment_refund_refund_selected() {
    $('#payment_refund_dialog  .select_credit').removeClass('selected').addClass('no_selected')
    $('#payment_refund_dialog  .select_refund').addClass('selected').removeClass('no_selected')
    $('#payment_refund_refund_type').val('Refund')

    $('#payment_refund_dialog .fields').removeClass('hide')

    var settings = $('#payment_refund_dialog').data('settings')

    if (!settings.can_refund_online) {



        $('#payment_refund_dialog .lower_fields').removeClass('hide')
        $('#payment_refund_dialog .reference').removeClass('hide')
        $('#payment_refund_dialog .amount').removeClass('hide')

    }else{
        $('#payment_refund_dialog .lower_fields').addClass('hide')

    }

}

function payment_submit_type_manual_selected() {
    $('#payment_refund_dialog  .select_manual').addClass('selected').removeClass('no_selected')
    $('#payment_refund_dialog  .select_online').removeClass('selected').addClass('no_selected')
    $('#payment_refund_submit_type').val('Manual')
    $('#payment_refund_dialog .lower_fields').removeClass('hide')

    $('#payment_refund_dialog .reference').removeClass('hide')

}


function payment_submit_type_online_selected() {
    $('#payment_refund_dialog  .select_manual').removeClass('selected').addClass('no_selected')
    $('#payment_refund_dialog  .select_online').addClass('selected').removeClass('no_selected')
    $('#payment_refund_submit_type').val('Online')

    $('#payment_refund_dialog .lower_fields').removeClass('hide')
    $('#payment_refund_dialog .reference').addClass('hide')


}




function cancel_payment(element, payment_key) {


    if ($(element).hasClass('super_discreet')) {
        return;
    }

    var tr = $(element).closest('tr')

    if (tr.hasClass('deleting_tr') || tr.hasClass('deleted_tr')) {
        return;
    }

    tr.addClass('deleting_tr')

    // tr.addClass('deleted_tr')
    //return;


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'object_operation')
    ajaxData.append("operation", 'delete')
    ajaxData.append("object", 'Payment')
    ajaxData.append("key", payment_key)


    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                // todo: update current view instead of reload page
                location.reload();

                // tr.removeClass('deleting_tr').addClass('deleted_tr')
                // $('#delete_payment_button_' + payment_key).html(data.msg).closest('td').addClass('hide').html('')

            } else if (data.state == '400') {
                tr.removeClass('deleting_tr')
                swal(data.msg);
            }


        }, error: function () {

        }
    });


}

function save_refund(element){

    var icon=$(element).find('i')

    if (icon.hasClass('wait')) {
        return;
    }

    if (!$(element).hasClass('valid')) {
        return;
    }

    icon.addClass('wait fa-spinner fa-spin')



    // tr.addClass('deleted_tr')
    //return;


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'refund_payment')
    ajaxData.append("operation", $('#payment_refund_refund_type').val())
    ajaxData.append("submit_type", $('#payment_refund_submit_type').val())
    ajaxData.append("key", $('#payment_refund_payment_key').val())
    ajaxData.append("amount", $('#payment_refund_amount').val())
    ajaxData.append("reference", $('#payment_refund_reference').val())


    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                // todo: update current view instead of reload page
                location.reload();

                // tr.removeClass('deleting_tr').addClass('deleted_tr')
                // $('#delete_payment_button_' + payment_key).html(data.msg).closest('td').addClass('hide').html('')

            } else if (data.state == '400') {
                tr.removeClass('deleting_tr')
                swal(data.msg);
            }


        }, error: function () {

        }
    });

}