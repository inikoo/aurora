var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},
{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
}, {
name: "label",
label:"{t}Label{/t}",
editable: false,
cell: "string"
}, {
name: "invoices",
label:"{t}Invoices{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
 {
name: "refunds",
label:"{t}Refunds{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "sales",
label:"{t}Amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},

]
function change_table_view(view,save_state){}
