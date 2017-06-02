var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},

{
name: "icon",
label: '',
editable: false,
title: '{t}Web page group{/t}',
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: ""
}),
headerCell: HeaderHtmlCell,

},

  {
name: "label",
label:"{t}Name{/t}",
editable: false,
sortable:false,
cell: Backgrid.HtmlCell.extend({  }),
},
 {
name: "in_process_webpages",
label: "{t}In Process{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='in_process_webpages'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "online_webpages",
label: "{t}Online{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='online_webpages'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "offline_webpages",
label: "{t}Offline{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='offline_webpages'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

]
function change_table_view(view,save_state){}
