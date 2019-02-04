var columns = [

{
name: "type",
label: "{t}Type{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})
}
,{
name: "active_users",
label: "{t}Active users{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='active_users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}
,{
name: "inactive_users",
label: "{t}Suspended users{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='inactive_users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]
function change_table_view(view,save_state){}
