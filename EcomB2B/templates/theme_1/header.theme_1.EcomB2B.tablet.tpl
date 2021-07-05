{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 March 2018 at 14:55:30 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<span id="webpage_data" style="display:none" data-webpage_key="{$webpage->id}" ></span>
<div class="sidebars sidebars-light">
    <div class="sidebar sidebar-left">
        <div class="sidebar-header sidebar-header-image bg-1">
            <div class="overlay "></div>
            <div class="sidebar-socials" >
                {if $store->get('Telephone')!=''}
                <a  class="invisible"  href="tel:{$store->get('Telephone')}"><i class="ion-ios-telephone"></i></a>
                {/if}
                <a class="invisible" href="#"><i class="ion-social-facebook"></i></a>
                <a class="invisible" href="#"><i class="ion-social-twitter"></i></a>
                <a class="invisible" href="#"><i class="ion-android-mail"></i></a>
                <a class="close-sidebar" href="#"><i class="fa fa-times"></i></a>
                <div class="clear"></div>
            </div>
            <a href="/" class="sidebar-logo">
                <strong>{if !empty($settings['left_menu_text'])}{$settings['left_menu_text']}{else}{$website->get('Website Name')}{/if}</strong>
            </a>
        </div>
        <div class="menu-search">
            <input id="header_search_input" type="text" class="search-field" value="" >
            <span id="header_search_icon" class="search-button"><i class="fa fa-search"></i></span>
        </div>
        <div class="menu-options icon-background no-submenu-numbers sidebar-menu">

            {foreach from=$header_data.menu.columns item=column key=key}

                {if $column.show}

                <a data-sub="sidebar-sub-{$key}" href="#" >
                    <i class="icon-bg bg-orange-dark fa  {$column.icon}"></i>

                    <span>{$column.label|strip_tags}</span><strong class="plushide-animated"></strong></a>
                {if $column.type=='three_columns'}

                    <div class="submenu" id="sidebar-sub-{$key}">
                    {foreach from=$column.sub_columns key=sub_col_key item=sub_column}
                        {if isset($sub_column.title)}<em class="menu-divider">{$sub_column.title}</em>{/if}



                        {if $sub_column.type=='items'}
                                 {foreach from=$sub_column.items item=item}
                                     <a href="{$item.url}"><span>{$item.label}</span></a>
                                 {/foreach}
                        {elseif $sub_column.type=='departments' or   $sub_column.type=='families' or  $sub_column.type=='web_departments' or   $sub_column.type=='web_families'}
                            {if isset($sub_column.title)}<em class="menu-divider">{$sub_column.title}</em>{/if}




                                {foreach from=$store->get_categories({$sub_column.type},{$sub_column.page},'menu') item=item}
                                    <a href="{$item['url']}"><span>{$item['label']}</span></a>

                                {/foreach}

                            </ul>
                        {/if}
                    {/foreach}
                    </div>
                {elseif $column.type=='single_column'}

                    <div class="submenu" id="sidebar-sub-{$key}">
                        {foreach from=$column.items key=sub_col_key item=item}
                            <a href="{$item.url}"><span>{$item.label}</span></a>
                        {/foreach}
                    </div>



                {/if}

                {/if}

            {/foreach}


            <a href="#" class="close-sidebar hide"><i class="icon-bg bg-red-light fa fa-times"></i><span>{t}Close{/t}</span><i class="ion-record"></i></a>
            <em class="menu-divider">{t}Copyright{/t} <u class="copyright-year"></u>.</em>
            <em class="menu-divider">{t}All rights reserved{/t}</em>
        </div>
    </div>
    <div class="sidebar sidebar-right">
        <div class="sidebar-header sidebar-header-classic">
            <div class="sidebar-socials">
                <a class="close-sidebar" href="#"><i class="fa fa-times"></i></a>
                <a href="#"></a>
                <a href="#"></a>  <a href="#"></a>
                <a href="#"></a>
                <div class="clear"></div>
            </div>
            <a href="/" class="sidebar-logo">
                <strong>{$website->get('Website Name')}</strong>
            </a>
        </div>

        <div class="menu-options icon-background sidebar-menu">


            {if $logged_in}
                {foreach from=$header_data.menu.control_panel|@array_reverse item=control key=key}
                    {if $control['show']}

                        {if $key=='basket'}
                            <a class="default-link" href="basket.sys"><i class="icon-bg bg-orange-dark  fa fa-shopping-cart"></i><span>{t}Basket{/t}</span><i class="ion-record"></i></a>
                        {elseif $key=='favourites'}
                            <a class="default-link" href="favourites.sys"><i class="icon-bg bg-orange-dark  fa fa-heart"></i><span>{t}Favourites{/t}</span><i class="ion-record"></i></a>

                        {elseif $key=='profile'}
                        <a class="default-link" href="profile.sys"><i class="icon-bg bg-orange-dark  fa fa-user"></i><span>{t}Profile{/t}</span><i class="ion-record"></i></a>


                        {elseif $key=='custom_design_products'}
                        <a class="default-link" href="custom_design_products.sys"><i class="icon-bg bg-orange-dark  fa fa-user-shield"></i><span>{if empty($labels._Customer_Products)}{t}My products{/t}{else}{$labels._Customer_Products}{/if}</span><i class="ion-record"></i></a>

                        {elseif $key=='customer_discounts'}
                        <a class="default-link" href="customer_discounts.sys"><i class="icon-bg bg-orange-dark fa fa-user-tag"></i><span>{if empty($labels._Customer_Discounts)}{t}My offers{/t}{else}{$labels._Customer_Discounts}{/if}</span><i class="ion-record"></i></a>


                        {elseif $key=='portfolio'}
                        <a class="default-link" href="portfolio.sys"><i class="icon-bg bg-orange-dark  fa fa-store-alt"></i><span>{if empty($labels._Portfolio)}{t}Portfolio{/t}{else}{$labels._Portfolio}{/if}</span><i class="ion-record"></i></a>

                        {elseif $key=='customers'}
                        <a class="default-link" href="clients.sys"><i class="icon-bg bg-orange-dark  fal fa-users"></i><span>{if empty($labels._Customers)}{t}Customers{/t}{else}{$labels._Customers}{/if}</span><i class="ion-record"></i></a>


                        {elseif $key=='client_orders'}
                        <a class="default-link" href="clients_orders.sys"><i class="icon-bg bg-orange-dark  fa fa-shopping-cart"></i><span>{if empty($labels._Orders)}{t}Orders{/t}{else}{$labels._Orders}{/if}</span><i class="ion-record"></i></a>

                    {/if}

                    {/if}
                {/foreach}


            {else}

            <a class="default-link" href="login.sys"><i class="icon-bg bg-orange-light fa fa-sign-in"></i><span>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span><i class="ion-record"></i></a>
            <a class="default-link" href="register.sys"><i class="icon-bg bg-orange-light fa fa-plus-circle"></i><span>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span><i class="ion-record"></i></a>
            {/if}

            {if $store->get('Telephone')!='' or $store->get('Email')!=''}
                <em class="menu-divider" style="margin-top:40px">{t}Get in touch with us{/t}</em>

                <em class="menu-divider" style="text-transform:none">
                    {if $store->get('Telephone')!=''}
                        <a style="color:black" href="tel:{$store->get('Telephone')}"><em class="fa fa-phone "></em>
                        {$store->get('Telephone')}
                        </a>
                    {/if}
                    {if $store->get('Email')!=''}
                        <a style="color:black" href="mailto:{$store->get('Email')}"><em  class="fa fa-envelope "></em>
                        {$store->get('Email')}</a>
                    {/if}

                </em>
            {/if}
                  <em class="menu-divider"></em>
            {if $logged_in}
                <a class="default-link" href="#" id="logout"><span style="padding-left: 20px">{t}Log out{/t}</span><i class="ion-record"></i></a>
            {/if}

            <em class="menu-divider">{t}Copyright{/t} <u class="copyright-year"></u>.</em>
            <em class="menu-divider">{t}All rights reserved{/t}</em>        </div>



    </div>
</div>

{if isset($checkout_mode)}

    <div class="header header-logo-center header-light">
        <a href="/basket.sys" class="header-icon " ><i class="fa fa-arrow-left center"  ></i></a>
        <a href="#" class="header-logo">{if isset($settings['header_text_mobile_website'])}{$settings['header_text_mobile_website']}{/if}</a>

    </div>
{else}

<div class="header header-logo-center header-light">
    <a href="#" class="header-icon header-icon-1 hamburger-animated open-sidebar-left"></a>
    <a href="/" style="margin:initial;left:70px" class="header-logo">{if isset($settings['header_text_mobile_website'])}{$settings['header_text_mobile_website']}{/if}</a>



    {if $logged_in}

        <span class="control_panel"  >

             {foreach from=$header_data.menu.control_panel item=control key=key}
                 {if $control['show']}

                     {if $key=='basket'}
                         <a id="header_order_totals" class="basket_link" href="basket.sys">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="order_amount" style="padding-right:10px" > <i class="fa fa-spinner fa-spin"></i> </span>
                        </a>
                        {elseif $key=='favourites'}
                            <a href="favourites.sys" ><i   class="fa fa-heart"></i></a>

                        {elseif $key=='profile'}
                            <a href="profile.sys"  ><i class="fa fa-user"></i></a>


                        {elseif $key=='custom_design_products'}
                         <a href="custom_design_products.sys"  ><i class="fa fa-user-shield"></i></a>

                        {elseif $key=='customer_discounts'}
                         <a href="customer_discounts.sys"  ><i class="fa fa-user-tag"></i></a>


                        {elseif $key=='portfolio'}
                            <a href="portfolio.sys" ><i   class="fa fa-store-alt"></i></a>

                        {elseif $key=='customers'}
                            <a href="clients.sys"  ><i class="fa fa-user"></i></a>


                        {elseif $key=='client_orders'}
                            <a href="clients_orders.sys"  ><i class="fa fa-shopping-cart"></i></a>

                     {/if}

                 {/if}
             {/foreach}





            {*

        {if $store->get('Store Type')=='Dropshipping'}
            <a href="profile.sys"  ><i class="fa fa-cog"></i></a>

            <a href="portfolio.sys" ><i   class="fa fa-store-alt"></i></a>
            <a href="clients.sys"  ><i class="fa fa-user"></i></a>
            <a href="clients_orders.sys"  ><i class="fa fa-shopping-cart"></i></a>
        {else}
            <a href="favourites.sys" ><i   class="fa fa-heart"></i></a>
            <a href="profile.sys"  ><i class="fa fa-user"></i></a>
            <a id="header_order_totals" class="basket_link" href="basket.sys">
            <i class="fas fa-shopping-cart"></i>
                <span class="order_amount" style="padding-right:10px" > <i class="fa fa-spinner fa-spin"></i> </span>
            </a>
        {/if}
        *}
    </span>

        <a href="#" class="header-icon header-icon-4 hamburger-animated open-sidebar-right"></a>


    {else}
    <span class="control_panel"  >
        <a href="/login.sys" ><i class="fa fa-sign-in" aria-hidden="true"></i> <span>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></a>
        <a href="/register.sys" ><i class="fa fa-user-plus" aria-hidden="true"></i> <span>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></a>


</span>



        <a href="#" class="header-icon header-icon-4 hamburger-animated open-sidebar-right"></a>

    {/if}
</div>
{/if}

