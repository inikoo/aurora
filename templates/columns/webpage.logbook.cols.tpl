{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2529-04-2019 22:19:26 MYT, Kuala Lumput, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


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
className: "width_250 aright "
}),
headerCell: integerHeaderCell
},{
name: "author",
label: "{t}Author{/t}",
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
