<div  class="button widget"
     onClick="get_widget_details(this,'warehouse.part_locations_with_errors.wget',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
    <div id="locations_with_errors"
         style="padding-top: 10px;padding-right: 10px;padding-left: 10px;padding-bottom: 10px;"></div>
    <div style="color:#aaa">{t}Urgent audit{/t}</div>
</div>

<script type="text/javascript">
    // // globals
    var dial = new AlertDial('#locations_with_errors', {
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
