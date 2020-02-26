{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  26 February 2020  22:47::39  +0800, Kuala Lumpur, Malaysia
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
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({ })

},{
name: "customer",
label: "{t}Customer{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='customer'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })


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