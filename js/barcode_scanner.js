/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 January 2017 at 11:54:31 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function scanned_barcode(value) {

    //console.log(state)
    //console.log(state.object)

    if (state.object == 'delivery_note') {
        scanned_barcode_in_delivery_note(value)
    }


}

function scanned_barcode_in_delivery_note(value) {

    if (state.tab == 'delivery_note.items') {

        var selected_tab = $('.table_views .view.selected')


        if (selected_tab.attr('id') == 'view_picking_aid') {

            var picking_barcode_feedback = $('#picking_barcode_feedback')
            picking_barcode_feedback.find('.fa-barcode').addClass('valid_save')
            window.setTimeout(function () {
                picking_barcode_feedback.find('.fa-barcode').removeClass('valid_save')
            }, 200);

            var found = false;

            $('#table .picking').each(function (i, obj) {


               console.log('bad >' + $(obj).attr('barcode') + '< >' + value + '<')
                if ($(obj).attr('barcode') == value) {
                   console.log($(obj).attr('barcode') + ' ' + value)
                    var settings = $(obj).closest('span').data('settings')
                    console.log(settings)
                    $('#picking_barcode_part_reference').html(settings.reference)
                    $('#picking_barcode_part_description').html(atob(settings.description))
                    $('#picking_barcode_part_image_src').html(settings.image_src)
                }
            });


        }


    }

}