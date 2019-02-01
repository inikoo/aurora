/*Author: Raul Perusquia <raul@inikoo.com>
 Refactored: 16 January 2019 at 01:58:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


$(document).on('input propertychange', '.field_to_check', function (evt) {


    on_field_to_check_changed(this)



});


function on_field_to_check_changed(element){
    var level=parseFloat($('.order_data_entry_picking_aid_state_after_save').val());


    if($(element).attr('id')=='set_dn_weight'   ){


        if($(element).val()==''){
            $(element).removeClass('invalid').addClass('valid')
        }else{
            var validation=validate_number($(element).val(),0);
            $(element).removeClass('invalid valid').addClass(validation.class)
        }



    }


    if(level>0){
        if( $(element).attr('id')=='set_dn_parcels'   ){

            var validation=validate_integer($(element).val(),0);
            $(element).removeClass('invalid valid').addClass(validation.class)

        }
    }else{


        if($(element).val()==''){
            $(element).removeClass('invalid').addClass('valid')
        }else{
            var validation=validate_integer($(element).val(),0);
            $(element).removeClass('invalid valid').addClass(validation.class)
        }

    }



    if( $(element).attr('id')=='set_tracking_number'   ){

        var tracking_number_value=$(element).val();




        if( tracking_number_value != '' && tracking_number_value.replace(/\s/g, '').length==0 ) {
            $(element).removeClass('valid').addClass('invalid')

        }else{
            $(element).removeClass('invalid').addClass('valid')

        }

    }




    validate_data_entry_picking_aid();
}


$(document).on('input propertychange', '.fast_track_packing', function (evt) {


    if ($(this).val() == $(this).attr('ovalue')) {
        $(this).closest('span').find('i.plus').removeClass('fa-sign-in-alt fa-exclamation-circle error').addClass('fa-plus')
        $(this).closest('span').find('i.minus').removeClass('invisible')

    } else {

        var labels = $('body').data('labels');


        var max_value = Math.min($(this).data('max'), $(this).data('to_pick'))

        if (!validate_number($(this).val(), 0, max_value) || $(this).val() == '') {


            $(this).closest('span').find('i.plus').removeClass('fa-plus fa-exclamation-circle error').addClass('fa-sign-in-alt').prop('title', labels.save)
            $(this).closest('span').find('i.minus').removeClass('fa-minus').addClass('fa-undo very_discreet').prop('title', labels.undo)

            $(this).addClass('discreet')
        } else {
            $(this).closest('span').find('i.plus').removeClass('fa-plus fa-sign-in-alt').addClass('fa-exclamation-circle error').prop('title', labels.invalid_val)
            $(this).closest('span').find('i.minus').removeClass('fa-minus').addClass('fa-undo very_discreet').prop('title', labels.undo)


        }
    }
});


function data_entry_delivery_note(dn_key) {

    $('#tabs').html('');

    change_view(state.request + '&tab=order.input_picking_sheet', {
        'dn_key': dn_key
    })


}


function close_data_entry_delivery_note() {


    change_view(state.request + '&tab=order.items')


}


function show_other_part_locations_for_input_delivery_note_packing(element) {


    $.post("/ar_edit_orders.php", {
        tipo:     "get_input_delivery_note_packing_all_locations",
        metadata: JSON.stringify($(element).data('metadata'))
    }, null, 'json').done(function (data) {
        console.log(data)

        //$('#' + dialog_name + '_save_buttons').addClass('button');
        //$('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
        //$('#' + dialog_name + '_save_buttons .label').removeClass('hide')


        if (data.state == 200) {

            var tr = $(element).closest('tr');


            var input = tr.find('.fast_track_packing')

            var location_key = input.data('location_key')
            var qty = input.val()


            tr.find('.picked_quantity_components').html(data.picked_offline_input)
            tr.find('.location_components').html(data.locations)


            $('.picked_quantity_components input.fast_track_packing', tr).each(function (i, obj) {
                if ($(obj).data('location_key') == location_key) {
                    $(obj).val(qty)
                } else {
                    $(obj).val('')
                }
                //test
            });

        } else if (data.state == 400) {


            swal($('#_labels').data('labels').error, data.msg, "error")
        }
    });


}


function delivery_note_fast_track_packing_qty_change(element) {

    var tmp;
    var input = $(element).closest('span').find('input')
    var labels = $('body').data('labels');


    $(element).addClass('fa-spinner fa-spin')

    var ops_trigger;


    if ($(element).hasClass('fa-plus')) {

        ops_trigger = 'add';
        var _icon = 'fa-plus'

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }


    } else if ($(element).hasClass('fa-minus')) {
        ops_trigger = 'remove';
        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }


        var _icon = 'fa-minus'

    } else if ($(element).hasClass('fa-undo')) {

        ops_trigger = 'undo';
        qty = input.attr('ovalue')


        var _icon = 'fa-minus'

    } else {

        ops_trigger = 'save';

        if (isNaN(parseFloat(input.val())) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val())
        }

        var _icon = 'fa-sign-in-alt'

    }

    var picked_quantity_components = input.closest('.picked_quantity_components')


    var _total_qty = 0;

    picked_quantity_components.find('input.fast_track_packing').each(function (i, obj) {

        if ($(obj).data('location_key') != input.data('location_key')) {

            tmp = parseFloat($(obj).val())
            if (!isNaN(tmp)) {
                _total_qty += tmp

            }

        }

    });

    var total_qty = qty + _total_qty


    var pending = picked_quantity_components.data('pending')

    if (total_qty > pending) {

        qty = pending - _total_qty;
    }


    if (qty > input.data('max')) {


        qty = input.data('max')
    }


    //console.log(_icon)

    $(element).addClass(_icon)

    if (qty == '') qty = 0;


    var status_icon = $(element).closest('tr').find('.picked_offline_status')
    var status_notes = $(element).closest('tr').find('.picked_offline_status_notes')


    // console.log(qty)
    // console.log(input.data('to_pick'))


    if (ops_trigger == 'add') {
        if (qty == 0) {
            input.val('')
        } else {
            input.val(qty)
        }
    } else {
        input.val(qty)
    }

    $(element).closest('span').find('i.plus').removeClass('fa-spinner fa-spin fa-sign-in-alt error fa-exclamation-circle').addClass('fa-plus').prop('title', labels.add)
    $(element).closest('span').find('i.minus').removeClass('fa-spinner fa-spin invisible fa-undo very_discreet').addClass('fa-minus').prop('title', labels.remove)


    var new_total_qty = 0


    picked_quantity_components.find('input.fast_track_packing').each(function (i, obj) {


        tmp = parseFloat($(obj).val())
        if (!isNaN(tmp)) {
            new_total_qty += tmp

        }


    });


    console.log(new_total_qty + '----' + pending)

    var diff = new_total_qty - pending;

    if (diff < 0) {
        status_notes.html(parseFloat(diff.toFixed(4)))
        status_icon.addClass('error fa-exclamation-circle').removeClass('success  fa-check-circle')
    } else if (diff > 0) {
        status_notes.html('+' + parseFloat(diff.toFixed(4)))

        status_icon.addClass('error fa-exclamation-circle').removeClass('success  fa-check-circle')

    } else {


        status_notes.html('')
        status_icon.removeClass('error fa-exclamation-circle').addClass('success  fa-check-circle')

    }

    validate_data_entry_picking_aid()

}


function select_dropdown_handler_for_fast_track_packing(type, element) {


    field = $(element).attr('field')
    value = $(element).attr('value')

    if (value == 0) {
        return;
    }


    formatted_value = $(element).attr('formatted_value')
    //metadata = $(element).data('metadata')


    $('#' + field + '_dropdown_select_label').val(formatted_value)


    $('#' + field).val(value)

    $('#' + field + '_results_container').addClass('hide').removeClass('show')
    validate_data_entry_picking_aid()

}


function change_order_data_entry_picking_aid_state_after_save(element) {

    var icon = $(element).find('i')

    console.log($(element).data('level'))

    var level=0;

    switch ($(element).data('level')) {
        case 'L10':
            if (icon.hasClass('fa-check-square')) {

                icon.removeClass('fa-check-square').addClass('fa-square')
                $('.L20 i').removeClass('fa-check-square').addClass('fa-square')
                $('.L30 i').removeClass('fa-check-square').addClass('fa-square')
                level=0;
            } else {
                icon.addClass('fa-check-square').removeClass('fa-square')
                level=10;
            }

            break;
        case 'L20':
            if (icon.hasClass('fa-check-square')) {

                icon.removeClass('fa-check-square').addClass('fa-square')
                $('.L30 i').removeClass('fa-check-square').addClass('fa-square')
                level=10;

            } else {
                icon.addClass('fa-check-square').removeClass('fa-square')
                $('.L10 i').addClass('fa-check-square').removeClass('fa-square')
                level=20;
            }

            break;
        case 'L30':
            if (icon.hasClass('fa-check-square')) {

                icon.removeClass('fa-check-square').addClass('fa-square')
                level=20;
            } else {
                icon.addClass('fa-check-square').removeClass('fa-square')
                $('.L10 i').addClass('fa-check-square').removeClass('fa-square')
                $('.L20 i').addClass('fa-check-square').removeClass('fa-square')
                level=30;
            }

            break;
    }

    $('.order_data_entry_picking_aid_state_after_save').val(level)

    on_field_to_check_changed($('#set_dn_parcels'))
    
    validate_data_entry_picking_aid()
}



function validate_data_entry_picking_aid() {

     check_list = {
        'picker':          {filled:false,valid:true},
        'packer':          {filled:false,valid:true},
        'weight':         {filled:false,valid:true},
        'parcels':         {filled:false,valid:true},
        'shipper':        {filled:false,valid:true},
        'tracking_number': {filled:false,valid:true},
        'items':           {filled:true,valid:true},
    };


    if ($('#set_picker').val()) {
        check_list.picker.filled = true
    }

    if ($('#set_packer').val()) {
        check_list.packer.filled = true
    }

    if ($('#set_dn_weight').val() !='') {
        check_list.weight.filled = true


    }else{
        check_list.weight.filled = false;
    }

    if($('#set_dn_weight').hasClass('invalid')){


        check_list.weight.valid = false

    }else{

        check_list.weight.valid = true
    }

    if ($('#set_dn_parcels').val() !='') {
        check_list.parcels.filled = true

    }else{
        check_list.parcels.filled = false;
    }


    if($('#set_dn_parcels').hasClass('invalid')){
        check_list.parcels.valid = false

    }else{
        check_list.parcels.valid = true
    }


    if ($('#set_shipper').val() != '__none__') {
        check_list.shipper.filled = true
    }




    if ($('#set_tracking_number').val() !='') {
        check_list.tracking_number.filled = true


    }else{
        check_list.tracking_number.filled = false
    }

    if($('#set_tracking_number').hasClass('invalid')){
        check_list.tracking_number.valid = false

    }else{
        check_list.tracking_number.valid = true
    }

    $('i.picked_offline_status').each(function (i, obj) {



        if (!$(obj).hasClass('fa-check-circle')) {
            check_list.items.filled = false;
            return false;
        }
    });

    var valid=true;
    var changed=false
    var save_type='final_save';
    $.each( check_list, function( key, value ) {

        if(!value.valid){
            valid=false;
        }
    });



    var level=parseFloat($('.order_data_entry_picking_aid_state_after_save').val());


    // required fields
    if(!check_list.picker.filled || !check_list.picker.filled  ){
        changed=false
    }else{
        changed=true
    }


    if( !check_list.items.filled){
        save_type='confirm_save';
    }


    if(level>=10){

        if(!check_list.parcels.filled ) {
            changed=false
        }


        if(!check_list.items.filled  ) {
            save_type='confirm_save';
        }

    }


    if(level>=30){

        if(!check_list.shipper.filled ) {
            changed=false
        }


        if(!check_list.tracking_number.filled   ) {
            save_type='confirm_save';
        }

    }




    if(changed){
        $('.input_picking_sheet_table .save').addClass('changed')

    }else{
        $('.input_picking_sheet_table .save').removeClass('changed')

    }


    if(valid){
        $('.input_picking_sheet_table .save').removeClass('invalid')

        if(changed){
            $('.input_picking_sheet_table .save').addClass('valid')

        }else{
            $('.input_picking_sheet_table .save').removeClass('valid')

        }



    }else{
        $('.input_picking_sheet_table .save').removeClass('valid').addClass('changed invalid')

    }


    if( save_type=='confirm_save'){


        $('.input_picking_sheet_table .save i').removeClass('fas').addClass('far')
    }else{
        $('.input_picking_sheet_table .save i').removeClass('far').addClass('fas')

    }


}


function confirm_save_data_entry_picking_aid(element) {

    if ($(element).hasClass('invalid')) {
        return
    }

    if (!$(element).hasClass('changed')) {

        var labels = $('.input_picking_sheet_table').data('labels')
        var level = parseFloat($('.order_data_entry_picking_aid_state_after_save').val());


        missing_fields = '';
        if (!check_list.picker.filled) {
            missing_fields += labels.picker + '<br>'
        }
        if (!check_list.packer.filled) {
            missing_fields += labels.packer + '<br>'
        }

        if (level >= 10) {

            if (!check_list.parcels.filled) {
                missing_fields += labels.parcels + '<br>'
            }


        }


        if (level >= 30) {

            if (!check_list.shipper.filled) {
                missing_fields += labels.shipper + '<br>'
            }


        }

        swal({
            title: labels.missing_fields,
            type:  'error',
            html:  missing_fields

        })


        return;
    }
    if ($(element).find('i').hasClass('far')) {



        var labels = $('.input_picking_sheet_table').data('labels')
        console.log(labels)


        issues_list='';


        var level=parseFloat($('.order_data_entry_picking_aid_state_after_save').val());




        if( !check_list.items.filled){
            issues_list+=labels.out_of_stock+'<br/>';
        }



        if(level>=30){



            if(!check_list.tracking_number.filled   ) {
                issues_list+=labels.tracking_number+'<br/>';
            }

        }


        issues_list+='<br/>'+labels.are_you_sure


        swal.fire({
            title: labels.issues,
            html:  issues_list,
            type: 'warning',
            showCancelButton: true,

            confirmButtonText:labels.confirm_button_text,
        }).then(function (result) {



            if (result.value) {

                save_data_entry_picking_aid();
            }
        });



        return;
    }

    save_data_entry_picking_aid();
}


function save_data_entry_picking_aid() {


    if( $('.save_data_entry_picking_aid_icon').hasClass('wait')){
        return;
    }

    $('.save_data_entry_picking_aid_icon').removeClass('fa-cloud').addClass('fa-spin fa-spinner wait')

    var items = { }
    var fields = { }

    $('.input_field').each(function (i, obj) {


        fields[$(obj).data('field')]= $(obj).val();


        //  items.push({ location_key:_data.location_key, part_sku:_data.item_key,itf_key:_data.transaction_key, qty:$(obj).val()})

    });

    $('.picked_quantity_components input.fast_track_packing').each(function (i, obj) {

        var _data = $(obj).closest('.picked_quantity').data('settings')


        if(items[_data.item_key]==undefined){



            items[_data.item_key]=[{
                location_key: $(obj).data('location_key'),
                part_sku:     _data.item_key,
                itf_key:      _data.transaction_key,
                qty:          $(obj).val()
            }]



        }else{

            items[_data.item_key].push({
                location_key: $(obj).data('location_key'),
                part_sku:     _data.item_key,
                itf_key:      _data.transaction_key,
                qty:          $(obj).val()
            })

        }





    });

   console.log(fields)
    console.log(items)


    $.post("/ar_edit_orders.php", {
        tipo: 'data_entry_picking_aid',
        order_key: $('.input_picking_sheet_table').data('order_key'),
        delivery_note_key: $('.input_picking_sheet_table').data('delivery_note_key'),
        level: $('.order_data_entry_picking_aid_state_after_save').val(),
        fields: JSON.stringify(fields),
        items:  JSON.stringify(items)
    },null, "json")
        .done(function (data) {


            console.log(data)
            if(data.state==200){

                change_tab('order.items',{'reload_showcase': 1})

            }else{
                $('.save_data_entry_picking_aid_icon').addClass('fa-cloud').removeClass('fa-spin fa-spinner wait')

                swal(data.msg)
            }


        });


}