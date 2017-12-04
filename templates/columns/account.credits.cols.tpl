{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 December 2017 at 12:48:59 GMT+7, Bangkok, Thailand
 Copyright (c) 2016, Inikoo

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
name: "store",
label:"{t}Store{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({  }),
},{
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
