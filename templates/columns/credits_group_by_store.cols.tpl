{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  07-06-2019 17:59:55 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

var columns = [
{
name: "store_key",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: ""} )
}, {
name: "name",
label:"{t}Store Name{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

 {
name: "credits",
label:"{t}Customers with credits{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",

}),
headerCell: integerHeaderCell

},{
name: "credits_amount",
label:"{t}Credit amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='credits_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",

}),
headerCell: integerHeaderCell

}
,{
name: "credits_amount_dc",
label:"{t}Credit{/t} {$account_currency_code}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='credits_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",

}),
headerCell: integerHeaderCell

},


{
name: "debits",
label:"{t}Customers with debit{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='debits'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",

}),
headerCell: integerHeaderCell

},{
name: "debits_amount",
label:"{t}Debit amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='debits_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",

}),
headerCell: integerHeaderCell

},

]
function change_table_view(view,save_state){}
