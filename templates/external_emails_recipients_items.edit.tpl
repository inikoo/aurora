{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 January 2019 at 12:30:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{foreach from=$external_emails item=external_email}

    <tr >
        <td class="operations">
            <i class="far fa-trash-alt very_discreet_on_hover  button" aria-hidden="true" onclick="remove_recipient(this)"></i>
        </td>
        <td class="mixed_recipients">
            <input class="valid external_email_mixed_recipients_value" value="{$external_email}" type="hidden">

            <span class="discreet italic recipient">{$external_email}</span>

        </td>
    </tr>
{/foreach}