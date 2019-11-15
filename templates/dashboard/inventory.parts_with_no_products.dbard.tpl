{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  15 November 2019  10:54::11  +0100, Malaga, Spain
 Copyright (c) 2019, Inikoo

 Version 3
*/*}

<div id="inventory_parts_no_products_wget"  class="button widget"
     onClick="get_widget_details(this,'inventory.parts_no_products.wget',{ parent: 'account','parent_key':1})">
    <div id="parts_no_products"
         style="padding-top: 10px;padding-right: 10px;padding-left: 10px;padding-bottom: 10px;"></div>
    <div style="color:#aaa">{t}No products{/t}</div>
</div>

<script>
    // // globals
    var dial = new AlertDial('#parts_no_products', {
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
