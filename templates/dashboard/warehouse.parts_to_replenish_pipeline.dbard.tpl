{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4:14 am 28 July 2021  Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3
-->
*}


<div  class="button widget"
     onClick="get_widget_details(this,'warehouse.parts_to_replenish_pipeline.wget',{ parent: 'pipeline','parent_key':{$pipeline->id}})">
    <div id="parts_to_replenish_pipeline_{$pipeline->id}"
         style="padding-top: 10px;padding-right: 10px;padding-left: 10px;padding-bottom: 10px;"></div>
    <div title="{t}To replenish from pipeline{/t}" style="color:#aaa">{t}To replenish{/t} <i  class="fal fa-project-diagram"></i> </div>
</div>

<script>
    // // globals
    var dial = new AlertDial('#parts_to_replenish_pipeline_{$pipeline->id}', {
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
