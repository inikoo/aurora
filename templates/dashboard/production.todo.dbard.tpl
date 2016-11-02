<div class="button widget"
     onClick="get_widget_details(this,'supplier.todo_parts.wget',{ parent: 'supplier','parent_key':{$supplier->id}})">
    <div id="todo" style="padding:10px;"></div>
    <div style="color:#aaa">{t}To produce{/t}</div>
</div>

<script type="text/javascript">
    // // globals
    var dial = new AlertDial('#todo', {
        ringBackgroundColor: ['{$data['color_min']}', '{$data['color_max']}'],
        frameBackgroundColor: 'white',
        frameSize: 100,
        ringWidth: 8.75,
        fontSize: 17.5,

    })
    dial.setValue({$data['value']},{$data['total']},{$data['min']},{$data['max']})
    dial.config({disabled: true})
</script>
