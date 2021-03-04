
{foreach from=$locations_data item=location_data}

{if $location_data.location_key!=$warehouse_unknown_location_key}

    <tr id="part_location_edit_{$location_data.location_key}" class="locations"  location_key="{$location_data.location_key}" >
        <td style="width:20px" class="undo_unlink_operations ">
            <i class="fa fa-fw  hide fa-undo button " title="{t}Undo{/t}" onclick="undo_disassociate_location(this)"></i>
        </td>
        <td>
	    <span class=" location_info">
            <span class="picking_location_note" style="margin-right: 3px">
                <i onclick="set_part_location_note_bis(this)" key="{$part_sku}_{$location_data.location_key}" class="button  fa-fw   {if $location_data.note==''}super_discreet_on_hover far fa-sticky-note{else}fas fa-sticky-note{/if}   " aria-hidden="true" title="{if $location_data.note!=''}{$location_data.note}{else}{t}Add part's location note{/t}{/if}" ></i>
                     <div  class="hide picking_location_note_value">{$location_data.note}</div>
              </span>
            <span class="picking_location_icon">
                <i onclick="set_as_picking_location({$part_sku},{$location_data.location_key})" class="fa fa-fw fa-shopping-basket  {if $location_data.can_pick=='No'}super_discreet_on_hover button{else}{/if}   " aria-hidden="true" title="{if $location_data.can_pick=='No'}{t}Set as picking location{/t}{else}{t}Picking location{/t}{/if}" ></i>
            </span>
            <span onclick="change_view_if_has_link_class(this,'/locations/{$location_data.warehouse_key}/{$location_data.location_key}')" class="link location_code">{$location_data.location_code}{$location_data.location_external_icon}</span>
        </span>
            <span class="hide  discreet disassociate_info italic small">
                {if $location_data.can_pick=='Yes'}<span class="margin_left_5" title="{t}Preferred picking location cc{/t}"><i class="fa fa-fw fa-shopping-basket "></i></span>{/if}
                {if $location_data.stock!=0}<span class=" margin_left_5">{t}current stock{/t} {$location_data.formatted_stock}{/if}</span>
            </span>
            <span class="hide  disassociate_warning small">

                {if $location_data.stock!=0}<span class="error margin_left_5"> <i class="fa   fa-exclamation-triangle"></i>  {t}current stock{/t} {$location_data.formatted_stock}</span>{/if}
                {if $location_data.can_pick=='Yes'}<span class="error margin_left_5" title="{t}Preferred picking location zz{/t}"><i class="fa margin_left_5  fa-exclamation-triangle"></i>  <i class="fa fa-fw fa-shopping-basket "></i></span>{/if}
            </span>

            <span class="very_discreet recommendations">
	        <span onClick="open_edit_min_max(this)"
                  location_key="{$location_data.location_key}" min="{$location_data.min_qty}" max="{$location_data.max_qty}"
                  title="{t}Recommended min/max stock{/t}"

                  class="button min_max open_edit_min_max {if $location_data.can_pick=='No'}hide{/if}">
                 ( <span class="formatted_recommended_min">{$location_data.formatted_min_qty}</span> ,
                   <span class="formatted_recommended_max">{$location_data.formatted_max_qty}</span> )
            </span>

	        
	        <span onClick="open_edit_recommended_move(this)"

                  location_key="{$location_data.location_key}"  recommended_move="{$location_data.move_qty}"

                  title="{t}Recommended replenishment quantity{/t}"

                  class="button open_edit_recommended_move {if $location_data.can_pick=='Yes'}hide{/if}">[ <span class="formatted_recommended_move">{$location_data.formatted_move_qty}</span> ]</span>



	        </span>
        </td>
        <td class="aright  last_audit_days">{$location_data.days_last_audit}</td>
        <td class="aright  formatted_stock">{$location_data.formatted_stock}  <i onclick="open_sent_part_to_production(this)" location_key="{$location_data.location_key}" max="{$location_data.stock}"  class="far fa-hand-rock padding_left_10 button production_supply_edit {if !$part->get('Part Raw Material Key')>0}hide{/if}" aria-hidden="true"></i> </td>
        <td class="aright  hide stock_input"><span class="stock_change"></span>

            <i class="far fa-dot-circle button super_discreet_on_hover set_as_audit" aria-hidden="true" title="{t}Mark as audited{/t}" onclick="set_as_audit(this)"></i>
            <input class="stock" style="width:60px" action="" location_key="{$location_data.location_key}" ovalue="{$location_data.stock}" value="{$location_data.stock}">




            <input type="hidden" class="note" value="">
            <i class="fal fa-notes-medical button very_discreet_on_hover add_note invisible " aria-hidden="true" title="{t}Note{/t}" data-note="" onclick="set_inventory_transaction_note(this)"></i>


            <i class="fa fa-fw fa-forklift move_trigger button super_discreet  {if $part->get_number_real_locations($warehouse_unknown_location_key)<=1}hide{/if} "  title="{t}Move from{/t}" onclick="move(this)"></i>

            <i data-location_key="{$location_data.location_key}" onclick="disassociate_location(this)" class="fal fa-fw margin_left_10 button fa-unlink very_discreet_on_hover hide location_to_be_disassociated_icon" title="{t}Unlink location{/t}"  ></i>


        </td>
    </tr>

    {/if}
{/foreach}


