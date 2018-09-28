var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},

{
name: "public_id",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
}, {
name: "last_updated_date",
label: "{t}Last updated{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='last_updated_date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "customer",
label: "{t}Customer{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({})
} , {
name: "net_amount",
label: "{t}Net amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='net_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
 {
name: "purge_status",
label: "{t}Purged state{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},
 {
name: "purged_date",
label: "{t}Purged date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='purged_date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

]

function change_table_view(view,save_state){}
