/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 1:22 pm Wednesday, 17 June 2020 (MYT), Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function job_order_item_action(element) {

    const action = $(element).data('action')

    switch (action) {
        case 'undo_qc_pass':
        case 'undo_manufactured':
            save_job_order_backward_action(element);
            break;
        default:
            const row = $(element).closest('tr')
            const cell = $(element).closest('td')

            cell.find('.action_container').addClass('hide')
            console.log(row)
            console.log('.follow_on_'+action)
            row.find('.follow_on_'+action).removeClass('hide')

            const action_container = row.find('.delivery_quantity_item_container')
            action_container.removeClass('invisible')
            validate_job_order_item_action(action_container)
    }


}


function validate_job_order_item_action(element) {

    const save_icon = element.find('.save')
    const input = element.find('input')
    const qty = input.val()

    if ($.isNumeric(qty) && qty > 0) {
        save_icon.addClass('valid').removeClass('invalid')

    } else {
        save_icon.removeClass('valid').addClass('invalid')

    }


}


function save_job_order_forward_action(element) {

    const row = $(element).closest('tr')
    const cell = $(element).closest('td')

    const action=$(element).data('action');

    const action_container_trigger = row.find('.action_container_trigger_'+action)

    console.log(action_container_trigger)

    const input = cell.find('input')


    $(element).addClass('fa-spin fa-spinner')


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'job_order_forward_item_action');
    ajaxData.append("key", action_container_trigger.data('key'));
    ajaxData.append("action", action_container_trigger.data('action'));
    ajaxData.append("qty", input.val());
    ajaxData.append("qty_type", input.data('qty_type'));


    $.ajax({
        url: '/ar_edit_production.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

        complete: function () {

        }, success: function (data) {
            $(element).removeClass('fa-spin fa-spinner wait')

            post_production_job_order_state_change(data,element)



        }, error: function () {
            $(element).removeClass('fa-spin fa-spinner wait')
        }
    });


}

function save_job_order_backward_action(element) {

    const icon = $(element)


    icon.addClass('fa-spin fa-spinner')


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'job_order_backward_item_action');
    ajaxData.append("key", icon.data('key'));
    ajaxData.append("action", icon.data('action'));


    $.ajax({
        url: '/ar_edit_production.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

        complete: function () {

        }, success: function (data) {
            icon.removeClass('fa-spin fa-spinner wait')
            post_production_job_order_state_change(data)

        }, error: function () {
            icon.removeClass('fa-spin fa-spinner wait')
        }
    });


}


function post_production_job_order_state_change(data,element) {


    if(data.transaction_data!= undefined){
        const tr=$(element).closest('tr')
        for (var key in data.transaction_data) {
            tr.find('.col_' + key).html(data.transaction_data[key])
        }

    }

    for (var key in data.update_metadata.class_html) {
        $('.' + key).html(data.update_metadata.class_html[key])
    }
    for (var key in data.update_metadata.hide) {
        $('.' + data.update_metadata.hide[key]).addClass('hide')
    }
    for (var key in data.update_metadata.show) {
        $('.' + data.update_metadata.show[key]).removeClass('hide')
    }
    $('.order_operation').addClass('hide')
    for (var key in data.update_metadata.operations) {
        $('#' + data.update_metadata.operations[key]).removeClass('hide')
    }

    $('.timeline .li').removeClass('complete')

    if (data.update_metadata.state_index >= 30) {
        $('#submitted_node').addClass('complete')
    }
    if (data.update_metadata.state_index >= 40) {
        $('#confirm_node').addClass('complete')
    }
    if (data.update_metadata.state_index >= 50) {
        $('#production_node').addClass('complete')
    }
    if (data.update_metadata.state_index >= 55) {
        $('#checked_node').addClass('complete')
    }


    if (data.update_metadata.state == 'InProcess') {
        $('#create_delivery').addClass('hide')

        $('#all_available_items,#new_item').removeClass('hide')

        change_tab('job_order.items_in_process')
    } else if (data.update_metadata.state == 'Submitted') {
        $('#all_available_items,#new_item').addClass('hide')

        change_tab('job_order.items')


    } else if (data.update_metadata.state == 'Confirmed') {
        $('#all_available_items,#new_item').addClass('hide')

        change_tab('job_order.items')
    }


    if (state.tab == 'supplier.order.history' || state.tab == 'job_order.items') {
        rows.fetch({
            reset: true
        });
    }
}

function post_select_dropdown_operator_handler(type, element) {


    const value=$('#' + $(element).attr('field')).val();

    const request = '/ar_edit_production.php?tipo=set_' + type + '&purchase_order_key=' + $('#purchase_order_key_value').val() + '&staff_key=' + value
    console.log(request)


    $.getJSON(request, function (data) {

        if (data.state == 200) {



        }

    })


}