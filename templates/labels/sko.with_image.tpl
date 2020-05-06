{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished:   02 May 2020  00:59::46  +0800 Kuala Lumpur, Malaysialabe
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

<style>
    table {
        font-size: 2mm;;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }

    .image_td {
        width: 22mm;
        text-align: center;
        vertical-align: middle;
    }

    .code{
        font-size:3mm;background: black;color: white;
    }

    .barcode_td{
        text-align: center;padding: 0px 2px
    }


    .EU30137 table{
        font-size: 5mm;
    }

    .EU30137 .code{
        font-size:6mm;
    }

    .EU30137 .image_td {
        width: 60mm;
    }



</style>
{if $size=='EU30137'}
    {assign 'img_size' '40mm'}
    {assign 'barcode_height' '1'}
    {assign 'code_size' '6mm'}


{else}
    {assign 'img_size' '15mm'}
    {assign 'barcode_height' '.75'}
    {assign 'code_size' '3mm'}


{/if}
<table class="{$size}" autosize="1" border="0" style="width:100%">
    <tr>
        <td style="vertical-align: top;" valign="top">
            <table autosize="1" style='width: 100%;vertical-align: top' border="0">
                <tr>
                    <td  style=" text-align: center;vertical-align:bottom;">
                        <span style=" font-size:{$code_size};margin-bottom: 10px" class="code" >&emsp;<b>{$part->get('Reference')}</b>&emsp;</span>

                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;vertical-align:bottom;">
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
        <td class="image_td" style="" valign="middle">
            <img src="../image.php?id={$part->get('Part Main Image Key')}&s=270x270" style="margin-right: 2mm;vertical-align: middle;max-height: {$img_size}" width="{$img_size}" height="{$img_size}"/>
        </td>
    </tr>
    <tr>

        <td class="barcode_td" colspan="2">
            <barcode code="{$part->get('Part SKO Barcode')}" type="C128A" size="1" height="{$barcode_height}"/>
        </td>


    </tr>
    <tr>
        <td colspan="2" style=" font-size: 1.75mm;text-align: center;">{$part->get('Part SKO Barcode')}</td>

    </tr>

</table>

