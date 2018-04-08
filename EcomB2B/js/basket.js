/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/




$("#_special_instructions").on("input propertychange", function (evt) {



    var delay = 100;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_save_special_instructions($(this),delay)
});
function delayed_save_special_instructions(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {

        save_special_instructions()
    }, timeout));
}
function save_special_instructions(){



    var ajaxData = new FormData();

    ajaxData.append("tipo", 'update_order')
    ajaxData.append("order_key", $('webpage_data').data('order_key'))
    ajaxData.append("field", 'Order Special Instructions')
    ajaxData.append("value",$('#_special_instructions').val())



    $.ajax({
        url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {




            } else if (data.state == '400') {

            }



        }, error: function () {

        }
    });



}

