var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "staff_key",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "check",
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "width_100"} ),
},
{
name: "status",
label: "{t}Status{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: " width_100 padding_right_10"} ),
},

{
name: "name",
label: "{t}Employee{/t}",
renderable: {if $data['object']=='employee'}false{else}true{/if},
editable: false,
sortType: "toggle",
{if $sort_key=='alias'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({

className: "padding_left_10 "

})

},


{
name: "clocking_records",
label: "{t}Clockings{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


]

function change_table_view(view,save_state){}