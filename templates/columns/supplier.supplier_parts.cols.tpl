var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

}
,{
    name: "status",
    label: "",
    editable: false,
     sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({
      className: "width_20"
})
   
}
,{
    name: "reference",
    label: "{t}Reference{/t}",
    editable: true,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view( '/supplier/'+this.model.get("supplier_key")+'/part/' + this.model.get("id"))
            }
        },
        className: "link"
       
})
   
}
,
{
    name: "formatted_sku",
    label: "{t}SKU{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view( '{if $data['parent']=='account'}account{else if $data['parent']=='warehouse'}inventory/{$data['parent_key']}{else}{$data['parent']}/{$data['parent_key']}{/if}/part/' + this.model.get("id"))

            }
        },
        className: "link"
       
})
   
}

,{
    name: "part_reference",
    label: "{t}Part{/t}",
    editable: true,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view( 'part/' + this.model.get("part_sku"))
            }
        },
        className: "link"
       
})
   
},{
    name: "description",
    label: "{t}Description{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
       
       
})
   
}
,{
    name: "part_description",
    label: "{t}Part{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.HtmlCell.extend({
       
       
})
   
}

,{
    name: "cost",
    label: "{t}Cost{/t}",
    editable: false,
     sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({
})
   
}
,{
    name: "batch",
    label: "{t}Batch{/t}",
    editable: false,
     sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({
})
   
}
]

function change_table_view(view,save_state){

    $('.view').removeClass('selected');
    $('#view_'+view).addClass('selected');
    
    grid.columns.findWhere({ name: 'formatted_sku'} ).set("renderable", false)
     grid.columns.findWhere({ name: 'part_description'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'description'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'part_reference'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'status'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'cost'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'batch'} ).set("renderable", false)

    if(view=='overview'){
               grid.columns.findWhere({ name: 'part_description'} ).set("renderable", true)
               grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
               grid.columns.findWhere({ name: 'cost'} ).set("renderable", true)
               grid.columns.findWhere({ name: 'batch'} ).set("renderable", true)

    }else if(view=='parts'){
        grid.columns.findWhere({ name: 'formatted_sku'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'description'} ).set("renderable", true)
    }else if(view=='reorder'){
        grid.columns.findWhere({ name: 'part_reference'} ).set("renderable", true)
                 grid.columns.findWhere({ name: 'status'} ).set("renderable", true)

                    grid.columns.findWhere({ name: 'batch'} ).set("renderable", true)

    }
    
    if(save_state){
     var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view
   
    $.getJSON(request, function(data) {});
    }

}