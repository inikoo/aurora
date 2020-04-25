{foreach from=$parts_list item=part_data}
    <tr class="part_tr">
        <td><i class="fa fa-trash button" aria-hidden="true" onclick="remove_part(this)"></i><input type="hidden"
                                                                                                    class="part_list_value product_part_key"
                                                                                                    value="{$part_data['Key']}"
                                                                                                    ovalue="{$part_data['Key']}">
        </td>

        {$ratio=$part_data['Ratio']}

        {if $ratio|strpos:'.' eq true}
            {$formatted_ratio= $ratio|rtrim:'0'|rtrim:'.'}

        {else}
            {$formatted_ratio=$ratio}
        {/if}
        <td class="parts_per_products"><input style="width:50px" class="part_list_value parts_per_product" value="{$formatted_ratio}" ovalue="{$formatted_ratio}"> x
        </td>
        <td class="parts">
            <input type="hidden" class="part_list_value sku" value="{$part_data['Part SKU']}" ovalue="{$part_data['Part SKU']}">
            <span class="Part_Reference">{$part_data['Part']->get('Reference')}</span>
        </td>
        <td class="notes"><input class="part_list_value note" value="{$part_data['Note']}" ovalue="{$part_data['Note']}" placeholder="{t}Note for pickers{/t}"></td>
    </tr>
{/foreach}