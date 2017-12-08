{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 December 2017 at 12:35:10 CET, Mijas Costa, Spain
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div  class="button widget"
     onClick="get_widget_details(this,'warehouse.parts_with_unknown_location.wget',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
    <div id="parts_with_unknown_location"
         style="padding-top: 10px;padding-right: 10px;padding-left: 10px;padding-bottom: 10px;"></div>
    <div style="color:#aaa">{t}Lost & found{/t}
</div>
</div>

<script type="text/javascript">
    // // globals
    var dial = new AlertDial('#parts_with_unknown_location', {
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
