{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14-09-2019 15:18:11 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

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
name: "reference",
label: "{t}Suppliers code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})

},{
name: "date",
label: "{t}Complain date{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_250 aright "
}),
headerCell: integerHeaderCell
},{
name: "author",
label: "{t}Reporter{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_200 "
})

}, {
name: "note",
label: "{t}Note{/t}",
sortable:false,
editable: false,
cell: Backgrid.HtmlCell.extend({
})
}
]


function change_table_view(view, save_state) {}
