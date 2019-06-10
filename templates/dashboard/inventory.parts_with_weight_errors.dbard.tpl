<div id="inventory_parts_weight_errors_wget"  class="button widget"
     onClick="get_widget_details(this,'inventory.parts_weight_errors.wget',{ parent: 'account','parent_key':1})">
    <div id="parts_weight_errors"
         style="padding-top: 10px;padding-right: 10px;padding-left: 10px;padding-bottom: 10px;"></div>
    <div style="color:#aaa">{t}Anomalous weight{/t}</div>
</div>

<script>
    // // globals
    var dial = new AlertDial('#parts_weight_errors', {
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
