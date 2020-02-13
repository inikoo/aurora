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

})