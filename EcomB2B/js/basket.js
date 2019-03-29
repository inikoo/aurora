/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/




$(document).on('change', "#order_for_collection", function(ev){



    if($(this).is(':checked')){
        $('#order_delivery_address_fields').addClass('hide')

    }else{
        $('#order_delivery_address_fields').removeClass('hide')

    }
});



var special_instructions_timeout

$(document).on('input propertychange', "#special_instructions", function(ev){


    if (special_instructions_timeout) clearTimeout(special_instructions_timeout);

    value= $(this).val()

    special_instructions_timeout = setTimeout(function () {

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'special_instructions')
        ajaxData.append("value",value)

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

    }, 400);






});




$(document).on('change', "#order_invoice_country_select", function(){



    var selected=$( "#invoice_country_select option:selected" )

    var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key='+$('#ordering_settings').data('website_key')

    $.getJSON(request, function( data ) {
        $.each(data.hidden_fields, function(index, value) {
            $('#order_invoice_'+value).addClass('hide')
            $('#order_invoice_'+value).find('input').addClass('ignore')

        });

        $.each(data.used_fields, function(index, value) {
            $('#invoice_'+value).removeClass('hide')
            $('#order_invoice_'+value).find('input').removeClass('ignore')

        });

        $.each(data.labels, function(index, value) {
            $('#order_invoice_'+index).find('input').attr('placeholder',value)
            $('#order_invoice_'+index).find('b').html(value)
            $('#order_invoice_'+index).find('label.label').html(value)

        });

        $.each(data.no_required_fields, function(index, value) {



            $('#order_invoice_'+value+' input').rules( "remove" );




        });

        $.each(data.required_fields, function(index, value) {

            $('#order_invoice_'+value+' input').rules( "add", { required: true});

        });


    });


});

$(document).on('change', "#order_delivery_country_select", function(){


    var selected=$( "#order_delivery_country_select option:selected" )
    // console.log(selected.val())

    var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key='+$('#ordering_settings').data('website_key')

    $.getJSON(request, function( data ) {
        $.each(data.hidden_fields, function(index, value) {



            $('#order_delivery_'+value).addClass('hide')
            $('#order_delivery_'+value).find('input').addClass('ignore')

        });

        $.each(data.used_fields, function(index, value) {
            $('#order_delivery_'+value).removeClass('hide')
            $('#order_delivery_'+value).find('input').removeClass('ignore')

        });

        $.each(data.labels, function(index, value) {
            $('#order_delivery_'+index).find('input').attr('placeholder',value)
            $('#order_delivery_'+index).find('b').html(value)
            $('#order_delivery_'+index).find('label.label').html(value)

        });

        $.each(data.no_required_fields, function(index, value) {

            $('#order_delivery_'+value+' input').rules( "remove" );
        });

        $.each(data.required_fields, function(index, value) {


            $('#order_delivery_'+value+' input').rules( "add", { required: true});

        });


    });


});

