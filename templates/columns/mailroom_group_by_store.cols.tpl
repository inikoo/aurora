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
name: "marketing",
label:"{t}Marketing{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='v'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: integerHeaderCell
},
{
name: "customer_notifications",
label:"{t}Customers notifications{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customer_notifications'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: integerHeaderCell
},
{
name: "user_notifications",
label:"{t}Staff notifications{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='user_notifications'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: integerHeaderCell
},

{
name: "all",
label:'',
html_label:"&Sigma; {t}Sent emails{/t}",
title:"{t}Sum orders{/t}",


editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='all'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright"}),
headerCell: rightHeaderHtmlCell
},

]
function change_table_view(view,save_state){}
