{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 April 2018 at 23:52:59 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}





<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">


    <div class="products  {if !$data.item_headers}no_items_header{/if}"  data-sort="{$data.sort}" >
    {foreach from=$data.items item=item}


        <div class="product_wrap wrap type_{$item.type}" data-type="{$item.type}" {if $item.type=='product'} data-sort_code="{$item.sort_code}" data-sort_name="{$item.sort_name}{/if} ">


            {if $item.type=='product'}

                <div class="product_block item"

                     data-product_id="{$item.product_id}"
                     data-web_state="{$item.web_state}"
                     data-price="{$item.price}"
                     data-rrp="{$item.rrp}"
                     data-code="{$item.code}"
                     data-name="{$item.name}"
                     data-link="{$item.link}"
                     data-webpage_code="{$item.webpage_code}"
                     data-webpage_key="{$item.webpage_key}"
                     data-out_of_stock_class="{$item.out_of_stock_class}"
                     data-out_of_stock_label=""

                >




                    <div class="panel_txt_control hide">


                        <i onclick="close_product_header_text(this)" class="fa fa-window-close button" style="float: right;margin-top:6px;margin-right:6px" title="{t}Close text edit mode{/t}"></i>

                    </div>


                    <div class="product_header_text fr-view" >
                        {$item.header_text}
                    </div>

                    <div class="wrap_to_center product_image" >
                        <i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"></i>
                        <i class="far fa-fw  fa-heart favourite" aria-hidden="true"></i>

                        <img src="{$item.image_src}" data-src="{$item.image_src}"  data-image_website="{$item.image_website}" />
                    </div>


                    <div class="product_description"  >
                        <span class="code">{$item.code}</span>
                        <h4 class="name item_name">{$item.name}</h4>

                    </div>







                    <div class="product_prices log_in " >
                        <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$item.price} {if isset($item.price_unit)}<small>{$item.price_unit}</small>{/if}</div>
                        {if !empty($item.rrp)}<div><small>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$item.rrp}</small></div>{/if}
                    </div>


                    {if $item.web_state=='Out of Stock'}
                        <div class="ordering log_in can_not_order  out_of_stock_row  {$item.out_of_stock_class} ">

                            <span class="product_footer label ">{$item.out_of_stock_label}</span>
                            <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span>


                        </div>
                    {elseif  $item.web_state=='For Sale'}

                        <div class="order_row empty">
                            <input maxlength=6 class='order_input ' type="text"' size='2' value='' data-ovalue=''>

                                <div class="label sim_button" style="margin-left:57px">
                                    <i class="fa fa-hand-pointer fa-fw" aria-hidden="true"></i> <span >{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span></div>


                        </div>



                    {/if}
                    <div class="ordering log_out hide" >

                        <div ><span class="login_button " >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                        <div ><span class="register_button" > {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>


                    </div>


                </div>

            {elseif $item.type=='text'}
                <div  class="panel_txt_control hide" >
                    <span class="hide"><i class="fa fa-expand" title="{t}Padding{/t}"></i> <input size="2" style="height: 16px;" value="20"></span>
                    <i onclick="$(this).closest('.wrap').remove();$('#save_button',window.parent.document).addClass('save button changed valid')" class="far fa-trash-alt padding_left_10 like_button" title="{t}Delete{/t}"></i>
                    <i onclick="close_panel_text(this)" class="fa fa-window-close button" style="float: right;margin-top:6px" title="{t}Close text edit mode{/t}"></i>

                </div>
                <div size_class="{$item.size_class}" data-padding="{$item.padding}" class="fr-view txt {$item.size_class}">
                    <div  style="padding:{$item.padding}px">{$item.text}</div>
                </div>


            {elseif $item.type=='image'}


                <img class="panel edit {$item.size_class}" size_class="{$item.size_class}" src="{if !preg_match('/^http/',$item.image_website)}EcomB2B/{/if}{$item.image_website}"  data-image_website="{$item.image_website}"  data-src="{$item.image_src}"    link="{$item.link}"  alt="{$item.title}" />


            {elseif $item.type=='video'}

                <div class="panel  {$item.type} {$item.size_class}" size_class="{$item.size_class}" video_id="{$item.video_id}">
                    <iframe width="470" height="{if $data.item_headers}330{else}290{/if}" frameallowfullscreen="" src="https://www.youtube.com/embed/{$item.video_id}?rel=0&amp;controls=0&amp;showinfo=0"></iframe>
                    <div class="block_video" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
                </div>




            {/if}

        </div>


    {/foreach}
    </div>

    <div style="clear:both"></div>
</div>

<script>


    function jQueryTabs3() {
        $(".tabs3").each(function () {
            $(".tabs-panel3").not(":first").hide(), $("li", this).removeClass("active"), $("li:first-child", this).addClass("active"), $(".tabs-panel:first-child").show(), $("li", this).on('click',function (t){
                var i=$("a",this).attr("href");
                $(this).siblings().removeClass("active"),$(this).addClass("active"),$(i).siblings().hide(),$(i).fadeIn(400),t.preventDefault()}), $(window).width() < 100 && $(".tabs-panel3").show()
        })
    }

    console.log('cacac')




</script>



