{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 12 May 11:53 2022 Sheffield Uk
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="variant_chooser_dialog_{$master_id}" class="variant_chooser_dialog hide" style="position: absolute;z-index: 4000;background: #FFF" >
    <table class="variant_chooser" >
        <tr>
            <th></th>
            <th style="text-align: right">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}</th>
            <th style="text-align: right">{if empty($labels._unit)}{t}Units{/t}{else}{$labels._unit}{/if}</th>
            <th style="text-align: right"> {if empty($labels._per_unit)}{t}Price/Unit{/t}{else}{$labels._per_unit}{/if}</th>
        </tr>
        {foreach from=$variants item=$variant  name=variant}
            <tr class="variant_option {if $master_id==$variant->id}current{/if}   "
                    data-id="{$variant->id}"
                    data-name="{$variant->get('Name')}"
                    data-code="{$variant->get('Product Code')}"
                    data-weight="{$variant->get('Package Weight')}"
            >
                <td>{$variant->get('Product Variant Short Name')}</td>
                <td   style="text-align: right">{$variant->get('Price')}</td>
                <td   style="text-align: right">{$variant->get('Formatted Units')}</td>
                <td   style="text-align: right">{$variant->get('Price Per Unit Bis')}</td>
            </tr>
        {/foreach}
        <tr></tr>
    </table>
</div>
<script>
  $( document ).ready(function() {
    let element=$('.variant_chooser .variant_option')
    variant_selected(element[0])
  });
</script>
