var columns = [
{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


},{
    name: "product_pid",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


},{
    name: "reference",
    label: "{t}S. Code{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({
      events: {
             "click": function() {
              change_view(this.model.get("parent_type")+"/"+this.model.get("parent_key")+"/part/"+this.model.get("id"))
            }
        },
      className: "link"
     }),
},{
    name: "description",
    label: "{t}Unit Description{/t}",
    editable: false,
     cell: "html"
    
}, {
    name: "subtotals",
    label: "{t}Subtotals{/t}",
    defautOrder:1,
    editable: false,
   sortable: false,
    {if $sort_key=='subtotals'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend( ),
},
{
    name: "quantity",
    label: "{t}Quantity{/t}",
    defautOrder:1,
    editable: false,
    sortType: "toggle",
    {if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell
}
]


function change_table_view(view, save_state) {}
