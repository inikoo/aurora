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
name: "checked",
label: '<i class="fa fa-square-o" style="margin-left:3.5px" aria-hidden="true"></i>',
headerCell: HeaderHtmlCell,
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})


},

{
name: "public_id",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

})
}, {
name: "date",
label: "{t}Created{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "customer",
label: "{t}Customer{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({})
}, {
name: "total_amount",
label: "{t}Total{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "last_updated",
label: "{t}Last updated{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='last_updated'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "idle_time",
label: "{t}Idle days{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='idle_time'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){}
