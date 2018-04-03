{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 April 2018 at 23:52:59 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>
    #sections_list {
        z-index: 1001;
        position: absolute;
        width: 300px;
        padding: 10px 20px 20px 20px;
        border: 1px solid #ccc;
        background: #fff;
        color: #555
    }

    #sections_list table {
        width: 100%;
    }

    #sections_list td {
        border: 1px solid #ccc;
        padding: 3px 7px;

    }

    #sections_list td.discreet {
        cursor: no-drop;
        opacity: .5;

    }


</style>

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="panel_txt_control" class="hide">
    <div class="panel_txt_control" style="padding:2px 10px;z-index:2001;position: absolute;top:-30px;width:100%;height: 30px;border:1px solid #ccc;background: #fff;border-bottom: none">
        <i class="fa fa-expand" title="{t}Padding{/t}"></i> <input size="2" style="height: 16px;" value="20">
        <i class="far fa-trash-alt padding_left_10 like_button" title="{t}Delete{/t}"></i>

        <i onclick="close_panel_text(this)" class="fa fa-window-close button" style="float: right;margin-top:6px" title="{t}Close text edit mode{/t}"></i>

    </div>
</div>


<div class="hide">
    <span class="button" style="position: relative;top: 5px; left:20px;"><i class="fas fa-plus "></i> {t}Add category{/t}</span> <span class="button" style="margin-left:20px;position: relative;top: 5px; left:20px;"><i
                class="fas fa-trash-alt "></i> {t}Delete this section{/t}</span>
</div>


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    {foreach from=$data.items item=item}


        <div class="product_wrap item_wrap"  >


            {if $item.type=='product'}

                <div class="product_block product_showcase " style="position:relative">
                    <div class="product_header_text " >
                        {$item.header_text}
                    </div>

                    <div class="wrap_to_center product_image" >
                        <i class="fa fa-info-circle more_info" aria-hidden="true"></i>
                        <img src="{$item.image_src}" />
                    </div>


                    <div class="product_description"  >
                        <span class="code">{$product->get('Code')}</span>
                        <div class="name item_name">{$product->get('Name')}</div>

                    </div>


                    <div class="product_prices log_in " >
                        <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$product->get('Price')}</div>
                        {assign 'rrp' $product->get('RRP')}
                        {if $rrp!=''}<div>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                    </div>

                    <div class="product_prices log_out hide" >
                        <div >{if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}</div>
                    </div>


                    {if $product->get('Web State')=='Out of Stock'}
                        <div class="ordering log_in can_not_order {$product->get('Out of Stock Class')} ">

                            <span class="product_footer label ">{$product->get('Out of Stock Label')}</span>
                            <span class="product_footer reminder"><i class="fa fa-envelope" aria-hidden="true"></i>  </span>


                        </div>
                    {elseif $product->get('Web State')=='For Sale'}

                        <div class="ordering log_in " >
                            <input maxlength=6  class='order_input ' id='but_qty{$product->id}'   type="text" size='2'  value='{$product->get('Ordered Quantity')}' ovalue='{$product->get('Ordered Quantity')}'>
                            <span class="product_footer order_button"   ><i class="fa fa-hand-pointer" aria-hidden="true"></i> {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>
                            <span class="product_footer  favourite "><i class="fa fa-heart" aria-hidden="true"></i>  </span>


                        </div>



                    {/if}
                    <div class="ordering log_out hide" >

                        <div ><span class="login_button" >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                        <div ><span class="register_button" > {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>


                    </div>


                </div>
                <div class="product_block item_overlay hide" >



                    <div class="buttons panel_type"  >
                        <div class="flex-item button" type="image"><i class="fa fa-image" aria-hidden="true"></i></div>
                        <div class="flex-item button" type="text"><i class="fa fa-align-center" aria-hidden="true"></i></div>
                        <div class="flex-item button " type="code"><i class="fa fa-code" aria-hidden="true"></i></div>
                        <div class="flex-item button invisible" type="banner"><i class="fa fa-bullhorn" aria-hidden="true"></i></div>
                    </div>



                    <div  class="buttons super_discreet panel_size">
                        <div class="flex-item {if $item.data.max_free_slots<1}hide{/if}" size="1" >1x</div>
                        <div class="flex-item {if $item.data.max_free_slots<2}hide{/if}" size="2">2x</div>
                        <div class="flex-item {if $item.data.max_free_slots<3}hide{/if}" size="3">3x</div>
                        <div class="flex-item {if $item.data.max_free_slots<4}hide{/if}" size="4">4x</div>
                    </div>




                </div>
            {else}

                {if $item.data.type=='text'}
                    <div id="{$item.data.id}" style="position:relative" class=" panel  panel_{$item.data.size} {$item.data.class}">
                        <div  class="edit_toolbar hide" section="panels"  style=" z-index: 200;position:absolute;left:-20px;top:7px;">
                            <i class="fa close_edit_text fa-window-close fa-fw button text" style="margin-bottom:10px" aria-hidden="true"></i><br>
                            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>

                        </div>

                        <div class="panel_content fr-view">
                            {$item.data.content}
                        </div>




                        <div class="panel_controls hide">
                            <div class="panel_settings buttons hide" >


                                <div class="flex-item button" type="update_text"><i class="fa fa-pencil edit_text_icon" aria-hidden="true"></i></div>
                                <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>

                                <div class="flex-item button invisible" type="update_class"><i class="fa fa-css3  class_icon" aria-hidden="true"></i></div>
                                <div class="flex-item button invisible" ><i class="fa" aria-hidden="true"></i></div>

                            </div>
                        </div>

                    </div>



                {elseif $item.data.type=='image'}
                    <div id="{$item.data.id}" style="position:relative" class=" panel image panel_{$item.data.size}">



                        <img  class="panel_image" src="{$item.data.image_src}"  title="{$item.data.caption}" />

                        <div class="panel_controls hide">
                            <div class="panel_settings buttons hide">




                                <div class="flex-item button" type="update_image" title="{t}Change image{/t}">
                                    <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                                        <input type="file" name="image_upload"  panel_key="{$item.data.id}" id="file_upload_{$item.data.id}" class="input_file input_file_panel " multiple/>
                                        <label for="file_upload_{$item.data.id}">
                                            <i class="fa  fa-image fa-fw button" aria-hidden="true"></i><br>
                                        </label>
                                    </form>
                                </div>
                                <div class="flex-item button" type="update_caption"><i class="fa fa-comment caption_icon" aria-hidden="true"></i></div>
                                <div class="flex-item button" type="update_link"><i class="fa fa-link link_url_icon link_icon" aria-hidden="true"></i></div>
                                <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>
                            </div>

                            <div style="position: absolute;color: white;top:60px;left:12px">
                                {if $item.data.size=='1x'}
                                    220 x 320
                                {elseif $item.data.size=='2x'}
                                    457 x 320
                                {elseif $item.data.size=='3x'}
                                    696 x 320
                                {elseif $item.data.size=='4x'}
                                    934 x {t}any height{/t}
                                {/if}
                            </div>

                            <div class="input_container caption hide column_{$stack_index % 4}  " style="">
                                <input  value="{$item.data.caption}" >
                            </div>
                            <div class="input_container link_url hide column_{$stack_index % 4}  " style="">
                                <input  value="{$item.data.link}" placeholder="http://">
                            </div>
                        </div>
                    </div>


                {elseif $item.data.type=='code'}
                    <div id="{$item.data.id}" code_key="{$item.data.key}" style="position:relative;" class=" panel image panel_{$item.data.size}">


                        <div  class="edit_toolbar hide" section="panels"  style=" z-index: 200;position:absolute;left:-20px;top:7px;">
                            <i class="fa close_edit_text fa-window-close fa-fw button code" style="margin-bottom:10px" aria-hidden="true"></i><br>
                            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>

                        </div>

                        <iframe class="" src="/panel_code.php?id={$item.data.key}"


                                style="position: absolute; height: 100%;width: 100%;padding:0px;margin:0px; "
                                marginwidth="0"
                                marginheight="0"
                                hspace="0"
                                vspace="0"
                                frameborder="0"
                                scrolling="no"



                                sandbox="allow-scripts allow-same-origin" />

                        <div class="code_editor_container hide">
                            <textarea  id="code_editor_{$item.data.key}"  style="width:100%;height: 100%">{$item.data.content}</textarea>

                        </div>
                        <div class="panel_controls hide">
                            <div class="panel_settings buttons hide">




                                <div class="flex-item button" type="update_code"><i class="fa fa-file-code-o code_icon" aria-hidden="true"></i></div>
                                <div class="flex-item button" type="delete_panel" title="{t}Delete panel{/t}"><i class="fa fa-trash error" aria-hidden="true"></i></div>
                            </div>



                            <div class="input_container caption hide column_{$stack_index % 4}  " style="">
                                <input  value="{$item.data.caption}" >
                            </div>
                            <div class="input_container link_url hide column_{$stack_index % 4}  " style="">
                                <input  value="{$item.data.link}" placeholder="http://">
                            </div>
                        </div>

                    </div>
                {/if}

            {/if}

        </div>


    {/foreach}


</div>




