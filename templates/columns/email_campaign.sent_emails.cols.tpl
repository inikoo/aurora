{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 July 2018 at 16:02:45 GMT+8, Kuala Lumpur, Malaysia
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
editable: false,
sortType: "toggle",
{if $sort_key=='customer'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
},
{
name: "date",
label: "{t}Date{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })


},
{
name: "state",
label: "{t}State{/t}",
sortType: "toggle",
editable: false,

{if $sort_key=='state'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })
},



]

function change_table_view(view,save_state){


}