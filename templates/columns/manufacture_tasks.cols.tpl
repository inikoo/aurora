var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},{
name: "name",
label: "{t}Name{/t}",
editable: false,
renderable: true,

sortType: "toggle",
{if $sort_key=='id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"click": function() {
change_view('manufacture_task/' + +this.model.get("id"))
}
},
className: "link"

})

},
{


name: "work_cost",
label: "{t}Work cost{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='payroll_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},


]

function change_table_view(view,save_state){


}