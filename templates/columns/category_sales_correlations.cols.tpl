{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 04-08-2019 12:43:55 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

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
name: "status",
label:'',
html_label: '<i class="fa fa-retweet discreet" ></i>',
editable: false,
renderable: {if $data['_object']->get('Category Subject')=='Category'}false{else}true{/if},

title: '{t}Category status{/t}',
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_40"
}),
headerCell: HeaderHtmlCell,

},

{
name: "code",
label: "{t}Code{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })


},


{
name: "correlation",
label: "{t}Correlation{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='correlation'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "customers_AB",
label:'',
html_label: '<i class="fa fa-user"></i> <i class="far fa-user"></i>',
title: "{t}Customers than order both categories{/t}",

editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customers_AB'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: rightHeaderHtmlCell,
headerClass:"aright"
},
{
name: "customers_A",
label:'',
html_label: '<i class="fa fa-user"></i> <i class="far fa-user-slash"></i>',
title: "{t}Customers that order A but no B {/t} (A={$data['_object']->get('Code')})",

editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customers_A'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: rightHeaderHtmlCell,
headerClass:"aright"
},
{
name: "customers_B",
label:'',
html_label: '<i class="fa fa-user-slash"></i> <i class="far fa-user"></i>',
title: "{t}Customers that order B but no A {/t} (A={$data['_object']->get('Code')})",

editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customers_B'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: rightHeaderHtmlCell,
headerClass:"aright"
},

]

function change_table_view(view,save_state){


}
