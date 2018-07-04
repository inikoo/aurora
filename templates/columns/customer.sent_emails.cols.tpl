{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2018 at 00:15:26 GMT+8, Kuala Lumpur, Malaysia
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
name: "subject",
label: "{t}Subject{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='subject'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
},
{
name: "type",
label: "{t}Type{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='type'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
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