{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2018 at 14:30:25 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2016, Inikoo

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
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({


}),
},


{
name: "subject",
label: "{t}Subject{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({


}),
},


{
name: "state",
label: "{t}State{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({


}),
},

{
name: "author",
label: "{t}Editor{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({


className: "aright"
}),
headerCell: integerHeaderCell

},

{
name: "date",
label: "{t}Last updates{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
}
]


function change_table_view(view, save_state) {}
