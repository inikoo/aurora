<div id="set_out_of_stock_items_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 20px;z-index: 100">
    <table>

        <tr>
            <td>{t}Out of stock{/t}

                <input class="picked_qty width_50" value="" ovalue=""/> <i onClick="save_item_out_of_stock_qty_change(this)" class="fa  fa-plus fa-fw button add_picked %s" aria-hidden="true"></i>


            </td>
        </tr>

        <tr class="hide">
            <td class="out_of_stock_location_code"></td>
            <td class="out_of_stock_part_reference"></td>
            <td class="out_of_stock_part_stock"></td>

        </tr>

    </table>
</div>


<span id="dn_data" class="hide" dn_key="{$dn->id}" picker_key="{$dn->get('Delivery Note Assigned Picker Key')}" packer_key="{$dn->get('Delivery Note Assigned Packer Key')}" no_picker_msg="{t}Please assign picker{/t}"
      no_packer_msg="{t}Please assign packer{/t}"

></span>
<div class="table_new_fields" style="border-bottom:1px solid #ccc;">

    <div id="picking_options" class="picking_options" style="align-items: stretch;flex: 1;border-left:1px solid #ccc">
        {include file="delivery_note.options.picking.tpl"}
    </div>
    <div id="packing_options" class="packing_options  " style="align-items: stretch;flex: 0;padding:10px 20px;border-left:1px solid #eee">
        {include file="delivery_note.options.packing.tpl"}
    </div>


</div>


