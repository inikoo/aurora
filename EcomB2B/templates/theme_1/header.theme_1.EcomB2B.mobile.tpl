{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 17:13:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<span id="webpage_data" style="display:none"
      webpage_key="{$webpage->id}"
      customer_key="{$customer_key}"
      order_key="{$order_key}"
></span>



<div class="sidebars sidebars-light">
    <div class="sidebar sidebar-left">
        <div class="sidebar-header sidebar-header-image bg-1">
            <div class="overlay "></div>
            <div class="sidebar-socials">
                <a  class="invisible"  href="tel:{$store->get('Telephone')}"><i class="ion-ios-telephone"></i></a>

                <a class="invisible" href="#"><i class="ion-social-facebook"></i></a>
                <a class="invisible" href="#"><i class="ion-social-twitter"></i></a>
                <a class="invisible" href="#"><i class="ion-android-mail"></i></a>
                <a class="close-sidebar" href="#"><i class="ion-android-close"></i></a>
                <div class="clear"></div>
            </div>
            <a href="/" class="sidebar-logo">
                <strong>{$website->get('Website Name')}</strong>
            </a>
        </div>
        <div class="menu-search">
            <input id="header_search_input" type="text" class="search-field" value="Search..." onblur="if (this.value == '') {
                this.value = 'Search...';}" onfocus="if (this.value == 'Search...') {
                this.value = '';}" >
            <span id="header_search_icon" class="search-button"><i class="fa fa-search"></i></span>
        </div>
        <div class="menu-options icon-background no-submenu-numbers sidebar-menu">

            {foreach from=$header_data.menu.columns item=column key=key}
                <a data-sub="sidebar-sub-{$key}" href="#" class="{if !$column.show}hide{/if}">
                    <i class="icon-bg bg-blue-dark fa  {$column.icon}"></i>

                    <span>{$column.label}</span><strong class="plushide-animated"></strong></a>

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
                {else}


                {/if}



            {/foreach}


            <a href="#" class="close-sidebar"><i class="icon-bg bg-red-light ion-android-close"></i><span>Close</span><i class="ion-record"></i></a>
            <em class="menu-divider">Copyright <u class="copyright-year"></u>. All rights reserved</em>
        </div>
    </div>
    <div class="sidebar sidebar-right">
        <div class="sidebar-header sidebar-header-classic">
            <div class="sidebar-socials">
                <a class="close-sidebar" href="#"><i class="ion-android-close"></i></a>
                <a class="" href="#"></a>
                <a class="" href="#"></a>  <a class="" href="#"></a>
                <a class="" href="#"></a>
                <div class="clear"></div>
            </div>
            <a href="/" class="sidebar-logo">
                <strong>{$website->get('Website Name')}</strong>
            </a>
        </div>

        <div class="menu-options icon-background sidebar-menu">


            {if $logged_in}
                <a class="default-link" href="basket.sys"><i class="icon-bg bg-magenta-light  ion-ios-cart"></i><span>{t}Basket{/t}</span><i class="ion-record"></i></a>
                <a class="default-link" href="profile.sys"><i class="icon-bg bg-green-light  ion-person"></i><span>{t}Profile{/t}</span><i class="ion-record"></i></a>

            {else}

            <a class="default-link" href="login.sys"><i class="icon-bg bg-orange-light ion-log-in"></i><span>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span><i class="ion-record"></i></a>
            <a class="default-link" href="register.sys"><i class="icon-bg bg-orange-light ion-android-add-circle"></i><span>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span><i class="ion-record"></i></a>
            {/if}






                        <em class="menu-divider" style="margin-top:40px">Get in touch with us</em>

            <em class="menu-divider">
                <a href="tel:{$store->get('Telephone')}"><em class="fa fa-phone color-black" style="font-size: 25px;margin-right: 20px;;position: relative;top:-2px" aria-hidden="true"></em></a>
                <a href="email:{$store->get('Email')}"><em class="fa fa-envelope-o color-black" style="font-size: 25px;position: relative;top:-4px" aria-hidden="true"></em></a>


            </em>

                  <em class="menu-divider"></em>
            {if $logged_in}
                <a class="default-link" href="#" id="logout"><span style="padding-left: 20px">{t}Log out{/t}</span><i class="ion-record"></i></a>
            {/if}

                <em class="menu-divider">Copyright <u class="copyright-year"></u>. {t}All rights reserved{/t}</em>
        </div>



    </div>
</div>

{if isset($checkout_mode)}

    <div class="header header-logo-center header-light">
        <a href="/basket.sys" class="header-icon " "><i class="fa fa-arrow-left center" style="position: relative;top:18px" ></i></a>
        <a href="#" class="header-logo">{$website->get('Website Name')}</a>

    </div>
{else}

<div class="header header-logo-center header-light">
    <a href="#" class="header-icon header-icon-1 hamburger-animated open-sidebar-left"></a>
    <a href="/" class="header-logo">{$website->get('Website Name')}</a>
    {if $logged_in}
        <a href="#" class="header-icon header-icon-4 open-sidebar-right"><i class="ion-ios-cart-outline"></i></a>
    {else}
        <a href="#" class="header-icon header-icon-4 open-sidebar-right"><i class="ion-log-in"></i></a>
    {/if}
</div>
{/if}

