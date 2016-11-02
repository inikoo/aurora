<div style="font-size:2.0mm;padding:3px 5px 2px 2px">
    <table style="width:100%;font-size:2.0mm;">
        <tr>

            <td style=" text-align: center;font-size:1.5mm;"> {$part->get('Unit Description')}</td>
            <td style=" border:0px solid red;text-align: center;vertical-align:bottom;padding:1px 5px 0px 5px;">

                {$part->get('Reference')} </td>
        </tr>
    </table>
    <table style="width:100%;font-size:2.0mm;">
        <tr>
            <td style=" text-align: center;font-size:2mm;padding:2mm">
                {if $part->get('Origin Country')!='' and $part->get('Part Origin Country Code')!=$account->get('Account Country Code')  }
                    Imported from {$part->get('Origin Country')} by
                {else}

                {/if}
                <span style="font-size:2.0mm">{$account->get('Name')}</span><br>
                Reg. no. 04108870
                <br>
                S3 8AL UK
                <br>
                All rights reserved

            </td>
            <td style="border:0px solid red;"><img style="height:20mm"
                                                   src="/barcode_asset.php?number={$part->get('Part Barcode Number')}">
            </td>

        </tr>
    </table>
</div>