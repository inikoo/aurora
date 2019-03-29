

<div style="padding:3px 5px 2px 5px">



    <table style='font-size:3mm;margin-top:.75mm;font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;' border="0">
        <tr>
            <td style=" text-align: center;vertical-align:bottom;padding:1px 5px 5px 5px;">
                    <b> {$part->get('Reference')}</b> {$part->get('Package Description')}

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
