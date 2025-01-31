/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  7 July 2021 at 21:17:16 GMT+8 MYT Kuala Lumpur Malaysia
 Copyright (c) 2021, Inikoo
 Version 3.0*/


$(document).on('click', "#show_delete_asset_column", function () {
    $(this).find('i').removeClass('very_discreet_on_hover');
    grid.columns.findWhere({name: 'delete'}).set("renderable", true)

});

$(document).on('click', "#new_customer_delivery", function () {

    const icon = $('#new_customer_delivery i');
    if (!icon.hasClass('fa-plus')) {
        return;
    }

    icon.removeClass('fa-plus').addClass('fa-spinner fa-spin');

    let object = 'Fulfilment_Delivery';
    let parent = 'customer';
    let parent_key = $('#customer').attr('key');
    let fields_data = {
        warehouse_key: $(this).attr('warehouse_key')
    };


    //let request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + parent + '&parent_key=' + parent_key + '&fields_data=' + JSON.stringify(fields_data)

    let form_data = new FormData();
    form_data.append("tipo", 'new_object');
    form_data.append("object", object);
    form_data.append("parent", parent);
    form_data.append("parent_key", parent_key);
    form_data.append("fields_data", JSON.stringify(fields_data));

    let request = $.ajax({
        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'
    });

    request.done(function (data) {

        if (data.state === 200) {
            change_view(data.redirect);

        } else {

            swal(data.msg);


        }
    });

    request.fail(function () {
        swal('Server error please contact Aurora support');

    });
});

$(document).on('click', "#add_fulfilment_asset", function () {
    const offset = $(this).offset();
    const form = $('#add_fulfilment_asset_form');
    form.removeClass('hide').offset({
        'top': offset.top, 'left': offset.left - form.width() - 10
    });
});

$(document).on('click', "#add_fulfilment_asset_type span", function () {

    let option = $(this).find('i');
    let number_assets = $('#add_fulfilment_asset_number_assets');

    if (option.hasClass('fa-circle')) {
        $('#add_fulfilment_asset_type .option').removeClass('fa-dot-circle').addClass('fa-circle');
        $('#add_fulfilment_asset_type span').addClass('very_discreet_on_hover button');
        option.addClass('fa-dot-circle').removeClass('fa-circle').closest('span').removeClass('very_discreet_on_hover');
        $('#add_fulfilment_asset_type').data('value', option.data('value'));

        if (option.data('value') === 'Pallet') {
            $('#add_fulfilment_asset_add_one i').addClass('fa-pallet-alt').removeClass('fa-box-alt');
            number_assets.attr("placeholder", number_assets.data('pallet_label'));

        } else {
            $('#add_fulfilment_asset_add_one i').removeClass('fa-pallet-alt').addClass('fa-box-alt');
            number_assets.attr("placeholder", number_assets.data('box_label'));

        }

    }


});

$(document).on('click', "#add_fulfilment_asset_add_multiple", function () {

    add_fulfilment_asset_set_add_multiple();
});

function add_fulfilment_asset_set_add_single() {
    $('#add_fulfilment_asset_form .show_if_add_one').removeClass('hide');
    $('#add_fulfilment_asset_form .show_if_add_multiple').addClass('hide');
    $('#add_fulfilment_asset_form').data('type', 'add_one');


    if ($('#add_fulfilment_asset_note').val() === '') {
        $('#add_fulfilment_asset_note_tr').addClass('hide')
    } else {
        $('#add_fulfilment_asset_note_button').addClass('hide')

    }


    validate_add_fulfilment_asset();

}

function add_fulfilment_asset_set_add_multiple() {
    $('#add_fulfilment_asset_form .show_if_add_one').addClass('hide');
    $('#add_fulfilment_asset_form .show_if_add_multiple').removeClass('hide');
    $('#add_fulfilment_asset_form').data('type', 'add_multiple');
    validate_add_fulfilment_asset();
    $('#add_fulfilment_asset_number_assets').trigger('focus');

}

$(document).on('click', "#add_fulfilment_asset_add_one", function () {

    add_fulfilment_asset_set_add_single()
});

$(document).on('input propertychange', "#add_fulfilment_asset_number_assets", function () {
    validate_add_fulfilment_asset();
});

function validate_add_fulfilment_asset() {
    if ($('#add_fulfilment_asset_form').data('type') === 'add_one') {


        if ($('#add_fulfilment_asset_reference').hasClass('error')) {
            $('#add_fulfilment_asset_save').removeClass('valid ').addClass('invalid changed')

        } else {
            $('#add_fulfilment_asset_save').addClass('valid changed').removeClass('invalid')

        }

    } else {
        let quantity = $('#add_fulfilment_asset_number_assets').val();
        if (quantity === '') {
            $('#add_fulfilment_asset_save').removeClass('valid invalid changed')
        } else if (!isNaN(quantity) && quantity > 0 && quantity <= 100) {
            $('#add_fulfilment_asset_save').addClass('valid changed').removeClass('invalid')
        } else {
            $('#add_fulfilment_asset_save').removeClass('valid ').addClass('invalid changed')

        }

    }
}


$(document).on('input propertychange', '#add_fulfilment_asset_reference', function () {


    let value = $(this).val();


    if (value === '') {
        $('#add_fulfilment_asset_reference').removeClass('error');

        validate_add_fulfilment_asset();
        return;
    }

    let object_data = $('#add_fulfilment_asset_form').data("metadata");
    let parent = object_data.parent;
    let parent_key = object_data.parent_key;
    let object = 'Fulfilment Asset';
    let key = '';
    let field = 'Fulfilment Asset Reference';


    //$(this).closest('table').find('td.buttons').addClass('changed')
    let request = '/ar_validation.php?tipo=check_for_duplicates&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value;


    $.getJSON(request, function (data) {


        if (data['state'] === 200) {

            if (data['validation'] === 'valid') {
                $('#add_fulfilment_asset_reference').removeClass('error')

            } else {
                $('#add_fulfilment_asset_reference').addClass('error')
            }


        } else {

            $('#add_fulfilment_asset_reference').addClass('error')

        }
        validate_add_fulfilment_asset();


    })


});

$(document).on('click', "#add_fulfilment_asset_save", function () {

    if ($(this).hasClass('invalid') || !$(this).hasClass('changed') || $(this).hasClass('wait')) {
        return;
    }

    let save_element = $(this);

    save_element.addClass('wait').find('i').addClass('fa-spin fa-spinner');


    let form = $('#add_fulfilment_asset_form');
    let ajaxData = new FormData();

    let object_data = form.data("metadata");

    let asset_data = {};
    let fields = {};
    fields['Fulfilment Asset Reference'] = $('#add_fulfilment_asset_reference').val();
    fields['Fulfilment Asset Note'] = $('#add_fulfilment_asset_note').val();
    fields['Fulfilment Asset Type'] = $('#add_fulfilment_asset_type').data('value');

    asset_data['fields'] = fields;
    asset_data['options'] = {
        'type': form.data('type'), 'number_assets': $('#add_fulfilment_asset_number_assets').val()
    };


    ajaxData.append("tipo", 'add_fulfilment_asset');
    ajaxData.append("fulfilment_delivery_key", object_data.parent_key);


    ajaxData.append("asset_data", JSON.stringify(asset_data));

    $.ajax({
        url: "/ar_edit_fulfilment.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


        complete: function () {

        }, success: function (data) {

            save_element.removeClass('wait').find('i').removeClass('fa-spin fa-spinner');

            if (data.state === 200) {
                $('#add_fulfilment_asset_reference').val('');
                $('#add_fulfilment_asset_note').val('');
                $('#add_fulfilment_asset_number_assets').val('');
                $('#add_fulfilment_asset_note_tr').addClass('hide');
                $('#add_fulfilment_asset_note_button').removeClass('hide');
                add_fulfilment_asset_set_add_single();
                $('#add_fulfilment_asset_form').addClass('hide');


                for (let key in data["metadata"]["class_html"]) {
                    $('.' + key).html(data["metadata"]["class_html"][key])
                }

                rows.fetch({
                    reset: true
                });

            } else {
                swal(data.msg);
            }


        }, error: function () {
            save_element.removeClass('wait').find('i').removeClass('fa-spin fa-spinner')

        }
    });


});

$(document).on('input propertychange', '.fulfilment_asset_location_code', function fulfilment_asset_location_code_changed() {
    let object = $(this);
    const timeout = 200;
    window.clearTimeout(object.data("timeout"));
    object.data("timeout", setTimeout(function () {
        get_asset_locations_select(object)
    }, timeout));
});


function get_asset_locations_select(object) {

    object.removeClass('invalid');

    let request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(object.val()) + '&scope=locations&state=' + JSON.stringify(state);
    $.getJSON(request, function (data) {


        let offset = object.offset();
        let location_results_container = $('#asset_location_results_container');
        if (data["number_results"] > 0) {
            location_results_container.removeClass('hide').addClass('show');
            location_results_container.offset({
                top: (offset.top + object.outerHeight() - 1), left: offset.left
            })

        } else {


            location_results_container.addClass('hide').removeClass('show');
            if (object.val() !== '') {
                object.addClass('invalid')
            }


        }

        let location_results = location_results_container.find(".location_results");

        location_results.find(".result").remove();

        let first = true;

        for (let result_key in data.results) {

            console.log(result_key);
            let clone = location_results.find(".asset_location_search_result_template").clone();
            clone.prop('id', 'location_result_' + result_key);
            clone.addClass('result').removeClass('hide asset_location_search_result_template');
            clone.data('value', data.results[result_key].value);
            clone.data('asset_key', object.closest('.asset_location_container').data('asset_key'));
            clone.data('formatted_value', data.results[result_key]["formatted_value"]);
            if (first) {
                clone.addClass('selected');
                first = false
            }

            // clone.children(".code").html(data.results[result_key].code);
            clone.children(".label").html(data.results[result_key].description);

            location_results.append(clone)


        }

    })


}

function select_asset_location_option(element) {

    let container = $('#fulfilment_delivery_edit_location_' + $(element).data('asset_key'));

    container.find('.fulfilment_asset_location_code').val($(element).data('formatted_value'));
    container.find('i.save').data('location_key', $(element).data('value')).addClass('valid changed');
    $('#asset_location_results_container').addClass('hide').removeClass('show')
}

function edit_asset_location(tipo, element) {


    let icon;
    if (tipo === 'delete_from_fld') {
        icon = $(element).find('i');
    } else {
        icon = $(element);

    }
    if (!icon.hasClass('valid') || icon.hasClass('wait')) {
        return;
    }
    icon.addClass('fa-spinner fa-spin wait');

    let form_data = new FormData();
    form_data.append("tipo", 'edit_field');
    form_data.append("object", 'Fulfilment_Asset');
    form_data.append("key", icon.closest('.asset_location_container').data('asset_key'));


    if (tipo === 'edit') {
        form_data.append("value", icon.data('location_key'));
    } else {
        form_data.append("value", '');
    }
    form_data.append("field", 'Fulfilment Asset Location Key');


    let request = $.ajax({
        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'
    });

    request.done(function (data) {
        icon.removeClass('fa-spinner fa-spin wait');

        if (data.state === 200) {
            if (tipo === 'delete_from_fld') {
                $('#unlink_asset_location_field').addClass('hide');
                update_field({field: 'Fulfilment_Asset_Location_Key', value: '', formatted_value: '',render:'true'})

            } else {
                let asset_location_container = icon.closest('.asset_location_container');
                asset_location_container.find('.location_code').html(data["formatted_value"]);
                icon.removeClass('changed valid').data('location_key', '');

                if (data['value']) {
                    asset_location_container.find('.asset_location').removeClass('hide');
                    asset_location_container.find('.select_container').addClass('hide');
                    asset_location_container.find('.fa-unlink').removeClass('invisible')

                } else {
                    asset_location_container.find('.fulfilment_asset_location_code').val('');
                    asset_location_container.find('.fa-unlink').addClass('invisible')

                }
            }

        } else {

            swal(data.msg);


        }
    });

    request.fail(function () {
        swal('Server error please contact Aurora support');

    });

}


function show_asset_location_edit(element) {
    $(element).closest('.asset_location').addClass('hide');
    $(element).closest('.asset_location').find('.show_asset_location_edit').removeClass('invisible');
    $(element).closest('.asset_location_container').find('.select_container').removeClass('hide')
}

function delete_fulfilment_asset_from_table(element) {

    let icon = $(element);
    let asset_key = icon.data('asset_key');


    icon.addClass('fa-spinner fa-spin');


    let metadata = {note: 'from_delivery'};

    let form_data = new FormData();
    form_data.append("tipo", 'object_operation');
    form_data.append("operation", 'delete');
    form_data.append("object", 'Fulfilment_Asset');
    form_data.append("key", asset_key);
    form_data.append("metadata", JSON.stringify(metadata));


    let request = $.ajax({
        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'
    });

    request.done(function (data) {
        icon.removeClass('fa-spinner fa-spin wait');

        if (data.state === 200) {
            icon.closest('tr').remove();


            for (let key in data["update_metadata"]["class_html"]) {
                $('.' + key).html(data["update_metadata"]["class_html"][key])
            }


        } else {

            swal(data.msg);


        }
    });

    request.fail(function () {
        swal('Server error please contact Aurora support');

    });


}

function post_fulfilment_delivery_state_change(data,element) {


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


    if (data['update_metadata']['state_index'] >= 40) {
        $('#received_node').addClass('complete')
    }
    if (data['update_metadata']['state_index'] >= 60) {
        $('#booked_in_node').addClass('complete')
    }


    $('.info_in_process').addClass('hide')
    $('.info_received').removeClass('hide')
    $('#tab_fulfilment\\.delivery\\.items').removeClass('hide');



    if (data['update_metadata']['state_index'] === 10) {

        $('.info_in_process').removeClass('hide')
        $('.info_received').addClass('hide')


        $('#tab_fulfilment\\.delivery\\.items').addClass('hide');

        change_tab('fulfilment.delivery.details')
    } else if (data['update_metadata']['state_index'] === 40) {

        change_tab('fulfilment.delivery.items')

    }else if (data['update_metadata']['state_index'] === 60) {

        change_tab('fulfilment.delivery.items')

    }



}



function save_assets_operation(element) {

    return;

    var data = $(element).data("data")
    var table_metadata = $('#table').data("metadata")
    var object_data = $('#object_showcase div.order').data("object")
    var dialog_name = data.dialog_name
    var field = data.field
    var value = data.value
    var object = object_data.object
    var key = object_data.key


    if (!$('#' + dialog_name + '_save_buttons').hasClass('button')) {
        return;
    }

    $('#' + dialog_name + '_save_buttons').removeClass('button');
    $('#' + dialog_name + '_save_buttons i').addClass('fa-spinner fa-spin')
    $('#' + dialog_name + '_save_buttons .label').addClass('hide')


    var metadata = {}

    //console.log('#' + dialog_name + '_dialog')

    $('#' + dialog_name + '_dialog  .option_input_field').each(function () {
        var settings = $(this).data("settings")





    });




    const form_data = new FormData();

    form_data.append("tipo", 'edit_field')
    form_data.append("object", object)
    form_data.append("key", key)
    form_data.append("field", field)
    form_data.append("value", value)
    form_data.append("metadata", JSON.stringify(metadata))

    const request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    });


    request.done(function (data) {


        $('#' + dialog_name + '_save_buttons').addClass('button');
        $('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
        $('#' + dialog_name + '_save_buttons .label').removeClass('hide')


        if (data.state == 200) {

            close_dialog(dialog_name)

            var key;

            for ( key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }
            for ( key in data.update_metadata.hide) {
                $('.' + data.update_metadata.hide[key]).addClass('hide')
            }
            for ( key in data.update_metadata.show) {
                $('.' + data.update_metadata.show[key]).removeClass('hide')
            }


            change_tab('fulfilment.asset.details')

        } else if (data.state == 400) {


            swal($('#_labels').data('labels').error, data.msg, "error")
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}

