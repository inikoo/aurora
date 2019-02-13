{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 February 2019 at 01:57:14 GMT+8, Kuala Lumpur Malaysia
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


},
{
name: "store",
label: "{t}Store{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
},
{
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({


}),
},
{
name: "subject",
label: "{t}Email subject{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({


}),
},
{
name: "date",
label: "{t}Date{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},
{
name: "open",
label: "{t}Opened{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},


{
name: "clicked",
label: "{t}Clicked{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},



{
name: "space",
label: "",
editable: false,
sortable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

className: "width_20"
}),
},
 {
name: "image",
label: "{t}Preview{/t}",
renderable:false,

editable: false,
sortable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

className: "width_300"
}),
},



{
name: "operations",
label: "{t}Operations{/t}",
sortable: false,
editable: false,
cell: Backgrid.HtmlCell.extend({


}),
}
]


function change_table_view(view, save_state) {}
