{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  29 April 2020  14:45::56  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


<div class="pdf_asset_dialog {$asset} options_dialog  hide" style="min-width: 150px" >
    <i onclick="$('.pdf_asset_dialog').addClass('hide')" style="float: right;margin-left: 10px" class="fa fa-window-close button"></i>
    {if $asset=='invoice'}
    <h2 class="unselectable">{t}PDF invoice{/t}</h2>
    {elseif $asset=='proforma'}
        <h2 class="unselectable">{t}Proforma invoice{/t}</h2>
    {/if}

    <table>
        {if $asset=='invoice'}
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
        <tr data-field='locale' class="button pdf_option {if !$pdf_show_locale_option}hide{/if}" onclick="check_field_value(this)">
            <td>
                <i class="far fa-square margin_right_10" data-value="en_GB"></i> <span class="discreet">{t}English{/t}</span>
            </td>
        </tr>
        </tbody>
        {elseif $asset=='proforma'}
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
        {/if}
        <tr>
            <td>
                <img class="button" onclick="download_pdf(this.closest('.pdf_asset_dialog'))" style="width: 50px;height:16px;margin-top:10px" src="/art/pdf.gif">
            </td>

        </tr>
    </table>


</div>
