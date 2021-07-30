var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "store_key",
label: "",
editable: false,
renderable: false,
cell: "string"
},{
name: "customer_key",
label: "",
editable: false,
renderable: false,
cell: "string"
},

{
name: "store_code",
label: "{t}Store{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
defaultOrder:-1,
{if $sort_key=='store_code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
},
{
name: "number",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
defaultOrder:1,
{if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
},
{
name: "customer",
label: "{t}Customer{/t}",
sortType: "toggle",
defaultOrder:-1,
{if $sort_key=='customer'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

editable: false,
cell: Backgrid.HtmlCell.extend({

className: "padding_left_20"
})
},
{
name: "billing_country_flag",
label: "",
sortType: "toggle",
defaultOrder:-1,
{if $sort_key=='billing_country_flag'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

editable: false,
cell: Backgrid.HtmlCell.extend({

className: " width_20",
})
},
{
name: "date",
label: "{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "width_100 padding_right_20 aright"} ),
headerCell: integerHeaderCell,
className: " min_width_150",

},


{
name: "net",
label: "{t}Net{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "tax",
label: "{t}Tax{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='tax'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "total_amount",
label: "{t}Total{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}]

function change_table_view(view,save_state){}
