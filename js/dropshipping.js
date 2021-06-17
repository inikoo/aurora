/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  05 February 2020  22:20::25  +0800. Kuala Lumpur, , Malaysia
 Copyright (c) 2020, Inikoo
 Version 3.0*/


$(function () {

    $(document).on('click', '#table .edit_object_reference', function (evt) {

        let container =$(this).closest('span.edit_object_reference_container')
        $(this).addClass('hide')
        container.find('.editor').removeClass('hide')

    });


    $(document).on('input propertychange', '.edit_object_reference_container input', function (evt) {

        var delay = 100;
        delayed_on_change_validate_object_reference($(this), delay)

    });

    function delayed_on_change_validate_object_reference(object,timeout){

        window.clearTimeout(object.data("timeout"));
        object.data("timeout", setTimeout(function () {
            validate_object_reference(object)
        }, timeout));
    }

    function validate_object_reference(object){

        let reference=$(object).val();

        if(reference==''){
            $(object).closest('.edit_object_reference_container').removeClass('error')
            if($(object).data('old_value')!=''){
                $(object).closest('.edit_object_reference_container').find('.save').addClass('valid')
            }


        }


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'validate_object_reference')
        ajaxData.append("reference", reference)
        ajaxData.append("object", $(object).closest('.edit_object_reference_container').data('object'))

        ajaxData.append("object_key",$(object).closest('.edit_object_reference_container').data('object_key'))

        $.ajax({
            url: 'ar_web_validate.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {
                if(data.state==200){

                    let container=$(object).closest('.edit_object_reference_container');
                    let save_icon=container.find('.save')

                    if(data.ok){

                        container.removeClass('error')

                        save_icon.addClass('valid')


                    }else{

                        container.addClass('error')
                        save_icon.removeClass('valid')
                    }

                }


            }, error: function () {

            }
        });
    }


    $(document).on('click', '#table .edit_object_reference_container .save', function () {


        if(!$(this).hasClass('valid') || $(this).hasClass('fa-spinner')){
            return;
        }

        let container= $(this).closest('.edit_object_reference_container');

        $(this).addClass('fa-spin fa-spinner')
        var ajaxData = new FormData();

        let ar_file='';
        switch (container.data('object')) {
            case 'Portfolio_Item':
                ajaxData.append("tipo", 'update_portfolio_product_reference')
                ajaxData.append("customer_portfolio_key",   container.data('object_key'))
                 ar_file='ar_web_portfolio.php'
                break;
            case 'Client':
                ajaxData.append("tipo", 'update_client_reference')
                ajaxData.append("client_key",   container.data('object_key'))
                 ar_file='ar_web_client.php'
                break;
            default:
                return;
        }



        ajaxData.append("reference",   container.find('input').val())

        $.ajax({
            url: ar_file, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {

                let reference=data.reference;

                if(!data.ok){
                    container.find('.save').removeClass('valid fa-spin fa-spinner')
                    container.addClass('error')
                }else{
                    if(reference==''){
                        container.find('.edit_object_reference').html(data.formatted_reference).addClass('very_discreet italic').removeClass('hide')

                    }else{
                        container.find('.edit_object_reference').html(data.formatted_reference).removeClass('very_discreet italic hide')

                    }
                    container.find('input').data('old_value',reference).val(reference)
                    container.find('.save').removeClass('valid fa-spin fa-spinner')

                    container.find('.editor').addClass('hide')
                }




            }, error: function () {

            }
        });


    });

    $(document).on('input propertychange', "#add_item_to_portfolio_form",function () {


        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_add_item_to_portfolio_field($(this), delay)

    });


})



function delayed_on_change_add_item_to_portfolio_field(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {
        get_items_add_to_portfolio_select()
    }, timeout));
}

function get_items_add_to_portfolio_select() {


    $('#add_item_to_portfolio_form').removeClass('invalid');


    var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent($('#add_item_to_portfolio').val()) + '&scope=item' + '&metadata=' + atob($('#add_item_to_portfolio_form').data("metadata")) + '&state=' + JSON.stringify(state);
    console.log(request);
    $.getJSON(request, function (data) {



        const offset=$('#add_item_to_portfolio_form').offset().left+$('#add_item_to_portfolio_form').width();
        if (data.number_results > 0) {
            $('#add_item_to_portfolio_results_container').removeClass('hide').addClass('show').offset({
                'left':offset-$('#add_item_to_portfolio_results_container').width()
            });





            $('#add_item_to_portfolio').removeClass('invalid')

        } else {


            $('#add_item_to_portfolio_results_container').addClass('hide').removeClass('show');

            //console.log(data)
            if ($('#add_item_to_portfolio').val() != '') {
                $('#add_item_to_portfolio').addClass('invalid')
            } else {
                $('#add_item_to_portfolio').removeClass('invalid')
            }

            $('#save_add_item_to_portfolio').data('item_key', '')


        }


        $("#add_item_to_portfolio_results .result").remove();

        var first = true;

        for (var result_key in data.results) {



            var clone = $("#add_item_to_portfolio_search_result_template").clone();
            clone.prop('id', 'add_item_to_portfolio_result_' + result_key);
            clone.addClass('result').removeClass('hide');


            clone.data('item_key', data.results[result_key].value);

            clone.data('formatted_value', data.results[result_key].formatted_value);
            if (first) {
                clone.addClass('selected');
                first = false
            }

            clone.children(".label").html(data.results[result_key].description);
            clone.children(".code").html(data.results[result_key].code);

            $("#add_item_to_portfolio_results").append(clone)


        }


        $('#save_add_item_to_portfolio').data('item_key', '');
        $('#add_item_to_portfolio_save').addClass('super_discreet').removeClass('invalid valid button')



    })


}

function select_add_item_to_portfolio_option(element) {


    $('#add_item_to_portfolio').val($(element).data('formatted_value'));
    $('#add_item_to_portfolio_save').data('item_key', $(element).data('item_key'));



    $('#add_item_to_portfolio_results_container').addClass('hide').removeClass('show');


    $('#add_item_to_portfolio_save').addClass('valid button changed').removeClass('super_discreet')



}

function show_add_item_to_portfolio_form() {

    $('#add_item_to_portfolio_msg').html('').removeClass('error success');
    $('#add_item_to_portfolio_form').removeClass('hide');
    $('.table_button').addClass('hide');

    $('#save_add_item_to_portfolio').data('item_key', '');
    $('#add_item_to_portfolio').val('').focus().removeClass('invalid');
    $('#add_item_to_portfolio_save').addClass('super_discreet').removeClass('invalid valid button')

}

function close_add_item_to_portfolio() {
    $('#add_item_to_portfolio_form').addClass('hide');
    $('.table_button').removeClass('hide')
}


function save_add_item_to_portfolio() {

    if(!$('#add_item_to_portfolio_save').hasClass('valid')){
        return
    }


    $('#add_item_to_portfolio_save').addClass('fa-spinner fa-spin');



    var table_metadata = $('#table').data("metadata");






    var form_data = new FormData();

    form_data.append("tipo", 'add_product_to_portfolio');

    form_data.append("customer_key", table_metadata.parent_key);
    form_data.append("product_id", $('#add_item_to_portfolio_save').data('item_key'));


    var request = $.ajax({

        url: $('#add_item_to_portfolio_form').data("ar_url"),
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    });


    request.done(function (data) {

        $('#add_item_to_portfolio_save').removeClass('fa-spinner fa-spin');

        if (data.state == 200) {

            $('#save_add_item_to_portfolio').data('item_key', '');
            $('#add_item_to_portfolio').val('').focus().removeClass('invalid');
            $('#add_item_to_portfolio_save').addClass('super_discreet').removeClass('invalid valid button');

            rows.fetch({
                reset: true
            });

            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }

            for (var key in data.update_metadata.hide) {
                $('#' + data.update_metadata.hide[key]).addClass('hide')
            }
            for (var key in data.update_metadata.show) {
                $('#' + data.update_metadata.show[key]).removeClass('hide')
            }





        } else if (data.state == 400) {
            Swal.fire({
                type: 'error', title: data.msg
            })

        }

    });


    request.fail(function (jqXHR, textStatus) {



    });


}


function remove_item_from_portfolio(element,customer_key,product_id) {

    $(element).removeClass('fa-trash-alt').addClass('fa-spinner fa-spin');

    var form_data = new FormData();

    form_data.append("tipo", 'remove_product_from_portfolio');
    form_data.append("customer_key", customer_key);
    form_data.append("product_id", product_id);


    var request = $.ajax({

        url: '/ar_edit_customer.php',
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    });


    request.done(function (data) {



        if (data.state == 200) {
            $(element).closest('tr').remove();
            for (var key in data.update_metadata.class_html) {
                console.log(key);
                $('.' + key).html(data.update_metadata.class_html[key])
            }

            for (var key in data.update_metadata.hide) {
                $('#' + data.update_metadata.hide[key]).addClass('hide')
            }
            for (var key in data.update_metadata.show) {
                $('#' + data.update_metadata.show[key]).removeClass('hide')
            }






        } else if (data.state == 400) {
            $(element).removeClass('fa-spinner fa-spin').addClass('fa-trash-alt');


            Swal.fire({
                type: 'error', title: data.msg
            })

        }

    });


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus);

        console.log(jqXHR.responseText)


    });


}