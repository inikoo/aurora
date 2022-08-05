var columns = [
{
name: "store_key",
label: "",
editable: false,
renderable: false,
cell: "string"
},


{
name: "code",
label: "{t}Code{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({})
},

{
name: "name",
label:"{t}Store Name{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({})
},
{
name: "contacts",
{if $sort_key=='contacts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
label:"{t}Total{/t}",
editable: false,
sortType: "toggle",
defaultOrder:1,
cell: "integer",
headerCell: integerHeaderCell
},

{
name: "new_contacts",
label:"{t}New{/t}",
editable: false,
sortType: "toggle",
defaultOrder:1,

{if $sort_key=='new_contacts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: "integer",
headerCell: integerHeaderCell
}, {
name: "active_contacts",
label:"{t}Active{/t}",
editable: false,
sortType: "toggle",
defaultOrder:1,
cell: "integer",
{if $sort_key=='active_contacts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

headerCell: integerHeaderCell
}, {
name: "losing_contacts",
label:"{t}Loosing{/t}",
editable: false,
sortType: "toggle",
defaultOrder:1,
{if $sort_key=='losing_contacts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: "integer",
headerCell: integerHeaderCell
}, {
name: "lost_contacts",
label:"{t}Lost{/t}",
editable: false,
sortType: "toggle",
defaultOrder:1,
{if $sort_key=='lost_contacts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: "integer",
headerCell: integerHeaderCell
}, {
name: "never_order",
label:"{t}Never order{/t}",
editable: false,
sortType: "toggle",
defaultOrder:1,
{if $sort_key=='never_order'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: "integer",
headerCell: integerHeaderCell
}


]
function change_table_view(view,save_state){

}
