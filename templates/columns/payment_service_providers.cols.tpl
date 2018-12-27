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
cell: Backgrid.HtmlCell.extend({

})
}, {
name: "name",
label:"{t}Name{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({  }),
}, {
name: "accounts",
label: "{t}Accounts{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='accounts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "transactions",
label: "{t}Transactions{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='transactions'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "payments",
label: "{t}Payments{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='payments'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "refunds",
label: "{t}Refunds{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "balance",
label: "{t}Balance{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='balance'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}


]
function change_table_view(view,save_state){}
