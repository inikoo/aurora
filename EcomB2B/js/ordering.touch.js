/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 September 2017 at 10:04:14 GMT+8, Kuala Lumpur, Malaysia
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

        $('#page-transitions').addClass('hide')

        form.css('top', '50%').css('left', '50%').css('margin-top', -form.outerHeight()/2).css('margin-left', -form.outerWidth()/2).fadeIn();

        return false;
    });

    $('.modal-closer').on('click', function()
    {

        $('#page-transitions').removeClass('hide')
        $('#sky-form-modal-overlay').fadeOut();
        $('.sky-form-modal').fadeOut();

        return false;
    });
})


$(document).on('click', '.profile_button', function (evt) {


    $('.profile_button').addClass(' border-black color-gray-light ').removeClass(' border-orange-dark ').find('i').addClass('color-gray-light').removeClass('color-orange-dark')

    $(this).removeClass(' border-black color-gray-light ').addClass(' border-orange-dark ').find('i').removeClass('color-gray-light').addClass('color-orange-dark')

    $('.profile_block').addClass('hide')
    $('#'+$(this).data('tab')).removeClass('hide')
});



$(document).on('click', '.payment_method_button', function (evt) {


    $('.payment_method_button').addClass('discreet bg-gray-light border-gray-dark').removeClass('bg-blue-light border-blue-dark').css({ 'opacity':.2})

    $(this).removeClass('discreet bg-gray-light border-gray-dark').addClass('bg-blue-light border-blue-dark').css({ 'opacity':1})

$('.payment_method_block').addClass('hide')
$('#'+$(this).data('tab')).removeClass('hide')
});



$(document).on('click', '.ordering_button', function (evt) {

    save_item_qty_change(this)

});



$(document).on('input propertychange', '.order_qty', function (evt) {

  //  if ($(this).val() == $(this).attr('ovalue')) {
  //      $(this).closest('span').find('i').removeClass('fa-floppy-o exclamation-circle error').addClass('fa-plus')

//    } else {


    console.log(validate_signed_integer($(this).val(), 4294967295) )

        if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {
            //$(this).closest('span').find('i').removeClass('fa-plus exclamation-circle error').addClass('fa-floppy-o')

            $(this).removeClass('error')
            save_item_qty_change(this)
        } else {

            $(this).addClass('error')
            //$(this).closest('span').find('i').removeClass('fa-plus fa-floppy-o').addClass('fa-exclamation-circle error')

        }
 //   }
});


function save_item_qty_change(element) {

    //$(element).addClass('fa-spinner fa-spin')

    var order_div= $(element).closest('div')
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

        var _icon='fa-floppy-o'

    }

    if (qty == '') qty = 0;

    var settings = order_div.data('settings')


    var request = 'ar_web_basket.php?tipo=update_item&product_id=' + settings.pid + '&order_key=' + $('#webpage_data').attr('order_key') + '&qty=' + qty + '&webpage_key=' + $('#webpage_data').attr('webpage_key') + '&page_section_type=Basket'

console.log(request)

    $.getJSON(request, function (data) {
        input.prop('readonly', false);

        if (data.state == 200) {



            input.val(data.quantity).removeClass('discreet')

            console.log(data)


        //    $(element).removeClass('fa-spinner fa-spin fa-floppy-o').addClass('fa-plus')


            console.log($(element))

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

            window.location.href = 'waiting_payment_confirmation.php?referral_key=' + $('#webpage_data').attr('webpage_key')


        }else if (data.state == 400) {

            $(element).removeClass('fa-spinner fa-spin fa-disk').addClass(_icon)

            swal(data.msg)

        }


    })

}



