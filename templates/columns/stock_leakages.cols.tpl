{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 October 2017 at 23:55:03 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},


{
name: "date",
label: "{t}Date{/t}",

editable: false,
cell: Backgrid.HtmlCell.extend({

className: " aright width_150"


}),
headerCell: integerHeaderCell,
sortType: "toggle",
},

{
name: "up_amount",
label: "{t}Amount found{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({
className: "aright "

}),
headerCell: integerHeaderCell,

},
{
name: "up_transactions",
label: "{t}Found transactions{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({
className: "aright "

}),
headerCell: integerHeaderCell,

},
{
name: "down_amount",
label: "{t}Amount lost{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "down_transactions",
label: "{t}Lost transactions{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({
className: "aright "

}),
headerCell: integerHeaderCell,

}
]

function change_table_view(view,save_state){}