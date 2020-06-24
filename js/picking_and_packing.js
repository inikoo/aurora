/*Author: Raul Perusquia <raul@inikoo.com>
 Refactored: 11 December 2018 at 12:46:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


function post_select_dropdown_picker_packer_handler(type, element) {


    const value=$('#' + $(element).attr('field')).val();
    if(value==0){
        return;
    }

    const request = '/ar_edit_orders.php?tipo=set_' + type + '&delivery_note_key=' + $('#dn_data').attr('dn_key') + '&staff_key=' + value
    console.log(request)


    $.getJSON(request, function (data) {

        if (data.state == 200) {

            $('#dn_data').attr(type + '_key', data.staff_key)


        }

    })


}



function select_courier(value) {


    //  $(element).addClass('fa-spinner fa-spin');


    var request = '/ar_edit.php?tipo=edit_field&object=DeliveryNote&key=' + $('#delivery_note').attr('dn_key') + '&field=Delivery_Note_Shipper_Key&value=' + value + '&metadata={}';
    console.log(request)

    var form_data = new FormData();

    form_data.append("tipo", 'edit_field')
    form_data.append("field", 'Delivery_Note_Shipper_Key')
    form_data.append("object", 'DeliveryNote')
    form_data.append("key", $('#delivery_note').attr('dn_key'))
    form_data.append("value", value)
    var request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {
        //$(element).removeClass('fa-spinner fa-spin')
        if (data.state == 200) {

            console.log(data)
            //input.attr('ovalue', data.value)
            // icon.removeClass('fa-cloud').addClass('fa-plus')
            $('#shipper_options').addClass('hide')
            $('#shipper').removeClass('hide')

            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }



            for (var key in data.update_metadata.title) {
                $('.' + key).attr('title',data.update_metadata.title[key])
            }

            $('#shipper_options .option').removeClass('selected')

            $('#shipper_option_'+data.value).addClass('selected')
            $('#dispatch_save_buttons').removeClass('very_discreet')
            if(data.value>0){
                $('#edit_shipper_tracking_tr').removeClass('hide')




            }else{
                $('#edit_shipper_tracking_tr').addClass('hide')





            }






            // $('.Shipper_Code').html(data.)

        } else if (data.state == 400) {
            sweetAlert(data.msg);
            input.val(input.attr('ovalue'))
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });

}



function save_number_parcels(element) {

    $(element).addClass('fa-spinner fa-spin');

    var input = $(element).prev('input')
    var icon = $(element)

    if ($(element).hasClass('fa-plus')) {

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }

        input.val(qty).addClass('discreet')

    } else if ($(element).hasClass('fa-minus')) {

        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }

        input.val(qty).addClass('discreet')

    } else {
        qty = parseFloat(input.val())

    }

    if (qty == '') qty = 0;


    var request = '/ar_edit.php?tipo=edit_field&object=DeliveryNote&key=' + $('#delivery_note').attr('dn_key') + '&field=Delivery_Note_Number_Parcels&value=' + qty + '&metadata={}';
    console.log(request)

    var form_data = new FormData();

    form_data.append("tipo", 'edit_field')
    form_data.append("field", 'Delivery_Note_Number_Parcels')
    form_data.append("object", 'DeliveryNote')
    form_data.append("key", $('#delivery_note').attr('dn_key'))
    form_data.append("value", qty)
    var request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {
        $(element).removeClass('fa-spinner fa-spin')
        if (data.state == 200) {
            input.attr('ovalue', data.value)
            icon.addClass('hide')
            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }
        } else if (data.state == 400) {
            sweetAlert(data.msg);
            input.val(input.attr('ovalue'))
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}

function save_delivery_note_weight(element) {

    var input = $(element).closest('td').find('input')
    var icon = $(element)


    if (!icon.hasClass('save') || icon.hasClass('wait')) {
        return
    }

    $(element).addClass('fa-spinner fa-spin');


    qty = parseFloat(input.val())


    if (qty == '') qty = 0;


    var request = '/ar_edit.php?tipo=edit_field&object=DeliveryNote&key=' + $('#delivery_note').attr('dn_key') + '&field=Delivery_Note_Weight&value=' + qty + '&metadata={}';
    console.log(request)

    var form_data = new FormData();

    form_data.append("tipo", 'edit_field')
    form_data.append("field", 'Delivery_Note_Weight')
    form_data.append("object", 'DeliveryNote')
    form_data.append("key", $('#delivery_note').attr('dn_key'))
    form_data.append("value", qty)
    var request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {
        $(element).removeClass('fa-spinner fa-spin wait')
        if (data.state == 200) {
            input.attr('ovalue', data.value)
            icon.addClass('hide')
            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }
        } else if (data.state == 400) {
            sweetAlert(data.msg);
            input.val(input.attr('ovalue'))
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}

function save_delivery_note_tracking(element) {

    var input = $(element).closest('td').find('input')
    var icon = $(element)


    if (!icon.hasClass('save') || icon.hasClass('wait')) {
        return
    }

    $(element).addClass('fa-spinner fa-spin');





    var request = '/ar_edit.php?tipo=edit_field&object=DeliveryNote&key=' + $('#delivery_note').attr('dn_key') + '&field=Delivery_Note_Weight&value=' + input.val() + '&metadata={}';
    console.log(request)

    var form_data = new FormData();

    form_data.append("tipo", 'edit_field')
    form_data.append("field", 'Delivery_Note_Shipper_Tracking')
    form_data.append("object", 'DeliveryNote')
    form_data.append("key", $('#delivery_note').attr('dn_key'))
    form_data.append("value", input.val())
    var request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {
        $(element).removeClass('fa-spinner fa-spin wait')
        if (data.state == 200) {
            input.attr('ovalue', data.value)
            icon.addClass('hide')


            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }


        } else if (data.state == 400) {
            sweetAlert(data.msg);
            input.val(input.attr('ovalue'))
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}

function dispatch_delivery_note() {


    var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key=' + $('#dn_data').attr('dn_key') + '&value=Dispatched'
    $.getJSON(request, function (data) {
        if (data.state == 200) {


        }
    })
}

function print_label() {

    $("#printframe").remove();

    // create new printframe
    var iFrame = $('<iframe></iframe>');
    iFrame
        .attr("id", "printframe")
        .attr("name", "printframe")
        .attr("src", "about:blank")
        .css("width", "0")
        .css("height", "0")
        .css("position", "absolute")
        .css("left", "-9999px")
        .appendTo($("body:first"));

    // load printframe
    var url = 'test'
    if (iFrame != null && url != null) {
        iFrame.attr('src', url);
        iFrame.load(function () {
            // nasty hack to be able to print the frame
            var tempFrame = $('#printframe')[0];
            var tempFrameWindow = tempFrame.contentWindow ? tempFrame.contentWindow : tempFrame.contentDocument.defaultView;
            tempFrameWindow.focus();
            tempFrameWindow.print();
        });
    }


}

function show_shipper_options(){
    $('#shipper_options').removeClass('hide')
    $('#shipper').addClass('hide')

}

function save_dispatch_dn(element) {


    if($(element).hasClass('very_discreet')){

        swal('Courier not set', 'Select a courier', "error");

        return;
    }


    var data = $(element).data("data")

    console.log(data)

    var object_data = $('#object_showcase div.order').data("object")

    var dialog_name = data.dialog_name
    var field = data.field
    var value = data.value
    var object = object_data.object
    var key = object_data.key


    if (!$('#' + dialog_name + '_save_buttons').hasClass('button')) {
        console.log('#' + dialog_name + '_save_buttons')
        return;
    }

    $('#' + dialog_name + '_save_buttons').removeClass('button');
    $('#' + dialog_name + '_save_buttons i').addClass('fa-spinner fa-spin')
    $('#' + dialog_name + '_save_buttons .label').addClass('hide')


    var metadata = {}

    //console.log('#' + dialog_name + '_dialog')

    $('#' + dialog_name + '_dialog  .option_input_field').each(function () {
        var settings = $(this).data("settings")



        if (settings.type == 'datetime') {
            metadata[settings.field] = $('#' + settings.id).val() + ' ' + $('#' + settings.id + '_time').val()

        }


    });

    console.log(field)

    if(field=='Replacement State'){
        metadata['Delivery Note Key']=data.replacement_key;
    }


    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value + '&metadata=' + JSON.stringify(metadata)



    //console.log(request)
    //  return;
    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_field')
    form_data.append("object", object)
    form_data.append("key", key)
    form_data.append("field", field)
    form_data.append("value", value)
    form_data.append("metadata", JSON.stringify(metadata))

    var request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {

        $('#' + dialog_name + '_save_buttons').addClass('button');
        $('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
        $('#' + dialog_name + '_save_buttons .label').removeClass('hide')


        if (data.state == 200) {

            close_dialog(dialog_name)




            if (data.value == 'Cancelled') {
                change_view(state.request, {
                    reload_showcase: true
                })
            }


            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }


            $('.order_operation').addClass('hide')
            // $('.items_operation').addClass('hide')




            for (var key in data.update_metadata.operations) {

                console.log('#' + data.update_metadata.operations[key])

                $('#' + data.update_metadata.operations[key]).removeClass('hide')
            }




            $('.timeline .li').removeClass('complete')







            $('#order_node').addClass('complete')



            if (data.update_metadata.state_index >= 20) {
                $('#start_picking_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 30) {
                $('#picked_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 70) {
                $('#packed_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 80) {
                $('#packed_done_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 90) {
                $('#dispatch_approved_node').addClass('complete')
            }
            if (data.update_metadata.state_index >= 100) {
                $('#dispatched_node').addClass('complete')
            }


            if(data.update_metadata.state_index > 10){
                $('.delivery_note_handling_fields').removeClass('hide')
            }else{
                $('.delivery_note_handling_fields').addClass('hide')

            }



            $('#Delivery_Note_State_Index').val(data.update_metadata.state_index)



            $('.final_info_block').removeClass('hide')
            $('.info_block').addClass('hide')
            $('._items_cost').css('border-bottom','none')






        } else if (data.state == 400) {


            swal($('#_labels').data('labels').error, data.msg, "error")
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}

function show_other_part_locations_for_picking(element) {


    $.post("/ar_edit_orders.php", {
        tipo:     "get_picked_offline_input_all_locations",
        metadata: JSON.stringify($(element).data('metadata'))
    }, null, 'json').done(function (data) {
        console.log(data)

        //$('#' + dialog_name + '_save_buttons').addClass('button');
        //$('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
        //$('#' + dialog_name + '_save_buttons .label').removeClass('hide')


        if (data.state == 200) {

            $(element).closest('tr').find('.picked_quantity_components').html(data.picked_offline_input)
            $(element).closest('tr').find('.location_components').html(data.locations)

        } else if (data.state == 400) {


            swal($('#_labels').data('labels').error, data.msg, "error")
        }
    });


}

function picked_offline_items_qty_change(element) {


    var input = $(element).closest('span').find('input')
    //  var icon = $(element)

    if ($(element).hasClass('fa-plus')) {


        var _icon = 'fa-plus'

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }






    } else if ($(element).hasClass('fa-minus')) {

        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }


        var _icon = 'fa-minus'

    } else {
        qty = parseFloat(input.val())

        var _icon = 'fa-cloud'

    }

    var picked_quantity_components=input.closest('.picked_quantity_components')

    console.log(qty)
    var _total_qty=0;

    picked_quantity_components.find('input.picked_offline').each(function(i, obj) {

        if($(obj).data('location_key')!=input.data('location_key')){
            _total_qty+=parseFloat($(obj).val())
        }

    });

    var total_qty=qty+_total_qty


    console.log(total_qty)


    if( total_qty>picked_quantity_components.data('pending')   ){

        qty=picked_quantity_components.data('pending')-_total_qty;
    }

    console.log(qty)

    if(qty>input.data('max')   ){



        qty=input.data('max')
    }


    input.val(qty).addClass('discreet')


    //console.log(_icon)

    $(element).addClass(_icon)

    if (qty == '') qty = 0;


    //var settings = $(element).closest('span').data('settings')
    //var table_metadata = $('#table').data("metadata")








}

function get_delivery_notes_table( delivery_note_flow, metadata, force) {

    $('.delivery_note_flow').removeClass('selected')
    $('.blue').removeClass('blue')


      console.log(delivery_note_flow)

    switch (delivery_note_flow){


        case 'ready_to_pick':

            $('#delivery_note_flow_ready_to_pick').addClass('selected')
            widget='delivery_notes.ready_to_pick.wget'
            $('.Delivery_Notes_Ready_to_Pick_Number').addClass('blue')
            $('.Delivery_Notes_Ready_to_Pick_Weight').addClass('blue')
            break;
        case 'assigned':
            $('#delivery_note_flow_assigned').addClass('selected')
            widget='delivery_notes.assigned.wget'
            $('.Delivery_Notes_Assigned_Number').addClass('blue')
            $('.Delivery_Notes_Assigned_Weight').addClass('blue')

            break;
        case 'website_mailshots':
            $('#delivery_note_flow_website').addClass('selected')
            widget='orders.website.mailshots.wget'
            $('.Orders_In_Basket_Number').addClass('blue')
            $('.Orders_In_Basket_Amount').addClass('blue')

            break;
        case 'submitted_not_paid':
            $('#delivery_note_flow_submitted').addClass('selected').find('')

            widget='orders.in_process.not_paid.wget'

            $('.Orders_In_Process_Not_Paid_Number').addClass('blue')
            $('.Orders_In_Process_Not_Paid_Amount').addClass('blue')

            break;
        case 'submitted':
            $('#delivery_note_flow_submitted').addClass('selected')
            widget='orders.in_process.paid.wget'
            $('.Orders_In_Process_Paid_Number').addClass('blue')
            $('.Orders_In_Process_Paid_Amount').addClass('blue')

            break;
        case 'in_warehouse':
            $('#delivery_note_flow_in_warehouse').addClass('selected')
            widget='orders.in_warehouse_no_alerts.wget'
            $('.Orders_In_Warehouse_No_Alerts_Number').addClass('blue')
            $('.Orders_In_Warehouse_No_Alerts_Amount').addClass('blue')

            break;
        case 'in_warehouse_with_alerts':
            $('#delivery_note_flow_in_warehouse').addClass('selected')
            widget='orders.in_warehouse_with_alerts.wget'
            $('.Orders_In_Warehouse_With_Alerts_Number').addClass('blue')
            $('.Orders_In_Warehouse_With_Alerts_Amount').addClass('blue')

            break;
        case 'packed_done':
            $('#delivery_note_flow_packed').addClass('selected')
            widget='orders.packed_done.wget'
            $('.Orders_Packed_Number').addClass('blue')
            $('.Orders_Packed_Amount').addClass('blue')

            break;
        case 'approved':
            $('#delivery_note_flow_packed').addClass('selected')
            widget='orders.approved.wget'
            $('.Orders_Dispatch_Approved_Number').addClass('blue')
            $('.Orders_Dispatch_Approved_Amount').addClass('blue')

            break;

        case 'dispatched_today':
            $('#delivery_note_flow_dispatched').addClass('selected')
            widget='orders.dispatched_today.wget'
            $('.Orders_Dispatched_Today_Number').addClass('blue')
            $('.Orders_Dispatched_Today_Amount').addClass('blue')

            break;

        default:
            $('#delivery_note_flow_ready_to_pick').addClass('selected')
            widget='delivery_notes.ready_to_pick.wget'
            $('.Delivery_Notes_Ready_to_Pick_Number').addClass('blue')
            $('.Delivery_Notes_Ready_to_Pick_Weight').addClass('blue')



    }


    if($('#delivery_note_flow_ready_to_pick').data('current_delivery_note_flow')!=delivery_note_flow  || force=='Yes' ){

        $('#delivery_note_flow_ready_to_pick').data('current_delivery_note_flow',delivery_note_flow)

        var new_url = window.location.pathname.replace(/pending_delivery_notes.*$/, '') + 'pending_delivery_notes/' + delivery_note_flow
        window.top.history.pushState({
            request: new_url}, null, new_url)
        var request = "/ar_views.php?tipo=widget_details&widget=" + widget + '&metadata=' + JSON.stringify(metadata)
        $.getJSON(request, function (data) {
            $('#widget_details').html(data.widget_details).removeClass('hide');
        });

    }




}



