/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2018 at 16:32:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


$(document).on('mousedown', '.proforma_button', function (evt) {

    $('.proforma_dialog').removeClass('hide').offset({
        top: $(this).offset().top-1, left: $(this).offset().left
    })

})


function show_pdf_invoice_dialog(element,invoice_key){
    var anchor_x=$(element).prev('.pdf_link')
    var anchor_y=$(element).closest('.node')
    $('.pdf_invoice_dialog').removeClass('hide').offset({
        top: $(anchor_y).offset().top-1, left: $(anchor_x).offset().left
    }).data('invoice_key',invoice_key)
}


function download_pdf_from_list(invoice_key,element){

    $(element).closest('.options_dialog').data('invoice_key',invoice_key)
    download_pdf(element)
}

function download_pdf(element) {

    var dialog = $(element).closest('.options_dialog')
    var data = dialog.data('data')

    var args = '';
    $('.pdf_option', dialog).each(function (i, obj) {
        console.log($(obj).data('field'))

        switch ($(obj).data('field')) {
            case 'locale':
                var icon = $(obj).find('i')
                if (icon.hasClass('fa-check-square')) {
                    args += '&locale=' + icon.data('value')
                }

                break;
            default:

                var icon = $(obj).find('i')

                if (icon.hasClass('fa-check-square')) {

                    args += '&' + $(obj).data('field')+'=1'

                }


                break;

        }


    })


    switch (data.type) {
        case 'proforma':
            window.open('/pdf/proforma.pdf.php?id=' + data.order_key + args, '_blank');

            break;
        case 'invoice':
            window.open('/pdf/invoice.pdf.php?id=' + dialog.invoice_key + args, '_blank');

            break;
        case 'invoice_from_list':
            window.open('/pdf/invoice.pdf.php?id=' + dialog.data('invoice_key') + args, '_blank');

            break;
    }


}
