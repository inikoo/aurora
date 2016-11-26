{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 November 2016 at 17:33:42 GMT+8, Yiwu , China
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<style>


    .description_block{

        width:895px;margin:auto;

    }


    #products{
        width:970px;margin:auto;

    }

#products    .block {
        border: 1px solid #ccc;background:#fff;padding:10px 0px 0px 0px;
    font-family: "Ubuntu",Helvetica,Arial,sans-serif;

}

#products    .block:hover{
    border:1px solid #A3C5CC;
}

    .block.four{
        float:left;width:218px;margin-left:18px;}


    .block.product_showcase{
        height:279px}


    .wraptocenter {

        display: table-cell;
        text-align: center;
        vertical-align: middle;
        width: 218px;
        height: 160px;
        margin-bottom:10px
    }
    .wraptocenter img {
        vertical-align: bottom;max-width:218px;max-height:160px
    }



    .order_fields{
        width:196px}


    .ind_form{
        font-size:12.5px}
    .ind_form .code{
        font-family: "Ubuntu",Helvetica,Arial,sans-serif;}
    .ind_form .name{
        margin-top:2px;margin-bottom:3px;padding:0px;display:block;min-height:30px;}
    .ind_form .rrp{
        color:	#777}
    .ind_form .price{
        color:#236E4B;text-align:right;margin-bottom:3px}


    .product_description{
        padding-left:10px;padding-right:10px;display:block;min-height:30px;height:88px;
    }


    .more_info{
        cursor:pointer;position:absolute;width:40px;top:-1px;left:179px}
    .product_footer{
        height:28px;position:relative;top:2px}
    .ind_form{
        margin-top:10px}

    .ind_form .product_description{
        min-height:80px}


    .description_block{
        margin-bottom:20px;background:#fff;padding:10px 20px;border:1px solid #eee}
    .description_block .image{
        vertical-align:middle;float:left;max-height:180px;max-width:300px;border:1px solid #ccc;padding:15px}
    .description_block .content{
        margin-left:50px;float:left;max-width:500px;width:100%}
    .description_block .content h1{
        float:right;font-size:140%;font-weight:800}
    .description_block .content h2{
        font-size:120%;padding:5px 0px 10px 0px}


    .ordering{
        }

    .out_of_stock{
        color:red}


    .order_input{
        float:left;position:relative;top:2px;border-right:none;border-left:none;height:20px;width:40px
    }

    .product_footer{
       padding:7px 20px 3px 10px;
        display:block;height:20px;cursor:pointer;
        float:left;font-weight: 800;
    }


    .product_footer.out_of_stock{
        color:#fff;
        background-color: darkgray;cursor:auto;width:154px;
    }


    .product_footer.out_of_stock_reminder{
        padding:6px 10px 2px 10px;
        background-color: inherit;
        color: darkgray;border-top:1px solid darkgray;cursor:pointer;
    }

    .product_footer.favorite{
        padding:6px 10px 2px 10px;
        background-color: inherit;
        color: darkgray;border-top:1px solid #ccc
    }

    .product_footer.order_button{
        width:102px;
        color:#fff;
        background-color: orange;
    }

    .product_footer.order_button:hover{
        color:#fff;
        background-color: maroon;
    }

    .product_footer.order_button.ordered{
        color:#fff;
        background-color: maroon;
    }


    .product_image{
        cursor:pointer}


</style>




<div style="padding:20px;">


    <span id="ordering_settings" class="hide" data-labels='{
    "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {t}Ordered{/t}",
    "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Order now{/t}",
    "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Update{/t}"
    }'></span>


    <div id="description_block" class="description_block" >

        <div class="webpage_content_header" style="position:relative;float:left" >


        <img  src="{$category->display('Main Image Src')}"  style="width:100%"  />

          </div>

        <div class="webpage_content_header"  style="position:relative;margin-left:40px;float:left" >
            <h1>
                {$category->get('Code')} {$category->get('Label')}
            </h1>

            <div class="description">
                {$category->get('Product Category Description')}
            </div>
        </div>
        <div style="clear:both">
        </div>
    </div>


<div id="products" >
    {foreach from=$_products item=product}
        <div class="product_wrap">

            <div id="product_target_div_{$product.stack_index}" stack_index="{$product.stack_index}" draggable="{if $product.object->get('Product Web State')=='For Sale' }true{else}false{/if}" ondragstart="drag(event)" product_code="{$product.object->get('Code')}" product_id="{$product.object->id}" ondrop="drop(event)" ondragover="allowDrop(event)" class="block four product_showcase " style="margin-bottom:20px;position:relative">



                <div class="wraptocenter product_image" onCLick="console.log('move')">
                    <img draggable="false" class="more_info" src="/art/moreinfo_corner{$product.col}.png">

                    <img draggable="false" src="{$product.img}" />

                 </div>


                <div class="product_description" >
                    <span class="code">{$product.object->get('Code')}</span>
                    <div class="name">{$product.name}</div>
                    <div>{t}Price{/t}: {$product.object->get('Webpage Price')}</div>
                    <div>{t}RRP{/t}: {$product.object->get('Webpage RRP')}</div>
                </div>

                {if $product.object->get('Product Web State')=='Out of Stock'}
                    <div class="ordering ">

                        <span class="product_footer out_of_stock">{$product.object->get('Webpage Out of Stock Label')}</span>
                        <span class="product_footer out_of_stock_reminder"><i class="fa fa-envelope-o" aria-hidden="true"></i>  </span>


                    </div>
                {else if $product.object->get('Product Web State')=='For Sale'}

                <div class="ordering">
                    <input maxlength=6  class='order_input ' id='but_qty{$product.object->id}'   type='text' size='2'  value='{$product.object->get('Ordered Quantity')}' ovalue='{$product.object->get('Ordered Quantity')}'>
                     <span class="product_footer order_button"   ><i class="fa fa-hand-pointer-o" aria-hidden="true"></i> {t}Order now{/t}</span>
                     <span class="product_footer  favorite"><i class="fa fa-heart-o" aria-hidden="true"></i>  </span>


                </div>

                {/if}

            </div>


        </div>
    {/foreach}
    <div style="clear:both"></div>
</div>

    </div>

<script>

    
    var te
    
    count=1;

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {

       // console.log(ev.target.id)

        ev.dataTransfer.setData("id", ev.target.id);

        ev.dataTransfer.setData("stack_index", ev.target.getAttribute('stack_index'));
        ev.dataTransfer.setData("product_id", ev.target.getAttribute('product_id'));

        ev.dataTransfer.setData("product_code", ev.target.getAttribute('product_code'));


        ev.dataTransfer.setData("html",  ev.target.innerHTML );



    }

    function drop(ev) {

        ev.preventDefault();
        var product_showcase=  $( ev.target).closest('div.product_wrap').find('.product_showcase')
        var target_stack_index=product_showcase.attr('stack_index')

        if(target_stack_index==ev.dataTransfer.getData("stack_index")){
            return;
        }else if(target_stack_index<ev.dataTransfer.getData("stack_index")){

            var tmp_html = product_showcase.html();
            var tmp_product_code = product_showcase.attr('product_code');
            var tmp_product_id = product_showcase.attr('product_id');

            product_showcase.html(ev.dataTransfer.getData("html"))
            product_showcase.attr('product_code',ev.dataTransfer.getData("product_code"))
            product_showcase.attr('product_id',ev.dataTransfer.getData("product_id"))

            change_next(  ev.dataTransfer.getData("stack_index") , tmp_html ,tmp_product_code,tmp_product_id,product_showcase.closest('.product_wrap').next().find('.product_showcase'))
            save_stack_index(target_stack_index,ev.dataTransfer.getData("product_id"))

        }else{


        }



    }

    function change_next(pivot_id,html,product_code,product_id,element){

        if(element.attr('id')==undefined   ){
            return;
        }

        var tmp_html = element.html();
        var tmp_product_code = element.attr('product_code');
        var tmp_product_id = element.attr('product_id');

        element.html(html)
        element.attr('product_code',product_code)
        element.attr('product_id',product_id)

        if( element.attr('stack_index')==pivot_id  ){
            return;
        }
        change_next(pivot_id,tmp_html,tmp_product_code,tmp_product_id,element.closest('.product_wrap').next().find('.product_showcase'))


    }

    function  save_stack_index(stack_index,product_id) {

        var request = '/ar_edit.php?tipo=edit_category_stack_index&key=' + {$category->id} + '&stack_index=' + (stack_index-0.5) + '&subject_key='+product_id
        console.log(request)
        $.getJSON(request, function (data) {

        })
    }

    function  save_element_state(element_id,element_state) {

        var request = '/ar_edit.php?tipo=edit_webpage_element_state&key=' + {$webpage->id} + '&element_id=' +element_id + '&state_data' + element_state
        console.log(request)
        $.getJSON(request, function (data) {

        })
    }


   


    $('.webpage_content_header')
            .draggable(
                    {
                        containment: "#description_block",
                        scroll: false,
                        stop: function (event, ui) {

                            console.log(this.id)
                            console.log(ui.position)

                        }
                    }

            )
            .resizable(

                    {
                        stop: function (event, ui) {

                            console.log(this.id)
                            console.log(ui.size)


                        }

                    }

            );


    $('.order_button').hover(
            function() {
                var input= $( this ).closest('.ordering').find('.order_input');
                if(input.val()==''){
                    $( this ).closest('.ordering').find('.order_input').val(1)
                }


            }, function() {
                var input= $( this ).closest('.ordering').find('.order_input');
                if(input.attr('ovalue')==''){
                    $( this ).closest('.ordering').find('.order_input').val('')
                }
            }
    );


    $('.order_button').click(function() {
        var input= $( this ).closest('.ordering').find('.order_input');

        var order_qty=input.val()

        input.attr('ovalue',order_qty)



        if(order_qty>0){
            $(this).html($('#ordering_settings').data('labels').ordered).addClass('ordered')
        }else{
            $(this).html($('#ordering_settings').data('labels').order).removeClass('ordered')

        }


    });


    $(".order_input").on('input propertychange', function(){

        var order_qty=$(this).val()

        var button=$( this ).closest('.ordering').find('.order_button');

        if(order_qty!=$(this).attr('ovalue')){


            button.html( $('#ordering_settings').data('labels').update).addClass('ordered')
        }else{

            if(order_qty>0){
                button.html($('#ordering_settings').data('labels').ordered).addClass('ordered')
            }else{
                button.html($('#ordering_settings').data('labels').order).removeClass('ordered')

            }

        }

    });




</script>