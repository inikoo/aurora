<div  class="button widget"
     onClick="get_widget_details(this,'inventory.parts_no_sko_barcode.wget',{ parent: 'account','parent_key':1})">
    <div id="parts_no_sko_barcode"
         style="padding-top: 10px;padding-right: 10px;padding-left: 10px;padding-bottom: 10px;"></div>
    <div style="color:#aaa">{t}Missing SKO Barcodes{/t}</div>
</div>

<script type="text/javascript">
    // // globals
    var dial = new AlertDial('#parts_no_sko_barcode', {
        ringBackgroundColor: ['{$data['color_min']}', '{$data['color_max']}'],
        frameBackgroundColor: 'white',
        frameSize: 100,
        ringWidth: 8.75,
        fontSize: 17.5

    });
    dial.setValue({$data['value']},{$data['total']},{$data['min']},{$data['max']});
    dial.config({
        disabled: true
    })
</script>
