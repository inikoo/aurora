/*Author: Raul Perusquia <raul@inikoo.com>
 Created: Mon 28 Oct 2019 23:46:57 +0800 MYT. Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


$(function () {

    $(document).on('click', '.portfolio_row .edit_portfolio_item_trigger', function (evt) {


        var action;



        if ($(this).find('i').hasClass('fa-spinner')) return;


        var product_id=$(this).closest('.product_container').data('product_id')

        if ($(this).hasClass('add_to_portfolio')) {
            action = 'add_product_to_portfolio';
        } else {
            action = 'remove_product_from_portfolio';
        }


        var ajaxData = new FormData();

        ajaxData.append("tipo", action)
        ajaxData.append("product_id", product_id)
        ajaxData.append("webpage_key", $('#webpage_data').data('webpage_key'))

        $.ajax({
            url: 'ar_web_portfolio.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {
                var portfolio_row=$('.portfolio_row_' + product_id);

                if(data.result=='add'){
                    portfolio_row.find('.add_to_portfolio').addClass('hide')
                    portfolio_row.find('.remove_from_portfolio').removeClass('hide')
                }else{
                    portfolio_row.find('.add_to_portfolio').removeClass('hide')
                    portfolio_row.find('.remove_from_portfolio').addClass('hide')
                }


            }, error: function () {

            }
        });


    });


    $(document).on('click', '#table .edit_portfolio_reference', function (evt) {

        let container =$(this).closest('span.edit_portfolio_reference_container')
        $(this).addClass('hide')
        container.find('.editor').removeClass('hide')

    });


    $(document).on('input propertychange', '.edit_portfolio_reference_container input', function (evt) {

            var delay = 100;
            delayed_on_change_validate_portfolio_reference($(this), delay)

    });

    function delayed_on_change_validate_portfolio_reference(object,timeout){

        window.clearTimeout(object.data("timeout"));
        object.data("timeout", setTimeout(function () {
            validate_portfolio_reference(object)
        }, timeout));
    }

    function validate_portfolio_reference(object){

        let reference=$(object).val();

        if(reference==''){
            $(object).closest('.edit_portfolio_reference_container').removeClass('error')
            if($(object).data('old_value')!=''){
                $(object).closest('.edit_portfolio_reference_container').find('.save').addClass('valid')
            }


        }


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'validate_portfolio_reference')
        ajaxData.append("reference", reference)
        ajaxData.append("customer_portfolio_key",$(object).closest('.edit_portfolio_reference_container').data('cp_key'))

        $.ajax({
            url: 'ar_web_portfolio.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {
                if(data.state==200){

                    let container=$(object).closest('.edit_portfolio_reference_container');
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


    $(document).on('click', '#table .edit_portfolio_reference_container .save', function (evt) {


        if(!$(this).hasClass('valid') || $(this).hasClass('fa-spinner')){
            return;
        }

        let container= $(this).closest('.edit_portfolio_reference_container');

        $(this).addClass('fa-spin fa-spinner')
        var ajaxData = new FormData();

        ajaxData.append("tipo", 'update_portfolio_product_reference')
        ajaxData.append("customer_portfolio_key",   container.data('cp_key'))
        ajaxData.append("reference",   container.find('input').val())

        $.ajax({
            url: 'ar_web_portfolio.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {

                let reference=data.reference;

                if(!data.ok){
                    container.find('.save').removeClass('valid fa-spin fa-spinner')
                    container.addClass('error')
                }else{
                    if(reference==''){
                        container.find('.edit_portfolio_reference').html(data.formatted_reference).addClass('very_discreet italic').removeClass('hide')

                    }else{
                        container.find('.edit_portfolio_reference').html(data.formatted_reference).removeClass('very_discreet italic hide')

                    }
                    container.find('input').data('old_value',reference).val(reference)
                    container.find('.save').removeClass('valid fa-spin fa-spinner')

                    container.find('.editor').addClass('hide')
                }




            }, error: function () {

            }
        });


    });

})