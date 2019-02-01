{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 January 2019 at 12:28:07 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{if $mode=='formatted_value' and ( $mixed_recipients.external_emails|@count == 0   and  $mixed_recipients.user_keys|@count == 0 )}
    <span class="very_discreet italic">{t}No recipients set{/t}</span>
{/if}


<table border=0 class="{if $mode=='edit'} mixed_recipients_container hide{/if}" data-field="{$field_id}" data-added="0" data-removed="0" >
    <tr class="bold {if $mixed_recipients.external_emails|@count == 0   and  $mixed_recipients.user_keys|@count == 0 }hide{/if} hide"    >
        <td class="operations {if $mode=='edit'}hide{/if}"></td>
        <td class="recipient">{t}Recipient{/t}</td>
    </tr>
    <tbody class="users_recipients_items">
    {foreach from=$mixed_recipients.users item=user}
        <tr >
            {if $mode=='edit'}
            <td class="operations ">
                <i class="far fa-trash-alt very_discreet_on_hover  button" aria-hidden="true" onclick="remove_recipient(this)"></i>
            </td>
            {/if}
            <td class="mixed_recipients">
                {if $mode=='edit'}
                <input type="hidden" class="user_recipient_value user_key" value="{$user->id}" />
                {/if}
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
    </tbody>
    <tbody class="external_emails_recipients_items">
    {foreach from=$mixed_recipients.external_emails item=external_email}

        <tr>
            {if $mode=='edit'}
            <td class="operations">
                <i class="far fa-trash-alt very_discreet_on_hover  button" aria-hidden="true" onclick="remove_recipient(this)"></i>
            </td>
            {/if}
            <td class="mixed_recipients">
                {if $mode=='edit'}
                <input class="valid external_email_mixed_recipients_value" value="{$external_email}" type="hidden">
                {/if}
                <span class="discreet italic recipient">{$external_email}</span>

            </td>
        </tr>
    {/foreach}
    </tbody>
    {if $mode=='edit'}
    <tr class="new_user_recipient user_tr new in_process hide">
        <td class="operations">
            <i class="far fa-trash-alt very_discreet_on_hover  button" aria-hidden="true" onclick="remove_recipient(this)"></i>
            <input type="hidden" class="user_recipient_value " value="" ovalue="">
        </td>
        <td class="mixed_recipients">
            <input type="hidden" class="user_recipient_value user_key" value="" ovalue="">
            <span class="User_Handle hide"></span>
            <input class="User_Handle_value" value="" ovalue="" placeholder="{t}User{/t}" parent_key="1"
                   parent="account" scope="users">
            <div class="search_results_container">
                <table class="results" border="1">
                    <tr class="hide search_result_template" field="" value="" formatted_value=""
                        onclick="select_dropdown_user_recipient(this)">
                        <td class="code"></td>
                        <td style="width:85%" class="label"></td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr class="new_external_email_recipient user_tr new in_process hide">
        <td class="operations">
            <i class="far fa-trash-alt very_discreet_on_hover  button" aria-hidden="true" onclick="remove_recipient(this)"></i>
        </td>
        <td class="mixed_recipients">
            <input class="potentially_valid" value="" ovalue=""   placeholder="{t}External email{/t}">

        </td>
    </tr>
    <tr class="add_new_mixed_recipients_tr">

        <td colspan="2">
            <span onclick="add_user_to_mixed_recipients(this)" class="button">{t}Add user{/t} <i class="fa fa-plus"></i></span>
            <span onclick="add_external_email_to_mixed_recipients(this)" class="button padding_left_10">{t}Add external email{/t} <i class="fa fa-plus"></i></span>

            <span   onclick="save_mixed_recipients(this)" class=" save padding_left_50 {if $mode=='new'}hide{/if} ">{t}Save{/t} <i id="{$field_id}_save_button" class="fa fa-cloud  "></i></span>

        </td>

    </tr>
    {/if}
</table>

