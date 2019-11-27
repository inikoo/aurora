{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 December 2017 at 15:57:37 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div style="font-size:2.0mm;padding:3px 5px 2px 5px">

    <table style="font-size:1.8mm;width:100%" >
        <tr>
            <td style=" text-align: center;vertical-align:bottom;padding:1px 5px 0px 5px;{if ($location->get('Location Code')|count_characters)>30}font-size:1.7mm;{/if}">
                    <b> {$location->get('Location Code')|truncate:70}</b>

            </td>
        </tr>
        <tr><td> </td>
        </tr>
        <tr>
            <td style="text-align: center"><img style="max-height: 50px" src="/barcode_asset.php?type=code128&number=!W{$location->get('Location Warehouse Key')}L{"%08d"|sprintf:$location->id}">
            </td>

        </tr>
        <tr>
            <td style="text-align: center">{$account->get('Code')|lower}.aurora.systems/locations/{$location->get('Location Warehouse Key')}/{$location->id}</td>

        </tr>
    </table>
</div>
