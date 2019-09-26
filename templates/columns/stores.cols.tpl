var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
}
, {
name: "access",
label: "",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
})

}
, {
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
}, {
name: "name",
label: "{t}Name{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
},

 {
name: "website",
label: "{t}Website{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
},
{
name: "in_process",
label:'',
html_label: '<i class="fa fa-child" aria-hidden="true"></i>',
title: "{t}New products{/t}",
headerCell: HeaderHtmlCell,
headerClass:"aright",

editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='in_process'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

},
{
name: "active",
label:'',
html_label: '<i class="fa fa-cube" aria-hidden="true"></i>',
title: "{t}Active products (including discontinuing){/t}",

editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='active'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: HeaderHtmlCell,
headerClass:"aright"
},
{
name: "discontinuing",
label:'',
html_label: '<i class="fa fa-cube warning discreet" aria-hidden="true"></i>',
title: "{t}Discontinuing products{/t}",

editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='discontinuing'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: HeaderHtmlCell,
headerClass:"aright"

},
{
name: "discontinued",
label:'',
html_label: '<i class="fa fa-cube very_discreet" aria-hidden="true"></i>',
editable: false,
title: "{t}Discontinued products{/t}",

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='discontinued'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: HeaderHtmlCell,
headerClass:"aright"

},


]
function change_table_view(view,save_state){}
