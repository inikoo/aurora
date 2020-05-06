/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2018 at 16:32:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


function show_pdf_settings_dialog(element, asset, asset_key, type) {
    "use strict"

    $('.pdf_asset_dialog').addClass('hide')

    const container = $(element).closest('.pdf_label_container');
    const anchor_x = container.find('.left_pdf_label_mark');
    const anchor_y = container.find('.top_pdf_label_mark');
    $('.pdf_asset_dialog.' + type).removeClass('hide').data('asset', asset).data('asset_key', asset_key).data('type', type).offset({
        top: $(anchor_y).offset().top - 1, left: $(anchor_x).offset().left
    })


}


function download_pdf_from_ui(element, asset, asset_key,type) {
    $(element).data('asset', asset).data('asset_key', asset_key).data('type', type)

    download_pdf(element)
}

function download_pdf(element) {
    "use strict"
    const dialog = $(element);
    let args = '';

    $('.pdf_option', dialog).each(function (i, obj) {

        let icon;
        switch ($(obj).data('field')) {
            case 'locale':
                icon = $(obj).find('i');
                if (icon.hasClass('fa-check-square')) {
                    args += '&locale=' + icon.data('value')
                }

                break;
            default:

                icon = $(obj).find('i');

                if (icon.hasClass('fa-check-square')) {

                    args += '&' + $(obj).data('field') + '=1'

                }


                break;

        }


    })


    switch (dialog.data('type')) {
        case 'proforma':
            window.open('/pdf/proforma.pdf.php?id=' + dialog.data('asset_key') + args, '_blank');

            break;
        case 'invoice':
            window.open('/pdf/invoice.pdf.php?id=' + dialog.data('asset_key') + args, '_blank');

            break;
        case 'unit':
        case 'sko':
        case 'carton':

            const settings=get_pdf_label_options(dialog);

            let url='/asset_label.pdf.php?object=' + dialog.data('asset') +'&key=' + dialog.data('asset_key')+ '&type=' +dialog.data('type');



            url+='&'+jQuery.param(settings);


            window.open(url, '_blank');

            break;
    }


}

function select_option_from_asset_labels(element) {
    "use strict"

    const options = $(element).closest('.options');
    options.find('.option').removeClass('selected');

    $(element).addClass('selected')

    const sheet_options=$('.pdf_asset_dialog  .options.set_ups .option.sheet')

    switch (options.data('type')) {
        case 'size':
            sheet_options.each(function (i, obj) {
                if ($(obj).hasClass($(element).data('value'))) {
                    $(obj).removeClass('hide')
                } else {
                    $(obj).addClass('hide')
                }
            });
            break;
        case 'set_up':
            if($(element).hasClass('sheet')){
                sheet_options.addClass('selected')
            }else{
                sheet_options.removeClass('selected')
                $(element).addClass('selected')

            }
            break;


    }
    $('.pdf_asset_dialog .save').addClass('changed valid')


}

function check_pdf_asset_label_field_value(element) {
    "use strict"
    const icon = $(element).find('i');

    if (icon.hasClass('fa-check-square')) {
        icon.removeClass('fa-check-square').addClass('fa-square').next('span').addClass('discreet')
        if ($(element).data('field') === 'with_custom_text') {
            $('.pdf_asset_dialog .custom_text_tr').addClass('hide')
        }

    } else if (icon.hasClass('fa-square')) {
        icon.removeClass('fa-square').addClass('fa-check-square').next('span').removeClass('discreet')
        if ($(element).data('field') === 'with_custom_text') {
            $('.pdf_asset_dialog .custom_text_tr').removeClass('hide')
        }

    }

    $('.pdf_asset_dialog .save').addClass('changed valid')

}


function save_pdf_asset_label_options(element) {
    "use strict"

    if($(element).hasClass('valid')){
        retuurn;
    }

    $(element).removeClass('valid changed').addClass('fa-spin fa-spinner')

    const dialog = $(element).closest('.pdf_asset_dialog')
    const options = get_pdf_label_options(dialog)


    const ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", dialog.data('asset'))
    ajaxData.append("key", dialog.data('asset_key'))
    ajaxData.append("field", 'label_' + dialog.data('type'))
    ajaxData.append("value", JSON.stringify(options))

    $.ajax({
        url: '/ar_edit.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

        complete: function () {

        }, success: function (data) {
            if (data.state === 200) {


                if (data.ok) {


                } else {


                }

            }


        }, error: function () {

        }
    }).then(r => {
        console.log(r)
        $(element).removeClass('fa-spin fa-spinner')

    })


}

$(document).on('input propertychange', '.pdf_asset_dialog textarea', function () {
    $('.pdf_asset_dialog .save').addClass('changed valid')

});

function get_pdf_label_options(element) {
    "use strict"

    let options = {};

    $('.pdf_option', element).each(function (i, obj) {

        options[$(obj).data('field')] = !!$(obj).find('i').hasClass('fa-check-square');
    })

    $('.options', element).each(function (i, objs) {
        $('.option', objs).each(function (j, obj) {


            if (!$(obj).hasClass('hide') && $(obj).hasClass('selected')) {
                options[$(obj).closest('.options').data('type')] = $(obj).data('value')
                return false;
            }

        })
    })

    options['custom_text'] = $(element).find('textarea.custom_text').val()

    return options;

}