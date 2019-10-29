/*Author: Raul Perusquia <raul@inikoo.com>
 Created: Mon 28 Oct 2019 23:46:57 +0800 MYT. Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(function () {

    $(document).on('click', '.order_row .edit_portfolio_item', function (evt) {

        var action;


        if ($(this).find('i').hasClass('fa-spinner')) return;


        if ($this.hasClass('add_to_portfolio')) {
            action = 'add';
        } else {
            action = 'remoce';
        }


        var ajaxData = new FormData();


        ajaxData.append("tipo", 'edit_portfolio_item')
        ajaxData.append("action", action)
        ajaxData.append("object", 'product')
        ajaxData.append("key", $(this).closest('.product_container').data('product_id'))
        ajaxData.append("webpage_key", $('#webpage_data').data('webpage_key'))


        $.ajax({
            url: request_file, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {


            }, error: function () {

            }
        });


    });

})