{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 November 2018 at 10:56:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},


{
name: "pin",
label: "",
html_label :"<i class=\"fal fa-thumbtack\"></i>",
title:"{t}Show if offer is pinned{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='pin'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ } ),
headerCell: HeaderHtmlCell

},

{
name: "name",
label: "{t}Name{/t}",
editable: false,

sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
},

{
name: "current_deal_status",
label: "",
renderable:{if $order->get('State Index')>=80 or  $order->get('State Index')<0 }false{else}true{/if},

html_label :"<i class=\"fa fa-adjust\"></i>",
title:"{t}Current offer state{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='current_deal_status'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({} ),
headerCell: HeaderHtmlCell

},

{
name: "description",
label: "{t}Terms/Allowances{/t}",
editable: false,

sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

}),

},
{
name: "items",
label: "{t}Items{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "discount_percentage",
label: "",
html_label :"<i class=\"fa fa-percentage\"></i>",
title:"{t}Percentage discounted{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='discount_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},

{
name: "bonus",
label: "",
html_label :"<i class=\"fa fa-gift\"></i>",
title:"{t}Free products given{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='bonus'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},

{
name: "amount_discounted",
label: "",
html_label :"{t}Discount value{/t}",
title:"{t}Amount discounted{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='amount_discounted'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},


]

function change_table_view(view,save_state){

return;

}