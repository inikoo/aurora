/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2018 at 20:28:46 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/

function open_part_stock_check() {

    $('.Part_Cost_in_Warehouse_info_set_up').addClass('invisible')

    $('.edit_stock_close_button').removeClass('hide')

    $('.showcase_component').addClass('super_discreet')
    process_edit_stock()


    $('.location_to_be_disassociated_icon').addClass('hide')



    $('.location_code').removeClass('link')
    $('.undo_unlink_operations').css('width','0px')



    $('.picking_location_note').addClass('hide')

    $('.move_trigger').addClass('hide')



   // $('.last_audit_days').addClass('hide')
   // $('.set_as_audit').addClass('hide')
    $('.recommendations').addClass('hide')
    $('.picking_location_icon').addClass('hide')
  //  $('.disassociate_info').removeClass('hide')


    $('input.stock').removeClass('hide')



    $('#locations_table .formatted_stock').addClass('hide')
    $('#locations_table .stock_input').removeClass('hide')




    $('.edit_stock_open_button').addClass('hide')
    $('.part_locations_stock_check_button').removeClass('hide')



}




function set_as_audit(element) {

    if ($(element).hasClass('super_discreet_on_hover')) {
        $(element).removeClass('super_discreet_on_hover')
        $(element).closest('tr').find('input').prop('readonly', true)
        $(element).closest('tr').find('.add_note').removeClass('invisible').addClass('visible')

    } else {
        $(element).addClass('super_discreet_on_hover')
        $(element).closest('tr').find('input').prop('readonly', false)
        $(element).closest('tr').find('.add_note').addClass('invisible').removeClass('visible')

    }

    process_edit_stock()
    part_locations_stock_check_look_for_changes()

}


function stock_field_changed(element) {
    stock_changed(element)
    process_edit_stock()
    part_locations_stock_check_look_for_changes()
}

function stock_changed(element) {


    var value = element.val()


    if (value == '') {
        value = 0
    }


    var validation = validate_number(value, 0, 999999999)

    if (!validation) {
        validation = {
            class: 'valid', type: 'valid'
        }
    }

    element.closest('tr').removeClass('valid invalid').addClass(validation.class)


    if (element.attr('ovalue') != value) {

        if (validation.class == 'invalid') {
            element.closest('tr').find('.stock_change').html('')
            element.closest('tr').find('.set_as_audit').addClass('super_discreet_on_hover').addClass('hide')

        } else {

            // console.log(value)
            // console.log(element.attr('ovalue'))

            var _diff = parseFloat(value) - parseFloat((element.attr('ovalue') == '' ? 0 : element.attr('ovalue')))


            var diff = _diff.toFixed(2).replace(/[.,]00$/, "")

            if (_diff > 0) {
                diff = '+' + diff
            }

            var change = '(' + diff + ')';
            element.closest('tr').find('.stock_change').html(change)

            element.closest('tr').find('.set_as_audit').addClass('super_discreet_on_hover').addClass('hide')

            element.closest('tr').find('.add_note').removeClass('super_discreet invisible').addClass('visible')


        }

    } else {
        element.closest('tr').find('.stock_change').html('')
        element.closest('tr').find('.set_as_audit').removeClass('hide')
        element.closest('tr').find('.add_note').addClass('very_discreet_on_hover invisible').removeClass('visible')

    }



}



function get_part_locations_edit_get_modified_stock_check_locations(){


    locations_to_confirm_stock=[]
    locations_to_update_stock=[]

    $('.locations  .set_as_audit:not(.super_discreet_on_hover)').each(function (i, obj) {

        locations_to_confirm_stock.push($(obj).closest('tr').attr('location_key'));


    })

    $('.locations  input.stock').each(function (i, obj) {

        if($(obj).attr('ovalue')!=$(obj).val()){
            locations_to_update_stock.push($(obj).closest('tr').attr('location_key'));

        }


    })

    //console.log(locations_to_remove)

    return { 'to_confirm':locations_to_confirm_stock, 'to_update':locations_to_update_stock}

}




function part_locations_stock_check_look_for_changes(){

    var changes=get_part_locations_edit_get_modified_stock_check_locations();



    if(changes.to_confirm.length==0 && changes.to_update.length==0){
        $('.part_locations_stock_check_button').removeClass('changed valid')

    }else{

        $('.part_locations_stock_check_button').addClass('changed valid')

        $('.part_locations_stock_check_button span').html($('.part_locations_stock_check_button span').data('labels').save_changes)




    }


}


function save_part_locations_stock_check(element){

    if (!$(element).hasClass('valid')) {

        return;
    }


    var icon=$(element).find('i');

    icon.removeClass('fa-cloud').addClass('fa-spinner fa-spin ')


    var stock_to_update=[]

    $('.locations  input.stock').each(function (i, obj) {

        if($(obj).attr('ovalue')!=$(obj).val()){
            stock_to_update.push(
                {
                    location_key:$(obj).closest('tr').attr('location_key'),
                    stock:$(obj).val(),
                    note:$(obj).closest('tr').find('.add_note').data('note'),
                    stock_confirm:false
                }

            );

        }else{

           if(! $(obj).closest('tr').find('.set_as_audit').hasClass('super_discreet_on_hover')){
               stock_to_update.push(
                   {
                       location_key:$(obj).closest('tr').attr('location_key'),
                       stock:$(obj).val(),
                       note:$(obj).closest('tr').find('.add_note').data('note'),
                       stock_confirm:true
                   }

               );

           }

        }


    })



    var form_data = new FormData();

    form_data.append("tipo", 'edit_part_stock_check')
    form_data.append("part_sku", $('#locations_table').attr('part_sku'))
    form_data.append("stock_to_update", JSON.stringify(stock_to_update))


    var request = $.ajax({

        url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {

        icon.addClass('fa-cloud').removeClass('fa-spinner fa-spin ')

        if(data.state==200){
            if (state.tab == 'part.stock.transactions' || state.tab == 'part.stock' || state.tab == 'part.locations') {
                rows.fetch({
                    reset: true
                });
            }

            $('#part_locations').html(data.part_locations)

            $('.edit_stock_open_button').removeClass('hide')
            $('.edit_stock_close_button').addClass('hide')
            $('.part_locations_stock_check_button').addClass('hide')

            $('.showcase_component').removeClass('super_discreet')


            for (var key in data.updated_fields) {

  


                $('.' + key).html(data.updated_fields[key])
            }


            if (data.Part_Unknown_Location_Stock != 0) {
                $('#unknown_location_tr').removeClass('hide')
            }

            $('#Part_Unknown_Location_Stock').attr('qty',data.Part_Unknown_Location_Stock)


            $('.part_locations_edit_locations_button span').html($('.part_locations_edit_locations_button span').data('labels').no_change)
            $('.part_locations_edit_locations_button').removeClass('changed valid')
        }else{
            swal(data.status, data.msg, "error")
        }





    })

    request.fail(function (jqXHR, textStatus) {
    });

}