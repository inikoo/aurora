{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  12 February 2020  19:46::38  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

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

},{
name: "code_plain",
label: "{t}ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='code_plain'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })


},  {
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
},
{
name: "operations",
label: "",
title: "",

editable: false,
sortable: false,


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){


}