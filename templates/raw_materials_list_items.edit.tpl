{foreach from=$raw_materials_list item=raw_material_production_part_data}
    <tr class="raw_material_tr">
        <td>
            <i class="fa fa-trash button" aria-hidden="true" onclick="remove_raw_material(this)"></i>
            <input type="hidden" class="raw_materials_list_value production_part_raw_material_key" value="{$raw_material_production_part_data['Key']}" ovalue="{$raw_material_production_part_data['Key']}">
        </td>

        {$ratio=$raw_material_production_part_data['Ratio']}

        {if $ratio|strpos:'.' eq true}
            {$formatted_ratio= $ratio|rtrim:'0'|rtrim:'.'}
        {else}
            {$formatted_ratio=$ratio}
        {/if}
        <td style="text-align: right" >
            <input style="width:80px;" class="raw_materials_list_value raw_material_qty" value="{$formatted_ratio}" ovalue="{$formatted_ratio}"/>
        </td>
        <td style="padding-right: 20px;min-width: 100px" class="raw_materials_unit_label">{$raw_material_production_part_data['Unit Label']}</td>

        <td style="width: 160px" class="raw_materials">
            <input type="hidden" class="raw_materials_list_value raw_material_key" value="{$raw_material_production_part_data['Raw Material Key']}" ovalue="{$raw_material_production_part_data['Raw Material Key']}">
            <span class="Raw_Material_Code">{$raw_material_production_part_data['Code']}</span>

        </td>
        <td class="raw_materials_description" style="width: 600px">{$raw_material_production_part_data['Name']}</td>

        <td class="hide notes"><input class="part_list_value note" value="{$raw_material_production_part_data['Note']}" ovalue="{$raw_material_production_part_data['Note']}" placeholder="{t}Note for pickers{/t}"></td>
    </tr>
{/foreach}