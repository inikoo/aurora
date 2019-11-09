{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2018 at 12:23:59 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

{include file="theme_1/_head.theme_1.tpl"}
<body xmlns="http://www.w3.org/1999/html">



<style>


    #bottom_header a.menu.dropdown .down_cadet{
        left:-4px

    }

    .like_button{
        cursor:pointer;
    }

    .sys{
        font-size: 10px;
        color: #555;
        font-family: "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, sans-serif;
    }

    .handle{
        cursor:move
    }


    .single_column .item_link{
        margin-left:5px
    }


    input.input_file {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }


    .button {
        cursor: pointer

    }

    .link {
        cursor: pointer

    }

    .link:hover {
        color:#000

    }

    .input_container{
        position:absolute;top:60px;left:10px;z-index: 100;border:1px solid #ccc;background-color: white;padding:10px 10px 10px 5px

    }


    .input_container input{
        width:400px
    }


    .list-unstyled span.link {
        color: #272727;
        padding: 4px 8px;
        width: 100%;
        transition-property: margin-left, background-color;
        transition-duration: 0.3s;
        transition-timing-function: ease-out;
    }



    .list-unstyled span.link:before {
        font-size: 12px;
        margin-right: 5px;
    }

    .submenu_expand,.item_link{
        margin-right: 5px; margin-left: 5px;
    }

    .item_delete{
        margin-left: 0px;
    }






</style>



<div id="aux" >


    <div id="image_control_panel" class="hide object_control_panel" style="z-index: 8000;width:450px">
        <div style="margin-bottom: 10px;padding-right: 5px;text-align: right">
            <span onclick="update_image()" class="button unselectable"><i class="fa fa-check button""></i> {t}Apply changes{/t}</span>
        </div>

        <table>
            <tr>
                <td class="label">{t}Image{/t}</td>
                <td class="image_control_panel_upload_td">
                    <input style="display:none" type="file" name="menu_image" id="update_images_block_image" class="image_upload_from_iframe"
                           data-parent="Website"  data-parent_key="{$website->id}"  data-parent_object_scope="Menu"  data-metadata='{ "header_key":"{$header_key}"}'  data-options=""  data-response_type="website"
                    />
                    <label style="font-weight: normal;cursor: pointer;width:100%"  for="update_images_block_image">
                        {t}Upload image{/t} <span class="image_size"></span> <i class="hide fa fa-check success" aria-hidden="true"></i>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="label">{t}Tooltip{/t}</td><td><input class="image_tooltip" style="width: 320px" placeholder="tooltip"></td>
            </tr>
            <tr>
                <td class="label">{t}Link{/t}</td><td><input class="image_url" style="width: 320px" placeholder="https://"></td>
            </tr>



        </table>



    </div>



<div class="hide">

            <a id="link_stem_cell" class="item" href=""><i style=";min-width: 16px" class="fa item_icon fa-fw fa-caret-right"></i> <span class="_item_label" contenteditable="true">{t}New link{/t}</span>
                <i class="fal item_delete  aux fa-trash-alt button  " style="float: right" title="{t}Remove link{/t}"></i>

                <i url="" class="fal item_link  aux fa-link button  " style="float: right;margin-right: 10px" title="{t}Update link{/t}"></i>
                <i url="" class="fal item_handle  aux far fa-hand-rock like_button  hide" style="float: right;margin-right: 10px" title="{t}Move link{/t}"></i>

            </a>



        <li id="single_column_link_stem_cell" class="_item" type="item">
            <a href="">
                <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
                <span class="_item_label" contenteditable="true">{t}Link{/t}</span>
                <i url="" class="fa item_link hide aux fa-link button very_discreet" aria-hidden="true"></i>
                <i class="fa item_delete hide aux fa-trash button very_discreet" aria-hidden="true"></i>
            </a>
        </li>








        <ul id="items_stem_cell" >

            <a class="add_link button" href=""><i  class="fa item_icon fa-fw fa-plus"></i> <span class="_item_label">{t}Add link{/t}</span></a>
        </ul>
        <ul id="empty_stem_cell"></ul>

        <ul id="text_stem_cell">



                <img data-type="image_and_text" link=""   src="https://placehold.it/360x120" alt=""   />


            <div class="new_editor"></div>


        </ul>

        <ul id="image_stem_cell">



                <img data-type="image" link=""  src="https://placehold.it/360x240" alt=""   />


        </ul>

        <ul id="catalogue_stem_cell">



        </ul>

        <ul id="three_columns_stem_cell" >

            <div  data-type="nothing" class="submenu nothing _1"></div>
            <div  data-type="nothing" class="submenu nothing _2"></div>
            <div  data-type="nothing" class="submenu nothing _3"></div>


        </ul>

        <div id="single_column_stem_cell" >

            <a class="add_link like_button" href=""><i  class="fa item_icon fa-fw fa-plus"></i> <span class="_item_label">{t}Add link{/t}</span></a>

        </div>


</div>


    <div id="input_container_link" class="input_container link_url hide  " style="z-index:6001">
        <input  value="" placeholder="{t}https://... or webpage code{/t}"> <i onclick="close_item_edit_link()" class="fa fa-check-square button" aria-hidden="true"></i>

    </div>



    <div id="icons_control_center" class="input_container link_url hide  " style="z-index:6000">

        <div style="margin-bottom:5px">  <i  onClick="$('#icons_control_center').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>


        <div>{t}Bullet points{/t}</div>

        <div>
            <i icon="fas fa-circle" class="button  fas fa-fw fa-circle" aria-hidden="true"></i>
            <i icon="far fa-circle" class="button  far fa-fw fa-circle" aria-hidden="true"></i>
            <i icon="fal fa-circle" class="button  fal fa-fw fa-circle" aria-hidden="true"></i>
            <i icon="fa-dot-circle" class="button  fa fa-fw fa-dot-circle" aria-hidden="true"></i>
            <i icon="fa fa-square" class="button  fa fa-fw fa-square" aria-hidden="true"></i>
            <i icon="far fa-square" class="button  far fa-fw fa-square" aria-hidden="true"></i>
            <i icon="fa fa-caret-square-right" class="button  fa fa-fw fa-caret-square-right" aria-hidden="true"></i>
            <i icon="fa fa-caret-right" class="button  fa fa-fw fa-caret-right" aria-hidden="true"></i>
            <i icon="fa fa-asterisk" class="button  fa fa-fw fa-asterisk" aria-hidden="true"></i>
            <i icon="fa fa-adjust" class="button  fa fa-fw fa-adjust" aria-hidden="true"></i>
            <i icon="fa fa-bullseye" class="button  fa fa-fw fa-bullseye" aria-hidden="true"></i>
            <i icon="fa fa-certificate" class="button  fa fa-fw fa-certificate" aria-hidden="true"></i>
            <i icon="fa fa-star" class="button  fa fa-fw fa-star" aria-hidden="true"></i>
            <i icon="fa fa-star" class="button  far fa-fw fa-star" aria-hidden="true"></i>
        </div>

        <div>{t}Store{/t}</div>

        <div>
            <i icon="fa fa-tag" class="button  fa fa-fw fa-tag" aria-hidden="true"></i>
            <i icon="fa fa-tags" class="button  fa fa-fw fa-tags" aria-hidden="true"></i>
            <i icon="fa fa-lightbulb" class="button  fa fa-fw fa-lightbulb" aria-hidden="true"></i>
            <i icon="fa fa-plus" class="button  fa fa-fw fa-plus" aria-hidden="true"></i>
            <i icon="fa fa-percent" class="button  fa fa-fw fa-percent" aria-hidden="true"></i>
            <i icon="fa fa-gift" class="button  fa fa-fw fa-gift" aria-hidden="true"></i>
            <i icon="fa fa-handshake" class="button  fa fa-fw fa-handshake" aria-hidden="true"></i>
            <i icon="fa fa-bullhorn" class="button  fa fa-fw fa-bullhorn" aria-hidden="true"></i>
            <i icon="fa fa-badge" class="button  fa fa-fw fa-badge" aria-hidden="true"></i>
            <i icon="fa fa-star-exclamation" class="button  fa fa-fw fa-star-exclamation" aria-hidden="true"></i>
            <i icon="fa fa-info" class="button  fa fa-fw fa-info" aria-hidden="true"></i>
            <i icon="fa fa-info-circle" class="button  fa fa-fw fa-info-circle" aria-hidden="true"></i>
            <i icon="fa fa-question" class="button  fa fa-fw fa-question" aria-hidden="true"></i>
            <i icon="fa fa-question-circle" class="button  fa fa-fw fa-question-circle" aria-hidden="true"></i>


        </div>
        <div>
            <i icon="fa fa-home" class="button  fa fa-fw fa-home" aria-hidden="true"></i>

            <i icon="fa fa-shopping-basket" class="button  fa fa-fw fa-shopping-basket" aria-hidden="true"></i>
            <i icon="far fa-shopping-basket" class="button  far fa-fw fa-shopping-basket" aria-hidden="true"></i>
            <i icon="fa fa-shopping-cart" class="button  fa fa-fw fa-shopping-cart" aria-hidden="true"></i>

            <i icon="far fa-money-bill" class="button  far fa-fw fa-money-bill" aria-hidden="true"></i>
            <i icon="fa fa-credit-card" class="button  fa fa-fw fa-credit-card" aria-hidden="true"></i>
            <i icon="fab fa-cc-vis" class="button  fab fa-fw fa-cc-visa" aria-hidden="true"></i>

            <i icon="fab fa-paypal" class="button  fab fa-fw fa-paypal" aria-hidden="true"></i>
            <i icon="fa fa-university" class="button  fa fa-fw fa-university" aria-hidden="true"></i>
            <i icon="fa fa-dollar-sign" class="button  fa fa-fw fa-dollar-sign" aria-hidden="true"></i>
            <i icon="fa fa-euro-sign" class="button  fa fa-fw fa-euro-sign" aria-hidden="true"></i>
            <i icon="fa fa-pound-sign" class="button  fa fa-fw fa-pound-sign" aria-hidden="true"></i>

        </div>


        <div>{t}Logistics{/t}</div>
        <div>



            <i icon="fa fa-truck" class="button  fa fa-fw fa-truck" aria-hidden="true"></i>
            <i icon="fa fa-shipping-fast" class="button  fa fa-fw fa-shipping-fast" aria-hidden="true"></i>


            <i icon="fa fa-ship" class="button  fa fa-fw fa-ship" aria-hidden="true"></i>
            <i icon="fa fa-paper-plane" class="button  fa fa-fw fa-paper-plane" aria-hidden="true"></i>
            <i icon="far fa-paper-plane" class="button  far fa-fw fa-paper-plane" aria-hidden="true"></i>

            <i icon="fa fa-plane" class="button  fa fa-fw fa-plane" aria-hidden="true"></i>
            <i icon="fa fa-fighter-jet" class="button  fa fa-fw fa-fighter-jet" aria-hidden="true"></i>
            <i icon="fa fa-box" class="button  fa fa-fw fa-box" aria-hidden="true"></i>

            <i icon="fa fa-stop" class="button  fa fa-fw fa-stop" aria-hidden="true"></i>
            <i icon="fa fa-th-large" class="button  fa fa-fw fa-th-large" aria-hidden="true"></i>
            <i icon="fa fa-th" class="button  fa fa-fw fa-th" aria-hidden="true"></i>



        </div>
        <div>{t}Contact{/t}</div>

        <div>
            <i icon="fa fa-user" class="button  fa fa-fw fa-user" aria-hidden="true"></i>
            <i icon="far fa-user" class="button  far fa-fw fa-user" aria-hidden="true"></i>
            <i icon="fa fa-user-circle" class="button  fa fa-fw fa-user-circle" aria-hidden="true"></i>
            <i icon="fa fa-at" class="button  fa fa-fw fa-at" aria-hidden="true"></i>
            <i icon="fa fa-envelope" class="button  fa fa-fw fa-envelope" aria-hidden="true"></i>
            <i icon="far fa-envelope" class="button  far fa-fw fa-envelope" aria-hidden="true"></i>
            <i icon="fa fa-comment-alt" class="button  fa fa-fw fa-comment-alt" aria-hidden="true"></i>
            <i icon="fa fa-phone" class="button  fa fa-fw fa-phone" aria-hidden="true"></i>
            <i icon="fa fa-phone-square" class="button  fa fa-fw fa-phone-square" aria-hidden="true"></i>
            <i icon="fa fa-mobile" class="button  fa fa-fw fa-mobile" aria-hidden="mobile"></i>
            <i icon="fa fa-bell" class="button  fa fa-fw fa-bell" aria-hidden="true"></i>
            <i icon="fa fa-building" class="button  fa fa-fw fa-building" aria-hidden="true"></i>
        </div>
        <div>
            <i icon="far fa-clock" class="button  fa fa-fw fa-clock" aria-hidden="true"></i>
            <i icon="fa fa-coffee" class="button  fa fa-fw fa-coffee" aria-hidden="true"></i>
            <i icon="fa fa-utensils" class="button  fa fa-fw fa-utensils" aria-hidden="true"></i>
            <i icon="fa fa-copyright" class="button  fa fa-fw fa-copyright" aria-hidden="true"></i>
            <i icon="fab fa-black-tie" class="button  fab fa-fw fa-black-tie" aria-hidden="true"></i>
            <i icon="fa fa-briefcase" class="button  fa fa-fw fa-briefcase" aria-hidden="true"></i>

        </div>

        <div>{t}Nature{/t}</div>

        <div>
            <i icon="fa fa-tree" class="button  fa fa-fw fa-tree" aria-hidden="true"></i>
            <i icon="fab fa-pagelines" class="button fab fa-fw fa-pagelines" aria-hidden="true"></i>
            <i icon="fa fa-leaf" class="button  fa fa-fw fa-leaf" aria-hidden="true"></i>
            <i icon="fa fa-lemon" class="button  fa fa-fw fa-lemon" aria-hidden="true"></i>
            <i icon="fab fa-apple" class="button  fab fa-fw fa-apple" aria-hidden="true"></i>
            <i icon="fa fa-sun" class="button  fa fa-fw fa-sun" aria-hidden="true"></i>
            <i icon="fa fa-moon" class="button  fa fa-fw fa-moon" aria-hidden="true"></i>
            <i icon="fa fa-star" class="button  fa fa-fw fa-star" aria-hidden="true"></i>
            <i icon="fa fa-snowflake" class="button  fa fa-fw fa-snowflake" aria-hidden="true"></i>
            <i icon="fa fa-fire" class="button  fa fa-fw fa-fire" aria-hidden="true"></i>
            <i icon="fa fa-cloud" class="button  fa fa-fw fa-cloud" aria-hidden="true"></i>
            <i icon="fa fa-bolt" class="button  fa fa-fw fa-bolt" aria-hidden="true"></i>
            <i icon="fa fa-tint" class="button  fa fa-fw fa-tint" aria-hidden="mobile"></i>
            <i icon="fa fa-thermometer" class="button  fa fa-fw fa-thermometer" aria-hidden="true"></i>
            <i icon="fa fa-paw" class="button  fa fa-fw fa-paw" aria-hidden="true"></i>
        </div>

        <div>{t}Humanoid{/t}</div>

        <div>
            <i icon="fa fa-male" class="button  fa fa-fw fa-male" aria-hidden="true"></i>
            <i icon="fa fa-female" class="button  fa fa-fw fa-female" aria-hidden="true"></i>
            <i icon="fa fa-child" class="button  fa fa-fw fa-child" aria-hidden="true"></i>
            <i icon="fa fa-blind" class="button  fa fa-fw fa-blind" aria-hidden="true"></i>
            <i icon="fa fa-smile" class="button  fa fa-fw fa-smile" aria-hidden="true"></i>
            <i icon="fa fa-meh" class="button  fa fa-fw fa-meh" aria-hidden="true"></i>
            <i icon="fa fa-frown" class="button  fa fa-fw fa-frown" aria-hidden="true"></i>
            <i icon="fa fa-hand-spock" class="button  fa fa-fw fa-hand-spock" aria-hidden="true"></i>
            <i icon="fa fa-hand-rock" class="button  fa fa-fw fa-hand-rock" aria-hidden="true"></i>
            <i icon="fa fa-thumbs-up" class="button  fa fa-fw fa-thumbs-up" aria-hidden="true"></i>
            <i icon="far fa-thumbs-up" class="button  far fa-fw fa-thumbs-up" aria-hidden="true"></i>
            <i icon="fa fa-heart" class="button  fa fa-fw fa-heart" aria-hidden="true"></i>
            <i icon="far fa-heart" class="button  far fa-fw fa-heart" aria-hidden="mobile"></i>

        </div>

        <div>{t}No icon{/t}</div>

        <div>
            <i icon="fa fa-ban" style="color:red" class="button   fa fa-fw fa-ban discreet" aria-hidden="true"></i>


        </div>
    </div>


</div>


<div class="wrapper_boxed">

    <div class="site_wrapper">


        <span id="webpage_data" data-header_key="{$header_key}" data-add_link_label="{t}Add link{/t}" style="display:none" data-website_key="{$website->id}" data-store_key="{$website->get('Website Store Key')}"  data-font="{$website->get('Website Text Font')}" ></span>




        <div id="bottom_header" class="">

            <div id="_columns">
            {foreach from=$header_data.menu.columns item=column key=key}
                <a id="menu_{$key}" class="menu  {if !$column.show}hide{/if}  _column {if $column.type=='nothing'}only_link{else}dropdown{/if}"  data-column_type="{$column.type}"  href="" data-key="{$key}"><i class="{$column.icon} menu_icon" icon="{$column.icon}" ></i> <span class="menu_label" contenteditable="true">{$column.label|strip_tags}</span> <i url="{if isset($column.link)}{$column.link}{/if}" class="down_cadet  fal fa-fw fa-angle-down"></i></a>
            {/foreach}
            </div>

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
        </div>


        <div id="_menu_blocks" class="" style="position:absolute">
            {foreach from=$header_data.menu.columns item=column key=key}
                {if $column.type=='three_columns'}
                    <div id="menu_block_menu_{$key}" class="_menu_block menu_block hide  " data-column_type="{$column.type}" data-key="{$key}">
                        {foreach from=$column.sub_columns key=sub_key item=sub_column}
                            {if $sub_column.type=='items'}
                                <div id="submenu_{$key}_{$sub_key}" data-type="{$sub_column.type}" class="submenu vertical-menu sortable  link_list">
                                    {foreach from=$sub_column.items item=item}
                                        <a class="item" href="{$item.url}"><i style=";min-width: 16px" class="item_icon fa-fw {$item.icon}" icon="{$item.icon}" ></i> <span class="_item_label" contenteditable="true">{$item.label}</span>
                                            <i class="fal item_delete  aux fa-trash-alt like_button  hide" style="float: right" title="{t}Remove link{/t}"></i>
                                            <i url="{$item.url}" class="fal item_link  aux fa-link like_button  hide" style="float: right;margin-right: 10px" title="{t}Update link{/t}"></i>
                                            <i class="fal item_handle  aux far fa-hand-rock like_button  hide" style="float: right;margin-right: 10px" title="{t}Move link{/t}"></i>

                                        </a>
                                    {/foreach}
                                    <a class="add_link like_button" href=""><i  class="fa item_icon fa-fw fa-plus"></i> <span class="_item_label">{t}Add link{/t}</span>


                                    </a>

                                </div>
                            {elseif $sub_column.type=='text'}
                                <div id="submenu_{$key}_{$sub_key}" data-type="{$sub_column.type}" class="submenu text">

                                        <img data-type="image_and_text" link="{$sub_column.url}" src="{$sub_column.image}" alt="{$sub_column.title}"/>

                                    <div class="editor">
                                        {$sub_column.text}
                                    </div>
                                </div>
                            {elseif $sub_column.type=='image'}
                                <div id="submenu_{$key}_{$sub_key}" data-type="{$sub_column.type}" class="submenu image">
                                    <img data-type="image" link="{$sub_column.url}" src="{$sub_column.image}" alt="{$sub_column.title}"/>
                                </div>
                            {elseif $sub_column.type=='departments' or   $sub_column.type=='families' or  $sub_column.type=='web_departments' or   $sub_column.type=='web_families'}
                                <div id="submenu_{$key}_{$sub_key}" data-type="{$sub_column.type}" _page="{$sub_column.page}" _page_label="{if isset($sub_column.page_label)}{$sub_column.page_label}{/if}" data-page="{$sub_column.page}" class="submenu vertical-menu  ">
                                    {foreach from=$store->get_categories({$sub_column.type},{$sub_column.page},'menu') item=item}
                                        <a href="{$item['url']}"><i class="fa fa-caret-right fa-fw "></i>{$item['label']}</a>
                                    {/foreach}
                                </div>
                            {else}
                                <div id="submenu_{$key}_{$sub_key}" data-type="{$sub_column.type}" class="submenu nothing ">
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                {elseif $column.type=='single_column'}
                    <div id="menu_block_menu_{$key}" class="_menu_block hide vertical-menu single_column sortable"  data-column_type="{$column.type}" data-key="{$key}">

                        {foreach from=$column.items item=item}
                            {if $item.type=='item'}
                                <a class="item" href="{$item['url']}"><span class="_item_label" contenteditable="true">{$item['label']}</span>
                                <i class="fal item_delete  aux fa-trash-alt like_button  hide" style="float: right" title="{t}Remove link{/t}"></i>
                                <i url="{$item['url']}" class="fal item_link  aux fa-link like_button  hide" style="float: right;margin-right: 10px" title="{t}Update link{/t}"></i>
                                <i  class="fal item_handle  aux far fa-hand-rock like_button  hide" style="float: right;margin-right: 10px" title="{t}Move link{/t}"></i>
                                </a>
                            {/if}


                            {/foreach}
                        <a class="add_link like_button" href=""><i  class="fa item_icon fa-fw fa-plus"></i> <span class="_item_label">{t}Add link{/t}</span></a>

                    </div>
                {elseif $column.type=='nothing'}
                    <div id="menu_block_menu_{$key}" class="_menu_block hide nothing"  data-column_type="{$column.type}" data-key="{$key}">
                    </div>
                {/if}


            {/foreach}
        </div>

    </div>
</div>



<script src="js/website.menu.edit.js?v=25"></script>
<script src="js/edit_webpage_upload_images_from_iframe.js?v2"></script>



</body>

</html>

