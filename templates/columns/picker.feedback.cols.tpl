{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 November 2019  11:13::11  +0100, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

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
name: "delivery_note",
label: "{t}Delivery note{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})}
,{
name: "delivery_note_date",
label: "{t}Delivery date{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})}
,{
name: "reference",
label: "{t}Part{/t}",
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
editable: false,
sortable:false,
cell: Backgrid.HtmlCell.extend({
})
}
]


function change_table_view(view, save_state) {}
