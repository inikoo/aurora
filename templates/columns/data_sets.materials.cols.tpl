var columns = [{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


}
,{
    name: "name",
    label: "{t}Name{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({
      events: {
            "click": function() {
                change_view('/account/data_sets/materials/'+this.model.get("id"))
            }
        },
     
      className: "link"
     }),
},{
    name: "type",
    label: "{t}Type{/t}",
    editable: false,
    cell: Backgrid.HtmlCell.extend({
    
     })
    
},{
    name: "parts",
    label: "{t}Parts{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
}




]


function change_table_view(view, save_state) {}
