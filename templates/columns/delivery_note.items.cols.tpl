var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "link"
}),
},
{
name: "description",
label: "{t}SKO Description{/t}",
editable: false,
cell: "html"

},





{
name: "overview_required",
label: "{t}Required{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_required'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "overview_problem",
label: "{t}Out of stock{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_problem'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "overview_picked",
label: "{t}Picked{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_picked'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "overview_packed",
label: "{t}Packed{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_packed'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "overview_state",
label: "{t}State{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_state'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "acenter"} ),

},



]


function change_table_view(view,save_state){


}
