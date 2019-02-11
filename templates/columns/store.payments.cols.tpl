{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 February 2019 at 15:05:22 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},{
name: "reference",
label:"{t}Reference{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({  }),
},
{
name: "account",
label:"{t}Account{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({  }),
},
{
name: "type",
label:"{t}Type{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({  }),
},
{
name: "order",
label:"{t}Order{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({  }),
},{
name: "amount",
label: "{t}Amount{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='transactions'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "refunds",
label: "",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "date",
label: "{t}Date{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},{
name: "status",
label:"{t}Status{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({  }),
}

]
function change_table_view(view,save_state){}
