{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2018 at 21:59:18 GMT+8, Kuala Lumpur, Malaysia
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

},{
name: "formatted_id",
label: "{t}ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })


}, 

{
name: "activity",
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='activity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: "string"
}
,
 {
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}, {
name: "location",
label: "{t}Location{/t}",
sortType: "toggle",
{if $sort_key=='location'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

editable: false,

cell: "html"
},
{
name: "date",
label: "{t}Requested date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]

function change_table_view(view,save_state){

}
