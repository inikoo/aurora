var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},


{
name: "date",
label: "{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_300"} ),
headerCell: integerHeaderCell
},
{
name: "user",
label: "{t}User{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({


})

},


{
name: "note",
label: "{t}Note{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({


})

},

{
name: "change",
label: "{t}Change{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},{
name: "type",
label: "",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},
{
name: "stock",
label: "{t}Stock{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}
,
]

function change_table_view(view,save_state){




}