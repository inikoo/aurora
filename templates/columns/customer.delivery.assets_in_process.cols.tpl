
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
name: "formatted_id",
label: "{t}Id{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})
},
{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})
},
{
name: "description_cartons",
label: "{t}Carton description{/t}",
editable: false,
cell: "html"

},

{
name: "other_deliveries_units",
label: "{t}Other deliveries{/t}",
editable: false,
cell: "html"

},
{
name: "other_deliveries_skos",
label: "{t}Other deliveries{/t}",
editable: false,
cell: "html"

},
{
name: "other_deliveries_cartons",
label: "{t}Other deliveries{/t}",
editable: false,
cell: "html"

},
{
name: "subtotals",
label: "{t}Subtotals{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='subtotals'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

},


]


function change_table_view(view, save_state) {



}
