var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},{
    name: "warehouse",
    label: "{t}Warehouse{/t}",
    editable:false,
    renderable: {if $data['parent']=='account'}true{else}false{/if},
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view( 'inventory/' + this.model.get("warehouse_key"))
            }
        },
        className: "link"
       
})
   
},{
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
   
},{
    name: "reference",
    label: "{t}Reference{/t}",
    editable: true,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view( '{if $data['parent']=='account'}account{else if $data['parent']=='warehouse'}inventory/{$data['parent_key']}{else}{$data['parent']}/{$data['parent_key']}{/if}/part/' + this.model.get("id"))
            }
        },
        className: "link"
       
})
   
},{
    name: "description",
    label: "{t}Description{/t}",
    editable: true,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
       
       
})
   
},{
    name: "products",
    label: "{t}Products{/t}",
    editable: true,
     sortType: "toggle",
    
    cell: "html",
   
}
]

function change_table_view(view,save_state){}