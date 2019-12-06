{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 July 2018 at 00:17:40 GMT+8, Kuala Lumpur, Malaysia
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

cell: Backgrid.HtmlCell.extend({

})
},

{
name: "customer",
label: "{t}Customer{/t}",

renderable:{if $mailshot_type=='Invite Full Mailshot'}false{else}true{/if},
sortType: "toggle",
editable: false,

{if $sort_key=='customer'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })
},
{
name: "prospect",
renderable:{if $mailshot_type=='Invite Full Mailshot'}true{else}false{/if},

label: "{t}Prospect{/t}",
sortType: "toggle",
editable: false,

{if $sort_key=='prospect'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })
},

 {
name: "date",
label: "{t}Sent date{/t}",
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
}

]

function change_table_view(view,save_state){


}