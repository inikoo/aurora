

<table >
    {for $row=1 to 15}
    <tr>
        {for $col=1 to 5}
            <td sxtyle="width: 20mm;height: 30.75mm">
                <table style='font-size:2.0mm; float:left;  font-family: Arial, " Helvetica Neue , Helvetica, sans-serif";' >
                    <tr>
                        <td style="width:40%;height:100%" valign="top">
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
                                            Imported from <b>{$part->get('Part Origin Country Code')}</b>
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

            </td>
        {/for}


        </tr>
    {/for}

</table>
