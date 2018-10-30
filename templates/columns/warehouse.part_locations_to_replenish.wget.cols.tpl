var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "part",
label: "{t}Part{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

}, {
name: "other_locations_stock",
label: "{t}Other locations stock{/t}",

sortable: false,
editable: false,

cell: "html"
},

{
name: "location",
label: "{t}Location{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

},
{
name: "quantity",
label: "{t}Stock{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "ordered_quantity",
label: "{t}Ordered{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='ordered_quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "effective_stock",
label: "{t}Eventual Stock{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='effective_stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


{
name: "recommended_quantity",
label:'',
html_label: "<i class=\"far fa-lightbulb\"></i> (min,max)",
title:'{t}Number of refunds difference form previous year{/t}',
editable: false,
sortType: "toggle",
{if $sort_key=='recommended_quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
headerCell: HeaderHtmlCell
},


{
name: "next_deliveries",
label: "{t}Next deliveries{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='next_deliveries'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({

} ),

},


]

function change_table_view(view,save_state){}