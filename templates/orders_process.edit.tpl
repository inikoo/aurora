{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2017 at 14:41:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="set_price_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 10px;z-index: 100">
    <i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_product_price_dialog()" aria-hidden="true"></i>
    {t}Price{/t} (<span id="set_price_currency"></span>)
    <input id="set_price_value" class=" width_75" value="" old_margin="" ovalue="" exchange="" cost="" product_id="" /> <i id="set_price_save" onClick="save_product_price(this)" class="fa  fa-cloud fa-fw button  save    " aria-hidden="true"/>
</div>



<div id="set_rrp_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 10px;z-index: 100">
    <i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_product_rrp_dialog()" aria-hidden="true"></i>
    {t}Unit RRP{/t} (<span id="set_rrp_currency"></span>)
    <input id="set_rrp_value" class=" width_75" value="" old_margin="" ovalue="" exchange="" cost="" product_id="" /> <i id="set_rrp_save" onClick="save_product_rrp(this)" class="fa  fa-cloud fa-fw button  save    " aria-hidden="true"/>
</div>

<div style="padding:10px;padding-left:7px;border-bottom:1px solid #ccc;display:flex;">
<div style="text-align: left;">

    <i class="fa fa-hand-o-down" style="margin-right:2.5px" aria-hidden="true"></i> <i class="fa fa-square-o" aria-hidden="true" title="{t}Mark all in list{/t}"></i>
</div>

</div>




<script>


    function set_all_products_web_configuration(value, element) {


        if ($(element).hasClass('disabled')) {
            return
        }

        icon = 'fa-magic';


        $(element).find('i.fa').removeClass(icon).addClass('fa-spinner fa-spin')

        var request = '/ar_edit.php?tipo=object_operation&operation=set_all_products_web_configuration&object=' + $(element).data('data').object + '&key=' + $(element).data('data').key + '&metadata=' + JSON.stringify({
                    "value": value})


        $.getJSON(request, function (data) {
            if (data.state == 200) {

                console.log(data)
                if (data.request != undefined) {
                    change_view(data.request)
                } else {
                    change_view(state.request, {
                        'reload_showcase': 1
                    })
                }

            } else if (data.state == 400) {
                $(element).find('i.fa').addClass(icon).removeClass('fa-spinner fa-spin')

            }


        })


    }

    function open_edit_price(element){

        var element=$(element)
        var offset = element.offset()
        $('#set_price_currency').html(element.attr('currency'))
        $('#set_price_value').val(element.attr('price')).attr('product_id',element.attr('pid')).attr('ovalue',element.attr('price')).attr('old_margin',element.attr('old_margin')).attr('exchange',element.attr('exchange')).attr('cost',element.attr('cost')).data('element',element).focus()
        $('#set_price_dialog').removeClass('hide').offset({
            top: offset.top -7.5,
            left: offset.left +element.width()- $('#set_price_dialog').width()-20
        })
    }

    function open_edit_rrp(element){

        var element=$(element)
        var offset = element.offset()
        $('#set_rrp_currency').html(element.attr('currency'))
        $('#set_rrp_value').val(element.attr('rrp')).attr('product_id',element.attr('pid')).attr('ovalue',element.attr('rrp')).data('element',element).focus()
        $('#set_rrp_dialog').removeClass('hide').offset({
            top: offset.top -7.5,
            left: offset.left +element.width()- $('#set_rrp_dialog').width()-15
        })
    }


    var price_timeout=false;

    $("#set_price_value").on("input propertychange", function (evt) {
        window.clearTimeout(price_timeout);

        var element=this
        price_timeout=setTimeout(function() {
            price_changed(element)
        }, 400);
    })
    
    function price_changed(e){


        new_price=$(e).val();

        if( (new_price-$(e).attr('ovalue'))==0){
            $('#set_price_save').removeClass('changed invalid valid')
            $('#set_price_value').removeClass('invalid')
        }else{
            $('#set_price_save').addClass('changed')
            var validation= client_validation('amount', true, new_price, '')

            element = $(e).data('element');
            var tr = element.closest('tr')
            if(validation.class=='invalid'){

                $('#set_price_value').addClass('invalid')
                $('#set_price_save').addClass('invalid')
            }
            else if(validation.class=='valid') {
                $('#set_price_save').removeClass('invalid').addClass('valid')
                $('#set_price_value').removeClass('invalid')
                element = $(e).data('element');
                cost = $(e).attr('cost');
                exchange = $(e).attr('exchange');
                new_margin = ((exchange * new_price - cost) / (exchange * new_price) * 100).toFixed(2) + '%'
                tr.find('.product_margin').html(new_margin)
            }
        }


    }
    
    function  save_product_price(){

        if($('#set_price_save').hasClass('valid')){
            $('#set_price_save').addClass('fa-spinner fa-spin').removeClass('valid changed')
            var request='/ar_edit.php?tipo=edit_field&object=Product&key='+ $('#set_price_value').attr('product_id')+'&field=Product_Price&value='+ $('#set_price_value').val()
            console.log(request)
            $.getJSON(request, function (r) {

                $('#set_price_save').removeClass('fa-spinner fa-spin')

                console.log(r)
                element = $('#set_price_value').data('element');
                var tr = element.closest('tr')
                tr.find('.product_price').parent().html(r.update_metadata.price_cell)
                tr.find('.product_margin').parent().html(r.update_metadata.margin_cell)
                close_product_price_dialog();
                console.log(r)
            });
        }
    }
    
    
    
    //====

    var rrp_timeout=false;

    $("#set_rrp_value").on("input propertychange", function (evt) {
        window.clearTimeout(rrp_timeout);

        var element=this
        rrp_timeout=setTimeout(function() {
            rrp_changed(element)
        }, 400);
    })

    function rrp_changed(e){


        new_rrp=$(e).val();

        if( (new_rrp-$(e).attr('ovalue'))==0){
            $('#set_rrp_save').removeClass('changed invalid valid')
            $('#set_rrp_value').removeClass('invalid')
        }else{
            $('#set_rrp_save').addClass('changed')
            var validation= client_validation('amount', true, new_rrp, '')

            element = $(e).data('element');
            var tr = element.closest('tr')
            if(validation.class=='invalid'){

                $('#set_rrp_value').addClass('invalid')
                $('#set_rrp_save').addClass('invalid')
            }
            else if(validation.class=='valid') {
                $('#set_rrp_save').removeClass('invalid').addClass('valid')
                $('#set_rrp_value').removeClass('invalid')

            }
        }


    }

    function  save_product_rrp(){

        if($('#set_rrp_save').hasClass('valid')){
            $('#set_rrp_save').addClass('fa-spinner fa-spin').removeClass('valid changed')
            var request='/ar_edit.php?tipo=edit_field&object=Product&key='+ $('#set_rrp_value').attr('product_id')+'&field=Product_Unit_RRP&value='+ $('#set_rrp_value').val()
            console.log(request)
            $.getJSON(request, function (r) {

                $('#set_rrp_save').removeClass('fa-spinner fa-spin')

                console.log(r)
                element = $('#set_rrp_value').data('element');
                var tr = element.closest('tr')
                tr.find('.product_rrp').parent().html(r.update_metadata.rrp_cell)
                close_product_rrp_dialog();
                console.log(r)
            });
        }
    }
    //====
    
    

    function close_product_price_dialog(){
        element = $('#set_price_value').data('element');
        var tr = element.closest('tr')
        tr.find('.product_margin').html( $('#set_price_value').attr('old_margin'))
        $('#set_price_dialog').addClass('hide')
    }

    function close_product_rrp_dialog(){
        element = $('#set_rrp_value').data('element');
        var tr = element.closest('tr')
        tr.find('.product_margin').html( $('#set_rrp_value').attr('old_margin'))
        $('#set_rrp_dialog').addClass('hide')
    }

</script>