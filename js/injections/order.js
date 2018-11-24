/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 July 2017 at 13:41:31 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo
 Version 3.0*/

$(document).on('input propertychange', '.order_qty', function (evt) {

    if ($(this).val() == $(this).attr('ovalue')) {
        $(this).closest('span').find('i.plus').removeClass('fa-cloud fa-exclamation-circle error').addClass('fa-plus')
        $(this).closest('span').find('i.minus').removeClass('invisible')

    } else {

        if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {
            $(this).closest('span').find('i.plus').removeClass('fa-plus fa-exclamation-circle error').addClass('fa-cloud')
            $(this).closest('span').find('i.minus').addClass('invisible')

            $(this).addClass('discreet')
        } else {
            $(this).closest('span').find('i.plus').removeClass('fa-plus fa-cloud').addClass('fa-exclamation-circle error')
            $(this).closest('span').find('i.minus').addClass('invisible')


        }
    }
});








