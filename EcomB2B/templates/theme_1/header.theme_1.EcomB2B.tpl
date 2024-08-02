{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 March 2017 at 17:45:30 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<span id="webpage_data" style="display:none" data-webpage_key="{$webpage->id}" ></span>
<div id="top_bar" style="background-color: rgb(75, 80, 88); color: white; padding: 4px 16px;display: grid;grid-template-columns: repeat(3, minmax(0, 1fr));">
    <div class="greetings">
        {if $logged_in}
            <span>{if empty($labels._hello)}{t}Hello{/t}{else}{$labels._hello}{/if},</span>
            <span style="font-weight: 600" id="top_bar_greetings_name" >friend</span>

            <span id="top_bar_is_gold_reward_member" class="hide">
                <span id="top_bar_is_gold_reward_member_label"></span>
                <span id="top_bar_is_gold_reward_member_until"></span>
            </span>

        {/if}
    </div>

    <div class="action_buttons" style="display: flex; justify-content: flex-end;
    {if $store->get('Store Type')=='Dropshipping'}column-gap: 20px;{else}column-gap: 45px;{/if}
     grid-column: span 2 / span 2">

        {if $logged_in}

            {if $store->get('Store Type')=='Dropshipping'}
                <a href="#"  class="button logout" style="margin-left: 0px;">
                    <i class="far fa-spinner fa-spin  fa-flip-horizontal  " title="{t}Log out{/t}" aria-hidden="true"></i>
                    <span>{if empty($labels._Logout)}{t}Log out{/t}{else}{$labels._Logout}{/if}</span>
                </a>

                <a id="profile_button" href="profile.sys" class="button" style="margin-left: 0px"><i class="far fa-user fa-flip-horizontal  " title="{t}Profile{/t}" aria-hidden="true"></i>
                    <span>{if empty($labels._Profile)}{t}Profile{/t}{else}{$labels._Profile}{/if}</span>
                </a>

                <a id="portfolio_button" href="portfolio.sys" class="button">
                    <i class=" far fa-store-alt  "  ></i> {if empty($labels._Portfolio)}{t}Portfolio{/t}{else}{$labels._Portfolio}{/if}
                </a>

                <a id="customers_button" href="clients.sys" class="button">
                    <i class=" fal fa-users  "  ></i> {if empty($labels._Customers)}{t}Customers{/t}{else}{$labels._Customers}{/if}
                </a>

                <a id="orders_button" href="clients_orders.sys" class="button">
                    <i class=" far fa-shopping-cart  "  ></i> {if empty($labels._Orders)}{t}Orders{/t}{else}{$labels._Orders}{/if}
                </a>

                <a href="/client_order_new.sys"  class="super_button" >
                    <i class="fa fa-shopping-cart  " title="{t}New order{/t}" aria-hidden="true"></i>
                    <span >{t}New order{/t}</span>
                </a>

                <a href="/balance.sys"  class="super_button " >
                    <i class="fa fa-piggy-bank  " title="{t}Top up{/t}" aria-hidden="true"></i>
                    <span id="top_bar_customer_balance" class="Customer_Balance"></span>
                </a>

            {else}
                <a href="#"  class="button logout" style="margin-left: 0px;">
                    <i class="far fa-spinner fa-spin  fa-flip-horizontal  " title="{t}Log out{/t}" aria-hidden="true"></i>
                    <span>{if empty($labels._Logout)}{t}Log out{/t}{else}{$labels._Logout}{/if}</span>
                </a>

                <a id="profile_button" href="profile.sys" class="button" style="margin-left: 0px"><i class="far fa-user fa-flip-horizontal  " title="{t}Profile{/t}" aria-hidden="true"></i>
                    <span>{if empty($labels._Profile)}{t}Profile{/t}{else}{$labels._Profile}{/if}</span>
                </a>

                <a id="favorites_button" href="favourites.sys" class="button" style="margin-left: 0px;margin-right: 0px;">
                    <i class=" far fa-heart"  title="{if empty($labels._Favourites)}{t}My favourites{/t}{else}{$labels._Favourites}{/if}" aria-hidden="true"></i>
                    <span>{if empty($labels._Favourites)}{t}My favourites{/t}{else}{$labels._Favourites}{/if}</span>
                </a>

                <a id="header_order_totals" href="basket.sys" class="button" style="">
                    <span class="hide ordered_products_number">0</span>
                    <i style="font-size: 1rem; padding-right:5px; padding-left:5px" class="fa fa-shopping-cart fa-flip-horizontal  "  title="{if empty($labels._Basket)}{t}Basket{/t}{else}{$labels._Basket}{/if}" aria-hidden="true"></i>
                    <span class="order_amount" title="" style="font-weight: 600; font-size: 1.1rem;">{$zero_money}</span>
                </a>
            {/if}
        
        {else}
            <div style="display: flex;column-gap: 16px;">
                <a href="/login.sys" class="button"  id="login_header_button" ><i class="fa fa-sign-in" aria-hidden="true"></i> <span>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></a>
                <a href="/register.sys" class="button"  id="register_header_button" ><i class="fa fa-user-plus" aria-hidden="true"></i> <span>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></a>
            </div>
        {/if}

    </div>
</div>

<div id="top_header" class="{$website->get('header_background_type')}" style="height: 90px; padding-right: 30px;width: auto;">
    <div id="header_logo" style="flex-grow: 0;flex-shrink: 0;text-align: center">
        {if !empty($settings['logo_website_website'])}
            <a href="https://{$website->get('Website URL')}">
                <img id="website_logo" style="max-height: 100%;max-width: 100%;vertical-align: middle;" alt="" src="{$settings['logo_website_website']}">
            </a>
        {/if}
    </div>
    
    <div id="buffer_zone" style="text-align: right;flex-grow: 0;flex-shrink: 0; flex-basis:100px;"> </div>

    
    <div id="search_header" style="text-align: right; flex-grow: 0; flex-shrink: 0; flex-basis:350px; position: relative; display: flex; align-items: center;justify-content: end">
        <div class="hide">
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
        
        <div id="search_hanger" style="">
            <input class="hide" id="inputLuigi" style="border: 1px solid #d1d5db; border-radius: 7px;height: 35px;padding-left: 10px;" placeholder="Search"/>
            <i id="luigi_search_icon" class="hide fal fa-search" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);font-size: 20px;" class="fal fa-search"></i>
            <input id="header_search_input"/>
            <i id="header_search_icon" class="button fa fa-search"></i>
        </div>
        
    </div>

    <div id="main_header" style="flex-grow:2; position: relative; display: flex; flex-direction: column;align-items: end; justify-content: center;">
        {if isset($settings.search_texts)}
            {foreach from=$settings.header_texts key=key item=header_text}
                {assign 'key'  "u_id_`$key`" }
                {if $header_text.type=='H1++'}
                    <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                        <h1 class="huge" type="{$header_text.type}">
                            {if !empty($header_text.link)}
                                <a href="{$header_text.link}">{$header_text.text}</a>
                            {else}
                                {$header_text.text}
                            {/if}</h1>
                    </div>
                {elseif $header_text.type=='H1+'}
                    <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                        <h1 class="big" type="{$header_text.type}">{if !empty($header_text.link)}
                                <a href="{$header_text.link}">{$header_text.text}</a>
                            {else}
                                {$header_text.text}
                            {/if}</h1>
                    </div>
                {elseif $header_text.type=='H1'}
                    <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                        <h1 type="{$header_text.type}">{if !empty($header_text.link)}
                                <a href="{$header_text.link}">{$header_text.text}</a>
                            {else}
                                {$header_text.text}
                            {/if}</h1>
                    </div>
                {elseif $header_text.type=='H2'}
                    <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                        <h2 type="{$header_text.type}">{if !empty($header_text.link)}
                                <a href="{$header_text.link}">{$header_text.text}</a>
                            {else}
                                {$header_text.text}
                            {/if}</h2>
                    </div>
                {elseif $header_text.type=='H3'}
                    <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                        <h3  type="{$header_text.type}" style="margin-bottom: 0px">
                            {if !empty($header_text.link)}
                                <a href="{$header_text.link}">{$header_text.text}</a>
                            {else}
                                {$header_text.text}
                            {/if}
                        </h3>
                    </div>
                {elseif $header_text.type=='N++'}
                    <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                        <span  type="{$header_text.type}">{if !empty($header_text.link)}
                                <a href="{$header_text.link}">{$header_text.text}</a>
                            {else}
                                {$header_text.text}
                            {/if}</span>
                    </div>
                {elseif $header_text.type=='N'}
                    <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                        <span  type="{$header_text.type}">{if !empty($header_text.link)}
                                <a href="{$header_text.link}">{$header_text.text}</a>
                            {else}
                                {$header_text.text}
                            {/if}</span>
                    </div>
                {elseif $header_text.type=='N-'}
                    <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                        <small  type="{$header_text.type}">{if !empty($header_text.link)}
                                <a href="{$header_text.link}">{$header_text.text}</a>
                            {else}
                                {$header_text.text}
                            {/if}</small>
                    </div>
                {/if}
            {/foreach}
        {/if}

    </div>

</div>

<div id="bottom_header">
    {foreach from=$header_data.menu.columns item=column key=key}
        {if $column.show}
        <a id="menu_{$key}" class="menu {if $column.type=='nothing'}only_link{else}dropdown{/if}  {if !empty($column.link)}real_link{/if} " href="{if !empty($column.link)}{$column.link}{/if}" data-key="{$key}">
            {if !empty($column.icon)}<i class="far  {$column.icon} "></i>{/if}
            <span>{$column.label|strip_tags}</span> <i  class="down_cadet {if $column.type=='nothing'}hide{/if}  fal fa-angle-down   "></i>
        </a>
        {/if}
    {/foreach}
</div>




<div id="_menu_blocks">
{foreach from=$header_data.menu.columns item=column key=key}
    {if $column.show}
    {if $column.type=='three_columns'}
        <div id="menu_block_menu_{$key}" class="_menu_block menu_block hide" data-key="{$key}">
            {foreach from=$column.sub_columns item=sub_column}
                {if $sub_column.type=='items'}
                    <div class="vertical-menu  ">
                        {foreach from=$sub_column.items item=item}
                            {if !empty($item.url) and !empty($item.label) }
                                <a href="{$item.url}">
                                        {if !empty($item.icon)}<i class="item_icon fa-fw {$item.icon}"></i> {/if}
                                    <span class="_item_label">{$item.label}</span>
                                </a>
                            {/if}
                        {/foreach}
                    </div>
                {elseif $sub_column.type=='text'}
                    <div class="text">
                        {if isset($sub_column.image)}
                        {if  $sub_column.url!=''}
                            <a href="{$sub_column.url}"  {if isset({$sub_column.title})}title="{$sub_column.title}"{/if}  ><img style="width: 100%" src="{$sub_column.image}" alt="{if isset({$sub_column.title})}{{$sub_column.title}}{/if}" /></a>
                        {else}
                            <img src="{$sub_column.image}" alt="{if isset({$sub_column.title})}{{$sub_column.title}}{/if}" />
                        {/if}
                        {/if}
                        <div>
                            {if isset($sub_column.text)}{$sub_column.text}{/if}
                        </div>
                    </div>
                {elseif $sub_column.type=='image'}
                    <div class="image">
                        {if  $sub_column.url!=''}
                            <a href="{$sub_column.url}"  {if isset({$sub_column.title})}title="{$sub_column.title}"{/if} ><img style="width: 100%" src="{$sub_column.image}" alt="{if isset({$sub_column.title})}{{$sub_column.title}}{/if}" /></a>
                        {else}
                            <img src="{$sub_column.image}" alt="{if isset({$sub_column.title})}{{$sub_column.title}}{/if}"  />
                        {/if}
                    </div>
                {elseif $sub_column.type=='departments' or   $sub_column.type=='families' or  $sub_column.type=='web_departments' or   $sub_column.type=='web_families'}
                    <div class="vertical-menu  ">
                        {foreach from=$store->get_categories({$sub_column.type},{$sub_column.page},'menu') item=item}
                            {if !empty($item.url) and !empty($item.label) }
                                <a href="{$item['url']}"><i class="fa fa-caret-right fa-fw"></i>{$item['label']}</a>
                            {/if}
                        {/foreach}
                    </div>
                {/if}
            {/foreach}
        </div>
    {elseif $column.type=='single_column'}
        <div id="menu_block_menu_{$key}" class="_menu_block hide vertical-menu single_column " data-key="{$key}">
            {foreach from=$column.items item=item}
                {if $item.type=='item'}
                    {if !empty($item.url) and !empty($item.label) }
                        <a href="{$item['url']}">{$item['label']}</a>
                    {/if}
                {/if}
            {/foreach}
        </div>
    {/if}
    {/if}
{/foreach}
</div>


<div id="header_features" class="{if $features_data|count > 0}hide debug_version3a{else} hide debug_version3b {/if}"     >
    <div style="height: 45px; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr))">

        <div>
            {if isset($features_data.reviews)}
                {if $features_data.reviews.type=='reviews.io'}
                    <script src="https://widget.reviews.io/badge-ribbon/dist.js"></script>
                    <div id="badge-ribbon" style="display: flex;align-items: center;margin-top: -8px;"></div>
                    <script>
                      reviewsBadgeRibbon("badge-ribbon", {
                        store: "{$features_data.reviews.data}",
                        size: "small",
                      });
                    </script>
                 {/if}
            {/if}
        </div>

        <div style="position: relative; height: inherit; display: flex; align-items: center; justify-content: center; overflow: hidden">
            {if isset($features_data.features) and  $features_data.features|count > 0 }
                {foreach $features_data.features as $feature_key  => $feature}
                    <div style="position: absolute; text-align: center; width: 100%; opacity: 0; animation: animate-spin-to-down {$features_data.features|count * 3}s ease-out {$feature_key * 3}s infinite">
                        {if isset($feature.icon)}
                            <i class="{$feature.icon}" style="font-size: 1.1rem;"></i>
                        {/if}
                        <span class="debug_label" style="font-size: 1.3rem;">
                            {$feature.label}
                        </span>
                    </div> 
                {/foreach}
            {/if}
        </div>


        <div style="filter: drop-shadow(0px 2px 0px #C8C8C800);padding: 10px 0px 10px 50px; display: flex; align-items: center;">
            <div style="height: 75px; width: 200px; border-radius: 5px; background: #9A4EAE; mask-image: radial-gradient(circle at 8px 50%, transparent 8px, red 8.5px); mask-position: -8px center;grid-template-rows: repeat(3, minmax(0, 1fr));display: none;color: white;">
                <div class="coupon_title" style="display: flex; align-items: end; justify-content: center; font-weight: 700">
                    FOB First Order Bonus
                </div>
                <div style="display: flex; align-items: center">
                    <hr style="border: 1px dashed #ffffff77; width: 80%">
                </div>
                <div class="coupon_description" style="display: flex; align-items: start; justify-content: center; font-size: 0.7rem">
                    10% off
                </div>
            </div>
        </div>
    </div>
</div>