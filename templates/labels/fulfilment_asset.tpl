{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 Jul 2021 Kualal Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>


    .root {
        font-size: 10.0mm;
        padding: 3px 5px 2px 5px;
        font-family: "Arial", "Helvetica Neue", Helvetica, sans-serif;
    }

    table {
        margin-top: .3mm;
        width: 100%;
        border-collapse: collapse;
    }

    .bottom td {
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

    .id td {
        font-size:xx-large
    }


</style>

<div class="root">


    <table>

        <tr class="labels">


            <td colspan="2">{t}Customer{/t}</td>

        </tr>
        <tr class="data">

            <td colspan="2">
                <b> {$customer->get('Name')} ({$customer->get('Formatted ID')})</b>
            </td>

        </tr>


        <tr class="labels">
            <td {if $asset->get('Reference')==''}colspan="2"{/if} >
                {t}Box Id{/t}

            </td>
            {if $asset->get('Reference')!=''}
            <td>
                {if $asset->get('Fulfilment Asset Type')=='Pallet'}{t}Customer pallet Id{/t}{else}{t}Customer box Id{/t}{/if}
            </td>
            {/if}
        </tr>

        <tr class="data id">
            <td {if $asset->get('Reference')==''}colspan="2"{/if}>
                <b>{$asset->get('Formatted ID')}</b>

            </td>
            {if $asset->get('Reference')!=''}
            <td>
                <b>{$asset->get('Reference')}</b>
            </td>
            {/if}
        </tr>
        {assign var='notes' value="{$asset->get('Note')}" }
        {if $notes!=''}
            <tr class="labels">
                <td colspan="2">
                    {t}Notes{/t}
                </td>
            </tr>
            <tr class="data">
                <td colspan="2" style="font-size:2.5mm">{$notes|strip_tags|truncate:550}</td>
            </tr>
        {/if}

    </table>

    <table>

        <tr>
            <td style="padding-top:2mm;padding-left:2mm;text-align: left">
                <barcode code="FA|{$asset->id}" type="C128B"></barcode>
            </td>
            <td style="padding-top:2mm;padding-right:4mm;text-align: right">
                <barcode code="{$account->get('System Public URL')}/fa/{$asset->id}" size="1" type="QR" error="M" class="barcode"></barcode>
            </td>


        </tr>

        <tr class="bottom">
            <td colspan="2" style="padding-top: 4mm"> {t}Stored by{/t} {$store->get('Name')} </td>

        </tr>


    </table>
</div>
