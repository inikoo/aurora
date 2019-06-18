{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18-06-2019 17:06:46 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},

{
name: "type",
label:'',
html_label: '{t}Type{/t}',
editable: false,
title: '{t}Online state{/t}',
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: ""
}),
headerCell: HeaderHtmlCell,

},



{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
},


{
name: "name",
label: "{t}Name{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
},




]
function change_table_view(view,save_state){}
