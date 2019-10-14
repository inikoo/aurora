{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2018 at 13:29:26 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

{include file="theme_1/_head.theme_1.tpl"}

<style>

    .header_text div{
        display: inline;padding: 0px;margin: 0px;
    }

    .object_control_panel td{
        padding:0px 4px;
    }

    #aux{
        color:#555
    }

    </style>

<script src="js/website_style.js?v=3"></script>
<script src="js/edit_webpage_upload_images_from_iframe.js"></script>


<body xmlns="http://www.w3.org/1999/html">

<div class="wrapper_boxed">


    <div id="aux" class="">



        <div id="header_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 5002;">



            <div class="handle" style="border-bottom: 1px solid #ccc;width: 100%;line-height: 30px;height: 30px">
                <i class="fa fa-window-close button padding_left_10" onclick="$('#header_style').addClass('hide')"></i>
            </div>
            <div style="padding: 20px">
                <table >


                    <tr>
                        <td class="label">{t}Header height{/t}</td>
                        <td class="margins_container unselectable border border-width" data-scope="header_height">
                            <input   class="header_height edit_margin " value=""  placeholder="0">

                            <i class="fa fa-plus-circle padding_left_10 like_button up_margins"></i>
                            <i class="fa fa-minus-circle padding_left_5 like_button down_margins"></i>

                        </td>
                    </tr>



                    <tr>
                        <td class="label">{t}Header text{/t}</td>
                        <td>
                     <span data-scope="header_color" class="fa-stack color_picker scope_header_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>



                        </td>
                    </tr>


                    <tr>
                        <td id="" class="label">{t}Header background{/t}</td>
                        <td>
                    <span data-scope="header_background-color" class="fa-stack color_picker scope_header_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>



                        </td>
                    </tr>




                </table>


            </div>




        </div>



        <div id="footer_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 5002;">


            <div class="handle" style="border-bottom: 1px solid #ccc;width: 100%;line-height: 30px;height: 30px">
                <i class="fa fa-window-close button padding_left_10" onclick="$('#footer_style').addClass('hide')"></i>
            </div>
            <div style="padding: 20px">
                <table>

                    <tr>
                        <td class="label">{t}Footer text{/t}</td>
                        <td>
                     <span data-scope="real_footer_color" class="fa-stack color_picker scope_footer_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>


                        </td>
                    </tr>

                    <tr>
                        <td id="" class="label">{t}Footer background{/t}</td>
                        <td>
                    <span data-scope="real_footer_background-color" class="fa-stack color_picker scope_footer_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>


                        </td>
                    </tr>

                    <tr>
                        <td class="label">{t}Lower footer text{/t}</td>
                        <td>
                     <span data-scope="lower_footer_color" class="fa-stack color_picker scope_lower_footer_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>


                        </td>
                    </tr>

                    <tr>
                        <td id="" class="label">{t}Lower footer background{/t}</td>
                        <td>
                    <span data-scope="lower_footer_background-color" class="fa-stack color_picker scope_lower_footer_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>


                        </td>
                    </tr>


                </table>


            </div>


        </div>


        <div id="menu_bar_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 5002;">



            <div class="handle" style="border-bottom: 1px solid #ccc;width: 100%;line-height: 30px;height: 30px">
                <i class="fa fa-window-close button padding_left_10" onclick="$('#menu_bar_style').addClass('hide')"></i>
            </div>
            <div style="padding: 20px">
                <table >

                    <tr>
                        <td id="" class="label">{t}Control area background{/t}</td>
                        <td>
                    <span data-scope="menu_bar_background-color" class="fa-stack color_picker scope_menu_bar_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>






                        </td>
                    </tr>

                    <tr>
                        <td class="label">{t}Control button text{/t}</td>
                        <td>
                     <span data-scope="menu_button_color" class="fa-stack color_picker scope_menu_button_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                            <span data-scope="menu_button_hover_color" class="fa-stack color_picker scope_menu_button_hover_color like_button" title="hover">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span> <small style="position:relative;left:-8px;top:1px;opacity:.5" >:hover</small>

                        </td>
                    </tr>
                    <tr>
                        <td id="" class="label">{t}Control button background{/t}</td>
                        <td>
                     <span data-scope="menu_button_background-color" class="fa-stack color_picker scope_menu_button_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                            <span data-scope="menu_button_hover_background-color" class="fa-stack color_picker scope_menu_button_hover_background-color like_button" title="hover">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span> <small style="position:relative;left:-8px;top:1px;opacity:.5" >:hover</small>

                        </td>
                    </tr>


                    <tr style="border-top:1px solid #ccc">
                        <td class="label">{t}Menu text{/t}</td>
                        <td>
                     <span data-scope="menu_color" class="fa-stack color_picker scope_menu_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                            <span data-scope="menu_hover_color" class="fa-stack color_picker scope_menu_hover_color like_button" title="hover">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span> <small style="position:relative;left:-8px;top:1px;opacity:.5" >:hover</small>

                        </td>
                    </tr>


                    <tr>
                        <td class="label">{t}Menu background{/t}</td>
                        <td>
                     <span data-scope="menu_background-color" class="fa-stack color_picker scope_menu_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                            <span data-scope="menu_hover_background-color" class="fa-stack color_picker scope_menu_hover_background-color like_button" title="hover">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span> <small style="position:relative;left:-8px;top:1px;opacity:.5" >:hover & {t}border{/t}</small>

                        </td>
                    </tr>
                    <tr style="border-top:1px solid #ccc">

                        <td class="label">{t}Submenu text{/t}</td>
                        <td>
                     <span data-scope="submenu_color" class="fa-stack color_picker scope_submenu_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>


                        </td>
                    </tr>

                    <tr >

                    <td class="label">{t}Submenu background{/t}</td>
                        <td>
                     <span data-scope="submenu_background-color" class="fa-stack color_picker scope_submenu_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>


                        </td>
                    </tr>

                    <tr>
                        <td class="label">{t}Submenu item text{/t}</td>
                        <td>
                     <span data-scope="submenu_item_color" class="fa-stack color_picker scope_submenu_item_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                            <span data-scope="submenu_item_hover_color" class="fa-stack color_picker scope_submenu_item_hover_color like_button" title="hover">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span> <small style="position:relative;left:-8px;top:1px;opacity:.5" >:hover</small>

                        </td>
                    </tr>
                    <tr>
                        <td id="" class="label">{t}Submenu item background{/t}</td>
                        <td>
                     <span data-scope="submenu_item_background-color" class="fa-stack color_picker scope_submenu_item_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                            <span data-scope="submenu_item_hover_background-color" class="fa-stack color_picker scope_submenu_item_hover_background-color like_button" title="hover">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span> <small style="position:relative;left:-8px;top:1px;opacity:.5" >:hover</small>

                        </td>
                    </tr>


                </table>


            </div>




        </div>




        <div id="navigation_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 3001;">



            <div class="handle" style="border-bottom: 1px solid #ccc;width: 100%;line-height: 30px;height: 30px">
                <i class="fa fa-window-close button padding_left_10" onclick="$('#navigation_style').addClass('hide')"></i>
            </div>
            <div style="padding: 20px">
                <table >
                    <tr>
                        <td class="label">{t}Navigation text{/t}</td>
                        <td>
                     <span data-scope="navigation_color" class="fa-stack color_picker scope_navigation_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>
                    <tr>
                        <td id="" class="label">{t}Navigation background{/t}</td>
                        <td>
                    <span data-scope="navigation_background-color" class="fa-stack color_picker scope_navigation_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>








                    <tr>
                        <td class="label">{t}Navigation bottom border{/t}</td>
                        <td class="margins_container unselectable border border-width" data-scope="navigation_bottom_border">

                            <input data-margin="bottom-width" class=" edit_margin navigation_bottom_border" value="" style="" placeholder="0">

                            <i class="fa fa-plus-circle padding_left_10 like_button up_margins"></i>
                            <i class="fa fa-minus-circle padding_left_5 like_button down_margins"></i>



                            <span data-scope="navigation_border_bottom_color" class="fa-stack color_picker scope_navigation_border_bottom_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>



                </table>


            </div>




        </div>



        <div id="body_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 3001;">



            <div class="handle" style="border-bottom: 1px solid #ccc;width: 100%;line-height: 30px;height: 30px">
                <i class="fa fa-window-close button padding_left_10" onclick="$('#body_style').addClass('hide')"></i>
            </div>
            <div style="padding: 20px">
                <table >



                    <tr>
                        <td class="label">{t}Body text{/t}</td>
                        <td>
                     <span data-scope="body_color" class="fa-stack color_picker scope_body_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>
                    <tr>
                        <td id="" class="label">{t}Body background{/t}</td>
                        <td>
                    <span data-scope="body_background-color" class="fa-stack color_picker scope_body_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>


                    <tr>
                        <td id="" class="label">{t}Outside background{/t}</td>
                        <td>
                    <span data-scope="scope_outside_background-color" class="fa-stack color_picker scope_outside_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>


                </table>


            </div>




        </div>


        <div id="button_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 3001;">



            <div class="handle" style="border-bottom: 1px solid #ccc;width: 100%;line-height: 30px;height: 30px">
                <i class="fa fa-window-close button padding_left_10" onclick="$('#button_style').addClass('hide')"></i>
            </div>
            <div style="padding: 20px">
                <table >

                    <tr>
                        <td id="" class="label">{t}Button text{/t}</td>
                        <td>
                    <span data-scope="button_color" class="fa-stack color_picker scope_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>


                    <tr>
                        <td id="" class="label">{t}Button background{/t}</td>
                        <td>
                    <span data-scope="button_background-color" class="fa-stack color_picker scope_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>


                </table>


            </div>




        </div>



        <div id="product_wrap_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 3001;">



            <div class="handle" style="border-bottom: 1px solid #ccc;width: 100%;line-height: 30px;height: 30px">
                <i class="fa fa-window-close button padding_left_10" onclick="$('#product_wrap_style').addClass('hide')"></i>
            </div>
            <div style="padding: 20px">
                <table >


                    <tr>
                        <td class="label">{t}Border{/t}</td>
                        <td class="margins_container unselectable border border-width" data-scope="border">
                            <input data-margin="top-width" class=" edit_margin top" value=""  placeholder="0"><input data-margin="bottom-width" class=" edit_margin bottom" value="" style="" placeholder="0">
                            <input data-margin="left-width" class=" edit_margin left" value="" style="" placeholder="0"><input data-margin="right-width" class=" edit_margin right" value="" style="" placeholder="0">

                            <i class="fa fa-plus-circle padding_left_10 like_button up_margins"></i>
                            <i class="fa fa-minus-circle padding_left_5 like_button down_margins"></i>

                            <span data-scope="border-color" style="position: relative;top:-1.5px" class="fa-stack color_picker scope_border-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                        </td>
                    </tr>

                    <tr>
                        <td class="label">{t}Text{/t}</td>
                        <td>
                     <span data-scope="product_container_color" class="fa-stack color_picker scope_product_container_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>

                    <tr>
                        <td class="label">{t}Price{/t}</td>
                        <td>
                     <span data-scope="price_color" class="fa-stack color_picker scope_price_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>

                    <tr>
                        <td id="" class="label">{t}Order button text{/t}</td>
                        <td>




                    <span data-scope="footer_color" class="fa-stack color_picker scope_footer_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                    <span data-scope="footer_hover_color" class="fa-stack color_picker scope_footer_hover_color like_button" title="hover">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span> <small style="position:relative;left:-8px;top:1px;opacity:.5" >:hover</small>
                        </td>
                    </tr>

                    <tr>
                        <td id="" class="label">{t}Order button background{/t}</td>
                        <td>
                    <span data-scope="footer_background-color" class="fa-stack color_picker scope_footer_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                    <span data-scope="footer_hover_background-color" class="fa-stack color_picker scope_footer_hover_background-color like_button" title="hover">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span> <small style="position:relative;left:-8px;top:1px;opacity:.5" >:hover</small>
                        </td>
                    </tr>


                </table>


            </div>




        </div>




        <div id="color_picker_dialog" style="position:absolute;z-index: 6000" class="hide">
            <input type='text'  class="hide"  />

        </div>

    </div>




    <div class="site_wrapper ">


        <span id="webpage_data" style="display:none" data-website_key="{$website->id}"

                {foreach from=$website->style  item=style  }
                    {$style[0]}{ {$style[1]}: {$style[2]}}
                {/foreach}




        ></span>

        <div id="top_header" style="width: 100%; display: flex;"  >

          <div id="header_logo" style="flex-grow:1;flex-grow: 0;flex-shrink: 0;flex-grow: 0;flex-shrink: 0; ;text-align: center">



                    <img  style="cursor:not-allowed	;max-height: 100%;max-width:  100%;vertical-align: middle;" src="{if empty($settings['logo_website'])}https://via.placeholder.com/60x60{else}{$settings['logo_website']}{/if}"/>


            </div>

            <div id="main_header" style="flex-grow:2;position: relative">
                {if isset($settings.search_texts)}
                    {foreach from=$settings.header_texts key=key item=header_text}
                        {assign 'key'  "u_id_`$key`" }
                        {if $header_text.type=='H1++'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <h1 class="huge" type="{$header_text.type}">{$header_text.text}</h1>
                            </div>
                        {elseif $header_text.type=='H1+'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <h1 class="big" type="{$header_text.type}">{$header_text.text}</h1>
                            </div>
                        {elseif $header_text.type=='H1'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <h1 type="{$header_text.type}">{$header_text.text}</h1>
                            </div>
                        {elseif $header_text.type=='H2'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <h2 type="{$header_text.type}">{$header_text.text}</h2>
                            </div>
                        {elseif $header_text.type=='H3'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <h3  type="{$header_text.type}">{$header_text.text}</h3>
                            </div>
                        {elseif $header_text.type=='N'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <span  type="{$header_text.type}">{$header_text.text}</span>
                            </div>
                        {elseif $header_text.type=='N-'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <small  type="{$header_text.type}">{$header_text.text}</small>
                            </div>
                        {/if}
                    {/foreach}
                {/if}

            </div>

            <div id="buffer_zone" style="flex-grow:1;text-align: right;flex-grow: 0;flex-shrink: 0; flex-basis:100px;" >

            </div>


            <div id="search_header" style="padding-top:5px;flex-grow:1;text-align: right;flex-grow: 0;flex-shrink: 0; flex-basis:350px;position: relative" oncxlick="open_header_style()" >


                <div id="search_hanger" style="position: absolute;left:10px;top:{if isset($settings.search_top)}{$settings.search_top}{else}0{/if}px"><input/> <i class="button fa fa-search"></i></div>

                {if isset($settings.search_texts)}
                    {foreach from=$settings.search_texts key=key item=header_text}
                        {assign 'key'  "su_id_`$key`" }
                        {if $header_text.type=='N b'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <span class="bold" type="{$header_text.type}">{$header_text.text}</span>
                            </div>
                        {elseif $header_text.type=='N- b'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <small  class="bold" type="{$header_text.type}">{$header_text.text}</small>
                            </div>
                        {elseif $header_text.type=='N'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <span type="{$header_text.type}">{$header_text.text}</span>
                            </div>
                        {elseif $header_text.type=='N-'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <small type="{$header_text.type}">{$header_text.text}</small>
                            </div>
                        {/if}
                    {/foreach}
                {/if}


            </div>

        </div>



        <div id="bottom_header" class="">


            {foreach from=$header_data.menu.columns item=column key=key}
                <a id="menu_{$key}" class="menu" href="" data-key="{$key}"><i class="far  {$column.icon} "></i> <span>{$column.label|strip_tags}</span> <i class="down_cadet fal fa-angle-down"></i></a>
            {/foreach}


                <div class="control_panel" onclick="open_menu_style()">

                    <a id="header_order_totals" href="basket.sys" class="button">
                        <span class="ordered_products_number">0</span>
                        <i style="padding-right:5px;padding-left:5px" class="fa fa-shopping-cart fa-flip-horizontal  " title="{if empty($labels._Basket)}{t}Basket{/t}{else}{$labels._Basket}{/if}" aria-hidden="true"></i>
                        <span class="order_amount" style="padding-right:10px" title="">{$zero_money}</span>
                    </a>

                    <a id="favorites_button" href="favourites.sys" class="button">
                        <i class=" far fa-heart fa-flip-horizontal  " title="{if empty($labels._Favourites)}{t}My favourites{/t}{else}{$labels._Favourites}{/if}" aria-hidden="true"></i>
                    </a>

                    <a id="profile_button" href="profile.sys" class="button"><i class="far fa-user fa-flip-horizontal  " title="{t}Profile{/t}" aria-hidden="true"></i>
                        <span>{if empty($labels._Profile)}{t}Profile{/t}{else}{$labels._Profile}{/if}</span></a>

                    <a href="#" id="logout" class="button">
                        <i class="far fa-sign-out-alt fa-flip-horizontal  " title="{t}Log out{/t}" aria-hidden="true"></i>
                        <span>{if empty($labels._Logout)}{t}Log out{/t}{else}{$labels._Logout}{/if}</span>
                    </a>


                </div>



        </div>
        <div id="_menu_blocks" class="" style="position:absolute">
            {foreach from=$header_data.menu.columns item=column key=key}
                {if $column.type=='three_columns'}
                    <div id="menu_block_menu_{$key}" class="_menu_block menu_block hide" data-key="{$key}">
                        {foreach from=$column.sub_columns item=sub_column}
                            {if $sub_column.type=='items'}
                                <div class="vertical-menu  ">
                                    {foreach from=$sub_column.items item=item}
                                        <a href="{$item.url}"><i class="far item_icon fa-fw {$item.icon}"></i> <span class="_item_label">{$item.label}</span></a>
                                    {/foreach}
                                </div>
                            {elseif $sub_column.type=='text'}
                                <div class="text">
                                    {if  $sub_column.url!=''}
                                        <a href="{$sub_column.url}"><img style="width: 100%" src="{$sub_column.image}" alt="" class=""/></a>
                                    {else}
                                        <img src="{$sub_column.image}" alt="" class=""/>
                                    {/if}
                                    <div>
                                        {$sub_column.text}
                                    </div>
                                </div>
                            {elseif $sub_column.type=='image'}
                                <div class="image">
                                    <img src="{$sub_column.image}" alt=""/>
                                </div>
                            {elseif $sub_column.type=='departments' or   $sub_column.type=='families' or  $sub_column.type=='web_departments' or   $sub_column.type=='web_families'}
                                <div class="vertical-menu  ">
                                    {foreach from=$store->get_categories({$sub_column.type},{$sub_column.page},'menu') item=item}
                                        <a href="{$item['url']}"><i class="fa fa-caret-right fa-fw "></i>{$item['label']}</a>
                                    {/foreach}
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                {elseif $column.type=='single_column'}
                    <div id="menu_block_menu_{$key}" class="_menu_block hide vertical-menu single_column " data-key="{$key}">

                        {foreach from=$column.items item=item}
                            {if $item.type=='item'}
                                <a href="{$item['url']}">{$item['label']}</a>
                            {/if}
                        {/foreach}
                    </div>
                {/if}


            {/foreach}
        </div>

        <div id=body"   >
            <div class="navigation top_body" onclick="open_navigation_style()" >
                <div class="breadcrumbs" style="">
                    <span class="breadcrumb ">
                        <a href="#" title="{t}Home{/t}"><i class="fa fa-home"></i></a>
                            <i class="fas padding_left_10 padding_right_10 fa-angle-double-right"></i>
                    </span>

                    <span class="breadcrumb ">
                <a href="#" title="{t}Department{/t}">{t}Department{/t}</a>
                <i class="fas padding_left_10 padding_right_10 fa-angle-double-right"></i>

                </span>

                    <span class="breadcrumb ">
                <a href="#" title="{t}Family{/t}">{t}Family{/t}</a>

                </span>
                </div>


                <div style="" class="nav">


                    <a href="#" title=""><i class="fas fa-arrow-left"></i></a>
                    <a href="#" title=""><i class="fas fa-arrow-right next"></i></a>
                </div>

                <div style="clear:both"></div>
            </div>
            <div class="products no_items_header"  style="margin-top: 20px;margin-bottom: 60px" >
                <div class="product_wrap wrap type_product " data-element=".empty" onClick="open_edit_product_wrap_style(this)"  >
                    <div class="product_block item product_container" >
                        <div class="wrap_to_center product_image" >
                                    <i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"  title="{t}More info{/t}"  ></i>

                                    {if $logged_in}
                                        <i    data-product_id="" data-favourite_key="0" class="favourite_ favourite far  fa-heart" aria-hidden="true"></i>
                                    {/if}
                                    <img src="art/apple.png" style="position: relative;left:5px" />
                                </div>


                                <div class="product_description"  >
                                    <span class="code">Fruit-01</span>
                                    <div class="name item_name">Fuji apple, Enjoy the full flavor of a Fuji!</div>

                                </div>
                                {if $logged_in}
                                    <div class="product_prices  " >
                                        <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$price}</div>

                                        {if $rrp!=''}<div>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                                    </div>
                                {else}
                                    <div class="product_prices  " >
                                        <div class="product_price">{if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}</div>

                                    </div>
                                {/if}




                                        <div class="order_row empty  order_row_ ">
                                            <input maxlength=6 style="" class='order_input  ' type="text"' size='2' value='' data-ovalue=''>

                                            <div class="label sim_button" style="margin-left:57px">
                                                <i class="hide fa fa-hand-pointer fa-fw" aria-hidden="true"></i> <span class="">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>
                                            </div>


                                        </div>









                            </div>
                </div>
                <div class="product_wrap wrap type_product " data-element=".ordered"  onClick="open_edit_product_wrap_style(this)">
                    <div class="product_block item product_container" >
                        <div class="wrap_to_center product_image" >
                            <a href="#"><i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"  title="{t}More info{/t}"  ></i></a>

                            {if $logged_in}
                                <i    data-product_id="" data-favourite_key="0" class="favourite_ favourite far  fa-heart" aria-hidden="true"></i>
                            {/if}
                            <img src="art/banana.png" style="position: relative;left:5px" />
                        </div>


                        <div class="product_description"  >
                            <span class="code">Fruit-02</span>
                            <div class="name item_name">Cavendish banana, I know you like it!</div>

                        </div>

                            <div class="product_prices  " >
                                <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$price}</div>

                                {if $rrp!=''}<div>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                            </div>





                            <div class="ordering log_in can_not_order  out_of_stock_row  hide ">

                                <span class="product_footer label "></span>
                                <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span>


                            </div>

                            <div class="order_row ordered  order_row_ ">
                                <input maxlength=6 style="" class='order_input  ' type="text"' size='2' value='1' data-ovalue=''>

                                <div class="label sim_button" style="margin-left:57px">
                                    <i class=" fa fa-thumbs-up fa-fw" aria-hidden="true"></i> <span class="">{if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}</span>
                                </div>




                            </div>








                    </div>

                </div>
                <div class="product_wrap wrap type_product "  data-element=".out_of_stock" onClick="open_edit_product_wrap_style(this)">
                    <div class="product_block item product_container" >
                        <div class="wrap_to_center product_image" >
                            <a href="#"><i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"  title="{t}More info{/t}"  ></i></a>

                            {if $logged_in}
                                <i    data-product_id="" data-favourite_key="0" class="favourite_ favourite far  fa-heart" aria-hidden="true"></i>
                            {/if}
                            <img src="art/avocado.png" style="position: relative;left:5px" />
                        </div>


                        <div class="product_description"  >
                            <span class="code">Fruit-03</span>
                            <div class="name item_name">Hass avocado, Fulfill your obsession with guacamole</div>

                        </div>

                            <div class="product_prices  " >
                                <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$price}</div>

                                {if $rrp!=''}<div>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                            </div>





                            <div class="ordering log_in can_not_order  out_of_stock_row out_of_stock  ">

                                <span class="product_footer label ">{t}Out of stock{/t}</span>
                                <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span>


                            </div>










                    </div>

                </div>
                <div class="product_wrap wrap type_product "  data-element=".launching_soon"  onClick="open_edit_product_wrap_style(this)">
                    <div class="product_block item product_container" >
                        <div class="wrap_to_center product_image" >
                            <a href="#"><i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"  title="{t}More info{/t}"  ></i></a>

                            {if $logged_in}
                                <i    data-product_id="" data-favourite_key="0" class="favourite_ favourite far  fa-heart" aria-hidden="true"></i>
                            {/if}
                            <img src="art/tomato.png" style="position: relative;left:5px" />
                        </div>


                        <div class="product_description"  >
                            <span class="code">Fruit-04</span>
                            <div class="name item_name">Roma tomato, Refresh your senses with this variety</div>

                        </div>
                        {if $logged_in}
                            <div class="product_prices  " >
                                <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$price}</div>

                                {if $rrp!=''}<div>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                            </div>
                        {else}
                            <div class="product_prices  " >
                                <div class="product_price">{if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}</div>

                            </div>
                        {/if}


                        {if $logged_in}

                            <div class="ordering log_in can_not_order  out_of_stock_row launching_soon   ">

                                <span class="product_footer label ">{t}Launching soon{/t}</span>
                                <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span>


                            </div>




                        {else}



                            <div class="ordering log_out " >

                                <div onclick='window.location.href = "/login.sys"' class="mark_on_hover" ><span class="login_button" >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                                <div onclick='window.location.href = "/register.sys"' class="mark_on_hover"><span class="register_button" > {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>


                            </div>

                        {/if}






                    </div>

                </div>


                <div class="text_block " style="float: left"   >


                    <div style="margin-left:15px;margin-bottom:20px;padding:10px;border: 1px dashed #ccc;"  onClick="open_edit_body_style(this)"  >

                        {t}Body text{/t}

                    </div>



                    <div action="" method="post" enctype="multipart/form-data"  class="sky-form" style="box-shadow: none">

                        <section class="col ">
                            <button  id="basket_go_to_checkout"   onClick="open_button_style(this)" style="margin:0px;" type="submit" class="button">{t}Button{/t}  <i  class="fas fa-fw fa-arrow-right" aria-hidden="true"></i> </button>



                        </section>


                    </div>

                </div>




            </div>
            <div class="clear" style="margin-bottom: 40px"></div>

        </div>









        <footer   onclick="open_footer_style()">


            {foreach from=$footer_data.rows item=row}

                {if $row.type=='main_4'}
                    <div class="text_blocks  top_header text_template_4  ">


                        {foreach from=$row.columns item=column name=main_4}


                            {if $column.type=='address'}
                                <div class="footer_block">


                                    <ul class="address " style="">
                                        {foreach from=$column.items item=item }
                                            {if $item.type=='logo'}
                                                <li class="item _logo"><img src="{$item.src}" alt="" title="{$item.title}"/></li>
                                            {elseif $item.type=='text'}
                                                <li class="item _text"><i class="fa-fw {$item.icon}"></i> <span>
                                          {if $item.text=='#tel' and  $store->get('Telephone')!=''}{$store->get('Telephone')}{elseif $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}{elseif $item.text=='#address' and  $store->get('Address')!=''}{$store->get('Address')}{else}{$item.text|strip_tags|trim}{/if}
                                      </span></li>
                                            {elseif $item.type=='email'}
                                                <li class="item _email"><i class="fa fa-fw fa-envelope"></i> <a href="mailto:{if $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}{else}{$item.text}{/if}">
                                                        {if $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}{else}{$item.text}{/if}

                                                    </a></li>
                                            {/if}
                                        {/foreach}


                                    </ul>


                                </div>
                            {elseif $column.type=='links'}
                                <div class="footer_block">



                                    <h5 >{$column.header}</h5>

                                    <ul class="links_list">
                                        {foreach from=$column.items item=item }
                                            <li class="item"><a href="{$item.url}"><i class="fa fa-fw fa-angle-right link_icon"></i><span class="item_label">{$item.label}</span></a></li>
                                        {/foreach}


                                    </ul>


                                </div>
                            {elseif $column.type=='text'}
                                <div class="footer_block">



                                    <h5 class="for_text">{$column.header}</h5>

                                    <div  class="footer_text" >
                                        {$column.text}
                                    </div>

                                </div>
                            {elseif $column.type=='nothing'}
                                <div class="footer_block">

                                </div>

                            {/if}


                        {/foreach}

                    </div>
                {elseif $row.type=='copyright'}
                    <div class="text_blocks  text_template_2 bottom_header copyright">
                        {foreach from=$row.columns item=column name=copyright_info}

                            {if $column.type=='text'}
                                <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if} ">

                                    <div class="text">
                                        {$column.text}
                                    </div>
                                </div>
                            {elseif $column.type=='nothing'}
                                <div class="footer_block "></div>
                            {elseif $column.type=='copyright_bundle'}
                                <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if} ">

                                    <div class="copyright_bundle ">
                                        <small>

                                            {t}Copyright{/t} © {"%Y"|strftime} <span class="copyright_bundle_owner">{$column.owner}</span>. {t}All rights reserved{/t}. <span
                                                    class="copyright_bundle_links">{foreach  from=$column.links item=item name=copyright_links}<a class="copyright_bundle_link"
                                                                                                                                                  href="{$item.url}">{$item.label}</a>{if !$smarty.foreach.copyright_links.last} | {/if}{/foreach}</span>
                                        </small>
                                    </div>

                                </div>
                            {elseif $column.type=='social_links'}
                                <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if} ">


                                    <ul class="footer_social_links">
                                        {foreach from=$column.items item=item}
                                            <li class="social_link"><a href="{$item.url}"><i class="fab {$item.icon}"></i></a></li>
                                        {/foreach}
                                    </ul>

                                </div>
                            {/if}


                        {/foreach}

                    </div>
                {/if}


            {/foreach}


        </footer>

    </div>
</div>


<script>






    var pinned = false;

    var menu_open = false;
    var mouse_over_menu = false;
    var mouse_over_menu_link = false;



    $( "#bottom_header a" ).on( 'mouseenter', menu_in ).on( 'mouseleave', menu_out );
    $( "#bottom_header a" ).on( 'mouseenter', menu_in_fast ).on( 'mouseleave', menu_out_fast );
    $( "#_menu_blocks ._menu_block" ).on( 'mouseenter', menu_block_in ).on( 'mouseleave', menu_block_out );



    $('#_menu_blocks').width($('#top_header').width())


    function show_column(key) {

        pinned = true;

        $('._menu_block').addClass('hide')
        $('#menu_block_menu_' + key).removeClass('hide')

    }


    var styles={
    {foreach from=$website->style  item=style  }
            "{$style[0]} {$style[1]}":['{$style[0]}','{$style[1]}','{$style[2]}'],
    {/foreach}

    }

    set_logo_position();

    function set_logo_position(){
        var height=$('#header_logo').height()
        var image_height=$('#header_logo img').height()

        console.log(height)
        console.log(image_height)

        if(height>image_height){
            $('#header_logo img').css('margin-top', 0.5*(height-image_height) + 'px')
        }else{
            $('#header_logo img').css('margin-top',   '0px')
        }


    }

</script>


</body>

</html>

