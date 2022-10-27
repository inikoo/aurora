<div class="button widget"
     onClick="get_widget_details(this,'production.external_products.wget',
     { parent: 'external','parent_key':'{$external_account_code}'})">
    <div id="external_{$external_account_code}_products" style="padding:10px;"></div>
    <div style="color:#aaa">{$title}</div>
</div>

<script>
    // // globals
    var dial = new AlertDial('#external_{$external_account_code}_products', {
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
