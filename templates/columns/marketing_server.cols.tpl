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
cell: Backgrid.HtmlCell.extend({ })
} ,{
name: "name",
label:"{t}Store Name{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({  }),
},
{
name: "campaigns",
label: "{t}Campaigns{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='campaigns'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
className: 'aright'

}),
headerCell: integerHeaderCell

},
{
name: "deals",
label: "{t}Offers{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='deals'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
className: 'aright'

}),
headerCell: integerHeaderCell

},
]
function change_table_view(view,save_state){}
