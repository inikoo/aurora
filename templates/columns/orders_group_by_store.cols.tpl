{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  29 August 2017 at 01:40:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

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
name: "in_basket",
label:"{t}In basket{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='in_basket'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: integerHeaderCell
},
{
name: "in_process",
label:"{t}In process{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='in_process'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: integerHeaderCell
},
{
name: "sent",
label:"{t}Dispatched{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sent'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: integerHeaderCell
},
{
name: "cancelled",
label:"{t}Cancelled{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='cancelled'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: integerHeaderCell
},

{
name: "orders",
label:"&Sigma; {t}Orders{/t}",
title:"{t}Sum orders{/t}",


editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='orders'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: rightHeaderHtmlCell
},
{
name: "invoices",
label:"{t}Invoices{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: integerHeaderCell
}, {
name: "refunds",
label:"{t}Refunds{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: integerHeaderCell
},

]
function change_table_view(view,save_state){}
