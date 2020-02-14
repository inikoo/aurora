<div id="add_item_to_portfolio_form" style="float:right;" class="hide" data-metadata="{$data.metadata}"  data-ar_url="{$data.ar_url}" >
    <span id="add_item_to_portfolio_invalid_msg" class="hide">{t}Invalid value{/t}</span>

    <span>{$data.field_label}</span>
    <input style="margin-right:2px" id="add_item_to_portfolio" class="item " value=""
           placeholder="{t}code{/t}">
    <div id="add_item_to_portfolio_results_container" class="search_results_container hide" style="width:500px;">

        <table id="add_item_to_portfolio_results" style="background:white;font-size:90%;width: 100%">
            <tr class="hide" id="add_item_to_portfolio_search_result_template" data-field="" data-item_key=""
                data-formatted_value="" onClick="select_add_item_to_portfolio_option(this)">
                <td class="code" style="padding-left:5px;"></td>
                <td class="label" style="padding-left:5px;"></td>

            </tr>
        </table>

    </div>


    <i id="add_item_to_portfolio_save" data-item_key="" class="save fa fa-cloud super_discreet"
       onClick="save_add_item_to_portfolio()"></i>
    <i class="fa fa-times padding_left_10 padding_right_10 button" onClick="close_add_item_to_portfolio()"></i>


</div>

<script>
    $('#{$trigger}').on("click", function () {
        show_add_item_to_portfolio_form()
    });

</script>