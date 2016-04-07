var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},{
    name: "number",
    label: "{t}Number{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view( 'inventory/barcode/' + this.model.get("id"))

            }
        },
        className: "link"
       
})
   
},{
    name: "status",
    label: "{t}Status{/t}",
    editable: true,
     sortType: "toggle",
    
    cell: Backgrid.HtmlCell.extend({
       
       
})
   
},{
    name: "notes",
    label: "{t}Notes{/t}",
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