/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  14 April 2016 at 10:43:39 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function post_validate_field(validation, field) {
    console.log(field)
    console.log(validation)

    var info = '';

    if (field == 'Supplier_Part_Unit_Extra_Cost') {

        var cost = $('#Supplier_Part_Unit_Cost').val()

        var value = $('#' + field).val()



        var regex = /\%$/
        if (regex.test(value)) {
            var percentage = parseFloat(value)
            var amount = cost * (1 + percentage / 100)
        } else {
            var amount = parseFloat(value)
            var percentage = parseFloat(100*((value / cost)-1))


        }

        info=amount+' '+percentage.toFixed(2) + "%"

    }


    if (validation.class == 'valid' && info != '') {
        $('#' + field + '_info').removeClass('hide').html(info)
    } else {
        $('#' + field + '_info').addClass('hide').html('')

    }


}
