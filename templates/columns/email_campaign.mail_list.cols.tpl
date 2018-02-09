{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:6 February 2018 at 15:03:36 GMT+8, Kuala Lumpur , Malaysia
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
name: "email",
label: "{t}Email{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='email'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.EmailCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
},
{
name: "formatted_id",
label: "{t}ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

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
}, {
name: "company_name",
label: "{t}Company{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='company_name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}, {
name: "contact_name",
label: "{t}Main contact{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='contact_name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}

]

function change_table_view(view,save_state){


}