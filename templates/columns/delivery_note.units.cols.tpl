var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}
,{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},{
name: "description",
label: "{t}Unit description{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},
{
name: "tariff_code",
label: "{t}Tariff code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},

{
name: "countries_of_origin",
label: "{t}Origin{/t}",
editable: false,
cell: "html"

},
{
name: "dangerous_goods",
label: "{t}DG{/t}",
editable: false,
cell: "html"

},


{
name: "weight",
label: "{t}Weight{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='weight'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "units_invoiced",
label: "{t}Units{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='units_invoiced'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},





{
name: "amount",
label: "{t}Amount{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},




]


function change_table_view(view,save_state){


}
