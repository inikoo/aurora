

<div style="font-size:2.0mm;padding:3px 5px 2px 5px">

    <table style="font-size:1.8mm;" border="0">
        <tr>
            <td style=" text-align: center;vertical-align:bottom;padding:1px 5px 0px 5px;{if ($part->get('Package Description')|count_characters)>30}font-size:1.7mm;{/if}">
                    <b> {$part->get('Reference')}</b> {$part->get('Package Description')|truncate:50}

            </td>
        </tr>
        <tr><td> </td>
        </tr>
        <tr>
            <td style="text-align: center"><img style="max-height: 50px" src="/barcode_asset.php?type=code128&number={$part->get('Part SKO Barcode')}">
            </td>

        </tr>
        <tr>
            <td style="text-align: center">{$part->get('Part SKO Barcode')}</td>

        </tr>
    </table>
</div>