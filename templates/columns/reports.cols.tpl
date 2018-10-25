var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "space",
label: "",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({

className: "width_20"
})
}, {
name: "name",
label: "{t}Name{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({


})
}, {
name: "section",
renderable: false,

label: "{t}Section{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
}

]
function change_table_view(view,save_state){}
