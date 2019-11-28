
<div class="move_all_parts_from_location_inline_form hide "  style="float:right"  data-location_key="{$data.location_key}" >
    <span class="invalid_msg hide">{t}Invalid value{/t}</span>
	<span class="button small remove_from_location padding_right_10 unselectable"><i class="fal fa-square"></i>  {t}Remove from here after{/t}</span>


	<span>{$data.field_label}</span>
    <input  class="inline_input" value=""  placeholder="{if isset($data.placeholder)}{$data.placeholder}{/if}">

    <div  class="search_results_container hide" style="width:400px;">
        <table class="move_part_to_location_res" style="background:white;font-size:90%">
            <tr class="hide add_item_search_result_template"  data-item_key="" data-item_historic_key=""
				data-formatted_value="" onClick="select_add_item_option(this)">
                <td class="code" style="padding-left:5px;"></td>
            </tr>
        </table>
    </div>
	    <i data-location_key=""  class="move_all_parts_from_location_save save fa fa-cloud super_discreet" ></i>
    <i class="fa fa-times padding_left_10 padding_right_10 button close_move_all_parts_from_location" ></i>


</div>