var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

}, {
    name: "stock_status",
    label: "",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({
        className: "width_20"
    })

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
   
},
{
    name: "unit_description",
    label: "{t}Unit description{/t}",
    editable: true,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
       
       
})
   
},
{
    name: "stock",
    label: "{t}Stock{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
}
]

function change_table_view(view,save_state){}