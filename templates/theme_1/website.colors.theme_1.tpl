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
    .object_control_panel td{
        padding:0px 4px;
    }

    </style>

<script src="js/website_style.js"></script>


<body xmlns="http://www.w3.org/1999/html">

<div class="wrapper_boxed">


    <div id="aux" class="">



        <div id="button_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 3001;">



            <div class="handle" style="border-bottom: 1px solid #ccc;;width: 100%;line-height: 30px;height: 30px">
                <i class="fa fa-window-close button padding_left_10" onclick="$('#button_style').addClass('hide')"></i>
            </div>
            <div style="padding: 20px">
                <table >


                    <tr>
                        <td class="label">{t}Text{/t}</td>
                        <td>
                     <span data-scope="color" class="fa-stack color_picker scope_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>
                    <tr>
                        <td id="" class="label">{t}Background{/t}</td>
                        <td>
                    <span data-scope="background-color" class="fa-stack color_picker scope_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                        </td>
                    </tr>


                </table>


            </div>




        </div>



        <div id="product_wrap_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 3001;">



            <div class="handle" style="border-bottom: 1px solid #ccc;;width: 100%;line-height: 30px;height: 30px">
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
                        <td class="label">{t}Body text{/t}</td>
                        <td>
                     <span data-scope="color" class="fa-stack color_picker scope_color like_button">
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
                        <td id="" class="label">{t}Footer text{/t}</td>
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
                        <td id="" class="label">{t}Footer background{/t}</td>
                        <td>
                    <span data-scope="background-color" class="fa-stack color_picker scope_footer_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                    <span data-scope="background-color" class="fa-stack color_picker scope_footer_hover_background-color like_button" title="hover">
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


        <span id="webpage_data" style="display:none" data-webpage_key="{$webpage->id}"

                {foreach from=$website->style  item=style  }
                    {$style[0]}{ {$style[1]}: {$style[2]}}
                {/foreach}




        ></span>

        <div id="top_header" style="width: 100%;">

            <div style="float:right;text-align: right;;" class="search_container {if $webpage->get('Webpage Code')=='search.sys'}hide{/if} ">


                <input id="header_search_input"/> <i id="header_search_input" class="button fa fa-search"></i>


            </div>
            <img style="padding:0px 10px ;float:left" src="{if empty($settings['logo_website'])}http://via.placeholder.com/60x60{else}{$settings['logo_website']}{/if}"/>

            <h1> {$website->get('Website Name')}</h1>


            <div style="clear:both"></div>

        </div>
        <div id="bottom_header" class="">


            {foreach from=$header_data.menu.columns item=column key=key}
                <a id="menu_{$key}" class="menu" href="" data-key="{$key}"><i class="far  {$column.icon} "></i> <span>{$column.label|strip_tags}</span> <i class="down_cadet fal fa-angle-down"></i></a>
            {/foreach}

            {if $logged_in}
                <div class="control_panel">

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
            {else}
                <div class="control_panel">
                    <a href="/login.sys" class="button"><i class="fa fa-sign-in" aria-hidden="true"></i> <span>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></a>
                    <a href="/register.sys" class="button"><i class="fa fa-user-plus" aria-hidden="true"></i> <span>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></a>
                </div>
            {/if}


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

        <div id=body">
            <div class="navigation">
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


                                {if $logged_in}

                                        <div class="ordering log_in can_not_order  out_of_stock_row  hide ">

                                            <span class="product_footer label "></span>
                                            <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span>


                                        </div>

                                        <div class="order_row empty  order_row_ ">
                                            <input maxlength=6 style="" class='order_input  ' type="text"' size='2' value='' data-ovalue=''>

                                            <div class="label sim_button" style="margin-left:57px">
                                                <i class="hide fa fa-hand-pointer fa-fw" aria-hidden="true"></i> <span class="">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>
                                            </div>


                                        </div>


                                {else}



                                    <div class="ordering log_out " >

                                        <div onclick='window.location.href = "/login.sys"' class="mark_on_hover" ><span class="login_button" >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                                        <div onclick='window.location.href = "/register.sys"' class="mark_on_hover"><span class="register_button" > {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>


                                    </div>

                                {/if}






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
                            <span class="code">Fruit-01</span>
                            <div class="name item_name">Cavendish apple, I know you like it!</div>

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

                            <div class="ordering log_in can_not_order  out_of_stock_row  hide ">

                                <span class="product_footer label "></span>
                                <span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span>


                            </div>

                            <div class="order_row empty  order_row_ ">
                                <input maxlength=6 style="" class='order_input  ' type="text"' size='2' value='1' data-ovalue=''>

                                <div class="label sim_button" style="margin-left:57px">
                                    <i class=" fa fa-thumbs-up fa-fw" aria-hidden="true"></i> <span class="">{if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}</span>
                                </div>




                            </div>


                        {else}



                            <div class="ordering log_out " >

                                <div onclick='window.location.href = "/login.sys"' class="mark_on_hover" ><span class="login_button" >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                                <div onclick='window.location.href = "/register.sys"' class="mark_on_hover"><span class="register_button" > {if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>


                            </div>

                        {/if}






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


                <div class="text_block " style="float: left">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form" style="box-shadow: none">

                        <section class="col ">
                            <button onClick="open_edit_button_style(this)" id="basket_go_to_checkout"   style="margin:0px;" type="submit" class="button">{t}Button{/t}  <i  class="fas fa-fw fa-arrow-right" aria-hidden="true"></i> </button>



                        </section>


                    </form>

                </div>




            </div>
            <div class="clear" style="margin-bottom: 40px"></div>

        </div>


        <footer>


            {foreach from=$footer_data.rows item=row}

                {if $row.type=='main_4'}
                    <div class="text_blocks  text_template_4  ">


                        {foreach from=$row.columns item=column name=main_4}


                            {if $column.type=='address'}
                                <div >


                                    <ul class="address " style="">
                                        {foreach from=$column.items item=item }
                                            {if $item.type=='logo'}
                                                <li class="item _logo"><img src="{$item.src}" alt="" title="{$item.title}"/></li>
                                            {elseif $item.type=='text'}
                                                <li class="item _text"><i class="fa-fw {$item.icon}"></i> <span>
                                          {if $item.text=='#tel' and  $store->get('Telephone')!=''}{$store->get('Telephone')}
                                          {elseif $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}
                                          {elseif $item.text=='#address' and  $store->get('Address')!=''}{$store->get('Address')}
                                          {else}{$item.text}{/if}
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
                                <div >



                                    <h5 >{$column.header}</h5>

                                    <ul class="links_list">
                                        {foreach from=$column.items item=item }
                                            <li class="item"><a href="{$item.url}"><i class="fa fa-fw fa-angle-right link_icon"></i><span class="item_label">{$item.label}</span></a></li>
                                        {/foreach}


                                    </ul>


                                </div>
                            {elseif $column.type=='text'}
                                <div class="   ">



                                    <h5 class="">{$column.header}</h5>

                                    <div>
                                        {$column.text}
                                    </div>

                                </div>
                            {elseif $column.type=='nothing'}
                                <div   ">

                            {/if}


                        {/foreach}

                    </div>
                {elseif $row.type=='copyright'}
                    <div class="text_blocks  text_template_2 copyright">
                        {foreach from=$row.columns item=column name=copyright_info}

                            {if $column.type=='text'}
                                <div class="one_half  ">
                                    <div class="footer_block _copyright_text">
                                        {$column.text}
                                    </div>
                                </div>
                            {elseif $column.type=='nothing'}
                                <div class="one_half  ">
                                    <div class="footer_block _copyright_nothing"></div>
                                </div>
                            {elseif $column.type=='copyright_bundle'}
                                <div class="one_half  ">


                                    <small>

                                        {t}Copyright{/t} © {"%Y"|strftime} <span class="copyright_bundle_owner">{$column.owner}</span>. {t}All rights reserved{/t}. <span
                                                class="copyright_bundle_links">{foreach  from=$column.links item=item name=copyright_links}<a class="copyright_bundle_link"
                                                                                                                                              href="{$item.url}">{$item.label}</a>{if !$smarty.foreach.copyright_links.last} | {/if}{/foreach}</span>
                                    </small>

                                </div>
                            {elseif $column.type=='social_links'}
                                <div class="one_half  ">


                                    <div class=" ">

                                        <ul class="footer_social_links">
                                            {foreach from=$column.items item=item}
                                                <li class="social_link"><a href="{$item.url}"><i class="fab {$item.icon}"></i></a></li>
                                            {/foreach}
                                        </ul>
                                    </div>
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

    $("#bottom_header a").hoverIntent(menu_in, menu_out);
    $("#bottom_header a").hover(menu_in_fast, menu_out_fast);
    $("#_menu_blocks ._menu_block").hover(menu_block_in, menu_block_out);

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


</script>


</body>

</html>

