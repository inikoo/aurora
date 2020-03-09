{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 March 2020  23:25::40  +0800, Kuala Lumpur Malaysia
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
name: "code",
label: "{t}Code{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},

{
name: "name",
label: "{t}Name{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},




]




function change_table_view(view, save_state) {



}
