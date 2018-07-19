{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 July 2018 at 23:34:21 GMT+8, Kuala Lumpur, Malaysia
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
name: "in_warehouse",
label:"{t}In warehouse{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='in_warehouse'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",}),
headerCell: integerHeaderCell

},
{
name: "sent",
label:"{t}Sent{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sent'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",}),
headerCell: integerHeaderCell

},
{
name: "returned",
label:"{t}Returned{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='returned'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",}),
headerCell: integerHeaderCell

},

{
name: "deliveries",
label:"{t}Delivery Notes{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='deliveries'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",}),
headerCell: integerHeaderCell

},

{
name: "replacements",
label:"{t}Replacements{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='replacements'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",

}),
headerCell: integerHeaderCell

}

]
function change_table_view(view,save_state){}
