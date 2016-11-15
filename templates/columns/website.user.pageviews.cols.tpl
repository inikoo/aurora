var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "page",
label: "{t}Page{/t}",
editable: false,
cell: Backgrid.Cell.extend({
events: {
"click": function() {
change_view( 'website/'+this.model.get("site_key")+'/page/' + this.model.get("id"))

}
},
className: "link",


})
}, {
name: "type",
label:"{t}Type{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: 'html'
}, {
name: "title",
label: "{t}Page title{/t}",
editable: false,
cell: 'string'
}, {
name: "date",
label:"{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]
function change_table_view(view,save_state){}
