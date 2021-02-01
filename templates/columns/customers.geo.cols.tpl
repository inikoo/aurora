{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:16 January 2018 at 15:21:19 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, 
{
name: "flag",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},
{
name: "country",
editable: false,
label: "{t}Country{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
}
)
},

{
name: "customers",
label: "{t}Registrations{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "customers_percentage",
label: "%",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='customers_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "invoices",
label: "{t}Invoices{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},

{
name: "sales",
label: "{t}Sales{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "sales_percentage",
label: "%",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='sales_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "sales_per_customer",
label: "{t}Sales per registration{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='sales_per_customer'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}
]

function change_table_view(view,save_state){


}