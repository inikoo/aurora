{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2018 at 10:09:39 CEST, Mijas Costa, Spain
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
name: "email",
label: "{t}Email{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='email'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.EmailCell.extend({

})
},
{
name: "formatted_id",
label: "{t}Customer Id{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })


},
{
name: "name",
label: "{t}Customer Name{/t}",
sortType: "toggle",
editable: false,

{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })
},

 {
name: "order",
label: "{t}Order{/t}",
sortType: "toggle",
editable: false,

{if $sort_key=='order'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
},

{
name: "inactive_days",
label: "{t}Inactivity (days){/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='inactive_days'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}

]

function change_table_view(view,save_state){


}