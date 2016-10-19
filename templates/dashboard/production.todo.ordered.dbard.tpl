<div  class="button widget" onClick="get_widget_details(this,'supplier.todo_paid_parts.wget',{ parent: 'supplier','parent_key':{$supplier->id}})">
    <div id="todo_urgent" style="padding:10px;"></div>
    <div style="color:#aaa">{t}Urgent{/t}</div>
</div>

    <script type="text/javascript">
      // // globals
     var dial= new AlertDial('#todo_urgent', {
        ringBackgroundColor: ['{$data['color_min']}', '{$data['color_max']}'],
        frameBackgroundColor: 'white',
        frameSize: 100,
        ringWidth: 8.75,
        fontSize: 17.5,
        
      }) 
      dial.setValue({$data['value']},{$data['total']},{$data['min']},{$data['max']})
      dial.config({ disabled: true})
</script>
