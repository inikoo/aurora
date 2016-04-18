var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},{
    name: "reference",
    label: "{t}Reference{/t}",
    editable: true,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view( '{if $data['parent']=='account'}{else}{$data['parent']}/{$data['parent_key']}/{/if}part/' + this.model.get("id"))
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