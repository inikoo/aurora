{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 October 2017 at 23:25:39 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

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
name: "reference",
label: "{t}Part{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='reference'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})

},
{
name: "description",
label: "{t}Description{/t}",
renderable: false,

editable: false,
sortType: "toggle",
{if $sort_key=='description'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({


})

},
{
name: "location",
label: "{t}Location{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='location'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})

},
{
name: "note",
label: "{t}Note{/t}",

editable: false,
sortType: "toggle",
{if $sort_key=='note'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

className: "hide_extra_note_info"
})

},

{
name: "date",
label: "{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: ""} ),
},
{
name: "user",
label: "{t}Reporter{/t}",
editable: false,
defaultOrder:-1,
sortType: "toggle",
{if $sort_key=='user'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({


})

},



{
name: "change",
label: "{t}SKOs{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='change'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "change_amount",
label: "{t}Value{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='change_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}

]

function change_table_view(view,save_state){




}