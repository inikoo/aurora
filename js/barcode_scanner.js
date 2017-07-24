/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 January 2017 at 11:54:31 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function scanned_barcode(value) {


    console.log(state.object)

    if (state.object == 'delivery_note') {
        scanned_barcode_in_delivery_note(value)
    }else if (state.object == 'supplierdelivery') {
        scanned_barcode_in_supplier_delivery_note(value)
    }

}


function scanned_barcode_in_supplier_delivery_note(value) {




    console.log(state.tab)

    if (state.tab == 'supplier.delivery.items') {





            var picking_barcode_feedback = $('#booking_in_barcode_feedback')
            picking_barcode_feedback.find('.fa-barcode').addClass('valid_save')
            window.setTimeout(function () {
                picking_barcode_feedback.find('.fa-barcode').removeClass('valid_save')
            }, 200);

            var found = false;

            $('#table .part_sko_item').each(function (i, obj) {


                console.log('bad >' + $(obj).attr('barcode') + '< >' + value + '<')
                if ($(obj).attr('barcode') == value) {
                    console.log($(obj).attr('barcode') + ' ' + value)
                    var settings = $(obj).closest('span').data('settings')
                    console.log(settings)
                    $('#booking_in_barcode_part_reference').html(settings.reference)
                    $('#booking_in_barcode_part_description').html(atob(settings.description))
                    $('#booking_in_barcode_part_image_src').html(settings.image_src)
                    found=true;
                    return false
                }
            });

            if(found){

                picking_barcode_feedback.find('.barcode_found').removeClass('hide')
                picking_barcode_feedback.find('.barcode_not_found').addClass('hide')


            }else{
                picking_barcode_feedback.find('.barcode_not_found').removeClass('hide')
                picking_barcode_feedback.find('.barcode_found').addClass('hide')
                picking_barcode_feedback.find('.not_found_barcode_number').html(value)


                var request = '/ar_find.php?tipo=find_object&scope=part&field=barcode&parent=warehouse&parent_key='+state.current_warehouse+'&query=' + value+'&state=' + JSON.stringify(state)
                console.log(request)
                $.getJSON(request, function (data) {


if(data.results==1){
       $('#booking_in_barcode_feedback .assign_barcode').addClass('hide')
    $('#booking_in_barcode_feedback .find_outside_order').removeClass('hide')

}else{

    $('#booking_in_barcode_feedback .assign_barcode').removeClass('hide')
    $('#booking_in_barcode_feedback .find_outside_order').addClass('hide')

}



                });





            }



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
                    return false
                }
            });


        }


    }

}