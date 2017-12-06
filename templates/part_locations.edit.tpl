

{foreach $locations_data item=location_data}

{if $location_data.location_key!=1}

    <tr id="part_location_edit_{$location_data.location_key}" class="locations"  location_key="{$location_data.location_key}" >
        <td style="width:20px" class="unlink_operations hide"><i class="fa fa-fw  fa-unlink button super_discreet"
                                                                 aria-hidden="true" title="{t}Disassociate location{/t}"
                                                                 onclick="disassociate_location(this)"></i></td>
        <td>
	    <span class=" location_info">
	        <span class="picking_location_icon">
                <i onclick="set_as_picking_location({$part_sku},{$location_data.location_key})" class="fa fa-fw fa-shopping-basket  {if $location_data.can_pick=='No'}super_discreet_on_hover button{else}{/if}   " aria-hidden="true" title="{if $location_data.can_pick=='No'}{t}Set as picking location{/t}{else}{t}Picking location{/t}{/if}" ></i>
            </span>

	         <span onclick="change_view('/locations/{$location_data.warehouse_key}/{$location_data.location_key}')" class="link location_code">
                 {$location_data.location_code}</span>
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

        <td class="aright  formatted_stock">{$location_data.formatted_stock}</td>
        <td class="aright  hide stock_input"><span class="stock_change"></span>

            <i class="fa fa-dot-circle-o button super_discreet set_as_audit" aria-hidden="true"
               title="{t}Mark as audited{/t}" onclick="set_as_audit(this)"></i>
            <input class="stock" style="width:60px" action="" location_key="{$location_data.location_key}"
                   ovalue="{$location_data.stock}" value="{$location_data.stock}">

            <input type="hidden" class="note" value="">
            <i class="fa fa-sticky-note-o button super_discreet add_note invisible " aria-hidden="true"
               title="{t}Note{/t}" onclick="set_inventory_transaction_note(this)"></i>

            <i class="fa fa-fw fa-caret-square-o-right move_trigger button super_discreet  " aria-hidden="true"
               title="{t}Move from{/t}" onclick="move(this)"></i></td>
    </tr>

    {/if}
{/foreach}






<tr id="add_location_template" class="hide">


    <td style="width:20px" class="unlink_operations hide"><i class="fa fa-fw  fa-unlink button super_discreet"
                                                             aria-hidden="true" title="{t}Disassociate location{/t}"
                                                             onclick="disassociate_location(this)"></i></td>
    <td>
	    <span class="location_info">
	        <span class="picking_location_icon"></span>
	        <span class="location_code link"></span>
        </span>

        <span class="very_discreet recommendations">
	        <span onClick="open_edit_min_max(this)" class="min_max open_edit_min_max">{literal}{{/literal}<span
                        class="formatted_recommended_min"></span>,<span
                        class="formatted_recommended_max"></span>}</span>
	        <span class="edit_min_max hide"><i onClick="close_edit_min_max(this)"
                                               class="close_min_max button fa fa-times" aria-hidden="true"></i> <input
                        class="recommended_min min_max" style="width:30px" ovalue="" value="" placeholder="{t}min{/t}"/><input
                        class="recommended_max min_max" style="width:30px" ovalue="" value="" placeholder="{t}max{/t}"/> <i
                        onClick="save_recommendations('min_max',this)" class="fa fa-cloud save"
                        aria-hidden="true"></i></span>
	        
	        <span onClick="open_edit_recommended_move(this)" class="recommended_move open_edit_recommended_move">[<span
                        class="formatted_recommended_move"></span>]</span>
	        <span class="edit_move hide"><i onClick="close_edit_recommended_move(this)"
                                            class="close_move button fa fa-times" aria-hidden="true"></i> <input
                        class="recommended_move" style="width:30px" ovalue="" value=""/> <i
                        onClick="save_recommendations('move',this)" class="fa fa-cloud save"
                        aria-hidden="true"></i></span>


	        </span>
    </td>

    <td class="aright  formatted_stock">0</td>
    <td class="aright  hide stock_input"><span class="stock_change"></span>

        <i class="fa fa-dot-circle-o button super_discreet set_as_audit" aria-hidden="true"
           title="{t}Mark as audited{/t}" onclick="set_as_audit(this)"></i>
        <input class="stock" style="width:60px" action="" location_key="0" ovalue="0" value="0">

        <input type="hidden" class="note" value="">
        <i class="fa fa-sticky-note-o button super_discreet add_note invisible " aria-hidden="true" title="{t}Note{/t}"
           onclick="set_inventory_transaction_note(this)"></i>

        <i class="fa fa-fw fa-caret-square-o-right move_trigger button super_discreet invisible " aria-hidden="true"
           title="{t}Move from{/t}" onclick="move(this)"></i></td>

</tr>


<tr id="add_location_tr" class="  hide">
    <td><i class="fa fa-fw  discreet fa-chain button" aria-hidden="true" title="{t}Associate location{/t}"
           onclick="open_add_location()"></i></td>
    <td colspan=2><span id="add_location_label" class="button discreet"
                        onclick="open_add_location()">{t}Associate location{/t}</span>
        <input class="hide" id="add_location" placeholder="{t}Location code{/t}"> <i class="fa  fa-cloud   save hide"
                                                                                     aria-hidden="true"
                                                                                     title="{t}Add location{/t}"
                                                                                     id="save_add_location"
                                                                                     location_key=""
                                                                                     onClick="save_add_location()"></i>

        <div id="add_location_results_container" class="search_results_container" style="width:220px;">

            <table id="add_location_results" border="0" style="background:white;">
                <tr class="hide" style=";" id="add_location_search_result_template" field="" value="" formatted_value=""
                    onClick="select_add_location_option(this)">
                    <td class="label" style="padding-left:5px;"></td>

                </tr>
            </table>

        </div>

    </td>

</tr>
<tr>
    <td></td>
    <td colspan="2" class="small" id="location_data_msg"></td>
</tr>





    <div id="inventory_transaction_note" style="position:absolute;z-index:100" class="hide" scope="">
        <textarea></textarea>
    </div>

    <script>
        $('#inventory_transaction_note textarea').bind('input propertychange', function () {
            inventory_transaction_note_changed()
        });
    </script>
