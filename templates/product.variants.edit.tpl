{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Wed 11 May 2022 12:53 Sheffield UK
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<style>
    .supplier_parts_table{
        font-size: 90%;
    }

.supplier_parts_table td{
    padding:3px 15px


}
.supplier_parts_table tr{
  border-bottom:1px solid #ccc;
    height: auto;


}
</style>

<div id="set_text_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 10px;z-index: 100">
    <i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_product_text_dialog()" aria-hidden="true"></i>
    <input id="set_text_value" style="width: 300px" value=""  data-product_id="" />
    <i id="set_text_save" onClick="save_product_text(this)" class="fa  fa-cloud fa-fw button  save    " aria-hidden="true"></i>
</div>




<div id="set_price_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 10px;z-index: 100">
    <i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_product_price_dialog()" aria-hidden="true"></i>
    {t}Price{/t} (<span id="set_price_currency"></span>)
    <input id="set_price_value" class=" width_75" value="" old_margin="" ovalue="" exchange="" cost="" data-product_id="" /> <i id="set_price_save" onClick="save_product_price(this)" class="fa  fa-cloud fa-fw button  save    " aria-hidden="true"></i>
</div>

<div class="hide"  style="border-bottom: 1px solid #ccc;min-height: 120px">


</div>

<script>

    function open_edit_position(element){

        var element=$(element)
        var offset = element.offset()

        $('#set_text_value')
            .data('product_id',element.data('id'))
            .data('field',element.data('field'))
            .data('type','position')
            .data('element',element).css('width', '40px')
        $('#set_text_dialog').removeClass('hide').offset({
            top: offset.top -7.5,
            left: offset.left +element.width()- $('#set_text_dialog').width()-30
        })

        $('#set_text_value').focus();

    }

    function open_edit_txt(element){
        var element=$(element)
        var offset = element.offset()
        $('#set_text_value').val(element.data('value'))
            .data('product_id',element.data('id'))
            .data('field',element.data('field'))
            .data('type','text')
            .data('element',element).focus().css('width', '300px')
        $('#set_text_dialog').removeClass('hide').offset({
            top: offset.top -7.5,
            left: offset.left +element.width()- $('#set_text_dialog').width()
        })

    }

    var text_timeout=false;

    $("#set_text_value").on("input propertychange", function (evt) {
        window.clearTimeout(text_timeout);

        var element=this
        text_timeout=setTimeout(function() {
            text_changed(element)
        }, 400);
    })

    function text_changed(e){


        new_text=$(e).val();

        if( new_text===''){
            $('#set_text_save').removeClass('changed invalid valid')
            $('#set_text_value').removeClass('invalid')
        }else{
            $('#set_text_save').addClass('changed')

                $('#set_text_save').removeClass('invalid').addClass('valid')
                $('#set_text_value').removeClass('invalid')

        }


    }
    function  save_product_text(){


        if($('#set_text_save').hasClass('valid')){
            $('#set_text_save').addClass('fa-spinner fa-spin').removeClass('valid changed')
            var request='/ar_edit.php?tipo=edit_field&object=Product&key='+ $('#set_text_value').data('product_id')+'&field='+$('#set_text_value').data('field')+'&value='+ $('#set_text_value').val()
            console.log(request)
            $.getJSON(request, function (r) {

                $('#set_text_save').removeClass('fa-spinner fa-spin')
                close_product_text_dialog();


                if($('#set_text_value').data('field')=='Product_Variant_Position'){
                    $('#set_text_value').val('')
                    rows.fetch({
                        reset: true
                    });
                }else{
                    element = $('#set_text_value').data('element');
                    var tr = element.closest('tr')
                    tr.find('.'+$('#set_text_value').data('field')).html(r.value).data('value',r.value)
                }









                //console.log($('#set_text_value').data('field'))
                //console.log(r)

            });
        }
    }



function change_variant_visibility(element){

        var value='';

    if($(element).hasClass('fa-spin') ){
        return;
    }

        if($(element).hasClass('fa-eye') ){
            value='No'
        }else{
            value='Yes'
        }

        $(element).addClass('fa-spinner fa-spin')

    var request='/ar_edit.php?tipo=edit_field&object=Product&key='+ $(element).data('id')+'&field=Product_Show_Variant&value='+ value
    console.log(request)
    $.getJSON(request, function (r) {

        $(element).removeClass('fa-spinner fa-spin')


        console.log()

        if(r.value=='Yes'){
            $(element).addClass('fa-eye').removeClass('fa-eye-slash')
        }else{
            $(element).removeClass('fa-eye').addClass('fa-eye-slash')

        }




    });


}


    function close_product_text_dialog(){
        $('#set_text_dialog').addClass('hide')
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