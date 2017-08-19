{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 August 2017 at 15:05:26 GMT+5:30, Flight Delhi India to Bangkok
 Copyright (c) 2017, Inikoo

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
name: "checked",
label: '<i class="fa fa-square-o" style="margin-left:3.5px" aria-hidden="true"></i>',
headerCell: HeaderHtmlCell,
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})


},

{
name: "public_id",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

})
}, {
name: "date",
label: "{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "customer",
label: "{t}Customer{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({})
}, {
name: "dispatch_state",
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",
cell: "html"
}, {
name: "payment_state",
label: "{t}Payment{/t}",
editable: false,
sortType: "toggle",
cell: "html"
}, {
name: "total_amount",
label: "{t}Total{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "actions",
label: "{t}Actions{/t}",
sortable: false,
editable: false,
cell: Backgrid.HtmlCell.extend({})
}


]

function change_table_view(view,save_state){}
