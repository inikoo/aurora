{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 19:40:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/*}

var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "date",
label: "{t}Date{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_250  "
}),

},{
name: "event",
label: "{t}Event{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_200 "
})

}, {
name: "note",
label: "{t}Note{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})
}
]


function change_table_view(view, save_state) {}
