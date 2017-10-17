{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 October 2017 at 17:33:35 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "stock_status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},{
name: "reference",
label: "{t}Part{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({ })

},
{
name: "description",
label: "{t}Description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({ })

},
{
name: "error",
label: "{t}Error{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({ })

},
{
name: "barcode",
label: "{t}Barcode{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

className: "width_500"
})

}

]

function change_table_view(view,save_state){

}