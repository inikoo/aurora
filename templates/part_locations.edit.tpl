{foreach $locations_data item=location_data}
<tr>
	<td style="width:20px" class="unlink_operations hide" ><i class="fa fa-fw  fa-unlink button super_discreet" aria-hidden="true" title="{t}Disassociate location{/t}" onclick="disassociate_location(this)"></i> </td>
	<td>
	    <span onclick="change_view('/locations/{$location_data.warehouse_key}/{$location_data.location_key}')" class="link location_info">
	        <span class="location_used_for_icon">{$location_data.location_used_for_icon}</span> 
	        <span class="location_code">{$location_data.location_code}</span></span> 
	        
	        <span class="very_discreet recommendations">
	        <span onClick="open_edit_min_max(this)" class="min_max button {if $location_data.can_pick=='No'}hide{/if}">{literal}{<span class="formatted_recommended_min">{/literal}{$location_data.formatted_min_qty}</span>,<span class="formatted_recommended_max">{$location_data.formatted_max_qty}</span>}</span> 
	        <span class="edit_min_max hide" ><i onClick="close_edit_min_max(this)" class="close_min_max button fa fa-times" aria-hidden="true" ></i> <input class="recommended_min min_max" style="width:30px" ovalue="{$location_data.min_qty}" value="{$location_data.min_qty}" placeholder="{t}min{/t}"/><input class="recommended_max min_max" style="width:30px"  ovalue="{$location_data.max_qty}" value="{$location_data.max_qty}" placeholder="{t}max{/t}"/> <i onClick="save_recomendations('min_max',this)" class="fa fa-cloud save" aria-hidden="true" ></i></span> 
	        
	        <span onClick="open_edit_recommended_move(this)" class="recommended_move button {if $location_data.can_pick=='Yes'}hide{/if}">[<span class="formatted_recommended_move">{$location_data.formatted_move_qty}</span>]</span>
	        <span class="edit_move hide" ><i onClick="close_edit_recommended_move(this)" class="close_move button fa fa-times" aria-hidden="true" ></i> <input class="recommended_move" style="width:30px" ovalue="{$location_data.move_qty}" value="{$location_data.move_qty}" /> <i onClick="save_recomendations('move',this)" class="fa fa-cloud save" aria-hidden="true" ></i></span> 


	        </span>
	        </td>

	<td class="aright button formatted_stock">{$location_data.formatted_stock}</td>
	<td class="aright  hide stock_input"  > <span class="stock_change"></span>
	 <i class="fa fa-dot-circle-o button super_discreet set_as_audit" aria-hidden="true" title="{t}Mark as audited{/t}"  onclick="set_as_audit(this)"></i>
	<input class="stock" style="width:60px" action="" location_key="{$location_data.location_key}" ovalue="{$location_data.stock}" value="{$location_data.stock}">
	<i class="fa fa-fw fa-caret-square-o-right move_trigger button super_discreet" aria-hidden="true" title="{t}Move from{/t}" onclick="move(this)"></i> </td>
</tr>
{/foreach}				

 <tr id="add_location_template" class="hide">
	<td style="width:20px" class="unlink_operations"><i class="fa fa-fw  fa-unlink button super_discreet" aria-hidden="true" title="{t}Disassociate location{/t}" onclick="disassociate_location(this)"></i> </td>
	<td><span class="link location_info"><span class="location_used_for_icon"></span> <span class="location_code"></span></span> </td>
	<td class="aright button formatted_stock"></td>
	<td class="aright  hide stock_input"> <span class="stock_change"></span>
	 <i class="fa fa-dot-circle-o button super_discreet set_as_audit" aria-hidden="true" title="{t}Mark as audited{/t}"  onclick="set_as_audit(this)"></i>
	<input class="_stock" style="width:60px" action="" location_key="" ovalue="0" value="">
	<i class="fa fa-fw fa-caret-square-o-right _move_trigger button super_discreet" aria-hidden="true" title="{t}Move from{/t}" onclick="move(this)"></i> </td>
</tr>
 
 
<tr id="add_location_tr" class="  hide">
	<td> <i class="fa fa-fw  discreet fa-chain button" aria-hidden="true" title="{t}Associate location{/t}"  onclick="open_add_location()"></i></td>
	<td colspan=2><span id="add_location_label" class="button discreet" onclick="open_add_location()">{t}Associate location{/t}</span>
	<input class="hide" id="add_location" placeholder="{t}Location code{/t}"> <i  class="fa  fa-cloud   save hide" aria-hidden="true" title="{t}Add location{/t}" id="save_add_location" location_key="" onClick="save_add_location()" ></i>
	
	<div id="add_location_results_container" class="search_results_container" style="width:220px;">
		
		<table id="add_location_results" border="0" style="background:white;" >
			<tr class="hide" style=";" id="add_location_search_result_template" field="" value="" formatted_value="" onClick="select_add_location_option(this)">
				<td class="label" style="padding-left:5px;"></td>
				
			</tr>
		</table>
	
	    </div>
	
	</td>
	
</tr>
<tr>
<td></td>
<td colspan="2" class="small" id="location_data_msg"></td>
<tr>

