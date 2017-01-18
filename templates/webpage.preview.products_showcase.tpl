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
    #sort_options { border:1px solid #ccc;background-color:white;position:absolute;z-index:200;padding:10px}
    #sort_options table{ backgroud-color:white}
    #sort_options  td{ padding:2px 20px}
</style>

<div id="sort_options" class="hide">

    <table>
        <tr>
            <td>{t}Code{/t}</td><td> <i class="fa fa-sort-alpha-asc button" aria-hidden="true" type="code_asc"></i> </td><td> <i class="fa fa-sort-alpha-desc button" aria-hidden="true" type="code_desc"></i></td>
        </tr>
        <tr>
            <td>{t}Name{/t}</td><td> <i class="fa fa-sort-alpha-asc button" aria-hidden="true" type="name_asc"></i> </td><td> <i class="fa fa-sort-alpha-desc button" aria-hidden="true" type="name_desc"></i></td>
        </tr>
        <tr>
            <td>{t}Best sellers{/t} </td><td> <i class="fa fa-sort-amount-desc button" aria-hidden="true" type="sales_asc"></i> </td><td> <i class="fa fa-sort-amount-asc button" aria-hidden="true"  type="sales_desc"></i></td>
        </tr>
        <tr>
            <td>{t}Release date{/t} </td><td> <i class="fa fa-sort-numeric-desc button" aria-hidden="true" type="date_asc"></i> </td><td> <i class="fa fa-sort-numeric-asc button" aria-hidden="true" type="date_desc"></i></td>
        </tr>
    </table>


</div>



<span id="ordering_settings" class="hide" data-labels='{ "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {t}Ordered{/t}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Order now{/t}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Update{/t}"  }'></span>



<div id="webpage_preview"  webpage_key="{$webpage->id}"  style="padding:20px;border-bottom:1px solid #ccc">



<span style="margin-right:20px" class="hide">
    <i class="fa fa-desktop padding_right_5 " aria-hidden="true"></i>
    <i class="fa fa-tablet padding_right_5" aria-hidden="true"></i>
    <i class="fa fa-mobile" aria-hidden="true"></i>
</span>

<span class="button " onclick="toggle_logged_in_view(this)"><i class="fa fa-toggle-on " aria-hidden="true" alt="{t}On{/t}"></i> <span class="unselectable">{t}Logged in{/t}</span></span>

    <a target="_blank" href="http://{$webpage->get('Page URL')}" ><i class="fa fa-external-link" aria-hidden="true"  style="float:right;margin-left:20px;position:relative;top:2px"></i>   </a>


    <span id="publish" class="button save {if $webpage->get('Publish')}changed valid{/if}" style="float:right" onclick="publish(this)"><span class="unselectable">{t}Publish{/t}</span> <i class="fa fa-rocket" aria-hidden="true"></i></span>




    <span style="float:right;margin-right:60px" >
    <i id="description_block_on" class="fa toggle_description_block fa-header fa-fw button" aria-hidden="true"  ></i>


    <span id="description_block_off"  class="toggle_description_block fa-stack hide button" style="position:relative;top:-5px;left:5px"  >
  <i class="fa fa-header fa-stack-1x"></i>
  <i class="fa fa-close fa-stack-2x very_discreet error"></i>
</span>

</span>









</div>


{assign 'see_also'  $category->webpage->get_see_also() }

{assign 'css'  $category->webpage->get('CSS') }

{include file="category.webpage.preview.style.tpl" }







<div id="page_content" style="position:relative">


    <div id="description_block" class="section description_block {$content_data.description_block.class} " >


        <i class="create_text fa fa-align-center fa-fw button" aria-hidden="true" style="position:absolute;left:-40px;top:10px"></i>
        <i class="create_image fa fa-picture-o fa-fw button" aria-hidden="true" style="position:absolute;left:-40px;top:30px"></i>


        <div id="image_edit_toolbar" class="edit_toolbar hide" section="description_block" style=" z-index: 200;position:relative;">
            <i class="fa fa-window-close fa-fw button" style="margin-bottom:10px" aria-hidden="true"></i><br>

            <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                <input type="file" name="image_upload" id="file_upload" class="input_file" multiple/>
                <label for="file_upload">
                    <i class="fa  fa-picture-o fa-fw button" aria-hidden="true"></i><br>
                </label>
            </form>



            <i class="fa caption_icon fa-comment  fa-fw button " style="margin-top:5px"  aria-hidden="true"></i><br>
            <div class="caption hide" >
                <input id="caption_input" value="" >
            </div>
            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>
      </div>



        <div id="text_edit_toolbar" class="edit_toolbar hide" section="description_block" style=" z-index: 200;position:relative;">
            <i class="fa fa-window-close fa-fw button" style="margin-bottom:10px" aria-hidden="true"></i><br>


            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>
        </div>





        {foreach from=$content_data.description_block.blocks key=id item=data}
            {if $data.type=='text'}

                <div id="{$id}" class="webpage_content_header webpage_content_header_text">
                    {$data.content}
                </div>
            {elseif $data.type=='image'}


                <div  id="{$id}" class="webpage_content_header webpage_content_header_image"  >
                   <img  src="{$data.image_src}"  style="width:100%"  title="{if isset($data.caption)}{$data.caption}{/if}" />
                </div>
            {/if}
        {/foreach}
        <div style="clear:both"></div>
    </div>

    <div id="items_container" class="product_blocks">
        <i id="add_panel" class="fa fa-cog button" aria-hidden="true" style="position:absolute;left:10px;margin-top:10px"></i>
        <i id="show_sort_options" class="fa fa-sort-alpha-asc button" aria-hidden="true" style="position:absolute;left:10px;margin-top:40px"></i>
        <div id="products_helper">
         {include file="webpage.preview.products_showcase.products.tpl" }
        </div>
        <div style="clear:both"></div>
    </div>


    <div id="related_products"  class="product_blocks {if $related_products|@count eq 0}hide{/if}">
        <div class="title">{t}Related products{/t}:</div>
        {foreach from=$related_products item=product_data key=stack_index}
            {assign 'product' $product_data.object}
            <div class="product_wrap">




        <div id="product_target_div_{$stack_index}"  index_key="{$product_data.index_key}"  stack_index="{$stack_index}" xdraggable="true" xondragstart="drag(event)" product_code="{$product->get('Code')}" product_id="{$product->id}" xondrop="drop(event)" xondragover="allowDrop(event)" class="product_block product_showcase " style="margin-bottom:20px;position:relative">
            <div class="product_header_text related_product fr-view" >
                {$product_data.header_text}
            </div>

        <div class="wrap_to_center product_image" onCLick="change_view('')">
            <img draggable="false" class="more_info" src="/art/moreinfo_corner1.png">
            <img draggable="false" src="{$product->get('Image')}" />
        </div>


        <div class="product_description"  >
            <span class="code">{$product->get('Code')}</span>
            <div class="name">{$product->get('Name')}</div>

        </div>


        <div class="product_prices log_in " >
            <div class="product_price">{t}Price{/t}: {$product->get('Price')}</div>
            {assign 'rrp' $product->get('RRP')}
            {if $rrp!=''}<div>{t}RRP{/t}: {$rrp}</div>{/if}
        </div>

        <div class="product_prices log_out hide" >
            <div >{t}For prices, please login or register{/t}</div>
        </div>


        {if $product->get('Web State')=='Out of Stock'}
            <div class="ordering log_in can_not_order {$product->get('Out of Stock Class')} ">

                <span class="product_footer label ">{$product->get('Out of Stock Label')}</span>
                <span class="product_footer reminder"><i class="fa fa-envelope-o" aria-hidden="true"></i>  </span>


            </div>
        {else if $product->get('Web State')=='For Sale'}

            <div class="ordering log_in " >
                <input maxlength=6  class='order_input ' id='but_qty{$product->id}'   type="text" size='2'  value='{$product->get('Ordered Quantity')}' ovalue='{$product->get('Ordered Quantity')}'>
                <span class="product_footer order_button"   ><i class="fa fa-hand-pointer-o" aria-hidden="true"></i> {t}Order now{/t}</span>
                <span class="product_footer  favorite "><i class="fa fa-heart-o" aria-hidden="true"></i>  </span>


            </div>



        {/if}
        <div class="ordering log_out hide" >
            <div ><span class="login_button" >{t}Login{/t}</span></div>
            <div ><span class="register_button" >{t}Register{/t}</span></div>
        </div>


    </div>





</div>
{/foreach}
        <div style="clear:both"></div>
    </div>

     <div id="bottom_see_also"  class="{if $see_also|@count eq 0}hide{/if}">
         <div class="title">{t}See also{/t}:</div>
         <div>
         {foreach from=$see_also item=see_also_item name=foo}
                <div class="item" >
                    <div class="image_container" >
                        <a href="http://{$see_also_item->get('URL')}"> <img src="{$see_also_item->get('Image')}" style="" /> </a>
                    </div>
                    <div class="label" >
                        {$see_also_item->get('Name')}
                    </div>
                </div>
            {/foreach}
             </div>
            <div style="clear:both">
            </div>

    </div>

</div>

<script>

    {include file="js/webpage.preview.publish.tpl.js" }
    {include file="js/webpage.preview.description_block.tpl.js" }
    {include file="js/webpage.preview.products.tpl.js" }
    {include file="js/webpage.preview.panels.tpl.js" }



    $('.favorite').click(function() {
        var icon= $( this ).find('i');


        if(icon.hasClass('fa-heart-o')){
            $( this ).addClass('marked')
            icon.removeClass('fa-heart-o').addClass('fa-heart')

        }else{
            $( this ).removeClass('marked')

            icon.removeClass('fa-heart').addClass('fa-heart-o')


        }

    });



    $('.reminder').click(function() {
        var icon= $( this ).find('i');


        if(icon.hasClass('fa-envelope-o')){
            $( this ).addClass('marked')
            icon.removeClass('fa-envelope-o').addClass('fa-envelope')

        }else{
            $( this ).removeClass('marked')

            icon.removeClass('fa-envelope').addClass('fa-envelope-o')


        }

    });




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



        $(this).val( $(this).val().replace(/[^\d]/g,'') )

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

    function toggle_logged_in_view(element){

        var icon=$(element).find('i')
        if(icon.hasClass('fa-toggle-on')){
            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')

            $('.product_prices.log_in').addClass('hide')
            $('.product_prices.log_out').removeClass('hide')

            $('.ordering.log_in').addClass('hide')
            $('.ordering.log_out').removeClass('hide')


        }else{
            icon.addClass('fa-toggle-on').removeClass('fa-toggle-off')

            $('.product_prices.log_in').removeClass('hide')
            $('.product_prices.log_out').addClass('hide')
            $('.ordering.log_in').removeClass('hide')
            $('.ordering.log_out').addClass('hide')

        }



    }

   // $('.image_upload').on('change', function (e) {});



</script>