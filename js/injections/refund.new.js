/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2017 at 14:47:35 GMT+8, Legian, Bali, Indonesia
 Copyright (c) 2017, Inikoo
 Version 3.0*/


$('body').on('input propertychange', '.new_refund_item', function (evt) {

    var error;


    if($(this).val()!='' && validate_number($(this).val(),0,$(this).attr('max'))){
        $(this).addClass('error')
        error=true;
    }else{
        $(this).removeClass('error')
        error=false;
    }

    var    feedback_element    = $(this).closest('tr').find('.set_otf_feedback_button').removeClass('hide')

    if( $(this).val()>0 && !error){
        feedback_element.removeClass('hide')
    }else{
        feedback_element.addClass('hide')
    }

    update_new_refund_totals()


});


$('body').on('input propertychange', '.new_refund_item_tax', function (evt) {

    if($(this).val()!='' && validate_number($(this).val(),0,$(this).attr('max'))){
        $(this).addClass('error')
    }else{
        $(this).removeClass('error')
    }


    update_new_refund_tax_totals()


});


$('body').on('click', 'span.new_refund_ordered_quantity', function () {

    var input = $(this).closest('tr').find('.new_refund_item')

    var add_amount = parseFloat($(this).attr('unit_amount'))
    var current_amount = input.val()
    if (current_amount == '') {
        current_amount = 0;
    }
    current_amount = parseFloat(current_amount)

    var to_add_amount = (current_amount + add_amount)

    var max = parseFloat(input.attr('max'));


    if (max < to_add_amount) {
        to_add_amount = max
    }


    change_item_refund(to_add_amount.toFixed(2), input)
});




$('body').on('click', 'span.new_refund_tax_ordered_quantity', function () {

    var input = $(this).closest('tr').find('.new_refund_item_tax')

    var add_amount = parseFloat($(this).attr('unit_amount'))
    var current_amount = input.val()
    if (current_amount == '') {
        current_amount = 0;
    }
    current_amount = parseFloat(current_amount)

    var to_add_amount = (current_amount + add_amount)

    var max = parseFloat(input.attr('max'));


    if (max < to_add_amount) {
        to_add_amount = max
    }


    change_item_tax_refund(to_add_amount.toFixed(2), input)
});





$('body').on('click', 'span.new_refund_order_item_net', function () {

    var input = $(this).closest('tr').find('.new_refund_item')

    var to_add_amount = parseFloat($(this).attr('amount'))


    var max = parseFloat(input.attr('max'));


    if (max < to_add_amount) {
        to_add_amount = max
    }


    change_item_refund(to_add_amount.toFixed(2), input)
});



$('body').on('click', 'span.new_refund_order_item_tax', function () {

    var input = $(this).closest('tr').find('.new_refund_item_tax')

    var to_add_amount = parseFloat($(this).attr('amount'))


    var max = parseFloat(input.attr('max'));


    if (max < to_add_amount) {
        to_add_amount = max
    }


    change_item_tax_refund(to_add_amount.toFixed(2), input)
});



function refund_tax_all(){

    $('.new_refund_order_item_tax').each(function (i, obj) {

        var input = $(obj).closest('tr').find('.new_refund_item_tax')

        var to_add_amount = parseFloat($(obj).attr('amount'))


        var max = parseFloat(input.attr('max'));


        if (max < to_add_amount) {
            to_add_amount = max
        }


        change_item_tax_refund(to_add_amount.toFixed(2), input)

    });

}


function change_item_refund(amount, input) {

    input.val(amount)
    var error;
    if(validate_number(input.val(),0,input.attr('max'))){
        input.addClass('error')
        error=true;
    }else{
        input.removeClass('error')
        error=false;
    }

    var    feedback_element= input.closest('tr').find('.set_otf_feedback_button').removeClass('hide')

    if( input.val()>0 && !error){
        feedback_element.removeClass('hide')
    }else{
        feedback_element.addClass('hide')
    }

    update_new_refund_totals()

}

function change_item_tax_refund(amount, input) {

    input.val(amount)

    if(validate_number(input.val(),0,input.attr('max'))){
        input.addClass('error')
    }else{
        input.removeClass('error')
    }


    update_new_refund_tax_totals()

}

function update_new_refund_tax_totals() {

    var number_affected_items=0;
    var number_affected_items_with_feedback=0;


    var data = $('#order').data('object')

    symbol = data.symbol
    tax_rate = parseFloat(data.tax_rate)
    available_to_refund= parseFloat(data.available_to_refund)


    var net = 0.00;
    var tax = 0.00;

    $('.new_refund_item_tax').each(function (i, obj) {
        if ($(obj).val() != ''  && !$(obj).hasClass('hide')  && !$(obj).hasClass('error')  ) {
            tax = tax + parseFloat($(obj).val());
            if($(obj).hasClass('item')) {
                number_affected_items++;


            }
        }

    });

    tax = parseFloat(tax);

    console.log(tax)

    total = tax ;

    if(total>0  ){
        $('.open_create_refund_dialog_button').addClass('changed valid')
    }else{
        $('.open_create_refund_dialog_button').removeClass('changed valid')

    }


    $('.Refund_Net_Amount').html(symbol + net.toFixed(2))
    $('.Refund_Tax_Amount').html(symbol + tax.toFixed(2))
    $('.Refund_Total_Amount').html(symbol + total.toFixed(2))

    $('.affected_items').html(number_affected_items)

    $('.percentage_refunded').html((100*(total/available_to_refund)).toFixed(2))





}

function update_new_refund_totals() {

    var number_affected_items=0;
    var number_affected_items_with_feedback=0;


    var data = $('#order').data('object')

    symbol = data.symbol
    tax_rate = parseFloat(data.tax_rate)
    available_to_refund= parseFloat(data.available_to_refund)



    var net = 0.00;

    $('.new_refund_item').each(function (i, obj) {
        if ($(obj).val() != ''  && !$(obj).hasClass('hide')  && !$(obj).hasClass('error')  ) {
            net = net + parseFloat($(obj).val());
            if($(obj).hasClass('item')) {
                number_affected_items++;
                var    feedback_data    = $(obj).closest('tr').find('.set_otf_feedback_button').data('feedback')
                if(    typeof(feedback_data) != "undefined" && feedback_data !== null && typeof(feedback_data) == "object"){

                    number_affected_items_with_feedback++

                }
            }
        }

    });

    net = parseFloat(net);
    tax = parseFloat(net * tax_rate);
    total = tax + net;

    if(total>0 && number_affected_items==number_affected_items_with_feedback  ){
        $('.open_create_refund_dialog_button').addClass('changed valid')
    }else{
        $('.open_create_refund_dialog_button').removeClass('changed valid')

    }


    $('.Refund_Net_Amount').html(symbol + net.toFixed(2))
    $('.Refund_Tax_Amount').html(symbol + tax.toFixed(2))
    $('.Refund_Total_Amount').html(symbol + total.toFixed(2))

    $('.affected_items').html(number_affected_items)

    $('.percentage_refunded').html((100*(total/available_to_refund)).toFixed(2))





}

function create_refund(){

    if (!$('.open_create_refund_dialog_button').hasClass('valid')) {
        return;
    }

    $('.open_create_refund_dialog_button').removeClass('valid').addClass('fa-spinner fa-spin')


    transactions = [];



    $('.new_refund_item').each(function (i, obj) {

        if($(obj).val()!='' && !$(obj).hasClass('error') && !$(obj).hasClass('hided') ) {

            var transaction = {
                type: $(obj).attr('transaction_type'), id: $(obj).attr('transaction_id'), amount: $(obj).val(),
                feedback: $(obj).closest('tr').find('.set_otf_feedback_button').data('feedback')



            }

            transactions.push(transaction);
        }

    });

    var _data =$('#order').data('object')


    request='ar_edit_orders.php?tipo=create_refund&key='+_data.key+'&transactions='+JSON.stringify(transactions);


    console.log(request)
    var ajaxData = new FormData();



    ajaxData.append("tipo", 'create_refund')
    ajaxData.append("key", _data.key)
    ajaxData.append("transactions", JSON.stringify(transactions))


    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                change_view('orders/'+data.store_key+'/'+data.order_key+'/invoice/'+data.refund_key);

            } else if (data.state == '400') {
                $('.open_create_refund_dialog_button').addClass('valid').removeClass('fa-spinner fa-spin')

                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });



}

function create_tax_only_refund(){

    if (!$('.open_create_refund_dialog_button').hasClass('valid')) {
        return;
    }

    $('.open_create_refund_dialog_button').removeClass('valid').addClass('fa-spinner fa-spin')


    transactions = [];



    $('.new_refund_item_tax').each(function (i, obj) {

        if($(obj).val()!='' && !$(obj).hasClass('error') && !$(obj).hasClass('hided') ) {

            var transaction = {
                type: $(obj).attr('transaction_type'), id: $(obj).attr('transaction_id'), amount: $(obj).val()


            }

            transactions.push(transaction);
        }

    });

    var _data =$('#order').data('object')


    request='ar_edit_orders.php?tipo=create_refund_tax_only&key='+_data.key+'&transactions='+JSON.stringify(transactions);


    console.log(request)
    var ajaxData = new FormData();



    ajaxData.append("tipo", 'create_refund_tax_only')
    ajaxData.append("key", _data.key)
    ajaxData.append("transactions", JSON.stringify(transactions))




    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                change_view('orders/'+data.store_key+'/'+data.order_key+'/invoice/'+data.refund_key);

            } else if (data.state == '400') {
                $('.open_create_refund_dialog_button').addClass('valid').removeClass('fa-spinner fa-spin')

                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });



}

function refund_tax_only(){

    change_tab('refund.new.items_tax')

    $('.show_refund_tax_only').addClass('hide')
    $('.create_net_and_tax_refund').addClass('hide')
    $('.create_tax_only_refund').removeClass('hide')
    $('.title_tax_only').removeClass('hide')

    update_new_refund_tax_totals()

}

function close_refund_tax_only(){

    change_tab('refund.new.items')
    $('.show_refund_tax_only').removeClass('hide')
    $('.title_tax_only').addClass('hide')
    $('.create_net_and_tax_refund').removeClass('hide')
    $('.create_tax_only_refund').addClass('hide')
    update_new_refund_totals()
    
}



//========


function close_otf_feedback(){

    $('.feedback_otf_form').addClass('hide')

    $('.close_otf_feedback').removeClass('hide')
    $('.feedback_otf_form textarea').val('')
    $('.feedback_otf_form .scope ').each(function (i, scope_element) {
        $(scope_element).addClass('very_discreet_on_hover')
        $(scope_element).find('i').addClass('fa-square').removeClass('fa-check-square')
    })

    validate_otf_feedback()

}


function feedback_otf_scope_clicked(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-square')){
        $(element).removeClass('very_discreet_on_hover')
        icon.removeClass('fa-square').addClass('fa-check-square')
    }else{
        $(element).addClass('very_discreet_on_hover')
        icon.addClass('fa-square').removeClass('fa-check-square')
    }

    validate_otf_feedback()
}


$(document).on('input propertychange paste', '.feedback_otf_form textarea', function (e) {
    validate_otf_feedback()
});



$(document).on('click', '.set_otf_feedback_button', function (e) {

    if(!$('.feedback_otf_form').hasClass('hide')){
        return;
    }

    var type=$(this).data('type')

    var key=$(this).data('key');



    $('.feedback_otf_form').data('key',key)

    $('.feedback_otf_form').data('type',type)

    var offset = $(this).offset()

    $('.feedback_otf_form').removeClass('hide').offset({
        'top': offset.top+$(this).height(),'left': offset.left+$(this).width()- $('.feedback_otf_form').width()-7
    })

    var feedback_data=$('#set_'+type+'_feedback_'+key).data('feedback')




    if(    typeof(feedback_data) != "undefined" && feedback_data !== null && typeof(feedback_data) == "object"){

        $.each( feedback_data.scopes, function( key, value ) {
            $('.feedback_otf_form  .'+ value).trigger( "click" );


        })
        $('.feedback_otf_form textarea').val(feedback_data.feedback)

    }
    validate_otf_feedback()

})


$(document).on('click', '.feedback_otf_form .save_feedback', function (e) {

    if(!$(this).hasClass('valid')){
        return;
    }

    var scopes=[];

    var formatted_feedback='<i class="fa fa-comment-alt-exclamation"></i> '+$('.feedback_otf_form textarea').val()+' <span class="italic">(';

    $('.feedback_otf_form .scope ').each(function (i, scope_element) {
        if($(scope_element).find('i').hasClass('fa-check-square')){
            scopes.push($(scope_element).data('scope'))
            formatted_feedback+=$(scope_element).data('label')+', '
        }

    })
    formatted_feedback=formatted_feedback.replace(/, $/g,")")


    var key=$('.feedback_otf_form').data('key');
    var type=$('.feedback_otf_form').data('type');


    if(type=='otf'){
        var feedback={ original_otf:key,  scopes:scopes, feedback:$('.feedback_otf_form textarea').val()}

    }else{
        var feedback={ original_onptf:key,  scopes:scopes, feedback:$('.feedback_otf_form textarea').val()}

    }


    $('#set_'+type+'_feedback_'+key).data('feedback',feedback).html($('.feedback_otf_form').data('feedback_set_label')).removeClass('very_discreet_on_hover italic')


    console.log('#feedback_description_'+type+'_'+key)

    $('#feedback_description_'+type+'_'+key).html(formatted_feedback).removeClass('hide')

    close_otf_feedback()
    update_new_refund_totals();

})


$(document).on('input propertychange paste', '.feedback_otf_form textarea', function (e) {
    validate_otf_feedback()

});

function validate_otf_feedback(){

    var scope_ok=false;

    $('.feedback_otf_form .scope ').each(function (i, scope_element) {
        if($(scope_element).find('i').hasClass('fa-check-square')){
            scope_ok=true;
            return false;
        }

    })


    if(scope_ok && $('.feedback_otf_form textarea').val()!='' ){
        $('.feedback_otf_form .save').addClass('valid changed')
        $('.close_otf_feedback').addClass('hide')
    }else{
        $('.feedback_otf_form .save').removeClass('valid changed')
        $('.close_otf_feedback').removeClass('hide')
    }


}


