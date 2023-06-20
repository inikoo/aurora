{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 13 May 10:46 2022 Sheffield Uk
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}



<div id="variant_chooser_dialog_{$master_id}" class="variant_chooser_dialog hide"  >
    <table class="variant_chooser" >
        <tr>
            <th></th>
            <th style="text-align: right">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}</th>
            <th style="text-align: right"> {if empty($labels._per_unit)}{t}Price/U{/t}{else}{$labels._per_unit}{/if}</th>
            <th style="text-align: right">U</th>

        </tr>
        {foreach from=$variants item=$variant  name=variant}
            <tr class="variant_option {if $master_id==$variant.id}current{/if}"
                    data-id="{$variant.id}"
                    data-name="{$variant.name}"
                    data-code="{$variant.code}"
                    data-price="{$variant.price}">
                <td class="smaller_font"  >{$variant.label}</td>
                <td   style="text-align: right">{$variant.price}</td>
                <td   style="text-align: right">{$variant.price_unit_bis}</td>
                <td  class="smaller_font"  style="text-align: right;padding-left: 0px;">{$variant.formatted_units}</td>
            </tr>
        {/foreach}
        <tr></tr>
    </table>
</div>

