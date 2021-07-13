
{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  2021-07-10T18:36:29+08:00 Kuala Lumpur Malaysia
 Copyright (c) 2021, Inikoo

 Version 3
*/*}
var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},
{
name: "type",
label: "",
editable: false,
sortType: "toggle",
{if $sort_key=='type'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"

} ),
headerCell: integerHeaderCell
},

{
name: "formatted_id",
label: "{t}Id{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
})
},
{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='reference'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
})
},
{
name: "notes",
label: "{t}Notes{/t}",
sortable:false,
editable: false,
cell: "html"

},

{
name: "location",
label: "{t}Location{/t}",
sortable:false,
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_250"

} )

},
{
name: "delete",
label: "",
renderable:false,
sortable:false,
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"

} )

},

]


function change_table_view(view, save_state) {


}
