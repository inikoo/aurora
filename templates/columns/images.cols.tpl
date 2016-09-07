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
    
},{
    name: "image_order",
    label: "{t}nth{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='image_order'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

    cell: Backgrid.HtmlCell.extend({
      className: "width_50",
     
     })
    
}, {
    name: "image",
    label: "{t}Image{/t}",
    editable: false,
     sortable: false,
     sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({
       events: {
        //    "click": function() {
         //       change_view('{$data['request']}/image/'+this.model.get("id"))
         //   }
        },
        className: "width_100"
     }),
},{
    name: "size",
    label: "{t}Size{/t}",
    editable: false,
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
      className: "aright width_150"
     }),
     headerCell: integerHeaderCell
},{
    name: "dimensions",
    label: "{t}Dimensions{/t}",
     sortType: "toggle",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: " width_150 "
     }),
},{
    name: "caption",
    label: "{t}Caption{/t}",
     sortType: "toggle",
    editable: false,
     cell: Backgrid.StringCell.extend({
      
     
      className: ""
     }),
},{
    name: "operations",
    label: "{t}Operations{/t}",
    sortable: false,
    editable: false,
     cell: Backgrid.HtmlCell.extend({
     
     
      className: "width_150"
     }),
}
]


function change_table_view(view, save_state) {}
