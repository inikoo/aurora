var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},

{
name: "code",
label: "{t}ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
})

},
{
name: "active",
label: "{t}Active{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='active'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
})

},
{
name: "scope",
label: "{t}Scope{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='scope'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
})

},

{
name: "from",
label: "{t}Created{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='from'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},


]

function change_table_view(view,save_state){}