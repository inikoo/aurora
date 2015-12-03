var columns = [
{
    name: "id",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "name",
    label: "{t}Group name{/t}",
    editable: false,
     cell: Backgrid.Cell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view( 'user_group/'+ +this.model.get("id"))
            }
        },
        className: "link",       
    })
}, {
    name: "view",
    label: "{t}View{/t}",
    editable: false,
    cell: Backgrid.HtmlCell.extend({
      className: "width_200 icon_container"
    })
}, {
    name: "edit",
    label: "{t}Edit{/t}",
    editable: false,
    cell: Backgrid.HtmlCell.extend({
      className: "width_200 icon_container"
    })
},{
    name: "active_users",
    label: "{t}Active users{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='active_users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},{
    name: "inactive_users",
    label: "{t}Suspended users{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='inactive_users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
}


]
function change_table_view(view,save_state){}
