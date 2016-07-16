/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 July 2016 at 13:09:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(document).on('input propertychange', '.received_qty', function(evt) {

delivery_qty_changed(this)
  
});


function delivery_qty_changed(element){
  if ($(element).val() == $(element).attr('ovalue')) {
        $(element).closest('span').find('i').removeClass('fa-cloud exclamation-circle error').addClass('invisible')

    } else {

        if (!validate_signed_integer($(element).val(), 4294967295) || $(element).val() == '') {
            $(element).closest('span').find('i').removeClass('fa-plus exclamation-circle error invisible').addClass('fa-cloud')
            $(element).addClass('discreet')
        } else {
            $(element).closest('span').find('i').removeClass('fa-plus fa-cloud invisible').addClass('fa-exclamation-circle error')

        }
    }
}

function copy_qty(element){

var qty=$(element).data('metadata').qty
console.log(qty)

var input=$(element).closest('tr').find('input.received_qty')

input.val(qty)

delivery_qty_changed(input)
}






