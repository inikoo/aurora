var columns = [
 {
    name: "id",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
},
 {
    name: "formatted_id",
    label: "{t}Id{/t}",
    editable: false,
     cell: Backgrid.Cell.extend({
        events: {
            "click": function() {
                change_view( '/timeseries/'+this.model.get("id") )
            }
        },
        className: "link",
        
      
        
        
    })
},
 {
    name: "type",
    label: "{t}Type{/t}",
    editable: false,
     cell: Backgrid.Cell.extend({
        
        
      
        
        
    })
},
{
    name: "parent",
    label: "{t}Parent{/t}",
    editable: false,
     sortable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},
{
    name: "records",
    label: "{t}Records{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},
{
    name: "from",
    label: "{t}From{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},
{
    name: "to",
    label: "{t}To{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},
{
    name: "last_updated",
    label: "{t}Last updated{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
}


]
function change_table_view(view,save_state){}
