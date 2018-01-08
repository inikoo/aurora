var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})

},
{
name: "description",
label: "{t}Description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

},


{
name: "required",
label: "{t}Ordered{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='required'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "stock",
label: "{t}Stock{/t}",
editable: false,

defaultOrder:-1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "next_deliveries",
label: "{t}In production{/t}",
editable: false,
sortable:false,

{if $sort_key=='next_deliveries'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({  className: "aright"  } ),

headerCell: integerHeaderCell
},


]



function change_table_view(view,save_state){


}