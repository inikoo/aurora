
<table border="0">
<tr>
<td >
    <table style='font-size:5mm;margin-top:.75mm;font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;' border="0">
        <tr>
            <td style=" text-align: center;vertical-align:bottom;padding:1px 5px 5px 5px;">
                <span style="background: black;color: white;padding 2mm">&emsp;<b >{$part->get('Reference')}</b>&emsp;</span>

            </td>
        </tr>
        <tr>
            <td style=" text-align: center;vertical-align:bottom;padding:1px 15px 5px 15px;">
               <b>{$part->get('Units Per Package')}x</b> {$part->get('Recommended Product Unit Name')}

            </td>
        </tr>




    </table>
</td>
    <td valign="top" >
        <img src="wi.php?id={$part->get('Part Main Image Key')}&s=270x270" width="40mm" />
    </td>
</tr>
    <tr>

        <td colspan="2" style="text-align: center"><barcode code="{$part->get('Part SKO Barcode')}" type="C128B" size="1" height="1.1" />
        </td>


    </tr>
    <tr>
        <td colspan="2" style="text-align: center;font-size:2mm;">{$part->get('Part SKO Barcode')}</td>

    </tr>

</table>

