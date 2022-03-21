{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  29 April 2020  14:45::56  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


<div class="pdf_asset_dialog {$type} options_dialog hide " data-type="{$type}" style="{if $type=='unit' or  $type=='sko' or  $type=='carton' or $type=='pallet' or  $type=='box'}min-width: 250px{else}}min-width: 150px{/if}">
    <i onclick="$('.pdf_asset_dialog').addClass('hide')" style="float: right;margin-left: 10px" class="fa fa-window-close button"></i>
    {if $type=='invoice'}
        <h2 class="unselectable">{t}PDF invoice{/t}</h2>
    {elseif $type=='proforma'}
        <h2 class="unselectable">{t}Proforma invoice{/t}</h2>
    {elseif $type=='unit'}
        <h2 class="unselectable">{t}Unit label{/t}</h2>
    {elseif $type=='sko'}
        <h2 class="unselectable">{t}SKO label{/t}</h2>
    {elseif $type=='carton'}
        <h2 class="unselectable">{t}Carton label{/t}</h2>
    {elseif $type=='fulfilment_asset_pallet'}
        <h2 class="unselectable">{t}Pallet label{/t}</h2>
    {elseif $type=='fulfilment_asset_box'}
        <h2 class="unselectable">{t}Box label{/t}</h2>
    {/if}

    <table style="width: 100%">
        {if $type=='invoice'}
            <tbody>
            <tr data-field='pro_mode' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_pro_mode}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_pro_mode}class="discreet"{/if}>{t}Pro mode{/t}</span>
                </td>
            </tr>
            <tr data-field='rrp' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_with_rrp}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_with_rrp}class="discreet"{/if}>{t}Recommended retail prices{/t}</span>
                </td>

            </tr>
            <tr data-field='parts' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far  {if $pdf_with_parts}fa-check-square{else}fa-square{/if}  margin_right_10"></i> <span class="discreet">{t}Parts{/t}</span>
                </td>
            </tr>
            <tr data-field='commodity' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_with_commodity}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_with_commodity}class="discreet"{/if}>{t}Commodity codes{/t}</span>
                </td>

            </tr>
            <tr data-field='barcode' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_with_barcode}fa-check-square{else}fa-square{/if}   margin_right_10"></i> <span class="discreet">{t}Product barcode{/t}</span>
                </td>
            </tr>
            <tr data-field='weight' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_with_weight}fa-check-square{else}fa-square{/if}   margin_right_10"></i> <span class="discreet">{t}Weight{/t}</span>
                </td>
            </tr>
            <tr data-field='origin' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_with_origin}fa-check-square{else}fa-square{/if}   margin_right_10"></i> <span class="discreet">{t}Country of origin{/t}</span>
                </td>
            </tr>
            <tr data-field='CPNP' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_with_CPNP}fa-check-square{else}fa-square{/if}   margin_right_10"></i> <span class="discreet">{t}CPNP{/t}</span>
                </td>
            </tr>
            <tr data-field='locale' class="button pdf_option {if !$pdf_show_locale_option}hide{/if}" onclick="check_field_value(this)">
                <td>
                    <i class="far fa-square margin_right_10" data-value="en_GB"></i> <span class="discreet">{t}English{/t}</span>
                </td>
            </tr>
            <tr data-field='group_by_tariff_code' style="border-top:1px solid #ccc" class="button pdf_option" onclick="check_field_value(this)">
                <td style="padding-top: 3px">
                    <i class="far fa-square   margin_right_10"></i> <span class="discreet">{t}Group by tariff code{/t}</span>
                </td>
            </tr>


            </tbody>
        {elseif $type=='proforma'}
            <tbody>
            <tr data-field='pro_mode' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_pro_mode}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_pro_mode}class="discreet"{/if}>{t}Pro mode{/t}</span>
                </td>
            </tr>
            <tr data-field='rrp' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_with_rrp}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_with_rrp}class="discreet"{/if}>{t}Recommended retail prices{/t}</span>
                </td>
            </tr>
            <tr data-field='parts' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far fa-square  margin_right_10"></i> <span class="discreet">{t}Parts{/t}</span>
                </td>
            </tr>
            <tr data-field='commodity' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far {if $pdf_with_commodity}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$pdf_with_commodity}class="discreet"{/if}>{t}Commodity codes{/t}</span>
                </td>

            </tr>
            <tr data-field='weight' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far fa-square  margin_right_10"></i> <span class="discreet">{t}Weight{/t}</span>
                </td>
            </tr>
            <tr data-field='origin' class="button pdf_option" onclick="check_field_value(this)">
                <td>
                    <i class="far fa-square  margin_right_10"></i> <span class="discreet">{t}Country of origin{/t}</span>
                </td>
            </tr>

            <tr data-field='locale' class="button pdf_option {if !$pdf_show_locale_option}hide{/if}" onclick="check_field_value(this)">
                <td>
                    <i class="far fa-square margin_right_10" data-value="en_GB"></i> <span class="discreet">{t}English{/t}</span>
                </td>
            </tr>
            </tbody>
        {elseif $type=='unit'}
            <tbody>
            <tr data-field='with_image' class="button pdf_option" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_image}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$labels_data.unit.with_image}class="discreet"{/if}>{t}With image{/t}</span>
                </td>
            </tr>
            <tr data-field='with_weight' class="button pdf_option hide" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_weight}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$labels_data.unit.with_weight}class="discreet"{/if}>{t}With weight{/t}</span>
                </td>
            </tr>
            <tr data-field='with_origin' class="button pdf_option" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_origin}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$labels_data.unit.with_origin}class="discreet"{/if}>{t}With made in{/t}</span>
                </td>
            </tr>
            <tr data-field='with_manufactured_by' class="button pdf_option" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_manufactured_by}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$labels_data.unit.with_manufactured_by}class="discreet"{/if}>{t}With manufactured by{/t}</span>
                </td>
            </tr>
            <tr data-field='with_weight' class="button pdf_option" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_weight}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$labels_data.unit.with_weight}class="discreet"{/if}>{t}With weight{/t}</span>
                </td>
            </tr>
            <tr data-field='with_custom_text' class="button pdf_option " onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_custom_text}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span
                            {if !$labels_data.unit.with_custom_text}class="discreet"{/if}>{t}With custom text{/t}</span>
                </td>
            </tr>
            <tr class="custom_text_tr {if !$labels_data.unit.with_custom_text}hide{/if} ">
                <td colspan=2>
                    <textarea style="width: 230px;height: 2.5em" class="custom_text">{$labels_data.unit.custom_text}</textarea>
                </td>
            </tr>
            <tr data-field='with_account_signature' class="button pdf_option" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_account_signature}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span
                            {if !$labels_data.unit.with_account_signature}class="discreet"{/if}>{t}With account signature{/t}</span>
                </td>
            </tr>
            <tr>
                <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                    {t}Size{/t} (mm)
                </td>
            </tr>
            <tr>
                <td class="options sizes " data-type="size" colspan="2">
                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30161" class="option {if $labels_data.unit.size=='EU30161'}selected{/if}">63 x 29.6</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="SK06302900" class="option can_image {if $labels_data.unit.size=='SK06302900'}selected{/if}">63.5 x 29.6</span>

                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30040" class="option {if $labels_data.unit.size=='EU30040'}selected{/if}">70 x 29.7</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30140" class="option can_image {if $labels_data.unit.size=='EU30140'}selected{/if}">125 x 37</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30137" class="option can_image {if $labels_data.unit.size=='EU30137'}selected{/if}">130 x 60</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30129" class="option can_image {if $labels_data.unit.size=='EU30129'}selected{/if}">140 x 90</span>


                </td>
            </tr>
            <tr>
                <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                    {t}Layout{/t}
                </td>
            </tr>
            <tr>
                <td class="options set_ups" data-type="set_up">
                    <span onclick="select_option_from_asset_labels(this)" data-value="single" class="option single {if $labels_data.unit.set_up=='single'}selected{/if} ">{t}Single{/t}</span>

                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.unit.size!='EU30040'}hide{/if}  {if $labels_data.unit.set_up!='single'}selected{/if} option  sheet EU30040">A4 <b>30</b> {t}labels{/t} (EU30040)</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.unit.size!='EU30161'}hide{/if}  {if $labels_data.unit.set_up!='single'}selected{/if} option sheet EU30161">A4 <b>27</b> {t}labels{/t} (EU30161)</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.unit.size!='SK06302900'}hide{/if}  {if $labels_data.unit.set_up!='single'}selected{/if} option sheet SK06302900">A4 <b>27</b> {t}labels{/t} (SK06302900)</span>

                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.unit.size!='EU30140'}hide{/if}  {if $labels_data.unit.set_up!='single'}selected{/if} option sheet EU30140">A4 <b>7</b> {t}labels{/t} (EU30140)</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.unit.size!='EU30137'}hide{/if}  {if $labels_data.unit.set_up!='single'}selected{/if} option sheet EU30137">A4 <b>6</b> {t}labels{/t} (EU30137)</span>


                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.unit.size!='EU30129'}hide{/if}  {if $labels_data.unit.set_up!='single'}selected{/if} option sheet EU30129">A4 <b>3</b> {t}labels{/t} (EU30129)</span>

                </td>

            </tr>
            </tbody>
        {elseif $type=='sko'}
            <tbody>
            <tr data-field='with_image' class="button pdf_option" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.sko.with_image}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$labels_data.sko.with_image}class="discreet"{/if}>{t}With image{/t}</span>
                </td>
            </tr>
            <tr data-field='with_weight' class="button pdf_option hide" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_weight}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$labels_data.unit.with_weight}class="discreet"{/if}>{t}With weight{/t}</span>
                </td>
            </tr>
            <tr data-field='with_origin' class="button pdf_option hide" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_origin}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$labels_data.unit.with_origin}class="discreet"{/if}>{t}With made in{/t}</span>
                </td>
            </tr>

            <tr data-field='with_custom_text' class="button pdf_option " onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.sko.with_custom_text}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span
                            {if !$labels_data.sko.with_custom_text}class="discreet"{/if}>{t}With custom text{/t}</span>
                </td>
            </tr>
            <tr class="custom_text_tr {if !$labels_data.sko.with_custom_text}hide{/if} ">
                <td colspan=2>
                    <textarea style="width: 230px;height: 2.5em" class="custom_text">{$labels_data.sko.custom_text}</textarea>
                </td>
            </tr>

            <tr>
                <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                    {t}Size{/t} (mm)
                </td>
            </tr>
            <tr>
                <td class="options sizes " data-type="size" colspan="2">

                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30161" class="option {if $labels_data.sko.size=='EU30161'}selected{/if}">63 x 29.6</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="SK06302900" class="option can_image {if $labels_data.sko.size=='SK06302900'}selected{/if}">63.5 x 29.6</span>

                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30040" class="option {if $labels_data.sko.size=='EU30040'}selected{/if}">70 x 29.7</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30137" class="option {if $labels_data.sko.size=='EU30137'}selected{/if}">130 x 60</span>


                </td>
            </tr>
            <tr>
                <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                    {t}Layout{/t}
                </td>
            </tr>
            <tr>
                <td class="options set_ups" data-type="set_up">
                    <span onclick="select_option_from_asset_labels(this)" data-value="single" class="option single {if $labels_data.sko.set_up=='single'}selected{/if} ">{t}Single{/t}</span>

                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.sko.size!='EU30161'}hide{/if}  {if $labels_data.sko.set_up!='single'}selected{/if} option sheet EU30161">A4 <b>27</b> {t}labels{/t} (EU30161)</span>

                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.sko.size!='SK06302900'}hide{/if}  {if $labels_data.sko.set_up!='single'}selected{/if} option sheet SK06302900">A4 <b>27</b> {t}labels{/t} (SK06302900)</span>

                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.sko.size!='EU30040'}hide{/if}  {if $labels_data.sko.set_up!='single'}selected{/if} option  sheet EU30040">A4 <b>30</b> {t}labels{/t} (EU30040)</span>


                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.sko.size!='EU30137'}hide{/if}  {if $labels_data.sko.set_up!='single'}selected{/if} option sheet EU30137">A4 <b>6</b> {t}labels{/t} (EU30137)</span>


                </td>

            </tr>
            </tbody>
        {elseif $type=='carton'}
            <tbody>
            <tr data-field='with_image' class="button pdf_option" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.carton.with_image}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span {if !$labels_data.carton.with_image}class="discreet"{/if}>{t}With image{/t}</span>
                </td>
            </tr>
            <tr data-field='with_ingredients' class="button pdf_option" onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.carton.with_ingredients}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span
                            {if !$labels_data.carton.with_ingredients}class="discreet"{/if}>{t}With ingredients/materials{/t}</span>
                </td>
            </tr>
            <tr data-field='with_custom_text' class="button pdf_option " onclick="check_pdf_asset_label_field_value(this)">
                <td colspan=2>
                    <i class="far {if $labels_data.unit.with_custom_text}fa-check-square{else}fa-square{/if} margin_right_10"></i> <span
                            {if !$labels_data.unit.with_custom_text}class="discreet"{/if}>{t}With custom text{/t}</span>
                </td>
            </tr>
            <tr class="custom_text_tr {if !$labels_data.unit.with_custom_text}hide{/if} ">
                <td colspan=2>
                    <textarea style="width: 230px;height: 2.5em" class="custom_text">{$labels_data.unit.custom_text}</textarea>
                </td>
            </tr>
            <tr>
                <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                    {t}Size{/t} (mm)
                </td>
            </tr>
            <tr>
                <td class="options sizes " data-type="size" colspan="2">
                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30090" class="option {if $labels_data.carton.size=='EU30090'}selected{/if}">97 x 69</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30036" class="option {if $labels_data.carton.size=='EU30036'}selected{/if}">105 x 74</span>

                </td>
            </tr>
            <tr>
                <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                    {t}Layout{/t}
                </td>
            </tr>
            <tr>
                <td class="options set_ups" data-type="set_up">
                    <span onclick="select_option_from_asset_labels(this)" data-value="single" class="option single {if $labels_data.carton.set_up=='single'}selected{/if} ">{t}Single{/t}</span>

                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.carton.size!='EU30090'}hide{/if}  {if $labels_data.carton.set_up!='single'}selected{/if} option  sheet EU30090">A4 <b>8</b> {t}labels{/t} (EU30090)</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.carton.size!='EU30036'}hide{/if}  {if $labels_data.carton.set_up!='single'}selected{/if} option sheet EU30036">A4 <b>8</b> {t}labels{/t} (EU30036)</span>

                </td>

            </tr>
            </tbody>

        {elseif $type=='pallet'}
            <tbody>
        <tr>
            <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                <i class="fal fa-pallet-alt"></i> {t}Size{/t} (mm)
            </td>
        </tr>
        <tr>
            <td class="options sizes " data-type="size" colspan="2">
                <span onclick="select_option_from_asset_labels(this)" data-value="EU30036" class="option {if $labels_data.pallet.size=='EU30036'}selected{/if}">105 x 74</span>
                <span onclick="select_option_from_asset_labels(this)" data-value="A4" class="option {if $labels_data.pallet.size=='A4'}selected{/if}">A4</span>

            </td>
        </tr>
        <tr>
            <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                {t}Layout{/t}
            </td>
        </tr>
        <tr>
            <td class="options set_ups" data-type="set_up">
                <span onclick="select_option_from_asset_labels(this)" data-value="single" class="option single {if $labels_data.pallet.set_up=='single'}selected{/if} ">{t}Single{/t}</span>


                <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                      class="option {if $labels_data.pallet.size!='EU30036'}hide{/if}  {if $labels_data.pallet.set_up!='single'}selected{/if} option sheet EU30036">A4 <b>8</b> {t}labels{/t} (EU30036)</span>

            </td>

        </tr>
        </tbody>
        {elseif $type=='box'}
            <tbody>
            <tr>
                <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                    <i class="fal fa-box-alt"></i> {t}Size{/t} (mm)
                </td>
            </tr>
            <tr>
                <td class="options sizes " data-type="size" colspan="2">
                    <span onclick="select_option_from_asset_labels(this)" data-value="EU30036" class="option {if $labels_data.box.size=='EU30036'}selected{/if}">105 x 74</span>
                    <span onclick="select_option_from_asset_labels(this)" data-value="A4" class="option {if $labels_data.box.size=='A4'}selected{/if}">A4</span>

                </td>
            </tr>
            <tr>
                <td colspan=2 style="border-bottom: 1px solid #ccc;padding-top:10px">
                    {t}Layout{/t}
                </td>
            </tr>
            <tr>
                <td class="options set_ups" data-type="set_up">
                    <span onclick="select_option_from_asset_labels(this)" data-value="single" class="option single {if $labels_data.box.set_up=='single'}selected{/if} ">{t}Single{/t}</span>


                    <span onclick="select_option_from_asset_labels(this)" data-value="sheet"
                          class="option {if $labels_data.box.size!='EU30036'}hide{/if}  {if $labels_data.box.set_up!='single'}selected{/if} option sheet EU30036">A4 <b>8</b> {t}labels{/t} (EU30036)</span>

                </td>

            </tr>
            </tbody>
        {/if}
        <tr style="height: 5px"><td colspan="2"></td></tr>
        <tr class="download_button">
            <td>
                <img alt="{t}Download{/t}" class="button" onclick="download_pdf(this.closest('.pdf_asset_dialog'))" style="" src="/art/pdf.gif">

            </td>
            {if $type=='unit' or $type=='sko' or $type=='carton' or $type=='pallet'  or $type=='box' }
                <td class="save  " style="padding-top: 20px;padding-right: 5px">
                    <i onclick="save_pdf_asset_label_options(this)" class="fa fa-cloud">
                </td>
            {/if}

        </tr>
    </table>


</div>
