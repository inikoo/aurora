{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  2 December 2017 at 14:54:48 GMT+7, Bangkok, Thailand
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
name: "payments",
label:"{t}Payments{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='payments'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",

}),
headerCell: integerHeaderCell

},{
name: "payments_amount",
label:"{t}Payments amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='payments_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: " aright",

}),
headerCell: integerHeaderCell

}

]
function change_table_view(view,save_state){}
