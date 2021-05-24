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
{
name: "skos_bonus",
label: "{t}SKOs bonus{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
headerCell: integerHeaderCell,

{if $sort_key=='skos_bonus'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

},
{
name: "cartons_bonus",
label: "{t}Cartons bonus{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
headerCell: integerHeaderCell,

{if $sort_key=='cartons_bonus'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

},
{
name: "picker_bonus",
label: "{t}Picker bonus{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
headerCell: integerHeaderCell,

{if $sort_key=='picker_bonus'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

},
{
name: "packer_bonus",
label: "{t}Packer bonus{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
headerCell: integerHeaderCell,

{if $sort_key=='packer_bonus'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

},

]



function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'description'} ).set("renderable", false)
grid.columns.findWhere({ name: 'overview_required'} ).set("renderable", false)
grid.columns.findWhere({ name: 'overview_problem'} ).set("renderable", false)
grid.columns.findWhere({ name: 'overview_picked'} ).set("renderable", false)
grid.columns.findWhere({ name: 'overview_packed'} ).set("renderable", false)
grid.columns.findWhere({ name: 'overview_state'} ).set("renderable", false)


grid.columns.findWhere({ name: 'picker_bonus'} ).set("renderable", false)
grid.columns.findWhere({ name: 'packer_bonus'} ).set("renderable", false)
grid.columns.findWhere({ name: 'skos_bonus'} ).set("renderable", false)
grid.columns.findWhere({ name: 'cartons_bonus'} ).set("renderable", false)



if(view=='overview'){
grid.columns.findWhere({ name: 'description'} ).set("renderable", true)
grid.columns.findWhere({ name: 'overview_required'} ).set("renderable", true)
grid.columns.findWhere({ name: 'overview_problem'} ).set("renderable", true)
grid.columns.findWhere({ name: 'overview_picked'} ).set("renderable", true)
grid.columns.findWhere({ name: 'overview_packed'} ).set("renderable", true)
grid.columns.findWhere({ name: 'overview_state'} ).set("renderable", true)


}else if(view=='bonus'){

grid.columns.findWhere({ name: 'overview_state'} ).set("renderable", true)

grid.columns.findWhere({ name: 'picker_bonus'} ).set("renderable", true)
grid.columns.findWhere({ name: 'packer_bonus'} ).set("renderable", true)
grid.columns.findWhere({ name: 'skos_bonus'} ).set("renderable", true)
grid.columns.findWhere({ name: 'cartons_bonus'} ).set("renderable", true)
}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
