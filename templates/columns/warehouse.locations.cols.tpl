var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "type",
label: "",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
})

},
{
name: "flag",
label: "{t}Flag{/t}",
sortType: "toggle",
{if $sort_key=='flag'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

editable: false,

cell: "html"
},{
name: "code",
label: "{t}Code{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})

},{
name: "area",
label: "{t}Area{/t}",
renderable:true,
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})

}, {
name: "max_weight",
label: "{t}Max weight{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='max_weight'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "current_weight",
label: "{t}Current weight{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='current_weight'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "max_volume",
label: "{t}Max volume{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='max_volume'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "parts",
label: "{t}Parts{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "stock_value",
label: "{t}Stock value{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock_value'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
]

function change_table_view(view,save_state){}