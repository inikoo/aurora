var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "handle",
label: "{t}Handle{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='handle'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view('account/user/' +this.model.get("id"))
}
},
className: "link"

})

}, {
name: "type",
label: "{t}Type{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='groups'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"
},{
name: "alias",
label: "{t}Name{/t}",
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {}
})
}, {
name: "date",
label: "{t}Deleted{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"
}
]

function change_table_view(view,save_state){


}