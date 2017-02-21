var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},


{
name: "state",
label: '<i class="fa fa-adjust discreet" aria-hidden="true"></i>',
editable: false,
title: '{t}Online state{/t}',
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
}),
headerCell: HeaderHtmlCell,

},



{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
},

{
name: "type",
label: '<i class="fa fa-envira discreet" aria-hidden="true"></i>',
editable: false,
title: '{t}Online state{/t}',
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_4s0"
}),
headerCell: HeaderHtmlCell,

}

, {
name: "version",
label:"Version",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='version'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })
}, {
name: "users",
label:"{t}Users{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "requests",
label:"{t}Views{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='requests'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]
function change_table_view(view,save_state){}
