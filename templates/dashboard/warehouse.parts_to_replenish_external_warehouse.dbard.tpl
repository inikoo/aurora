{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:16:31:49 MYT Monday, 13 July 2020, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


<div  class="button widget"
     onClick="get_widget_details(this,'warehouse.parts_to_replenish_external_warehouse.wget',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
    <div id="parts_to_replenish_external_warehouse"
         style="padding-top: 10px;padding-right: 10px;padding-left: 10px;padding-bottom: 10px;"></div>
    <div title="{t}To replenish from external warehouse{/t}" style="color:#aaa">{t}To replenish from{/t} <i style="color:tomato" class="fal fa-garage-car"></i> </div>
</div>

<script>
    // // globals
    var dial = new AlertDial('#parts_to_replenish_external_warehouse', {
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
