{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2016 at 10:23:23 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}



<div id="set_price_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 10px;z-index: 100">

    <i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_product_price_dialog()" aria-hidden="true"></i>


    {t}Price{/t} (<span id="set_price_currency"></span>)

                <input id="set_price_value" class=" width_75" value="" old_margin="" ovalue="" exchange="" cost="" product_id="" /> <i id="set_price_save" onClick="save_product_price(this)" class="fa  fa-cloud fa-fw button  save    " aria-hidden="true"/>


    <script>

    </script>
</div>

<div style="padding:10px;border-bottom:1px solid #ccc;display:flex;justify-content: space-around;">
<div style="text-align: left;">

 {if isset($supplier_part)}  {t}Current unit cost{/t}: {$supplier_part->get('Unit Cost')} {t}Extras{/t}: {$supplier_part->get('Unit Extra Cost')}  {/if}


</div>
    <div style="text-align: right;;">
        {$part->get('SKO Cost in Warehouse - Price')}
    </div>
</div>

<div style="padding:10px;border-bottom:1px solid #ccc;text-align:right"
     class="{if !$products_without_auto_web_configuration}hide{/if}">
    <span class="button" data-data='{ "object": "part", "key":"{$part_sku}"}'
          onClick="set_all_products_web_configuration('Online Auto',this)"><i class="fa fa-magic "
                                                                              aria-hidden="true"></i>  <span
                class="padding_left_5">{t}Set all products web configuration as Automatic{/t}</span> </span>
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

                console.log(validation)


                element = $(e).data('element');

                cost = $(e).attr('cost');
                exchange = $(e).attr('exchange');

                new_margin = ((exchange * new_price - cost) / (exchange * new_price) * 100).toFixed(2) + '%'



               // tr.find('.product_price').addClass('very_discreet')


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

    function close_product_price_dialog(){

        element = $('#set_price_value').data('element');
        var tr = element.closest('tr')

        tr.find('.product_margin').html( $('#set_price_value').attr('old_margin'))

        $('#set_price_dialog').addClass('hide')
    }

</script>