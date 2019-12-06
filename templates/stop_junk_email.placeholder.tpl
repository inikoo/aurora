{*
<!--
About:
Author: Raul Perusquia <raul@inikoo.com>
Created:  06 December 2019  11:51::01  +0100, Kuala Lumpur
Copyright (c) 2019, Inikoo

Version 3
-->
*}

<div style="color:#555555;line-height:120%;font-family:'Ubuntu', Tahoma, Verdana, Segoe, sans-serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
    <div style="font-size:12px;line-height:14px;font-family:Ubuntu, Tahoma, Verdana, Segoe, sans-serif;color:#555555;text-align:left;">
        <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center">
            <span style="font-size: 11px; line-height: 13px;">{if isset($localised_labels['_remove_from_junk_email_text'])}{$localised_labels['_remove_from_junk_email_text']}{else}{t}You want to be removed from this mailing list?{/t}{/if} <a ses:tags="type:unsubscribe;" style="color:#0068A5;text-decoration: underline;"  href="{$link}" target="_blank" rel="noopener">{if isset($localised_labels['_remove_from_junk_email_link'])}{$localised_labels['_remove_from_junk_email_link']}{else}{t}Click here to be removed{/t}{/if}</a>
            </span>
        </p>
    </div>
</div>