{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13-09-2019 15:45:03 MYT Kuala Lumpur , Malaydsia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div class="website_navigation" >
    <div style="float:left">

            <i class="fa fa-home"></i> [
            <span title="{t}Homepage logout view{/t}" class="  button" onclick="change_view('website/{$website->id}/webpage/{$website->get_system_webpage_key('home_logout.sys')}')"><i class="fal fa-user-slash"></i></span>
            <span title="{t}Homepage{/t}" class="button" onclick="change_view('website/{$website->id}/webpage/{$website->get_system_webpage_key('home.sys')}')"><i class="fal fa-user-crown"></i></span>

            ]


    <i class="fal padding_right_20 padding_left_20 fa-chevron-double-right"></i>

    {foreach from=$navigation.breadcrumbs item=$breadcrumb name=breadcrumbs}
        <span class="breadcrumb">
            <span class="button"  onclick="change_view('website/{$website->id}/webpage/{$breadcrumb.key}')" title="{$breadcrumb.title}">
                    <i class="fal fa-{$breadcrumb.icon}"></i>  {$breadcrumb.label|truncate:40}
                </span>
            <i class="fal padding_right_10 padding_left_10 fa-chevron-double-right"></i>
            </span>
    {/foreach}
    <i class="fal fa-{$webpage->get('Icon')}"></i> {{$webpage->get('Name')}|truncate:30}

    </div>

    <div style="float:right" class="nav ">
        {if $navigation.next}<div style="width: 150px;float: right;text-align: right" ><span title="{$navigation.next.title}" class="button unselectable padding_left_20" onclick="change_view('website/{$website->id}/webpage/{$navigation.next.key}')"> {$navigation.next.label_short|truncate:16}  <i class="padding_left_5 fas fa-arrow-right next"></i></span></div>{/if}

        {if $navigation.prev}<div style="border-right:1px solid #ccc;width: 150px;float: right;" ><span class="button unselectable" onclick="change_view('website/{$website->id}/webpage/{$navigation.prev.key}')" title="{$navigation.prev.title}"><i class="fas padding_right_5 fa-arrow-left"></i>  {$navigation.prev.label_short|truncate:16}</span></div>{/if}
        <div style="clear:both"></div>
    </div>
    <div style="clear: both"></div>
</div>

