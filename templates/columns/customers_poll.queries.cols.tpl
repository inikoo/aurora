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
name: "position",
label: "{t}nth{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
{if $sort_key=='position'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
}
)
},
{
name: "query",
label: "{t}Query{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
{if $sort_key=='query'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
}
)
},

{
name: "type",
label: "{t}Type{/t}",
sortType: "toggle",
editable: false,
sortable:false,
cell: Backgrid.HtmlCell.extend({

}
)
},

{
name: "in_registration",
label: "{t}In registration{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
{if $sort_key=='in_registration'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: 'width_200'

}
)
},

{
name: "in_profile",
label: "{t}In profile{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
{if $sort_key=='in_profile'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: 'width_200'
}
)
},



{
name: "customers",
label: "{t}Customers{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell,


},

{
name: "customers_percentage",
label: "{t}Reply %{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='customers_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright width_200"} ),
headerCell: integerHeaderCell,


}
]

function change_table_view(view,save_state){


}