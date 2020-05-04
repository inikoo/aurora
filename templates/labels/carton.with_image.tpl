{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 March 2019 at 14:13:56 GMT+8, Ubud, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}
<style>
    .top td {
        font-size: 1.5mm;
        color: #000;
        text-align: center;
        vertical-align: bottom;
        padding: 0 5px 2px 5px;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }

    .labels td {
        font-size: 2mm;
        color: #000;
        text-align: center;
        vertical-align: bottom;
        padding: 4px 5px 0 5px;
        border-top: .1mm solid #000;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }

    .data td {
        text-align: center;
        vertical-align: bottom;
        padding: 1px 5px 4px 5px;
        border-bottom: .1mm solid #000;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }
</style>

<table style="margin-top:.3mm;width: 100%; border-collapse: collapse;" border="0">
    <tr class="top">
        <td colspan="5" style="padding-top: 2px"> Commercialised by {$account->get('Code')}</td>

    </tr>
    <tr class="labels" style="">
        <td>Reference</td>
        <td>Units per pack</td>
        <td>
            Packs per carton
        </td>
        <td>
            Units per carton
        </td>
        <td rowspan="4" style="text-align: center;vertical-align: middle;" valign="middle">
            <img style="vertical-align: middle;max-height: 25mm" src="../wi.php?id={$supplier_part->part->get('Part Main Image Key')}&s=270x270" width="25mm"/>
        <td>
    </tr>
    <tr class="data">
        <td style=" ">
            <b> {$supplier_part->get('Reference')}</b>

        </td>
        <td>
            <b> {$supplier_part->part->get('Part Units Per Package')}</b>
        </td>
        <td>
            <b> {$supplier_part->get('Supplier Part Packages Per Carton')}</b>
        </td>
        <td>
            <b> {$supplier_part->get('Supplier Part Units Per Carton')}</b>
        </td>
    </tr>

    <tr class="labels">
        <td colspan="4">
            Unit description

        </td>

    </tr>

    {assign var='description' value="{$supplier_part->get('Supplier Part Description')}" }
    {assign var='materials' value="{$supplier_part->part->get('Materials')}  " }

    <tr class="data">
        <td colspan="4" style="font-size: 3.5mm;">


            {if ($materials|strip_tags|count_characters)>250}

                {if ($description|count_characters)>80}
                    <span style="font-size:2mm;">{$description|strip_tags|truncate:200}</span>
                {elseif ($description|count_characters)>50}
                    <span style="font-size:2.5mm;">{$description|strip_tags|truncate:75}</span>
                {elseif ($description|count_characters)>30}
                    <b style="font-size:3mm;">{$description|strip_tags|truncate:50}</b>
                {else}
                    <b>{$description}</b>
                {/if}
            {else}
                <b>{$description|strip_tags|truncate:120}</b>
            {/if}


        </td>


    </tr>
    <tr class="labels">
        <td colspan="5">
            Materials

        </td>

    </tr>


    <tr class="data">
        <td colspan="5" style="font-size:2.5mm">

            {$materials|strip_tags|truncate:550}

        </td>

    </tr>

    <tr class="labels">
        <td colspan="2">
            Batch code

        </td>
        <td>
            Net weight
        </td>
        <td>
            Gross weight
        </td>
        <td>
            Origin
        </td>
    </tr>

    <tr class="data">
        <td colspan="2">
            <b> {if empty($batch_code)}{$supplier_part->get('Supplier Code')}{$smarty.now|date_format:"%Y%m"}{else}{$batch_code}{/if}</b>

        </td>
        <td>
            <b>{$supplier_part->get('Carton Net Weight Approx')}</b>
        </td>
        <td>
            <b>{$supplier_part->get('Carton Weight')}</b>
        </td>
        <td>
            <b>{$supplier_part->part->get('Part Origin Country Code')|strtoupper}</b>
        </td>
    </tr>

    {if $supplier_part->part->get('Part Carton Barcode')!=''}
        <tr>
            <td colspan="5" style="text-align: center">
                <barcode code="{$supplier_part->part->get('Part Carton Barcode')}" type="C128A"/>

            </td>

        </tr>
        <tr class="labels">
            <td colspan="5" style="padding-top: 2px">{$supplier_part->part->get('Part Carton Barcode')}</td>

        </tr>
    {/if}
    {if $with_custom_text}

        <tr class="data">
            <td colspan="4" style="font-size:2.5mm">

                {$custom_text|strip_tags|truncate:550}

            </td>

        </tr>
    {/if}

</table>

