{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 January 2018 at 17:50:09 GMT+8, Kuala Lumpur, Malaysia
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
name: "code",
label: "{t}ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
})

},

{
name: "scope",
label: "{t}Scope{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='scope'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
})

},


{
name: "deleted_date",
label: "{t}Deleted{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='deleted_date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},


]

function change_table_view(view,save_state){}