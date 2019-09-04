{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 December 2017 at 15:51:44 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
*/*}

var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},


{
name: "reference",
label: "{t}Reference{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},




{
name: "name",
label: "{t}Unit name{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},


{
name: "cost",
label: "{t}Current unit cost{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell

},

{
name: "weight",
label: "{t}Unit weight{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "units_received",
label: "{t}Units received{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell

},
{
name: "amount",
label: "{t}Amount{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell

},

]


function change_table_view(view, save_state) {



}
