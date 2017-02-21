var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},
{
name: "code",
label:"{t}Code{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({  }),
},
  {
name: "label",
label:"{t}Label{/t}",
editable: false,
sortable:false,
cell: Backgrid.HtmlCell.extend({  }),
},
 {
name: "online_webpages",
label: "{t}Online web pages{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='online_webpages'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

]
function change_table_view(view,save_state){}
