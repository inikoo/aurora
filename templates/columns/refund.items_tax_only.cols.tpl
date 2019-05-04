var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "product_pid",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({

}),
},{
name: "description",
label: "{t}Description{/t}",
editable: false,
cell: "html"

},

{
name: "tax",
label: "{t}Tax{/t}",

defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='tax'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}
]


function change_table_view(view, save_state) {}
