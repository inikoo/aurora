var columns = [{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


}, {
    name: "store_key",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "code",
    label: "Code",
    editable: false,
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view('store/'+this.model.get("store_key")+'/department/' + this.model.get("id"))
            }
        },
        className: "link",
    })
}, {
    name: "name",
    label: "Name",
    editable: false,
    cell: "string"
}, {
    name: "active_families",
    label:"{t}Families{/t}",
       editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='active_families'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

}


]


function change_table_view(view, save_state) {}
