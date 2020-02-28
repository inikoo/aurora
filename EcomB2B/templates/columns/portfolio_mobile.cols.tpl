{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   28 February 2020  23:38::17  +0800, Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo

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
name: "stock_status",
label: "",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({

} ),

},

{
name: "description",
label: "{t}Description{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},



{
name: "operations",
label: "",
defaultOrder:1,
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},



]




function change_table_view(view, save_state) {



}
