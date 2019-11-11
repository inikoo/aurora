/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2015 at 12:20:32 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


$(function () {


    $('.modal-opener').on('click', function () {
        if (!$('#sky-form-modal-overlay').length) {
            $('body').append('<div id="sky-form-modal-overlay" class="sky-form-modal-overlay"></div>');
        }

        $('#sky-form-modal-overlay').on('click', function () {
            $('#sky-form-modal-overlay').fadeOut();
            $('.sky-form-modal').fadeOut();
        });

        form = $($(this).attr('href'));
        $('#sky-form-modal-overlay').fadeIn();
        form.css('top', '50%').css('left', '50%').css('margin-top', -form.outerHeight() / 2).css('margin-left', -form.outerWidth() / 2).fadeIn();

        return false;
    });

    $('.modal-closer').on('click', function () {
        $('#sky-form-modal-overlay').fadeOut();
        $('.sky-form-modal').fadeOut();

        return false;
    });


    $(document).on('click', '.payment_method_button', function (evt) {
        $('.payment_method_button').addClass('discreet bg-gray-light border-gray-dark').removeClass('bg-blue-light border-blue-dark').css({'opacity': .2})
        $(this).removeClass('discreet bg-gray-light border-gray-dark').addClass('bg-blue-light border-blue-dark').css({'opacity': 1})
        $('.payment_method_block').addClass('hide')
        $('#' + $(this).data('tab')).removeClass('hide')
    });


    $(document).on('click', '.ordering_button', function (evt) {

        save_item_qty_change(this)

    });


    $(document).on('mouseenter', '.order_row .label', function (evt) {
        var input = $(this).closest('.order_row').find('.order_input');
        if (input.val() == '') {
            input.val(1)
        }
    });


    $(document).on('mouseleave', '.order_row .label', function (evt) {
        var input = $(this).closest('.order_row').find('.order_input');
        if (input.data('ovalue') == '' && !input.is('[readonly]')) {
            input.val('')
        }
    });


    $(document).on('click', '.order_row .label', function (evt) {

        var element = $(this);
        var order_row = $(this).closest('.order_row');
        if ($(this).find('i').hasClass('fa-spinner')) return;


        var input = order_row.find('.order_input')

        var order_qty = input.val()
        $(this).find('i').removeClass('fa-hand-pointer').addClass('fa-spinner fa-spin  ')
        input.prop('readonly', true);


        if (order_qty > 0) {
            order_row.addClass('ordered').removeClass('empty')
        } else {
            //   order_row.removeClass('ordered').addClass('empty')

        }


        var request = 'ar_web_basket.php?tipo=update_item&product_id=' + $(this).closest('.product_container').data('product_id') + '&qty=' + order_qty + '&webpage_key=' + $('#webpage_data').data('webpage_key') + '&page_section_type=Family'


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


                /*
                                if(data.order_empty){
                                    $('#basket_go_to_checkout').css({
                                        display:'none'
                                    })
                                }else{
                                    $('#basket_go_to_checkout').css({
                                        display:'normal'
                                    })
                                }




                                for (var key in data.discounts_data) {
                                    $('#transaction_deal_info_'+key).html(data.discounts_data[key]['deal_info'])
                                    $('#transaction_item_net_'+key).html(data.discounts_data[key]['item_net'])

                                    //$('.' + key).html(data.metadata.class_html[key])
                                }
                */


                if (data.analytics.action != '') {


                    ga('auTracker.ec:addProduct', data.analytics.product_data);
                    ga('auTracker.ec:setAction', data.analytics.action);
                    ga('auTracker.send', 'event', 'UX', 'click', data.analytics.event);
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



})


$(document).on('input propertychange', '.order_qty', function (evt) {


    if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {
        $(this).closest('span').find('i.minus').addClass('hide')
        $(this).closest('span').find('i.plus').removeClass('fa-plus fa-exclamation-circle error').addClass('fa-save')
        $(this).addClass('discreet')
    } else {
        $(this).closest('span').find('i.minus').addClass('hide')
        $(this).closest('span').find('i.plus').removeClass('fa-plus fa-save').addClass('fa-exclamation-circle error')

    }

});


function save_item_qty_change(element) {


    if ($(element).hasClass('fa-exclamation-circle')) {
        return;
    }


    $(element).addClass('fa-spinner fa-spin')

    var input = $(element).closest('span').find('input')

    input.prop('readonly', true);

    if ($(element).hasClass('fa-plus')) {


        var _icon = 'fa-plus'

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }

        input.val(qty).addClass('discreet')

    } else if ($(element).hasClass('fa-minus')) {


        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }

        input.val(qty).addClass('discreet')

        var _icon = 'fa-minus'

    } else {
        qty = parseFloat(input.val())

        var _icon = 'fa-save'

    }

    if (qty == '') qty = 0;

    var settings = $(element).closest('span').data('settings')


    var request = 'ar_web_basket.php?tipo=update_item&product_id=' + settings.item_key + '&qty=' + qty + '&webpage_key=' + $('#webpage_data').data('webpage_key') + '&page_section_type=Basket'


    $.getJSON(request, function (data) {
        input.prop('readonly', false).removeClass('discreet');

        if (data.state == 200) {


            $(element).closest('span').find('i.plus').removeClass('fa-spinner fa-spin fa-save').addClass('fa-plus')
            $(element).closest('span').find('i.minus').removeClass('hide fa-spinner fa-spin').addClass('fa-minus')


            if (data.order_empty) {
                $('#basket_go_to_checkout').css({
                    display: 'none'
                })
            } else {
                $('#basket_go_to_checkout').css({
                    display: 'block'
                })
            }


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

                $('.' + key).addClass(data.metadata.add_class[key])
            }
            for (var key in data.metadata.remove_class) {
                $('.' + key).removeClass(data.metadata.remove_class[key])
            }


            $(element).closest('tr').find('.item_amount').html(data.to_charge)


            if (data.quantity > 0) {

            } else {

            }

            if (data.quantity == 0) data.quantity = ''

            for (var key in data.discounts_data) {

                $('#transaction_deal_info_' + key).html(data.discounts_data[key]['deal_info'])
                $('#transaction_item_net_' + key).html(data.discounts_data[key]['item_net'])

                //$('.' + key).html(data.metadata.class_html[key])
            }


            if (data.analytics.action != '') {


                ga('auTracker.ec:addProduct', data.analytics.product_data);
                ga('auTracker.ec:setAction', data.analytics.action);
                ga('auTracker.send', 'event', 'UX', 'click', data.analytics.event);
            }


            for (var key in data.metadata.deleted_otfs) {
                $('#transaction_item_net_' + data.metadata.deleted_otfs[key]).closest('tr').remove()
            }

            console.log(data.metadata.deleted_otfs.length)

            if (data.metadata.new_otfs.length > 0 || data.metadata.deleted_otfs.length > 0) {


                var request = 'ar_web_basket.php?tipo=get_items_html&device_prefix=' + $('body').data('device_prefix')


                $.getJSON(request, function (data2) {


                    if (data2.state == 200) {

                        $('.basket_order_items').html(data2.html)
                    }

                })

            }


            //input.val(data.quantity).data('ovalue', data.quantity).prop('readonly', false);

        } else if (data.state == 201) {

            // window.location.href = 'waiting_payment_confirmation.php?referral_key=' + $('#webpage_data').data('webpage_key')


        } else if (data.state == 400) {

            $(element).removeClass('fa-spinner fa-spin fa-disk').addClass(_icon)

            swal(data.msg)

        }


    })

}

