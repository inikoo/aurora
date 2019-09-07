{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 06-09-2019 23:49:01 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019 Inikoo

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
label: "{t}Part{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})

},{
name: "date",
label: "{t}Last complain{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_250 aright "
}),
headerCell: integerHeaderCell
},{
name: "number_feedback",
label: "{t}Complains{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: " aright "
}),
headerCell: integerHeaderCell
},

]


function change_table_view(view, save_state) {}
