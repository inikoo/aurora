{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   30 April 2020  02:39::37  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}
<div >

    <table border=0 style='width:100%;font-size:2.0mm;font-family: Arial, " Helvetica Neue , Helvetica, sans-serif' >
    <tr>
        <td style="height:100%;" valign="top">
            <table style="height: 100%;">
                <tr>
                    <td>
                        <b>{$part->get('Reference')}</b></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000">{$part->get('Recommended Product Unit Name')}</td>
                </tr>
                <tr>
                    <td style="height: 1mm"></td>
                </tr>
                <tr>
                    <td>
                        {if $part->get('Origin Country')!='' and $part->get('Part Origin Country Code')!=$account->get('Account Country Code')  }
                            Imported from {$part->get('Origin Country')} by {$account->get('Name')}
                            <br>
                        {else}
                            <span >{$account->get('Name')}</span>
                            <br>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {$account->get('Label Signature')}

                    </td>
                </tr>
            </table>

        </td>

        <td >
            <barcode code="{$part->get('Part Barcode Number')}"/>

        </td>
    </tr>

    </table>
</div>
