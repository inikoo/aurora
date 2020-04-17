
<div >

    <table border=0 style='margin-right: 1mm;width:100%;font-size:3.0mm;font-family: Arial, " Helvetica Neue , Helvetica, sans-serif;' >
    <tr>


        <td style="height:100%;margin-top:2mm" valign="top">
            <table style="height: 100%;margin-left: 3mm;margin-top:4mm">

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



                {if $part->get('Part Unit Weight')>0}
                    <tr>
                        <td>
                            {t}Weight{/t} {$part->get('Unit Smart Weight')} 	&#8494;
                        </td>
                    </tr>
                {/if}





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
                    <td style="margin-right: 3mm">
                        <barcode code="{$part->get('Part Barcode Number')}"/>
                    </td>
                </tr>


            </table>



        </td>
    </tr>
        <td style="height:100%;" valign="top">
            <img src="wi.php?id={$part->get('Part Main Image Key')}&s=270x270" width="60mm" />
        </td>
    </table>
</div>