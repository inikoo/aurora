/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2017 at 12:23:41 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function cancel_payment(element,payment_key) {


    if($(element).hasClass('super_discreet')){
        return;
    }

    var tr = $(element).closest('tr')

    if (tr.hasClass('deleting_tr') || tr.hasClass('deleted_tr')) {
        return;
    }

    tr.addClass('deleting_tr')

    // tr.addClass('deleted_tr')
    //return;


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'object_operation')
    ajaxData.append("operation", 'delete')
    ajaxData.append("object", 'Payment')
    ajaxData.append("key", payment_key)



    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                // todo: update current view instead of reload page
                location.reload();

               // tr.removeClass('deleting_tr').addClass('deleted_tr')
               // $('#delete_payment_button_' + payment_key).html(data.msg).closest('td').addClass('hide').html('')

            } else if (data.state == '400') {
                tr.removeClass('deleting_tr')
                swal(data.msg);
            }



        }, error: function () {

        }
    });



}
