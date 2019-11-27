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
    <tr >
        <td class="operations">
            <i class="far fa-trash-alt very_discreet_on_hover  button" aria-hidden="true" onclick="remove_recipient(this)"></i>
        </td>
        <td class="mixed_recipients">
            <input type="hidden" class="user_recipient_value user_key" value="{$user->id}" />
            {if $user->get('User Active')=='No'}
                <span class="discreet strikethrough">{$user->get('Alias')}</span> <span class="error padding_left_10"><i class=" fa fa-exclamation-circle"></i> <span class="very_discreet italic error">{t}Inactive user{/t}</span></span>
            {else}
                <span class="recipient">{$user->get('Alias')}</span>
                {if $user->get('User Password Recovery Email')==''}
                    <span class="error padding_left_10"><i class=" fa fa-exclamation-circle"></i> <span class="very_discreet italic error">{t}No email set{/t}</span></span>
                {else}
                    <span class="discreet recipient italic padding_left_10">{$user->get('User Password Recovery Email')}</span>

                {/if}
            {/if}
        </td>
    </tr>
{/foreach}