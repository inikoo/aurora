/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 September 2017 at 10:04:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/










$(document).on('input propertychange', '.order_qty', function (evt) {

  //  if ($(this).val() == $(this).attr('ovalue')) {
  //      $(this).closest('span').find('i').removeClass('fa-save-o exclamation-circle error').addClass('fa-plus')

//    } else {


    $(this).closest('div').find('.ordering_button').addClass('invisible')
    $(this).closest('div').find('.save').removeClass('invisible').css({ 'display':'inline'})


        if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {
            //$(this).closest('span').find('i').removeClass('fa-plus exclamation-circle error').addClass('fa-save')

            $(this).removeClass('error')
          //  save_item_qty_change(this)
        } else {
            $(this).addClass('error')
            //$(this).closest('span').find('i').removeClass('fa-plus fa-save').addClass('fa-exclamation-circle error')

        }
 //   }
});


function save_item_qty_change(element) {




    var order_div= $(element).closest('div')
    if(order_div.hasClass('wait')){
        return;
    }
    order_div.addClass('wait')

    var input = order_div.find('input')
    //var icon=$(element)




    input.prop('readonly', true);




    if ($(element).hasClass('fa-plus-circle')) {

        var _icon='fa-plus-circle'

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }




        input.val(qty).addClass('discreet')

    }
    else if ($(element).hasClass('fa-minus-circle')) {

        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }

        input.val(qty).addClass('discreet')

        var _icon='fa-minus'

    }
    else {
        qty = parseFloat(input.val())

        var _icon='fa-save'

    }

    $(element).addClass('fa-circle-o-notch fa-spin')

    if (qty == '') qty = 0;

    var settings = order_div.data('settings')


    var request = 'ar_web_basket.php?tipo=update_item&product_id=' + settings.pid + '&order_key=' + $('#webpage_data').data('order_key') + '&qty=' + qty + '&webpage_key=' + $('#webpage_data').data('webpage_key') + '&page_section_type=Basket'

console.log(request)

    $.getJSON(request, function (data) {
        input.prop('readonly', false);
        order_div.removeClass('wait')

        if (data.state == 200) {

            $(element).removeClass('fa-circle-o-notch fa-spin')

            if(_icon=='fa-save'){

                order_div.find('.ordering_button').removeClass('invisible')
                order_div.find('.save').addClass('invisible').css({ 'display':'none'})
            }


            if(data.order_empty){
                $('#basket_go_to_checkout').css({
                    display:'none'
                })
                $('#basket_continue_shopping').css({
                    display:'block'
                })
            }else{
                $('#basket_go_to_checkout').css({
                    display:'block'
                })
                $('#basket_continue_shopping').css({
                    display:'none'
                })
            }


            input.val(data.quantity).removeClass('discreet')

           // console.log(data)


        //    $(element).removeClass('fa-spinner fa-spin fa-save').addClass('fa-plus')


           // console.log($(element))

         //   $('#header_order_total_amount').html(data.data.order_total)
         //   $('#header_order_products').html(data.data.ordered_products_number)


            for (var key in data.metadata.class_html) {
                $('.' + key).html(data.metadata.class_html[key])
            }



            if(settings.basket!=undefined){
                $(element).closest('tr').find('.item_amount').html(data.to_charge)
            }

           //


            if (data.quantity > 0) {

            } else {

            }

            if (data.quantity == 0) data.quantity = ''






            //input.val(data.quantity).attr('ovalue', data.quantity).prop('readonly', false);

        } else if (data.state == 201) {

            window.location.href = 'waiting_payment_confirmation.php?referral_key=' + $('#webpage_data').data('webpage_key')


        }else if (data.state == 400) {

            $(element).removeClass('fa-spinner fa-spin fa-disk').addClass(_icon)

            swal(data.msg)

        }


    })

}



function validate_signed_integer(value, max_value) {

    if (!$.isNumeric(value)) {
        return {
            class: 'invalid', type: 'not_integer'
        }
    }

    if (value > max_value) {
        return {
            class: 'invalid',

            type: 'too_big'
        }
    }

    if (value < 0) {
        return {
            class: 'invalid',

            type: 'negative'
        }
    }
    if (Math.floor(value) != value) {


        return {
            class: 'invalid',

            type: 'not_integer'
        }
    }

    return false
}







