<div id="add_fulfilment_asset_form" class="hide" data-type="add_one" data-metadata='{$data.metadata}' data-ar_url="{$data.ar_url}"
     style="z-index:1000;padding:5px 20px 20px 20px;background:#FFF ;width: 450px;border:1px solid #ccc;position: absolute;">
    <span id="add_fulfilment_asset_invalid_msg" class="hide">{t}Invalid value{/t}</span>


    <table style="width: 100%;">
        <tr>
            <td></td>
            <td style="text-align: right"><span onclick="$('#add_fulfilment_asset_form').addClass('hide')" class="small button"><i class="fa fa-window-close"></i> {t}Close{/t}</span></td>

        </tr>
        <tr>
            <td>{t}Type{/t}</td>
            <td id="add_fulfilment_asset_type" data-value="Pallet">
                <span style="margin-right: 25px"><i data-value="Pallet" class="option far fa-dot-circle"></i>  {t}Pallet{/t} <em class="fal fa-pallet-alt"></em></span>
                <span class="very_discreet_on_hover button"><i data-value="Box" class="option far fa-circle"></i>  {t}Box{/t} <em class="fal fa-box-alt"></em></span>
            </td>
        </tr>
        <tr class="show_if_add_one ">
            <td><label for="add_fulfilment_asset_reference">{t}Reference{/t}</label></td>
            <td><input style="margin-right:2px" id="add_fulfilment_asset_reference" value=""
                       placeholder="{t}Customer reference{/t}"></td>
        </tr>

        <tr class="show_if_add_one  " id="add_fulfilment_asset_note_button">
            <td></td>
            <td><span class="button small" onclick="$('#add_fulfilment_asset_note_tr').removeClass('hide');$('#add_fulfilment_asset_note_button').addClass('hide')"><i class="far fa-plus"></i> {t}Add note{/t}</span></td>

        </tr>
        <tr class="hide show_if_add_one" id="add_fulfilment_asset_note_tr">
            <td><label for="add_fulfilment_asset_note">{t}Note{/t}</label></td>
            <td><textarea id="add_fulfilment_asset_note" style="width: 95%"></textarea></td>

        </tr>
        <tr class="show_if_add_multiple hide">
            <td><label for="add_fulfilment_asset_number_assets">{t}Quantity{/t} <small class="discreet">(max 100)</small></label></td>
            <td><input style="margin-right:2px" id="add_fulfilment_asset_number_assets" type="number" min="1" step="1" value=""
                       placeholder="{t}Number of pallets{/t}" data-pallet_label="{t}Number of pallets{/t}" data-box_label="{t}Number of boxes{/t}"></td>
        </tr>
        <tr>
            <td>
                <span class="button small show_if_add_one" id="add_fulfilment_asset_add_multiple"><i class="far fa-fw  fa-layer-group"></i> {t}Add multiple{/t}</span>
                <span class="button small show_if_add_multiple hide" id="add_fulfilment_asset_add_one"><i class="far fa-fw fa-pallet-alt"></i> {t}Add only one{/t}</span>

            </td>
            <td style="text-align: right"><span id="add_fulfilment_asset_save" class="save valid changed"><i class="fa fa-cloud"></i> {t}Save{/t}</span></td>

        </tr>
    </table>


</div>


