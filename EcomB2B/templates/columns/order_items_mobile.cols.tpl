{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   25 February 2020  19:46  +0800, Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},
{
name: "item",
label: "{t}Item{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},


{
name: "ordered",
label: "{t}Ordered{/t}",

defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='ordered'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "qty",
label: "{t}Dispatched{/t}",

defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='qty'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "net",
label: "{t}Net{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell
},



]




function change_table_view(view, save_state) {





}
