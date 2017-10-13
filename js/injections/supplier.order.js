/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 June 2016 at 12:46:38 GMT+8, Legian, Bali, Indonesia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(document).on('input propertychange', '.order_qty', function (evt) {


    if ($(this).val() == $(this).attr('ovalue')) {
        $(this).closest('span').find('i.plus').removeClass('fa-cloud exclamation-circle error').addClass('fa-plus')
        $(this).closest('span').find('i.minus').removeClass('invisible')

    } else {

        if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {
            $(this).closest('span').find('i.plus').removeClass('fa-plus exclamation-circle error').addClass('fa-cloud')
            $(this).closest('span').find('i.minus').addClass('invisible')

            $(this).addClass('discreet')
        } else {
            $(this).closest('span').find('i.plus').removeClass('fa-plus fa-cloud').addClass('fa-exclamation-circle error')
            $(this).closest('span').find('i.minus').addClass('invisible')


        }
    }


});






