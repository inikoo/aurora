{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 December 2018 at 15:17:01 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}

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
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

}
, {
name: "code",
label:'',
html_label: '{t}Code{/t}',
title: "{t}Store Code{/t}",
headerCell: HeaderHtmlCell,

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
name: "invoices",
label:'',
html_label: '{t}Invoices{/t}',
title: "{t}Invoices{/t}",
headerCell: rightHeaderHtmlCell,
headerClass:"aright",

editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

},


{
name: "refunds",
label:'',
html_label: '{t}Refunds{/t}',
title: "{t}Refunds{/t}",
headerCell: rightHeaderHtmlCell,
headerClass:"aright",

editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

},


{
name: "refund_percentage",
label:'',
html_label: '{t}% Ref{/t}',
title: "{t}Percentage refunds{/t}",
headerCell: rightHeaderHtmlCell,
headerClass:"aright",

editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refund_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

},


]
function change_table_view(view,save_state){}
