var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "alias",
label: "{t}Code{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
events: {
"click": function() {
change_view('contractor/' + +this.model.get("id"))
}
},
className: "link"
})
},{


name: "employee_id",
label: "{t}Payroll ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='payroll_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},


{
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
events: {

}
})
},
{
name: "date",
label: "{t}Deleted{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


]

function change_table_view(view,save_state){}