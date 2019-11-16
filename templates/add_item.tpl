<div style="float:right;" class="{$trigger} add_item_form hide" data-field="{$data.field}" data-trigger="{$trigger}" data-metadata="{$data.metadata}">
    <span class="hide add_item_invalid_msg">{t}Invalid value{/t}</span>
    <span>{$data.field_label}</span>
    <input style="margin-right:2px"  class="item " value="" placeholder="{if isset($data.placeholder)}{$data.placeholder}{/if}">
    <input style="margin-right:2px" class="qty width_75 " value="" placeholder="{if isset($data.placeholder_qty)}{$data.placeholder_qty}{else}{t}qty{/t}{/if}">
    <div  class="search_results_container hide" style="width:400px;">
        <table class="add_item_results" style="background:white;font-size:90%">
            <tr class="hide add_item_search_result_template"  data-item_key="" data-item_historic_key=""
                data-formatted_value="" onClick="select_add_item_option(this)">
                <td class="code" style="padding-left:5px;"></td>
                <td class="label" style="padding-left:5px;"></td>
            </tr>
        </table>
    </div>
    <i data-item_key="" data-item_historic_key="" class="add_item_save save fa fa-cloud super_discreet" onClick="save_add_item(this)"></i>
    <i class="fa fa-times padding_left_10 padding_right_10 button" onClick="close_add_item(this)"></i>
</div>

<script>
    $('#{$trigger}').on("click", function () {
        show_add_item_form($('.{$trigger}'))
    });
</script>