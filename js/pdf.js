/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2018 at 16:32:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


$(document).on('mousedown', '.proforma_buttonxxx', function (evt) {

    $('.proforma_dialog').removeClass('hide').offset({
        top: $(this).offset().top-1, left: $(this).offset().left
    })

})


function show_pdf_settings_dialog(element,asset, asset_key){

    var container=$(element).closest('.pdf_label_container')
    console.log($(element))
    console.log(container)
    var anchor_x=container.find('.left_pdf_label_mark')
    var anchor_y=container.find('.top_pdf_label_mark')


    $('.pdf_asset_dialog.'+asset).removeClass('hide').data('asset',asset).data('asset_key',asset_key).offset({
        top: $(anchor_y).offset().top-1, left: $(anchor_x).offset().left
    })



}


function download_pdf_from_ui(element,asset,asset_key){
    $(element).data('asset',asset)
    $(element).data('asset_key',asset_key)
    download_pdf(element)
}

function download_pdf(element) {

    var dialog = $(element)



    var args = '';
    $('.pdf_option', dialog).each(function (i, obj) {


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



    switch (dialog.data('asset')) {
        case 'proforma':
            window.open('/pdf/proforma.pdf.php?id=' +dialog.data('asset_key')+ args, '_blank');

            break;
        case 'invoice':
            window.open('/pdf/invoice.pdf.php?id=' + dialog.data('asset_key') + args, '_blank');

            break;
        case 'invoice_from_list':
            window.open('/pdf/invoice.pdf.php?id=' + dialog.data('asset_key') + args, '_blank');

            break;
    }


}
