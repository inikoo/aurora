{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:    02 May 2020  01:51::05  +0800 Kuala Lumpur, Malaysialabe
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

<style>
    table {
    {if $size=='EU30137'}
    {assign 'barcode_height' 'size="1.5" height="1"'}
    {assign 'code_size' '6mm'}

        font-size: 6mm;
    {elseif $size=='EU30161'}
        font-size: 3mm;
    {assign 'barcode_height' 'size="1" height="1"'}
    {assign 'code_size' '3mm'}


    {else}
    {assign 'barcode_height' 'size="1" height=".75"'}
    {assign 'code_size' '3mm'}

        font-size: 3mm;

    {/if}
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }



</style>
<table class="{$size}" autosize="1" border="0" style="width:100%;">
    <tr  >
        <td style="vertical-align: top;" valign="top">
            <table class="descriptions" autosize="1" style='width: 100%;vertical-align: top;'  border="0">
                <tr>
                    <td style=" text-align: center;vertical-align:bottom;">
                        <span style="font-size:{$code_size};background: black;color: white;">&emsp;<b>{$part->get('Reference')}</b>&emsp;</span>

                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;vertical-align:bottom;padding:;">
                        <b>{$part->get('Units Per Package')}x</b> {$part->get('Recommended Product Unit Name')}

                    </td>
                </tr>
                {if $with_custom_text}
                    <tr>
                        <td style="text-align: center;">

                            {$custom_text|strip_tags|truncate:550}

                        </td>

                    </tr>
                {/if}




            </table>
        </td>

    </tr>
    <tr class="barcode">

        <td  style="text-align: center;padding: 0px 5px">
            <barcode code="{$part->get('Part SKO Barcode')}" type="C128A" {$barcode_height}

            />
        </td>


    </tr>
    <tr>
        <td  style=" font-size: 1.75mm;text-align: center;">{$part->get('Part SKO Barcode')}</td>

    </tr>

</table>

