/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 November 2018 at 15:29:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/

function open_part_edit_locations() {

    $('.Part_Cost_in_Warehouse_info_set_up').addClass('invisible')

    $('.edit_stock_close_button').removeClass('hide')

    $('.showcase_component').addClass('super_discreet')
    process_edit_stock()
    open_add_location()

    $('.location_to_be_disassociated_icon').removeClass('hide')

    $('.location_code').removeClass('link')


    $('.undo_unlink_operations').removeClass('hide')

    $('.picking_location_note').addClass('hide')
    $('.last_audit_days').addClass('hide')
    $('.set_as_audit').addClass('hide')
    $('.recommendations').addClass('hide')
    $('.picking_location_icon').addClass('hide')
    $('.disassociate_info').removeClass('hide')
    $('input.stock').addClass('hide')



    $('#locations_table .formatted_stock').addClass('hide')
    $('#locations_table .stock_input').removeClass('hide')

    $('#add_location_tr').removeClass('hide')



    $('.edit_stock_open_button').addClass('hide')
    $('.part_locations_edit_locations_button').removeClass('hide')



}

function close_part_location_edit() {


    var form_data = new FormData();

    form_data.append("tipo", 'get_part_locations_html')
    form_data.append("part_sku", $('#locations_table').attr('part_sku'))


    var request = $.ajax({

        url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {


        $('#part_locations').html(data.part_locations)

        $('#add_location_tr').addClass('hide')
        $('.edit_stock_open_button').removeClass('hide')
        $('.edit_stock_close_button').addClass('hide')
        $('.edit_stock_save_button').addClass('hide')

        $('.Part_Cost_in_Warehouse_info_set_up').removeClass('invisible')


        $('.showcase_component').removeClass('super_discreet')

    })

    request.fail(function (jqXHR, textStatus) {
    });


}


function disassociate_location(element) {


    if($(element).hasClass('fa')){
        return
    }

    var tr = $(element).closest('tr')


    $(element).removeClass('very_discreet_on_hover fal').addClass('fa')


    tr.find('.location_to_be_disassociated_icon').removeClass('hide')
    tr.find('.disassociate_warning').removeClass('hide')
    tr.find('.disassociate_info').addClass('hide')
    tr.find('.location_code').addClass('strikethrough')
    tr.find('.add_note').removeClass('invisible')

    tr.find('.stock_change').html('(' + -1 * tr.find('.stock').val() + ')')
    tr.find('.undo_unlink_operations i').removeClass('hide')
    part_locations_edit_locations_look_for_changes();

}


function undo_disassociate_location(element) {

    var tr = $(element).closest('tr')

    tr.find('.location_to_be_disassociated_icon').addClass('very_discreet_on_hover fal').removeClass('fa')


    tr.find('.disassociate_warning').addClass('hide')
    tr.find('.disassociate_info').removeClass('hide')
    tr.find('.location_code').removeClass('strikethrough')
    tr.find('.add_note').addClass('invisible')

    tr.find('.stock_change').html('')
    tr.find('.undo_unlink_operations i').addClass('hide')
    part_locations_edit_locations_look_for_changes();
}


function open_add_location() {

    if ($('#add_location_label').hasClass('hide')) {
        close_add_location()
    } else {

        $('#add_location_label').addClass('hide')
        $('#add_location').removeClass('hide').focus()
        $('#save_add_location').removeClass('hide')
    }

}

function close_add_location() {

    $('#add_location_label').removeClass('hide')
    $('#add_location').addClass('hide').val('')
    $('#save_add_location').addClass('hide')

}


function delayed_on_change_add_location_field(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {

        get_locations_select()
    }, timeout));
}

function get_locations_select() {


    $('#add_location_tr').removeClass('invalid')

    var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent($('#add_location').val()) + '&scope=locations&state=' + JSON.stringify(state)

    $.getJSON(request, function (data) {


        if (data.number_results > 0) {
            $('#add_location_results_container').removeClass('hide').addClass('show')
        } else {


            $('#add_location_results_container').addClass('hide').removeClass('show')

            //console.log(data)
            if ($('#add_location').val() != '') {
                $('#add_location_tr').addClass('invalid')
            }

            // $('#add_location').val('')
            // on_changed_value(field, '')
        }


        $("#add_location_results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = $("#add_location_search_result_template").clone()
            clone.prop('id', 'add_location_result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('value', data.results[result_key].value)
            clone.attr('formatted_value', data.results[result_key].formatted_value)
            // clone.attr('field', field)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            // clone.children(".code").html(data.results[result_key].code)
            clone.children(".label").html(data.results[result_key].description)

            $("#add_location_results").append(clone)


        }

    })


}

function select_add_location_option(element) {



    var clone = $("#add_location_template").clone().removeClass('hide').addClass('locations').attr('id', 'part_location_edit_' + $(element).attr('value')).attr('location_key', $(element).attr('value'))



    clone.find(".location_code").html($(element).attr('formatted_value'))
    clone.find(".unlink_operations").html('<i class="fal fa-fw button fa-trash-alt" onclick="remove_new_associated_location(this)" ></i>')
    clone.find(".location_to_be_associated_icon").removeClass('hide').data('location_key',$(element).attr('value'))
    clone.find(".move_trigger").addClass('hide')





    $("#part_locations").append(clone)




    $('#add_location').val('')


   // $('#save_add_location').attr('location_key', $(element).attr('value')).addClass('valid changed')

//    $('#add_location_tr').addClass('valid')
    $('#add_location_results_container').addClass('hide').removeClass('show')

    //console.log($(element).attr('value'))
    //console.log($('#save_add_location').attr('location_key'))

    part_locations_edit_locations_look_for_changes();
}



function delete_new_associated_location(element){
    $(element).closest('tr').remove()
}


function part_locations_edit_locations_look_for_changes(){

    locations_changes=get_part_locations_edit_get_modified_locations();



    if(locations_changes.to_add.length==0 && locations_changes.to_remove.length==0){
        $('.part_locations_edit_locations_button').removeClass('changed valid')

    }else{

        $('.part_locations_edit_locations_button').addClass('changed valid')


        if(locations_changes.to_add.length>0 && locations_changes.to_remove.length>0){



            $('.part_locations_edit_locations_button span').html($('.part_locations_edit_locations_button span').data('labels').save_changes)

        }else if(locations_changes.to_add.length>0 ){



            if(locations_changes.to_add.length==1){
                $('.part_locations_edit_locations_button span').html($('.part_locations_edit_locations_button span').data('labels').add_location)

            }else{
                $('.part_locations_edit_locations_button span').html($('.part_locations_edit_locations_button span').data('labels').add_locations)
            }
        }else if(locations_changes.to_remove.length>0 ){



            if(locations_changes.to_remove.length==1){
                $('.part_locations_edit_locations_button span').html($('.part_locations_edit_locations_button span').data('labels').remove_location)

            }else{
                $('.part_locations_edit_locations_button span').html($('.part_locations_edit_locations_button span').data('labels').remove_locations)
            }

    }



    }


}

function get_part_locations_edit_get_modified_locations(){


    locations_to_add=[]
    locations_to_remove=[]

    $('.locations  .location_to_be_disassociated_icon.fa').each(function (i, obj) {

        console.log($(obj).data('location_key'))

        locations_to_remove.push($(obj).data('location_key'));


    })

    $('.locations  .location_to_be_associated_icon').each(function (i, obj) {
        locations_to_add.push($(obj).data('location_key'));
    })

    //console.log(locations_to_add)
    //console.log(locations_to_remove)

    return { 'to_add':locations_to_add, 'to_remove':locations_to_remove}

}




function save_part_locations_edit_locations(element) {


    if (!$(element).hasClass('valid')) {

        return;
    }


    var icon=$(element).find('i');

    icon.removeClass('fa-cloud').addClass('fa-spinner fa-spin ')


    locations_to_add=[]
    locations_to_remove=[]

    $('.locations  .location_to_be_disassociated_icon.fa').each(function (i, obj) {

        console.log($(obj).data('location_key'))

        locations_to_remove.push($(obj).data('location_key'));


    })

    $('.locations  .location_to_be_associated_icon').each(function (i, obj) {
        locations_to_add.push($(obj).data('location_key'));
    })


    var form_data = new FormData();

    form_data.append("tipo", 'edit_part_linked_locations')
    form_data.append("part_sku", $('#locations_table').attr('part_sku'))
    form_data.append("locations_to_add",JSON.stringify(locations_to_add) )
    form_data.append("locations_to_remove", JSON.stringify(locations_to_remove))


    var request = $.ajax({

        url: "/ar_edit_stock.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {


        if (state.tab == 'part.stock.transactions' || state.tab == 'part.stock' || state.tab == 'part.locations') {
            rows.fetch({
                reset: true
            });
        }

        $('#part_locations').html(data.part_locations)

        $('#add_location_tr').addClass('hide')
        $('.edit_stock_open_button').removeClass('hide')
        $('.edit_stock_close_button').addClass('hide')
        $('.part_locations_edit_locations_button').addClass('hide')

        $('#stock_table').removeClass('super_discreet')


        for (var key in data.updated_fields) {
            //console.log(key)
            //console.log(data.updated_fields[key])
            $('.' + key).html(data.updated_fields[key])
        }


        if (data.Part_Unknown_Location_Stock != 0) {
            $('#unknown_location_tr').removeClass('hide')
        }

        $('#Part_Unknown_Location_Stock').attr('qty',data.Part_Unknown_Location_Stock)


        $('.part_locations_edit_locations_button span').html($('.part_locations_edit_locations_button span').data('labels').no_change)
        $('.part_locations_edit_locations_button').removeClass('changed valid')
        icon.addClass('fa-cloud').removeClass('fa-spinner fa-spin ')

    })

    request.fail(function (jqXHR, textStatus) {
    });

}
