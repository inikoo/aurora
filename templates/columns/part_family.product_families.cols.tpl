var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
}

, {
name: "code",
label: "{t}Store{/t}",
editable: false,
cell: Backgrid.Cell.extend({
events: {
"click": function() {
change_view('store/' + this.model.get("id") )
}
},
className: "link",


})
},
{
name: "operations",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},
{
name: "family",
label:"{t}Family{/t}",
editable: false,
defautOrder:1,
sortType: "toggle",
{if $sort_key=='family'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({  }),
},
{
name: "number_products",
label:"{t}Products{/t}",
editable: false,
defautOrder:1,
sortType: "toggle",
{if $sort_key=='number_products'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]
function change_table_view(view,save_state){}
