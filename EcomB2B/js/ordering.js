/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2015 at 12:20:32 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/



$(function() {







        $('.modal-opener').on('click', function()
        {
            if( !$('#sky-form-modal-overlay').length )
            {
                $('body').append('<div id="sky-form-modal-overlay" class="sky-form-modal-overlay"></div>');
            }

            $('#sky-form-modal-overlay').on('click', function()
            {
                $('#sky-form-modal-overlay').fadeOut();
                $('.sky-form-modal').fadeOut();
            });

            form = $($(this).attr('href'));
            $('#sky-form-modal-overlay').fadeIn();
            form.css('top', '50%').css('left', '50%').css('margin-top', -form.outerHeight()/2).css('margin-left', -form.outerWidth()/2).fadeIn();

            return false;
        });

        $('.modal-closer').on('click', function()
        {
            $('#sky-form-modal-overlay').fadeOut();
            $('.sky-form-modal').fadeOut();

            return false;
        });




    $(document).on('click', '.payment_method_button', function (evt) {

        console.log(this)

        $('.payment_method_button').addClass('discreet bg-gray-light border-gray-dark').removeClass('bg-blue-light border-blue-dark').css({ 'opacity':.2})

        $(this).removeClass('discreet bg-gray-light border-gray-dark').addClass('bg-blue-light border-blue-dark').css({ 'opacity':1})

        $('.payment_method_block').addClass('hide')
        $('#'+$(this).data('tab')).removeClass('hide')
    });



    $(document).on('click', '.ordering_button', function (evt) {

        save_item_qty_change(this)

    });



    $('.order_row .label').hover(function () {


        var input = $(this).closest('.order_row').find('.order_input');

        // console.log(input)

        if (input.val() == '') {
            input.val(1)
        }


    }, function () {
        var input = $(this).closest('.order_row').find('.order_input');
        if (input.data('ovalue') == '' && !input.is('[readonly]')) {
            input.val('')
        }
    });

    $('.order_row .label').click(function () {

        var element = $(this);
        var order_row = $(this).closest('.order_row');
        if ($(this).find('i').hasClass('fa-spinner')) return;


        var input = order_row.find('.order_input')

        var order_qty = input.val()
        $(this).find('i').removeClass('fa-hand-pointer').addClass('fa-spinner fa-spin  ')
        input.prop('readonly', true);

        var order_key = $('#webpage_data').data('order_key');
        if (order_key == '') order_key = 0;

        if (order_qty > 0) {
            order_row.addClass('ordered').removeClass('empty')
        } else {
            //   order_row.removeClass('ordered').addClass('empty')

        }


        var request = 'ar_web_basket.php?tipo=update_item&product_id=' + $(this).closest('.product_container').data('product_id') + '&order_key=' + order_key + '&qty=' + order_qty + '&webpage_key=' + $('#webpage_data').data('webpage_key') + '&page_section_type=Family'
console.log(request)
        console.log(request)
        $.getJSON(request, function (data) {


            if (data.state == 200) {



                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }


                if (data.quantity > 0) {
                    element.html($('#ordering_settings').data('labels').ordered)
                    order_row.addClass('ordered').removeClass('empty')
                } else {
                    element.html($('#ordering_settings').data('labels').order)
                    order_row.removeClass('ordered').addClass('empty')
                }

                if (data.quantity == 0) data.quantity = ''

                input.val(data.quantity).data('ovalue', data.quantity).prop('readonly', false);



                for (var key in data.discounts_data) {
                    console.log(key+': '+data.discounts_data[key])
                    $('#transaction_deal_info_'+key).html(data.discounts_data[key]['deal_info'])
                    $('#transaction_item_net_'+key).html(data.discounts_data[key]['item_net'])

                    //$('.' + key).html(data.metadata.class_html[key])
                }


            } else if (data.state == 201) {

              //  window.location.href = 'waiting_payment_confirmation.php?referral_key=' + $('#webpage_data').data('webpage_key')


            }


        })


    });


    $(".order_input").on('input propertychange', function () {


        $(this).val($(this).val().replace(/[^\d]/g, ''))

        var order_qty = $(this).val()
        var order_row = $(this).closest('.order_row')

        var button = order_row.find('.label');

        console.log(button)

        if (order_qty != $(this).data('ovalue')) {


            button.html($('#ordering_settings').data('labels').update)
            order_row.addClass('ordered').removeClass('empty')
        } else {

            if (order_qty > 0) {
                button.html($('#ordering_settings').data('labels').ordered)
                order_row.addClass('ordered').removeClass('empty')

            } else {
                button.html($('#ordering_settings').data('labels').order)
                order_row.removeClass('ordered').addClass('empty')


            }

        }

    });


    $(".label_when_log_out").each(function (index) {


        var len_fit = 10;
        var un = $(this)


        var len_user_name = un.html().length;
        if (len_fit < len_user_name) {

            var size_now = parseInt(un.css("font-size"));
            var size_new = size_now * len_fit / len_user_name;
            un.css("font-size", size_new);

        }

    });

    $(".order_button_text").each(function (index) {


        var len_fit = 9;
        var un = $(this)


        var len_user_name = un.html().length;
        if (len_fit < len_user_name) {

            var size_now = parseInt(un.css("font-size"));
            var size_new = size_now * len_fit / len_user_name;
            console.log(size_now)
            console.log(size_new)

            un.css("font-size", size_new);

        }

    });


    $(".item_name").each(function (index) {


        var len_fit = 50; // According to your question, 10 letters can fit in.
        var un = $(this)

        // Get the lenght of user name.
        var len_user_name = un.html().length;
        if (len_fit < len_user_name) {

            // Calculate the new font size.
            var size_now = parseInt(un.css("font-size"));
            var size_new = size_now * len_fit / len_user_name;

            // Set the new font size to the user name.
            un.css("font-size", size_new);

        }

    });

})




$(document).on('input propertychange', '.order_qty', function (evt) {

  //  if ($(this).val() == $(this).data('ovalue')) {
  //      $(this).closest('span').find('i').removeClass('fa-save-o exclamation-circle error').addClass('fa-plus')

//    } else {

        if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {
            $(this).closest('span').find('i.minus').addClass('hide')
            $(this).closest('span').find('i.plus').removeClass('fa-plus exclamation-circle error').addClass('fa-save')
            $(this).addClass('discreet')
        } else {
            $(this).closest('span').find('i.minus').addClass('hide')
            $(this).closest('span').find('i.plus').removeClass('fa-plus fa-save').addClass('fa-exclamation-circle error')

        }
 //   }
});


function save_item_qty_change(element) {

    $(element).addClass('fa-spinner fa-spin')

    var input = $(element).closest('span').find('input')
    var icon=$(element)

    input.prop('readonly', true);

    if ($(element).hasClass('fa-plus')) {


        var _icon='fa-plus'

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }

        input.val(qty).addClass('discreet')

    }
    else if ($(element).hasClass('fa-minus')) {



        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }
        console.log(qty)
        input.val(qty).addClass('discreet')

        var _icon='fa-minus'

    }
    else {
        qty = parseFloat(input.val())

        var _icon='fa-save'

    }

    if (qty == '') qty = 0;

    var settings = $(element).closest('span').data('settings')


    var request = 'ar_web_basket.php?tipo=update_item&product_id=' + settings.item_key + '&order_key=' + $('#webpage_data').data('order_key') + '&qty=' + qty + '&webpage_key=' + $('#webpage_data').data('webpage_key') + '&page_section_type=Basket'


    $.getJSON(request, function (data) {
        input.prop('readonly', false);

        if (data.state == 200) {




            $(element).closest('span').find('i.plus').removeClass('fa-spinner fa-spin fa-save').addClass('fa-plus')
            $(element).closest('span').find('i.minus').removeClass('hide fa-spinner fa-spin').addClass('fa-minus')

            console.log($(element))

         //   $('#header_order_total_amount').html(data.data.order_total)
         //   $('#header_order_products').html(data.data.ordered_products_number)


            for (var key in data.metadata.class_html) {
                $('.' + key).html(data.metadata.class_html[key])
            }



            $(element).closest('tr').find('.item_amount').html(data.to_charge)


            if (data.quantity > 0) {

            } else {

            }

            if (data.quantity == 0) data.quantity = ''
console.log(data.discounts_data)

            for (var key in data.discounts_data) {
                console.log(key+': '+data.discounts_data[key])
                $('#transaction_deal_info_'+key).html(data.discounts_data[key]['deal_info'])
                $('#transaction_item_net_'+key).html(data.discounts_data[key]['item_net'])

                //$('.' + key).html(data.metadata.class_html[key])
            }



            //input.val(data.quantity).data('ovalue', data.quantity).prop('readonly', false);

        } else if (data.state == 201) {

           // window.location.href = 'waiting_payment_confirmation.php?referral_key=' + $('#webpage_data').data('webpage_key')


        }else if (data.state == 400) {

            $(element).removeClass('fa-spinner fa-spin fa-disk').addClass(_icon)

            swal(data.msg)

        }


    })

}




$("#_special_instructions").on("input propertychange", function (evt) {

    console.log('xx')

    var delay = 100;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_save_special_instructions($(this),delay)
});



function delayed_save_special_instructions(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {

        save_special_instructions()
    }, timeout));
}


function save_special_instructions(){



    var ajaxData = new FormData();

    ajaxData.append("tipo", 'update_order')
    ajaxData.append("order_key", $('webpage_data').data('order_key'))
    ajaxData.append("field", 'Order Special Instructions')
    ajaxData.append("value",$('#_special_instructions').val())



    $.ajax({
        url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {




            } else if (data.state == '400') {

            }



        }, error: function () {

        }
    });



}


function use_other_credit_card(){

    $('.credit_cards_list').addClass('hide')
    $('.credit_card_form').removeClass('hide')
    $('.show_saved_cards_list').removeClass('hide')

}

function show_saved_cards(){

    $('.credit_cards_list').removeClass('hide')
    $('.credit_card_form').addClass('hide')
    $('.show_saved_cards_list').addClass('hide')
}

function use_this_credit_card(element){
    console.log(element)


    $(element).closest('fieldset').find('.row').addClass('hide')



    $(element).closest('div.row').find('.delete_this_credit_card').addClass('hide')
    $(element).closest('div.row').find('.cancel_use_this_card').removeClass('hide')


    $(element).closest('div.row').find('.check_icon_saved_card').removeClass('fa-circle').addClass('fa-check-circle success')






    $('.cvv_for_saved_card').addClass('invisible')
    $(element).closest('div.row').removeClass('hide').find('.cvv_for_saved_card').removeClass('invisible')
}


function cancel_use_this_card(element){

    $(element).closest('div.row').find('.delete_this_credit_card').removeClass('hide')
    $(element).closest('div.row').find('.cancel_use_this_card').addClass('hide')

    $('.cvv_for_saved_card').addClass('invisible')

    $('.check_icon_saved_card').addClass('fa-circle').removeClass('fa-check-circle success')


    $(element).closest('fieldset').find('.row').removeClass('hide')
}


function place_order(element) {


    var button=$(element);

    if(button.hasClass('wait')){
        return;
    }

    button.addClass('wait')
    button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')



    var settings=$(element).data('settings')

    var ajaxData = new FormData();

    ajaxData.append("tipo", settings.tipo)
    ajaxData.append("payment_account_key", settings.payment_account_key)
    ajaxData.append("order_key", settings.order_key)


    $.ajax({
        url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {



            if (data.state == '200') {


                $('.ordered_products_number').html('0')
                $('.order_total').html('')

                window.location.replace("thanks.sys?order_key="+data.order_key);

            } else if (data.state == '400') {
                button.removeClass('wait')
                button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                swal("Error!", data.msg, "error")
            }



        }, error: function () {

        }
    });

}


