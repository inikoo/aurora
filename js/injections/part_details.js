/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  26 April 2016 at 16:48:33 GMT+8, Ubud, Bali, Indonesia
 Copyright (c) 2015, Inikoo
 Version 3.0*/



function post_update_field(data) {

    if (data.value != undefined) {
        if (data.field == 'Part_Barcode_Number') {

            if (data.value == '') {
                $('#barcode_data').addClass('hide')
            } else {
                $('#barcode_data').removeClass('hide')

                if(data.barcode_key){
                    $('#barcode_data').find('.barcode_labels').removeClass('hide')
                    $('#barcode_data').find('td.label i').addClass('button').attr("onclick","change_view('inventory/barcode/"+data.barcode_key+"')")
                }else{
                    $('#barcode_data').find('.barcode_labels').addClass('hide')
                    $('#barcode_data').find('td.label i').removeClass('button').attr("onclick","return false")

                }

            }


        }

    }
}
