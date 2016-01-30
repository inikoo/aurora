var columns = [{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


}
,{
    name: "formatted_id",
    label: "{t}Id{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({
      events: {
            "click": function() {
                change_view('/image/'+this.model.get("id"))
            }
        },
     
      className: "link"
     }),
},{
    name: "kind",
    label: "{t}Kind{/t}",
    editable: false,
    cell: Backgrid.HtmlCell.extend({
    
     })
    
},{
    name: "size",
    label: "{t}Size{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},{
    name: "filesize",
    label: "{t}File size{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},{
    name: "thumbnail",
    label: "",
    editable: false,
    cell: Backgrid.HtmlCell.extend({
     className: "aright"
     })
    
}





]


function change_table_view(view, save_state) {}
