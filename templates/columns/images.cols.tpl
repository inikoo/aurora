var columns = [{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


},{
    name: "visibility",
    label: "",
    editable: false,
    cell: Backgrid.HtmlCell.extend({
      className: "width_20",
     
     })
    
}, {
    name: "image",
    label: "{t}Image{/t}",
    editable: false,
    cell: Backgrid.HtmlCell.extend({
      className: "width_100",
       events: {
            "click": function() {
                change_view('/{$data['request']}/image/'+this.model.get("id"))
            }
        },
        className: "button"
     }),
},{
    name: "size",
    label: "{t}Size{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright width_150"
     }),
     headerCell: integerHeaderCell
},{
    name: "dimensions",
    label: "{t}Dimensions{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: " width_150 "
     }),
},{
    name: "caption",
    label: "{t}Caption{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({
      
     
      className: ""
     }),
},{
    name: "operations",
    label: "{t}Operations{/t}",
    editable: false,
     cell: Backgrid.HtmlCell.extend({
     
     
      className: "width_150"
     }),
}
]


function change_table_view(view, save_state) {}
