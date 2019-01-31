{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 January 2019 at 12:30:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}



{foreach from=$users item=user}
    <tr class="">
        <td class="operations">
            <i class="far fa-trash-alt very_discreet_on_hover  button" aria-hidden="true" onclick="remove_recipient(this)"></i>
        </td>
        <td class="mixed_recipients">
            <input type="hidden" class="user_recipient_value user_key" value="{$user->id}" ovalue="{$user->id}">
            {if $user->get('User Active')=='Yes'}
                <span class="error discreet strikethrough">{$user->get('Alias')}</span>
            {else}
                <span class="">{$user->get('Alias')}</span>
            {/if}
        </td>
    </tr>
{/foreach}