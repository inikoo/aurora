var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},

{
name: "formated_id",
label: "{t}Id{/t}",
renderable: true,
editable: false,
sortType: "toggle",
{if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({
events: {
"click": function() {
{if $upload_objects=='employees'}
    change_view('hr/uploads/'+this.model.get("id") )

{else}
    change_view('uploads/'+this.model.get("id") )
{/if}
}
},
className: "link"

})

},
{
name: "state",
label: "{t}State{/t}",
renderable: true,
editable: false,
sortType: "toggle",
{if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

className: " width_50"

})

},
{

name: "date",
label: "{t}Date{/t}",
editable: false,
defautOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright width_200 padding_right_20"} ),
headerCell: integerHeaderCell

},
{
name: "ok",
label: "{t}OK{/t}",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='ok'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "warnings",
label: "{t}Warnings{/t}",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='warnings'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "errors",
label: "{t}Errors{/t}",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='errors'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){}