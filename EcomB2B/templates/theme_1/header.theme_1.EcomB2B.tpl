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

<div id="top_header" style="width: 100%; display: flex;"  >

    <div id="header_logo" style="flex-grow:1;;flex-grow: 0;flex-shrink: 0;flex-grow: 0;flex-shrink: 0; ;text-align: center">
            <a href="https://{$website->get('Website URL')}"><img id="website_logo" style="margin-top:{if isset($settings['logo_top_margin'])}{$settings['logo_top_margin']}{else}0px{/if};max-height: 100%;max-width:  100%;vertical-align: middle;" src="{if empty($settings['logo_website'])}https://via.placeholder.com/60x60{else}{$settings['logo_website']}{/if}"/></a>
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

    <div id="buffer_zone" style="flex-grow:1;text-align: right;;flex-grow: 0;flex-shrink: 0; flex-basis:100px;" >

    </div>


    <div id="search_header" style="padding-top:5px;flex-grow:1;text-align: right;;flex-grow: 0;flex-shrink: 0; flex-basis:350px;position: relative" oncxlick="open_header_style()" >


        <div id="search_hanger" style="position: absolute;left:10px;top:{if isset($settings.search_top)}{$settings.search_top}{else}0{/if}px"><input id="header_search_input"/> <i id="header_search_icon" class="button fa fa-search"></i></div>

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


<div id="bottom_header">


    {foreach from=$header_data.menu.columns item=column key=key}
        {if $column.show}
        <a id="menu_{$key}" class="menu {if $column.type=='nothing'}only_link{else}dropdown{/if}  {if !empty($column.link)}real_link{/if} " href="{if !empty($column.link)}{$column.link}{/if}" data-key="{$key}"><i class="far  {$column.icon} "></i> <span>{$column.label|strip_tags}</span> <i  class="down_cadet {if $column.type=='nothing'}hide{/if}  fal fa-angle-down   "></i></a>
        {/if}
    {/foreach}

        {if $logged_in}
        <div class="control_panel">

            <a  id="header_order_totals" href="basket.sys" class="button">
                <span class="ordered_products_number">0</span>
                <i style="padding-right:5px;padding-left:5px" class="fa fa-shopping-cart fa-flip-horizontal  "  title="{if empty($labels._Basket)}{t}Basket{/t}{else}{$labels._Basket}{/if}"
                   aria-hidden="true"></i>
                <span class="order_amount" style="padding-right:10px" title="">{$zero_money}</span>
            </a>

            <a id="favorites_button" href="favourites.sys" class="button">
                <i class=" far fa-heart fa-flip-horizontal  "  title="{if empty($labels._Favourites)}{t}My favourites{/t}{else}{$labels._Favourites}{/if}" aria-hidden="true"></i>
            </a>

            <a id="profile_button" href="profile.sys" class="button"><i class="far fa-user fa-flip-horizontal  " title="{t}Profile{/t}" aria-hidden="true"></i>
                <span>{if empty($labels._Profile)}{t}Profile{/t}{else}{$labels._Profile}{/if}</span></a>

            <a href="#" id="logout" class="button">
                <i class="far fa-spinner fa-spin  fa-flip-horizontal  " title="{t}Log out{/t}" aria-hidden="true"></i>
                <span>{if empty($labels._Logout)}{t}Log out{/t}{else}{$labels._Logout}{/if}</span>
            </a>






        </div>
        {else}
            <div class="control_panel">
                <a href="/login.sys" class="button"  id="login_header_button" ><i class="fa fa-sign-in" aria-hidden="true"></i> <span>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></a>
                <a href="/register.sys" class="button"  id="register_header_button" ><i class="fa fa-user-plus" aria-hidden="true"></i> <span>{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></a>
            </div>
        {/if}




</div>


<div id="_menu_blocks" style="position:absolute">
{foreach from=$header_data.menu.columns item=column key=key}

    {if $column.show}
    {if $column.type=='three_columns'}
        <div id="menu_block_menu_{$key}" class="_menu_block menu_block hide" data-key="{$key}">
            {foreach from=$column.sub_columns item=sub_column}
                {if $sub_column.type=='items'}
                    <div class="vertical-menu  ">
                        {foreach from=$sub_column.items item=item}
                            <a href="{$item.url}"><i class="item_icon fa-fw {$item.icon}"></i> <span class="_item_label">{$item.label}</span></a>
                        {/foreach}
                    </div>
                {elseif $sub_column.type=='text'}
                    <div class="text">
                        {if isset($sub_column.image)}
                        {if  $sub_column.url!=''}
                            <a href="{$sub_column.url}"><img style="width: 100%" src="{$sub_column.image}" alt="" class=""/></a>
                        {else}
                            <img src="{$sub_column.image}" alt="" class=""/>
                        {/if}
                        {/if}
                        <div>
                            {if isset($sub_column.text)}{$sub_column.text}{/if}
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
    {/if}

{/foreach}
</div>

