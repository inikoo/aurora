{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 June 2018 at 19:26:21 GMT+8, Kuala Lumpur, Malysia
 Copyright (c) 2018, Inikoo

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
name: "name",
label: "{t}Name{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
},



 {
name: "date",
label: "{t}Date{/t}",
sortType: "toggle",
editable: false,

{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell

},

{
name: "state",
label: "{t}State{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='state'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sent",
label: "{t}Sent{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sent'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "bounces",
title: '{t}Bounces{/t}',
label:'',
html_label: '{t}Bounces{/t} <i class="fa error fa-arrow-alt-from-right"></i>',
headerCell: rightHeaderHtmlCell,
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='bounces'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
},
{
name: "hard_bounces",
title: '{t}Hard bounces{/t}',
label:'',
html_label: '{t}Hard{/t} <i class="fa error fa-arrow-alt-from-right"></i>',
renderable:false,
headerCell: rightHeaderHtmlCell,
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='hard_bounces'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
},
{
name: "soft_bounces",
title: '{t}Soft bounces{/t}',
label:'',
html_label: '{t}Soft{/t} <i class="fa warning fa-arrow-alt-from-right"></i>',
editable: false,
renderable:false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='soft_bounces'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell
},

{
name: "delivered",
label: "{t}Delivered{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='delivered'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "open",
label: "{t}Opened{/t}",
editable: false,
defaultOrder:1,
sortType: "open",
{if $sort_key=='read'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "clicked",
label: "{t}Clicked{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='clicked'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "spam",
label: "{t}Spam{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='spam'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]

function change_table_view(view,save_state){


}