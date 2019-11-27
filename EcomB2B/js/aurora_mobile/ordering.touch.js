/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 September 2017 at 10:04:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


$(document).on('input propertychange', '.order_qty', function (evt) {



    $(this).closest('div').find('.ordering_button.one_less').addClass('invisible')
    $(this).closest('div').find('.ordering_button.add_one').addClass('hide')
    $(this).closest('div').find('.save').removeClass('hide')


        if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {

            $(this).removeClass('error')
        } else {
            $(this).addClass('error')

        }
});


function save_item_qty_change(element) {

    if ($(element).hasClass('fa-exclamation-circle')) {
        return;
    }


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

        var _icon='fa-minus-circle'

    }
    else {
        qty = parseFloat(input.val())

        var _icon='fa-save'

    }

    $(element).addClass('fa-circle-notch fa-spin').removeClass(_icon)

    if (qty == '') qty = 0;

    var settings = order_div.data('settings')


    var request = 'ar_web_update_order_item.php?tipo=update_order_item&product_id=' + settings.pid +'&qty=' + qty + '&webpage_key=' + $('#webpage_data').data('webpage_key') + '&page_section_type=Basket'


    //console.log(request)

    $.getJSON(request, function (data) {
        input.prop('readonly', false);
        order_div.removeClass('wait')

        if (data.state == 200) {

            $(element).removeClass('fa-circle-notch fa-spin').addClass(_icon)

            if(_icon=='fa-save'){


                order_div.find('.ordering_button.one_less').removeClass('invisible')
                order_div.find('.ordering_button.add_one').removeClass('hide')
                order_div.find('.ordering_button.save').addClass('hide')


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

            for (var key in data.metadata.class_html) {
                $('.' + key).html(data.metadata.class_html[key])
            }
            for (var key in data.metadata.hide) {
                $('.' + data.metadata.hide[key]).addClass('hide')
            }

            for (var key in data.metadata.show) {
                $('.' + data.metadata.show[key]).removeClass('hide')
            }
            for (var key in data.metadata.add_class) {

                $('.' + key).addClass( data.metadata.add_class[key])
            }
            for (var key in data.metadata.remove_class) {
                $('.' + key).removeClass( data.metadata.remove_class[key])
            }



            if(settings.basket!=undefined){
                $(element).closest('tr').find('.item_amount').html(data.to_charge)
            }

           //


            if (data.quantity > 0) {

            } else {

            }

            if (data.quantity == 0) data.quantity = ''


            if(data.analytics.action!=''){


                ga('auTracker.ec:addProduct', data.analytics.product_data);
                ga('auTracker.ec:setAction', data.analytics.action);
                ga('auTracker.send', 'event', 'UX', 'click',data.analytics.event);
            }

            for (var key in data.metadata.deleted_otfs) {
                $('#transaction_item_net_' + data.metadata.deleted_otfs[key]).closest('tr').remove()
            }
            if(data.metadata.new_otfs.length>0 ||  data.metadata.deleted_otfs.length>0){



                var request = 'ar_web_basket.php?tipo=get_items_html&device_prefix=' + $('body').data('device_prefix')


                $.getJSON(request, function (data2) {


                    if (data2.state == 200) {

                        $('.basket_order_items').html(data2.html)
                    }

                })

            }



        }else if (data.state == 400) {

            $(element).removeClass('fa-circle-notch  fa-spin').addClass(_icon)

            swal(data.msg)

        }


    })
    validate_signed_integer
}








