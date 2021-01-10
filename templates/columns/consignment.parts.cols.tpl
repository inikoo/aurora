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
label: "{t}Unit Description{/t}",
editable: false,
cell: "html"

},
{
name: "origin",
label: "{t}Origin{/t}",
editable: false,
cell: "html"

},

{
name: "tariff_code",
label: "{t}Tariff code{/t}",
editable: false,
cell: "html"

},


{
name: "quantity_units",
label: "{t}Quantity (units){/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity_units'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
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
name: "invoiced_amount",
label: "{t}Invoiced{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='invoiced_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},




]


function change_table_view(view,save_state){


}



