/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  18-09-2019 14:07:56 MYT Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/







function set_up_customer_integration(element,integration_type){

    const icon = $(element).find('i');
    const container = $(element).closest('td.container');


    if(icon.hasClass('wait')){
        return;
    }

    icon.removeClass('fa-arrow-right').addClass(' fa-spinner fa-spin wait' )





    var ajaxData = new FormData();

    ajaxData.append("tipo", 'set_up_customer_integration')

    ajaxData.append("customer_key", $('#fields').attr('key'))

    ajaxData.append("integration_type", integration_type)


    $.ajax({
        url: "/ar_edit_customers.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {


            icon.removeClass('wait')


            if (data.state == '200') {


                console.log(data)

                container.find('.button').addClass('hide');

                container.find('.integration_result').text(data.result)


                if (data.other_fields) {
                    for (var key in data.other_fields) {

                        //   console.log(data.other_fields[key])

                        update_field(data.other_fields[key])
                    }
                }

                if (data.deleted_fields) {
                    for (var key in data.deleted_fields) {
                        delete_field(data.deleted_fields[key])
                    }
                }

                for (var key in data.update_metadata.class_html) {
                    $('.' + key).html(data.update_metadata.class_html[key])
                }


                for (var key in data.update_metadata.hide) {
                    $('.' + data.update_metadata.hide[key]).addClass('hide')
                }

                for (var key in data.update_metadata.show) {

                    $('.' + data.update_metadata.show[key]).removeClass('hide')
                }


            } else if (data.state == '400') {

                icon.addClass('fa-arrow-right').removeClass(' fa-spinner fa-spin')


                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });


}

function customer_email_width_hack(email) {
    if (email.text().length > 30) {
        email.css("font-size", "90%");
    }
}

function unauthorized_open_fund_credit(element) {
    var _labels = $(element).data('labels');
    var request = '/ar_find.php?tipo=users_with_right&right=IS'
    $.getJSON(request, function (data) {
        var a = [];
        $.each(data.users_data, function (key, value) {
            if (value['UIR'] == 'No') {
                a.push(value['User Alias'])
            }
        });
        var authorised_users = a.join(', ');
        footer_text = _labels.footer + authorised_users


        Swal.fire({
            type: 'error', title: _labels.title, text: _labels.text, footer: footer_text,
        })

    })

}

function close_fund_credit(){
    $('.add_funds_to_customer_account').addClass('hide')
    $('.remove_funds_to_customer_account').addClass('hide')

}




function show_edit_credit_dialog(type) {

    if(type=='add_funds'){
        $('.remove_funds_to_customer_account').addClass('hide')

    }else if(type=='remove_funds'){
        $('.add_funds_to_customer_account').addClass('hide')

    }

    if ($('.'+type+'_to_customer_account').hasClass('hide')) {

        $('.'+type+'_to_customer_account').removeClass('hide')
        $('.'+type+'_to_customer_field').attr("disabled", true);
        $('.'+type+'_to_customer_fields').addClass("just_hinted").val('');
    }
}



function select_fund_type(element) {
    
    var operation_type=$(element).closest('.edit_funds_to_customer_account').data('operation_type')

    console.log(operation_type)

    var parent=$(element).closest('.customer_account_type_buttons')
    parent.find('.fund_type_button').removeClass('selected').addClass('very_discreet')
    $(element).addClass('selected').removeClass('very_discreet')
    $('.'+operation_type+'_to_customer_account_type').val($(element).data('type'))
    $('.'+operation_type+'_to_customer_field').attr("disabled", false);
    $('.'+operation_type+'_to_customer_fields').removeClass("just_hinted")
    $('.'+operation_type+'_to_customer_field.amount').focus()


}

$(document).on('input propertychange', '.edit_funds_to_customer_field', function (evt) {
    validate_edit_funds_to_customer(this);
})


function validate_edit_funds_to_customer(element) {

    var operation_type=$(element).closest('.edit_funds_to_customer_account').data('operation_type')


    var invalid=false;
    var valid=false;

    if($('.'+operation_type+'_to_customer_field.note').val()!=''){
        valid=true
    }

    if( !validate_number($('.'+operation_type+'_to_customer_field.amount').val(), 0, 999999999) ){
        $('.'+operation_type+'_to_customer_field.amount').removeClass('invalid')



    }else{
        invalid=true
        valid=false
        $('.'+operation_type+'_to_customer_field.amount').addClass('invalid')

    }


    $('.save_'+operation_type+'_to_customer_account').addClass('changed');



    if(invalid){
        $('.save_'+operation_type+'_to_customer_account').addClass('invalid ').removeClass('valid')

    }else{

        if(valid){
            $('.save_'+operation_type+'_to_customer_account').addClass('valid').removeClass('invalid')

        }else{
            $('.save_'+operation_type+'_to_customer_account').removeClass('invalid valid')

        }


    }


}



function save_edit_funds_to_customer_account(operation_type) {





    if ($('.save_'+operation_type+'_to_customer_account').hasClass('wait')   ||  !$('.save_'+operation_type+'_to_customer_account').hasClass('valid')  ) {
        return;
    }
    $('.save_'+operation_type+'_to_customer_account').addClass('wait')

    $('.save_'+operation_type+'_to_customer_account i').removeClass('fa-cloud').addClass('fa-spinner fa-spin');


    var ajaxData = new FormData();

    ajaxData.append("tipo", ''+operation_type+'_to_customer_account')

    ajaxData.append("customer_key", $('#customer').attr('key'))
    ajaxData.append("credit_transaction_type", $('.'+operation_type+'_to_customer_account_type').val())
    ajaxData.append("amount", $('.'+operation_type+'_to_customer_field.amount').val())

    ajaxData.append("note", $('.'+operation_type+'_to_customer_field.note').val())



    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {




            $('.save_'+operation_type+'_to_customer_account').removeClass('wait')


            //console.log(data)

            if (data.state == '200') {

                close_fund_credit();

                change_view(state.request, { 'reload_showcase': 1})
                if (state.tab == 'customer.credit_blockchain' || state.tab=='customer.history' ) {
                    rows.fetch({
                        reset: true
                    });
                }

                $('.save_'+operation_type+'_to_customer_account i').addClass('fa-cloud').removeClass('fa-spinner fa-spin');



            } else if (data.state == '400') {
                $('.save_'+operation_type+'_to_customer_account i').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

                swal("Error!", data.msg, "error")
            }


        }, error: function () {
            $('.save_'+operation_type+'_to_customer_account').removeClass('wait')
            $('.save_'+operation_type+'_to_customer_account i').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        }
    });


}

