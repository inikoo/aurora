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


    [contenteditable="true"]:empty {
        background-color: #f4f4f4;
        padding: 10px;
    }

    .discount_card{

        border:1px solid #ccc;width:300px;margin-left:10px;float:right;padding:2px 4px

    }

    .discount_card .discount_name{
        font-size: 90%;
    }

    .discount_card  .discount_allowance{
       font-weight: 800;
    }

    .parent_up{
        font-weight:800;
        font-size:18px;
        cursor: pointer;
    }

    #sort_options {
        border: 1px solid #ccc;
        background-color: white;
        position: absolute;
        z-index: 200;
        padding: 10px
    }

    #sort_options table {
        backgroud-color: white
    }

    #sort_options td {
        padding: 2px 20px
    }
</style>

<div id="sort_options" class="hide">

    <table>
        <tr>
            <td>{t}Code{/t}</td>
            <td><i class="fa fa-sort-alpha-down button" aria-hidden="true" type="code_asc"></i></td>
            <td><i class="fa fa-sort-alpha-up button" aria-hidden="true" type="code_desc"></i></td>
        </tr>
        <tr>
            <td>{t}Name{/t}</td>
            <td><i class="fa fa-sort-alpha-down button" aria-hidden="true" type="name_asc"></i></td>
            <td><i class="fa fa-sort-alpha-up button" aria-hidden="true" type="name_desc"></i></td>
        </tr>
        <tr>
            <td>{t}Best sellers{/t} </td>
            <td><i class="fa fa-sort-amount-down button" aria-hidden="true" type="sales_asc"></i></td>
            <td><i class="fa fa-sort-amount-up button" aria-hidden="true" type="sales_desc"></i></td>
        </tr>
        <tr>
            <td>{t}Release date{/t} </td>
            <td><i class="fa fa-sort-numeric-up button" aria-hidden="true" type="date_asc"></i></td>
            <td><i class="fa fa-sort-numeric-down button" aria-hidden="true" type="date_desc"></i></td>
        </tr>
    </table>


</div>


<div id="code_editor_dialog" class="hide" style="width:920px;height: 300px;position:absolute;left:90px;border:1px solid #ccc;background-color:white;padding:20px 20px 20px 25px;;z-index: 400">
    <div class="edit_toolbar " section="panels" style=" z-index: 200;position:absolute;left:4px;top:17px;">
        <i id="save_code" class="fa close_edit_text fa-window-close fa-fw button code" style="margin-bottom:10px" aria-hidden="true"></i><br>
        <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>

    </div>

    <textarea id="code_editor" style="margin-left:20px;"></textarea>
</div>


<span id="ordering_settings" class="hide"
      data-labels='{ "ordered":"<i class=\"fa fa-thumbs-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Update{/t}"  }'></span>


<div id="webpage_preview" webpage_key="{$webpage->id}" style="padding:20px;border-bottom:1px solid #ccc">



<span style="margin-right:20px" class="hide">
    <i class="fa fa-desktop padding_right_5 " aria-hidden="true"></i>
    <i class="fa fa-tablet padding_right_5" aria-hidden="true"></i>
    <i class="fa fa-mobile" aria-hidden="true"></i>
</span>


    <span style="border:1px solid #ccc;padding:10px 10px "><i class="fa fa-diamond" aria-hidden="true"></i> {t}Category Description{/t}</span>
    <span style="border:1px solid #ccc;padding:10px 10px "><i class="fa fa-cubes" aria-hidden="true"></i> {t}Products{/t}</span>


    {if $website->get('Website Status')=='Active'}
        <a target="_blank" href="{$webpage->get('URL')}"><i class="fa fa-external-link" aria-hidden="true" style="float:right;margin-left:20px;position:relative;top:2px"></i> </a>
        <span id="publish" webpage_key="{$webpage->id}" class="button save {if $webpage->get('Publish')}changed valid{/if}" style="float:right" onclick="publish(this,'publish_webpage')">
        <span class="unselectable">{t}Publish{/t}</span> <i class="fa fa-rocket" aria-hidden="true"></i>
    </span>
    {elseif $website->get('Website Status')=='InProcess'}
        <span id="set_as_ready_webpage_field" style="margin:10px 0px;padding:10px;border:1px solid #ccc;float:right;position: relative;top:-22px" webpage_key="{$webpage->id}"
              onClick="publish(this,'set_webpage_as_ready')" class=" button   {if $webpage->get('Webpage State')=='Ready'}hide{/if} ">{t}Set as Ready{/t} <i class="fa fa-check-circle padding_left_5  button  "></i></span>
        <span id="set_as_not_ready_webpage_field" style="margin:10px 0px;padding:10px;border:1px solid #ccc;float:right;position: relative;top:-22px" webpage_key="{$webpage->id}"
              onClick="publish(this,'set_webpage_as_not_ready')" class=" button super_discreet {if $webpage->get('Webpage State')=='InProcess'}hide{/if} ">{t}Set as not Ready{/t} <i
                    class="fa fa-child padding_left_5 hide button"></i></span>
    {/if}


    <span style="float:right;margin-right:60px">





<span class="button " onclick="toggle_logged_in_view(this)"><i class="fa fa-toggle-on " aria-hidden="true" alt="{t}On{/t}"></i> <span class="unselectable">{t}Logged in{/t}</span></span>

    <i id="description_block_on" class="hide fa toggle_description_block fa-header fa-fw button" aria-hidden="true"></i>


    <span id="description_block_off" class="toggle_description_block fa-stack hide button" style="position:relative;top:-5px;left:5px">
  <i class="fa fa-header fa-stack-1x"></i>
  <i class="fa fa-close fa-stack-2x very_discreet error"></i>
</span>

</span>


</div>


{assign 'see_also'  $category->webpage->get_see_also() }
{assign 'css'  $category->webpage->get('CSS') }
{include file="category.webpage.preview.style.tpl" }


<div id="page_content" style="position:relative">
    {if $category->get('Product Category Status')=='Discontinued'}
        <div class="section description_block alert alert-error alert-title" style="text-align:center">
            <i class="fa fa-frown-o padding_right_20" aria-hidden="true"></i> {t}Discontinued{/t} <i class="fa fa-frown-o padding_left_20" aria-hidden="true"></i>
        </div>
    {/if}



   <div class="description_block">

       <span class="parent_up">
       <i class="fa fa-arrow-up" aria-hidden="true"></i>
       {foreach from=$category->get_parent_categories('data') item=item key=key}
           <span  >{$item.label}</span>
           {break}
       {/foreach}
           </span>


       {foreach from=$category->get_deal_components('objects') item=item key=key}
           <div class="discount_card" key="{$item->id}" >
               <span class="discount_icon">{$item->get('Deal Component Icon')}</span>

               <span contenteditable="true" class="discount_name">{$item->get('Deal Component Name Label')}</span><br/>
               <span contenteditable="true"  class="discount_term">{$item->get('Deal Component Term Label')}</span>

               <span contenteditable="true"  class="discount_allowance">{$item->get('Deal Component Allowance Label')}</span>

           </div>

       {/foreach}


<div style="clear: both"></div>

   </div>

    <div id="description_block" class="section description_block {$content_data.description_block.class} ">


        <i class="_description_block_edit  create_text fa fa-align-center fa-fw button" aria-hidden="true" style="position:absolute;left:-40px;top:10px"></i>
        <i class="_description_block_edit create_image fa fa-image fa-fw button" aria-hidden="true" style="position:absolute;left:-40px;top:30px"></i>


        <div id="image_edit_toolbar" class="edit_toolbar hide" section="description_block" style=" z-index: 200;position:relative;">
            <i class="fa fa-window-close fa-fw button" style="margin-bottom:10px" aria-hidden="true"></i><br>

            <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                <input type="file" name="image_upload" id="file_upload" class="input_file" multiple/>
                <label for="file_upload">
                    <i class="fa  fa-image fa-fw button" aria-hidden="true" style="position:relative;left:-18px"></i><br>
                </label>
            </form>


            <i class="fa caption_icon fa-comment  fa-fw button " style="margin-top:5px" aria-hidden="true"></i><br>
            <div class="caption hide">
                <input id="caption_input" value="">
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
                <div id="{$id}" class="webpage_content_header webpage_content_header_image">
                    <img src="{$data.image_src}" style="width:100%" title="{if isset($data.caption)}{$data.caption}{/if}"/>
                </div>
            {/if}
        {/foreach}
        <div style="clear:both"></div>
    </div>

    {if $category->get('Product Category Status')=='Discontinued'}
        <div class="section description_block alert alert-error alert-title" style="text-align:center">
            {t}Sorry, but all products in this web page are discontinued{/t}
        </div>
    {/if}

    <div id="items_container" class="product_blocks">
        <i id="add_panel" class="fa fa-cog button" aria-hidden="true" style="position:absolute;left:10px;margin-top:10px"></i>
        <i id="show_sort_options" class="fa fa-sort-alpha-down button" aria-hidden="true" style="position:absolute;left:10px;margin-top:40px"></i>
        <div id="products_helper">
            {include file="webpage.preview.products_showcase.products.tpl" }
        </div>
        <div style="clear:both"></div>
    </div>


    <div id="related_products" class="product_blocks {if $related_products|@count eq 0}hide{/if}">
        <div class="title">{t}Related products{/t}:</div>
        {foreach from=$related_products item=product_data key=stack_index}
            {assign 'product' $product_data.object}
            <div class="product_wrap">


                <div id="product_target_div_{$stack_index}" index_key="{$product_data.index_key}" stack_index="{$stack_index}" xdraggable="true" xondragstart="drag(event)" product_code="{$product->get('Code')}"
                     product_id="{$product->id}" xondrop="drop(event)" xondragover="allowDrop(event)" class="product_block product_showcase " style="margin-bottom:20px;position:relative">
                    <div class="product_header_text related_product fr-view">
                        {$product_data.header_text}
                    </div>


                    <div class="wrap_to_center product_image" onCLick="console.log('move')">

                        <i class="fa fa-info-circle more_info" aria-hidden="true"></i>


                        <img draggable="false" src="{$product->get('Image')}"/>
                    </div>


                    <div class="product_description">
                        <span class="code">{$product->get('Code')}</span>
                        <div class="name">{$product->get('Name')}</div>

                    </div>


                    <div class="product_prices log_in ">
                        <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$product->get('Price')}</div>
                        {assign 'rrp' $product->get('RRP')}
                        {if $rrp!=''}
                            <div>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                    </div>

                    <div class="product_prices log_out hide">
                        <div>{if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}</div>
                    </div>


                    {if $product->get('Web State')=='Out of Stock'}
                        <div class="ordering log_in can_not_order {$product->get('Out of Stock Class')} ">

                            <span class="product_footer label ">{$product->get('Out of Stock Label')}</span>
                            <span class="product_footer reminder"><i class="fa fa-envelope" aria-hidden="true"></i>  </span>


                        </div>
                    {else if $product->get('Web State')=='For Sale'}
                        <div class="ordering log_in ">
                            <input maxlength=6 class='order_input ' id='but_qty{$product->id}' type="text" size='2' value='{$product->get('Ordered Quantity')}' ovalue='{$product->get('Ordered Quantity')}'>
                            <span class="product_footer order_button"><i class="fa fa-hand-pointer"
                                                                         aria-hidden="true"></i> {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>
                            <span class="product_footer  favourite "><i class="fa fa-heart" aria-hidden="true"></i>  </span>


                        </div>
                    {/if}
                    <div class="ordering log_out hide">
                        <div><span class="login_button">{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                        <div><span class="register_button"> {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>
                    </div>


                </div>


            </div>
        {/foreach}
        <div style="clear:both"></div>
    </div>

    <div id="bottom_see_also" class="{if $see_also|@count eq 0}hide{/if}">
        <div class="title">{t}See also{/t}:</div>
        <div>
            {foreach from=$see_also item=see_also_item name=foo}
                <div class="item">
                    <div class="image_container">
                        <a href="https://{$see_also_item->get('URL')}"> <img src="{$see_also_item->get('Image')}" style=""/> </a>
                    </div>
                    <div class="label">
                        {$see_also_item->get('Name')}
                    </div>
                </div>
            {/foreach}
        </div>
        <div style="clear:both"></div>

    </div>

</div>

<script>


    var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('code_editor'), {
        lineNumbers: true, styleActiveLine: true, matchBrackets: true, theme: 'dracula'
    });

    $('#code_editor').data('CodeMirrorInstance', myCodeMirror);


    {include file="js/webpage.preview.publish.tpl.js" }
    {include file="js/webpage.preview.description_block.tpl.js" }
    {include file="js/webpage.preview.discounts.tpl.js" }

    {include file="js/webpage.preview.products.tpl.js" }
    {include file="js/webpage.preview.panels.tpl.js" }



    $('.favourite').click(function () {
        var icon = $(this).find('i');


        if (icon.hasClass('far.fa-heart')) {
            $(this).removeClass('marked')
            icon.removeClass('fa-heart').addClass('fa-heart')

        } else {

            $(this).addClass('marked')
            icon.removeClass('fa-heart').addClass('fa-heart')

        }

    });


    $('.reminder').click(function () {
        var icon = $(this).find('i');


        if (icon.hasClass('fa-envelope')) {
            $(this).addClass('marked')
            icon.removeClass('fa-envelope').addClass('fa-envelope')

        } else {
            $(this).removeClass('marked')

            icon.removeClass('fa-envelope').addClass('fa-envelope')


        }

    });


    $('.order_button').hover(function () {
        var input = $(this).closest('.ordering').find('.order_input');
        if (input.val() == '') {
            $(this).closest('.ordering').find('.order_input').val(1)
        }


    }, function () {
        var input = $(this).closest('.ordering').find('.order_input');
        if (input.attr('ovalue') == '') {
            $(this).closest('.ordering').find('.order_input').val('')
        }
    });

    $('.order_button').click(function () {
        var input = $(this).closest('.ordering').find('.order_input');

        var order_qty = input.val()

        input.attr('ovalue', order_qty)


        if (order_qty > 0) {
            $(this).html($('#ordering_settings').data('labels').ordered).addClass('ordered')
        } else {
            $(this).html($('#ordering_settings').data('labels').order).removeClass('ordered')

        }


    });


    $(".order_input").on('input propertychange', function () {


        $(this).val($(this).val().replace(/[^\d]/g, ''))

        var order_qty = $(this).val()

        var button = $(this).closest('.ordering').find('.order_button');

        if (order_qty != $(this).attr('ovalue')) {


            button.html($('#ordering_settings').data('labels').update).addClass('ordered')
        } else {

            if (order_qty > 0) {
                button.html($('#ordering_settings').data('labels').ordered).addClass('ordered')
            } else {
                button.html($('#ordering_settings').data('labels').order).removeClass('ordered')

            }

        }

    });

    function toggle_logged_in_view(element) {

        var icon = $(element).find('i')
        if (icon.hasClass('fa-toggle-on')) {
            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')

            $('.product_prices.log_in').addClass('hide')
            $('.product_prices.log_out').removeClass('hide')

            $('.ordering.log_in').addClass('hide')
            $('.ordering.log_out').removeClass('hide')


        } else {
            icon.addClass('fa-toggle-on').removeClass('fa-toggle-off')

            $('.product_prices.log_in').removeClass('hide')
            $('.product_prices.log_out').addClass('hide')
            $('.ordering.log_in').removeClass('hide')
            $('.ordering.log_out').addClass('hide')

        }


    }

    // $('.image_upload').on('change', function (e) {});


</script>