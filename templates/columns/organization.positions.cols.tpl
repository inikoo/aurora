var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},

  {
name: "position",
label:"{t}Job position{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='position'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({  }),
},
 {
name: "employees",
label: "{t}Employees{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

]
function change_table_view(view,save_state){}
