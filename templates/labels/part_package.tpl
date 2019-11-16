

<div style="padding:3px 5px 2px 5px">



    <table style='font-size:3mm;margin-top:.75mm;font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;' border="0">
        <tr>
            <td style=" text-align: center;vertical-align:bottom;padding:1px 5px 5px 5px;">
                <span style="background: black;color: white;padding 2mm">&emsp;<b >{$part->get('Reference')}</b>&emsp;</span> <b>{$part->get('Units Per Package')}x</b> {$part->get('Recommended Product Unit Name')}

            </td>
        </tr>


        <tr>
            <td style="text-align: center"><barcode code="{$part->get('Part SKO Barcode')}" type="C128A" size="1" height="1.25" />
            </td>

        </tr>
        <tr>
            <td style="text-align: center;font-size:2mm;">{$part->get('Part SKO Barcode')}</td>

        </tr>
    </table>
</div>
