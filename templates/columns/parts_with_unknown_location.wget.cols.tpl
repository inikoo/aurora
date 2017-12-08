{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 December 2017 at 13:02:27 CET, Mijas Costa, Spain
 Copyright (c) 2017, Inikoo

 Version 3
*/*}
var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "part_status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},{
name: "reference",
label: "{t}Part{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({})

},

{
name: "description",
label: "{t}Description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({})

},

{
name: "locations",
label: "{t}Locations{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({})

},
{
name: "quantity",
label: "{t}Leakage to fix{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

]

function change_table_view(view,save_state){

}