var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},


{
name: "tariff_code",
label: "{t}Tariff code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},
{
name: "origin",
label: "{t}Origin{/t}",
editable: false,
cell: "html"

},

{
name: "dangerous_goods",
label: "{t}DG{/t}",
sortable:false,
editable: false,
cell: "html"

},


{
name: "references",
label: "{t}Parts{/t}",
sortable:false,
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
