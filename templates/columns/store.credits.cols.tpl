{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 November 2018 at 22:56:31 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

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
},
{
name: "customer_id",
label:"{t}Customer Id{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({  }),
},{
name: "customer",
label:"{t}Customer name{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({  }),
},  {
name: "amount",
label: "{t}Credit amount{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='transactions'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]
function change_table_view(view,save_state){}
