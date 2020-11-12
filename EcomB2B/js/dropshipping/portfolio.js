/*Author: Raul Perusquia <raul@inikoo.com>
 Created: Mon 28 Oct 2019 23:46:57 +0800 MYT. Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


$(function () {

    $(document).on('click', '.portfolio_row .edit_portfolio_item_trigger', function () {


        var action;


        if ($(this).find('i').hasClass('fa-spinner')) return;


        var product_id=$(this).closest('.product_container').data('product_id');

        if ($(this).hasClass('add_to_portfolio')) {
            action = 'add_product_to_portfolio';
        } else {
            action = 'remove_product_from_portfolio';
        }


        var ajaxData = new FormData();

        ajaxData.append("tipo", action);
        ajaxData.append("product_id", product_id);
        ajaxData.append("webpage_key", $('#webpage_data').data('webpage_key'));

        $.ajax({
            url: 'ar_web_portfolio.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {
                var portfolio_row=$('.portfolio_row_' + product_id);

                if(data.result=='add'){
                    portfolio_row.find('.add_to_portfolio').addClass('hide');
                    portfolio_row.find('.remove_from_portfolio').removeClass('hide')
                }else{
                    portfolio_row.find('.add_to_portfolio').removeClass('hide');
                    portfolio_row.find('.remove_from_portfolio').addClass('hide')
                }


                let number_items_in_family=0;
                let number_products_in_portfolio_in_family=0;



                $('.category_products.dropshipping .portfolio_row').each(function(i, obj) {


                    number_items_in_family++;
                    let _portfolio_row=$(obj).find('.add_to_portfolio');
                    if(_portfolio_row.hasClass('hide')){
                        number_products_in_portfolio_in_family++

                    }
                });


                $('.number_products_in_portfolio_in_family').html(number_products_in_portfolio_in_family);
                $('.number_products_in_family').html(number_items_in_family);


                if(number_items_in_family>0){
                    $('.portfolio_in_family').removeClass('hide');

                    $('.add_family_label').addClass('hide');
                    $('.add_rest_label').addClass('hide');
                    $('.add_all_family_to_portfolio').removeClass('hide');

                    if(number_products_in_portfolio_in_family==0) {
                        $('.add_family_label').removeClass('hide')
                    }else if(number_products_in_portfolio_in_family<number_items_in_family){
                        $('.add_rest_label').removeClass('hide')
                    }else{
                        $('.add_all_family_to_portfolio').addClass('hide')
                    }
                }



            }, error: function () {

            }
        });


    });

    $(document).on('click', '.add_all_family_to_portfolio', function () {





        if ($(this).find('i').hasClass('fa-spinner')) return;




        var ajaxData = new FormData();

        ajaxData.append("tipo", 'add_category_to_portfolio');
        ajaxData.append("webpage_key", $('#webpage_data').data('webpage_key'));

        $.ajax({
            url: 'ar_web_portfolio.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {



                data.products.forEach(function( product_id) {




                    var portfolio_row=$('.portfolio_row_' + product_id);

                    if(data.result=='add'){
                        portfolio_row.find('.add_to_portfolio').addClass('hide');
                        portfolio_row.find('.remove_from_portfolio').removeClass('hide')
                    }else{
                        portfolio_row.find('.add_to_portfolio').removeClass('hide');
                        portfolio_row.find('.remove_from_portfolio').addClass('hide')
                    }



                });





                let number_items_in_family=0;
                let number_products_in_portfolio_in_family=0;



                $('.category_products.dropshipping .portfolio_row').each(function(i, obj) {


                    number_items_in_family++;
                    let _portfolio_row=$(obj).find('.add_to_portfolio');
                    if(_portfolio_row.hasClass('hide')){
                        number_products_in_portfolio_in_family++

                    }
                });



                $('.number_products_in_portfolio_in_family').html(number_products_in_portfolio_in_family);
                $('.number_products_in_family').html(number_items_in_family);


                if(number_items_in_family>0){
                    $('.portfolio_in_family').removeClass('hide');

                    $('.add_family_label').addClass('hide');
                    $('.add_rest_label').addClass('hide');
                    $('.add_all_family_to_portfolio').removeClass('hide');

                    if(number_products_in_portfolio_in_family==0) {
                        $('.add_family_label').removeClass('hide')
                    }else if(number_products_in_portfolio_in_family<number_items_in_family){
                        $('.add_rest_label').removeClass('hide')
                    }else{
                        $('.add_all_family_to_portfolio').addClass('hide')
                    }
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




});




function delayed_on_change_add_item_to_portfolio_field(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {
        get_items_add_to_portfolio_select()
    }, timeout));
}

function get_items_add_to_portfolio_select() {

    $('#add_item_to_portfolio_form').removeClass('invalid');


    var request = '/ar_web_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent($('#add_item_to_portfolio').val()) + '&scope=item' + '&metadata=' + atob($('#add_item_to_portfolio_form').data("metadata")) + '&state=' + JSON.stringify(state);
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

        url: $('.portfolio').data("ar_url"),
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
