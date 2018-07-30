{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2016 at 20:10:15 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},

{
name: "type",
label: "{t}Type{/t}",
editable: false,
cell: Backgrid.Cell.extend({


})
},
{
name: "timeseries",
label: "{t}Time series{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},
{
name: "records",
label: "{t}Records{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},



]
function change_table_view(view,save_state){}
