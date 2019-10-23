var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},

{
name: "used",
label: "",
renderable: {if $can_edit}true{else}false{/if},

editable: false,
sortable:false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 center"
})

},





{

name: "time",
label: "{t}Time{/t}",
renderable: true,
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='time'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: " width_100 padding_right_20"} ),
headerCell: Backgrid.HeaderCell.extend({ className: " padding_right_20"})

},

{
name: "type",
label: "{t}Type{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: " padding_left_20 "} ),
headerCell: Backgrid.HeaderCell.extend({ className: "padding_left_20"})


},

{
name: "source",
label: "{t}Source{/t}",
editable: false,
sortType: "toggle",
cell:'string'
},

{
name: "action_type",
label: "{t}Action{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_200"
})
},
{
name: "ignored",
renderable:false,
editable: false,
label: "{t}Ignored{/t}",
sortType: "toggle",
cell:'string'
},
{
name: "notes",
label: "{t}Notes{/t}",
editable: false,
sortable:false,
cell:'Html'
},

]

function change_table_view(view,save_state){}