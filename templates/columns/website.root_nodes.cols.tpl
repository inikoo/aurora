var columns = [
 {
    name: "id",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "code",
    label: "{t}Code{/t}",
    editable: false,
     cell: Backgrid.Cell.extend({
        events: {
            "click": function() {
                change_view( 'website/{$data['key']}/node/' + this.model.get("id"))
   
            }
        },
        className: "link",
        
       
        
        
    })
}, {
    name: "name",
    label:"{t}Name{/t}",
    editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='title'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
}, {
    name: "nodes",
    label:"{t}Nodes{/t}",
        editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

}

]
function change_table_view(view,save_state){}
