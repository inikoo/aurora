var columns = [
{
    name: "request",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "type",
    label: "{t}Type{/t}",
    editable: false,
     cell: Backgrid.Cell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view( this.model.get("request") )
            }
        },
        className: "link",       
    })
}
,{
    name: "active_users",
    label: "{t}Active users{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='active_users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
}


]
function change_table_view(view,save_state){}
