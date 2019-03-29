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
.labels td{
   font-size: 2mm;color:#000; text-align: center;vertical-align:bottom;padding:4px 5px 0px 5px;border-top:.1mm solid #000;font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;}
.data td{
     text-align: center;vertical-align:bottom;padding:1px 5px 4px 5px;border-bottom:.1mm solid #000;font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;}
</style>

<div style="font-size:10.0mm;padding:3px 5px 2px 5px;font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;">



    <table style="margin-top:.75mm;width: 100%; border-collapse: collapse;" >
        <tr class="labels">
            <td >Reference
            </td>
            <td >Units per pack</td>
            <td >
                Packs per carton
            </td>
            <td >
                Units per carton
            </td>
        </tr>
        <tr class="data">
            <td style=" ">
                    <b> {$supplier_part->get('Reference')}</b>

            </td>
            <td >
                <b> {$supplier_part->part->get('Part Units Per Package')}</b>
            </td>
            <td >
                <b> {$supplier_part->get('Supplier Part Packages Per Carton')}</b>
            </td>
            <td >
                <b> {$supplier_part->get('Supplier Part Units Per Carton')}</b>
            </td>
        </tr>

        <tr class="labels">
            <td colspan="4">
                Unit description

            </td>

        </tr>
        <tr class="data">
            <td colspan="4">
                <b>{$supplier_part->get('Supplier Part Description')}</b>

            </td>

        </tr>
        <tr class="labels">
            <td colspan="4">
                Materials

            </td>

        </tr>
        <tr class="data">
            <td colspan="4" style="font-size:2.5mm">
                {$supplier_part->part->get('Materials')}

            </td>

        </tr>

        <tr class="labels">
            <td >
                Batch code

            </td>
            <td >
                Carton weight
            </td>
            <td >
                Origin
            </td>
            <td >
                Commercialised by
            </td>
        </tr>

        <tr class="data">
            <td >
                <b> {if empty($batch_code)}{$supplier_part->get('Supplier Code')}{$smarty.now|date_format:"%Y%m"}{else}{$batch_code}{/if}</b>

            </td>
            <td  >
                <b>{$supplier_part->get('Carton Weight Approx')}</b>
            </td>
            <td  >
                <b>{$supplier_part->part->get('Part Origin Country Code')}</b>
            </td>
            <td  >
                <b> {$account->get('Code')}</b>
            </td>
        </tr>

        {if $supplier_part->part->get('Part Carton Barcode')!=''}
        <tr>
            <td colspan="4" style="text-align: center"><img style="max-height: 70px" src="/barcode_asset.php?type=code128&number={$supplier_part->part->get('Part Carton Barcode')}">
            </td>

        </tr>
        <tr class="labels" >
            <td colspan="4" style="padding-top: 2px">{$supplier_part->part->get('Part Carton Barcode')}</td>

        </tr>
        {/if}

    </table>
</div>
