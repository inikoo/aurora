{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  02 February 2020  18:58::17  +0800, Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},

{
name: "transaction",
label: "{t}Transaction{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
},
 {
name: "amount",
label: "{t}Amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "running_amount",
label: "{t}Balance{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='running_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){

}
