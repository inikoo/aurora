{*
<!--
About:
Author: Raul Perusquia <raul@inikoo.com>
Created: 3 July 2018 at 18:12:42 GMT+8, Kuala Lumpur, Malaysia
Copyright (c) 2017, Inikoo

Version 3
-->
*}

<div style="color:#555555;line-height:120%;font-family:'Ubuntu', Tahoma, Verdana, Segoe, sans-serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
    <div style="font-size:12px;line-height:14px;font-family:Ubuntu, Tahoma, Verdana, Segoe, sans-serif;color:#555555;text-align:left;">
        <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center">
            <span style="font-size: 11px; line-height: 13px;">{if isset($localised_labels['_unsubscribe_text'])}{$localised_labels['_unsubscribe_text']}{else}{t}If you do not wish to receive more marketing emails from us{/t}{/if} <a ses:tags="type:unsubscribe;" style="color:#0068A5;text-decoration: underline;"  href="{$link}" target="_blank" rel="noopener">{if isset($localised_labels['_unsubscribe_link'])}{$localised_labels['_unsubscribe_link']}{else}{t}Unsubscribe here{/t}{/if}</a>
            </span>
        </p>
    </div>
</div>