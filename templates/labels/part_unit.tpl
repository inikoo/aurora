<script>

</script>
<div style="">

    <table style="width:100%;font-size:2.0mm;" border="0">
        <tr>

            <td colspan="2" style=" text-align: center;"><b>{$part->get('Reference')}</b> {$part->get('Recommended Product Unit Name')}</td>
        </tr>
        <tr>
            <td style=" text-align: center;width: 25mm">
                <div style=";font-size:0.5mm;">
                {if $part->get('Origin Country')!='' and $part->get('Part Origin Country Code')!=$account->get('Account Country Code')  }
                    Imported from {$part->get('Origin Country')} by
                {else}

                {/if}
                <span style="">Ancient Wisdom</span><br>
                Reg. no. 04108870
                <br>
                S3 8AL UK
                <br>
                All rights reserved
                </div>

            </td>
            <td ><img style="height:20mm" src="/barcode_asset.php?number={$part->get('Part Barcode Number')}">
            </td>

        </tr>
    </table>
</div>