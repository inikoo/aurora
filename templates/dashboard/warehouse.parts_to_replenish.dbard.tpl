<div  class="button widget"
     onClick="get_widget_details(this,'warehouse.parts_to_replenish.wget',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
    <div id="parts_to_replenish"
         style="padding-top: 10px;padding-right: 10px;padding-left: 10px;padding-bottom: 10px;"></div>
    <div style="color:#aaa">{t}Insufficient picking stock{/t}</div>
</div>

<script>
    // // globals
    var dial = new AlertDial('#parts_to_replenish', {
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
