<script>

</script>

<table border="0" style="margin: 0mm">
    {for $row=1 to 8}
    <tr>
        {for $col=1 to 5}
            <td style="width: 59.4mm;height: 26.25mm">
                <table border="0" style='font-size:2.0mm; float:left;  font-family: Arial, " Helvetica Neue , Helvetica, sans-serif";' >
                    <tr>
                        <td style="height:100%;" valign="top">
                            <table border="0" style="height: 100%;">
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
                                    <td style="font-size: 1.5mm">
                                        {if $part->get('Origin Country')!='' and $part->get('Part Origin Country Code')!=$account->get('Account Country Code')  }
                                            Imported from {$part->get('Origin Country')} by {$account->get('Name')}
                                            <br>
                                        {else}
                                            <span  >{$account->get('Name')}</span>
                                            <br>
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.5mm">
                                        {$account->get('Label Signature')}
                                    </td>
                                </tr>
                            </table>

                        </td>

                        <td >
                            <barcode size=".75" code="{$part->get('Part Barcode Number')}"/>

                        </td>
                    </tr>

                </table>

            </td>
        {/for}


        </tr>
    {/for}

</table>
