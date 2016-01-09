var columns = [
 {
    name: "id",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
},
 {
    name: "name",
    label: "{t}Name{/t}",
    editable: false,
     cell: Backgrid.Cell.extend({
        events: {
            "click": function() {
                change_view( 'data_set/'+this.model.get("id") )
            }
        },
        className: "link",
        
      
        
        
    })
},
{
    name: "sets",
    label: "{t}Sets{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},
{
    name: "items",
    label: "{t}Records{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},
{
    name: "size",
    label: "{t}Size{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
}


]
function change_table_view(view,save_state){}
