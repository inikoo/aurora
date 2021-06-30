{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2529-04-2019 22:19:26 MYT, Kuala Lumput, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

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
label: "{t}Code{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })


},


{
name: "label",
label:"{t}Name{/t}",
editable: false,
cell: "string"
}, {
name: "subcategories",
label:"{t}Subcategories{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='subcategories'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "customers",
label:"{t}Customers{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='subjects'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "percentage_assigned",
label:"{t}Assigned{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='assigned'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}


]
function change_table_view(view,save_state){}
