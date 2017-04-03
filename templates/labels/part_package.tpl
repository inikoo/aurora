<div style="font-size:2.0mm;padding:3px 5px 2px 5px">

    <table style="font-size:2.0mm;">
        <tr >
            <td style=" text-align: center;vertical-align:bottom;padding:1px 5px 0px 5px;">

                <b> {$part->get('Reference')}</b>  {$part->get('Package Description')}</td>
        </tr>
        <tr>
            <td style=""><img  src="/barcode_asset.php?type=code128&number={$part->get('Part SKO Barcode')}">
            </td>

        </tr>
        <tr>
            <td style="text-align: center">{$part->get('Part SKO Barcode')}</td>

        </tr>
    </table>
</div>