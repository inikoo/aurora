/*Author: Raul Perusquia <raul@inikoo.com>
 Refactored: 16 January 2019 at 01:58:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


$(document).on('input propertychange', '.field_to_check', function (evt) {


    on_field_to_check_changed(this)



});


function on_field_to_check_changed(element){
    var level=parseFloat($('.order_data_entry_picking_aid_state_after_save').val());


    if($(element).hasClass('parcel_weight')   ){

        if($(element).val()==''){

            let rowCount = $('.parcels tr').length;
            if(rowCount>2){
                $(element).addClass('invalid').removeClass('valid')

            }else{
                $(element).removeClass('invalid').addClass('valid')

            }

        }else{

            var validation=validate_number($(element).val(),0);
            $(element).removeClass('invalid valid').addClass(validation.class)
        }



    }else if($(element).hasClass('parcel_dimension')   ){

        if($(element).val()==''){
            $(element).addClass('invalid').removeClass('valid')
        }else{

            var validation=validate_number($(element).val(),0);
            $(element).removeClass('invalid valid').addClass(validation.class)
        }



    }else if( $(element).attr('id')=='set_tracking_number'   ){

        var tracking_number_value=$(element).val();




        if( tracking_number_value != '' && tracking_number_value.replace(/\s/g, '').length==0 ) {
            $(element).removeClass('valid').addClass('invalid')

        }else{
            $(element).removeClass('invalid').addClass('valid')

        }

    }else if(level>0){
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








    validate_data_entry_picking_aid();
}


$(document).on('input propertychange', '.fast_track_packing', function (evt) {

    fast_track_packing_qty_changed(this)


});


function fast_track_packing_qty_changed(input_element){

    if ($(input_element).val() == $(input_element).attr('ovalue')) {
        $(input_element).closest('span').find('i.plus').removeClass('fa-exclamation-circle error fa-lock fa-plus')
        $(input_element).closest('span').find('i.minus').removeClass('invisible')


        if($(input_element).val()>=$(input_element).data('quantity_on_location')  && $(input_element).data('quantity_on_location_locked')){
            $(input_element).closest('span').find('i.plus').addClass('fa-lock')

        }else{
            $(input_element).closest('span').find('i.plus').addClass('fa-plus')
        }


    } else {

        var labels = $('body').data('labels');


        var max_value = Math.min($(input_element).data('max'), $(input_element).data('to_pick'))

        if (!validate_number($(input_element).val(), 0, max_value) || $(input_element).val() == '') {




            $(input_element).closest('span').find('i.plus').removeClass('fa-plus fa-exclamation-circle error').prop('title', labels.save)
            //  $(input_element).closest('span').find('i.minus').removeClass('fa-minus').addClass('fa-undo very_discreet').prop('title', labels.undo)
            $(input_element).addClass('discreet')


            if($(input_element).val()>=$(input_element).data('quantity_on_location') && $(input_element).data('quantity_on_location_locked')   ){
                $(input_element).closest('span').find('i.plus').addClass('fa-lock error')
            }else{
                $(input_element).closest('span').find('i.plus').addClass('fa-plus')

            }


        } else {

            $(input_element).closest('span').find('i.plus').removeClass('fa-plus fa-lock').addClass('fa-exclamation-circle error').prop('title', labels.invalid_val)
            //   $(input_element).closest('span').find('i.minus').removeClass('fa-minus').addClass('fa-undo very_discreet').prop('title', labels.undo)


        }
    }

    var new_total_qty=$(input_element).val()
    var picked_quantity_components = $(input_element).closest('.picked_quantity_components')
    var pending = picked_quantity_components.data('pending')




    var status_icon = $(input_element).closest('tr').find('.picked_offline_status')
    var status_notes = $(input_element).closest('tr').find('.picked_offline_status_notes')

    var diff = new_total_qty - pending;

   // console.log(pending)

    console.log(diff)


    if (diff < 0) {
        status_notes.html(parseFloat(diff.toFixed(4))).data('no_picked_qty',-diff)


        status_icon.addClass('error fa-exclamation-circle').removeClass('success  fa-check-circle')
    } else if (diff > 0) {
        status_notes.html('+' + parseFloat(diff.toFixed(4))).data('no_picked_qty',0)

        status_icon.addClass('error fa-exclamation-circle').removeClass('success  fa-check-circle')

    } else {


        status_notes.html('').data('no_picked_qty',0)
        status_icon.removeClass('error fa-exclamation-circle').addClass('success  fa-check-circle')

    }



    validate_data_entry_picking_aid()
    update_no_picked_info()
}


function data_entry_delivery_note(dn_key) {

    $('#tabs').html('');

    change_view(state.request + '&tab=order.input_picking_sheet', {
        'dn_key': dn_key
    })


}

function update_delivery_note_picking_locations(element,delivery_note_key){

    $(element).addClass('fa-spin')


    $.post("/ar_edit_orders.php", {
        tipo:     "update_delivery_note_picking_locations",
        delivery_note_key: delivery_note_key
    }, null, 'json').done(function (data) {
       // console.log(data)

        if (data.state == 200) {

            $(element).removeClass('fa-spin')


            if(state.tab=='order.input_picking_sheet'){
                rows.fetch({
                    reset: true
                });
            }



        } else if (data.state == 400) {


            swal($('#_labels').data('labels').error, data.msg, "error")
        }
    });


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

        if (data.state == 200) {

            var tr = $(element).closest('tr');


            var input = tr.find('.fast_track_packing')

            var location_key = input.data('location_key')
            var qty = input.val()


            tr.find('.picked_quantity_components').html(data.picked_offline_input)
            tr.find('.location_components').html(data.locations)

            console.log(location_key)
            $('.picked_quantity_components input.fast_track_packing', tr).each(function (i, obj) {



                if ($(obj).data('location_key') == location_key) {



                    $(obj).attr('value',qty)
                    $(obj).attr('ovalue',qty)
                } else {
                    $(obj).attr('value','')
                    $(obj).attr('ovalue','')

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





    var ops_trigger;

    console.log('hola2')
    if ($(element).hasClass('fa-plus')) {

        ops_trigger = 'add';
        var _icon = 'fa-plus'

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }



        var fast_track_packing_element=$(element).closest('.picked_quantity').find('.fast_track_packing')


        if(qty>=fast_track_packing_element.data('quantity_on_location') && fast_track_packing_element.data('quantity_on_location_locked')  ){
            $(element).removeClass('fa-spinner fa-spin')

            $(element).addClass('fa-lock').removeClass('fa-plus')

            return

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

    } else if ($(element).hasClass('fa-lock')) {


        $(element).removeClass('fa-spinner fa-spin')

        if (isNaN(parseFloat(input.val())) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val())
        }

        _labels=$('.input_picking_sheet_table').data('labels')



        console.log(_labels.title_no_stock)

        swal({
            title: _labels.title_no_stock,
            text: _labels.text_no_stock,
            type: "warning",
            showCloseButton: true,
            showCancelButton: true,
            cancelButtonText:_labels.no_text_no_stock,
            confirmButtonText:_labels.yes_text_no_stock

        }).then(function (result) {

            console.log(result)

            if (result.value) {




                $(element).removeClass('fa-lock error').addClass('fa-plus')

                var fast_track_packing_element=$(element).closest('.picked_quantity').find('.fast_track_packing')
                fast_track_packing_element.data('quantity_on_location_locked',false)
                console.log(fast_track_packing_element)
                console.log($(element))
                fast_track_packing_qty_changed(fast_track_packing_element)

            }else{


            }
        });



        return;


    } else {

        return;

        /*
        ops_trigger = 'save';

        if (isNaN(parseFloat(input.val())) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val())
        }

        var _icon = 'fa-sign-in-alt'
*/
    }





   // console.log('holaxxx '+input.data('location_key'))


    var picked_quantity_components = input.closest('.picked_quantity_components')


    var _total_qty = 0;

    picked_quantity_components.find('input.fast_track_packing').each(function (i, obj) {

        console.log(obj)


        if ($(obj).data('location_key') != input.data('location_key')) {


            console.log($(obj).val())
            tmp = parseFloat($(obj).val())
            console.log(tmp)

            if (!isNaN(tmp)) {
                _total_qty += tmp
                console.log('x '+tmp)

            }

        }

    });

    //console.log('qty '+qty)
    //console.log('_total_qty '+_total_qty)

    var total_qty = qty + _total_qty

    //console.log('total_qty '+total_qty)

    var pending = picked_quantity_components.data('pending')

    if (total_qty > pending) {

        qty = pending - _total_qty;
        //console.log(qty)
        //console.log(pending)

        //console.log(_total_qty)

    }


    if (qty > input.data('max')) {

      //  console.log('maxout')

        qty = input.data('max')
    }

    //console.log(qty)
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

    $(element).closest('span').find('i.plus').removeClass('fa-spinner fa-spin  error fa-exclamation-circle').addClass('fa-plus').prop('title', labels.add)
    $(element).closest('span').find('i.minus').removeClass('fa-spinner fa-spin invisible fa-undo very_discreet').addClass('fa-minus').prop('title', labels.remove)

    var new_total_qty = 0


    picked_quantity_components.find('input.fast_track_packing').each(function (i, obj) {


        tmp = parseFloat($(obj).val())
        if (!isNaN(tmp)) {
            new_total_qty += tmp

            console.log(tmp)

        }


    });

    //console.log(pending)

    var diff = new_total_qty - pending;
    if (diff < 0) {
        status_notes.html(parseFloat(diff.toFixed(4))).data('no_picked_qty',-diff)
        status_icon.addClass('error fa-exclamation-circle').removeClass('success  fa-check-circle')
    } else if (diff > 0) {
        status_notes.html('+' + parseFloat(diff.toFixed(4))).data('no_picked_qty',0)

        status_icon.addClass('error fa-exclamation-circle').removeClass('success  fa-check-circle')

    } else {


        status_notes.html('').data('no_picked_qty',0)
        status_icon.removeClass('error fa-exclamation-circle').addClass('success  fa-check-circle')

    }

    validate_data_entry_picking_aid()

    update_no_picked_info()


}

function update_no_picked_info(){


    var info_block=$('.not_picked_info')

    var no_picked_number_items=0
    var no_picked_amount=0;

    $('i.picked_offline_status').each(function (i, obj) {



        if (!$(obj).hasClass('fa-check-circle')) {
            no_picked_number_items++;
        }
    });

    $('.picked_offline_status_notes').each(function (i, obj) {


        no_picked_amount+=$(obj).data('no_picked_qty')*$(obj).data('value_per_qty')



    });





   // console.log(no_picked_number_items)


    if(no_picked_number_items>0){
        info_block.removeClass('hide')
    }else{
        info_block.addClass('hide')
    }

    info_block.find('.items_with_problems').html(no_picked_number_items)
    info_block.find('.percentage_items_with_problems').html('('+Number(no_picked_number_items/info_block.data('total_items')).toLocaleString(undefined,{style: 'percent', minimumFractionDigits:1})+')')


    info_block.find('.items_with_problems_amount').html(Number(no_picked_amount).toLocaleString(undefined,{style: 'currency', currency:info_block.data('currency')}))
    info_block.find('.percentage_items_with_problems_amount').html('('+Number(no_picked_amount/info_block.data('total_items_amount')).toLocaleString(undefined,{style: 'percent', minimumFractionDigits:1})+')')




}




function change_order_data_entry_picking_aid_state_after_save(element) {

    var icon = $(element).find('i')


    var level=0;


    switch ($(element).data('level')) {
        case 'L5':
            if (icon.hasClass('fa-check-square')) {

                icon.removeClass('fa-check-square').addClass('fa-square')
                $('.L10 i').removeClass('fa-check-square').addClass('fa-square')
                $('.L20 i').removeClass('fa-check-square').addClass('fa-square')
                $('.L30 i').removeClass('fa-check-square').addClass('fa-square')
                level=0;
            } else {

                icon.addClass('fa-check-square').removeClass('fa-square')
                level=5;
            }
            break;
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
    render_editable_set_picking();
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
         'items_errors':           {filled:true,valid:true},
    };


    var level=parseFloat($('.order_data_entry_picking_aid_state_after_save').val());
    var is_packed=$('#is_order_packed').val();


    if(is_packed=='No') {

        if ($('#set_picker').val()) {
            check_list.picker.filled = true
        }

        if ($('#set_packer').val()) {
            check_list.packer.filled = true
        }

        if ($('#set_dn_weight').val() != '') {
            check_list.weight.filled = true


        } else {
            check_list.weight.filled = false;
        }

        if ($('#set_dn_weight').hasClass('invalid')) {


            check_list.weight.valid = false

        } else {

            check_list.weight.valid = true
        }

        if ($('#set_dn_parcels').val() != '') {
            check_list.parcels.filled = true

        } else {
            check_list.parcels.filled = false;
        }


        if ($('#set_dn_parcels').hasClass('invalid')) {
            check_list.parcels.valid = false

        } else {
            check_list.parcels.valid = true
        }


        if ($('#set_shipper').val() != '__none__') {
            check_list.shipper.filled = true
        }


        if ($('.tracking_number_container').hasClass('hide')) {
            check_list.tracking_number.filled = true
            check_list.tracking_number.valid = true

        } else {
            if ($('#set_tracking_number').val() != '') {
                check_list.tracking_number.filled = true


            } else {
                check_list.tracking_number.filled = false
            }

            if ($('#set_tracking_number').hasClass('invalid')) {
                check_list.tracking_number.valid = false

            } else {
                check_list.tracking_number.valid = true
            }
        }
    }else{
        check_list.picker.filled = true
        check_list.packer.filled = true
        check_list.weight.filled = true
        check_list.parcels.filled = true
        check_list.tracking_number.filled = true
        check_list.shipper.filled = true

    }

    if(level>5) {

        $('.picked_quantity_components i.plus').each(function (i, obj) {


            if ($(obj).hasClass('error')) {
                check_list.items_errors.valid = false;
                return false;
            }
        });


        $('i.picked_offline_status').each(function (i, obj) {


            if (!$(obj).hasClass('fa-check-circle')) {
                check_list.items.filled = false;
                return false;
            }
        });
    }


    var valid=true;
    var changed=false
    var save_type='final_save';
    $.each( check_list, function( key, value ) {

        if(!value.valid){
            valid=false;
        }
    });





    // required fields
    if(!check_list.picker.filled || !check_list.picker.filled  ){
        changed=false
    }else{
        changed=true
    }


    if( !check_list.items.filled){
        save_type='confirm_save';
    }

    if(is_packed=='No') {
        if (level >= 10) {

            if (!check_list.parcels.filled) {
                changed = false
            }


            if (!check_list.items.filled) {
                save_type = 'confirm_save';
            }

        }


        if (level >= 30) {

            if (!check_list.shipper.filled) {
                changed = false
            }


            if (!check_list.tracking_number.filled) {
                save_type = 'confirm_save';
            }

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

    var parcels=[]


    $('.parcels tr.parcel_tr').each(function (i, obj) {

        parcels.push({
            'weight':$(obj).find('.parcel_weight').val(),
            'dim_0':$(obj).find('.dim_0').val(),
            'dim_1':$(obj).find('.dim_1').val(),
            'dim_2':$(obj).find('.dim_2').val()

        })


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

    var service_type='';
    if(!$('.apc_service_type').hasClass('hide')){
        $('.apc_service_type .service_type').each(function(i, obj) {

            if($(obj).find('i').hasClass('fa-dot-circle')){
                service_type=$(obj).data('service')
            }

        });
    }


    $.post("/ar_data_entry_picking_aid.php", {
        order_key: $('.input_picking_sheet_table').data('order_key'),
        delivery_note_key: $('.input_picking_sheet_table').data('delivery_note_key'),
        level: $('.order_data_entry_picking_aid_state_after_save').val(),
        fields: JSON.stringify(fields),
        parcels: JSON.stringify(parcels),
        service:service_type,

        items:  JSON.stringify(items)
    },null, "json")
        .done(function (data) {


            if(data.state==200){

                change_tab('order.items',{'reload_showcase': 1})

            }else{
                $('.save_data_entry_picking_aid_icon').addClass('fa-cloud').removeClass('fa-spin fa-spinner wait')

                swal(data.msg)
            }


        });


}

function show_parcel_types(element){

    $('#select_parcel_type_dialog').removeClass('hide').offset({
        top: $(element).offset().top - 5, left: $(element).offset().left
    }).data('element',element)
}

function close_select_parcel_type(){
    $('#select_parcel_type_dialog').closest('div').addClass('hide')

}

function select_parcel_type(element){

    let target=$($(element).closest('div').data('element'))
    let td=target.closest('td')
    td.find('.dim_0').val($(element).data('dim_0'))
    td.find('.dim_1').val($(element).data('dim_1'))
    td.find('.dim_2').val($(element).data('dim_2'))
    close_select_parcel_type()

}

function add_parcel(){

    let last_row=$('.parcels tr:last')
    last_row.clone().insertAfter(last_row)
    prepare_parcels_table()
}

function prepare_parcels_table(){

    let rowCount = $('.parcels tr').length;
    if(rowCount>2){
        $('.parcels .delete_parcel').removeClass('hide')
    }else{
        $('.parcels .delete_parcel').addClass('hide')

    }


}

function delete_parcel(element){
    $(element).closest('tr').remove()
    prepare_parcels_table()
}

function render_editable_set_picking(){
    var level = parseFloat($('.order_data_entry_picking_aid_state_after_save').val());



    if(level>5){
        $('.editable_set_picking').removeClass('invisible')
        $('.editable_set_picking_qty i').removeClass('invisible')
        $('.editable_set_picking_qty input').prop('readonly', false);

    }else{
        $('.editable_set_picking').addClass('invisible')
        $('.editable_set_picking_qty i').addClass('invisible')
        $('.editable_set_picking_qty input').prop('readonly', true);



    }

}