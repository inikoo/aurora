{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 August 2017 at 14:23:08 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


var deal_component_name_timer=false;
var deal_component_term_timer=false;
var deal_component_allowance_timer=false;



$(document).on('keyup', '.discount_name', function () {
    var element=this
    if(deal_component_name_timer) {
        clearTimeout(deal_component_name_timer);
    }
    deal_component_name_timer = setTimeout(function(){ save_deal_component('name',element); }, 300);

});


$(document).on('keyup', '.discount_term', function () {
    var element=this
    if(deal_component_term_timer) {
        clearTimeout(deal_component_term_timer);
    }
    deal_component_term_timer = setTimeout(function(){ save_deal_component('term',element); }, 300);

});


$(document).on('keyup', '.discount_allowance', function () {
    var element=this
    if(deal_component_allowance_timer) {
        clearTimeout(deal_component_allowance_timer);
    }
    deal_component_allowance_timer = setTimeout(function(){ save_deal_component('allowance',element); }, 300);

});


function save_deal_component(label,element){

    var ajaxData = new FormData();

    ajaxData.append("tipo", 'save_deal_component_labels')
    ajaxData.append("label", label)
    ajaxData.append("key", $(element).closest('.discount_card').attr('key'))
    ajaxData.append("value", $(element).html())



    $.ajax({
        url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {



            if (data.state == '200') {



            } else if (data.state == '400') {
            }



        }, error: function () {

        }
    });


}