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
    font-size: 2mm;color:gray; text-align: center;vertical-align:bottom;padding:4px 5px 0px 5px;border-top:.1mm solid #000}
.data td{
    text-align: center;vertical-align:bottom;padding:1px 5px 4px 5px;border-bottom:.1mm solid #000}
</style>

<div style="font-size:10.0mm;padding:3px 5px 2px 5px;">



    <table style="margin-top:.75mm;width: 100%; border-collapse: collapse;" >
        <tr class="labels">
            <td >
                Reference

            </td>
            <td >
                Units per pack
            </td>
            <td >
                Packs per carton
            </td>
            <td >
                <b>Units per carton</b>
            </td>
        </tr>
        <tr class="data">
            <td style=" ">
                    <b> {$supplier_part->get('Reference')}</b>

            </td>
            <td >
                {$supplier_part->get('Part Units')}
            </td>
            <td >
                {$supplier_part->get('Supplier Part Packages Per Carton')}
            </td>
            <td >
                {$supplier_part->get('Units Per Carton')}
            </td>
        </tr>

        <tr class="labels">
            <td colspan="4">
                Unit description

            </td>

        </tr>
        <tr class="data">
            <td colspan="4">
                {$supplier_part->get('Supplier Part Description')}

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
                {if empty($batch_code)}{$supplier_part->get('Supplier Code')}{$smarty.now|date_format:"%Y%m"}{else}{$batch_code}{/if}

            </td>
            <td  >
                {$supplier_part->get('Carton Weight Approx')}
            </td>
            <td  >
                {$supplier_part->part->get('Origin Country')}
            </td>
            <td  >
                {$account->get('Code')}
            </td>
        </tr>


        <tr>
            <td colspan="4" style="text-align: center"><img style="max-height: 70px" src="/barcode_asset.php?type=code128&number={$supplier_part->id}">
            </td>

        </tr>
        <tr class="labels" >
            <td colspan="4" style="padding-top: 2px">{$supplier_part->id}</td>

        </tr>
    </table>
</div>
