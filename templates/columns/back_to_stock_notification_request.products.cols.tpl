{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2018 at 20:28:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}


var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},


{
name: "status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

class:'width_20'

} ),

},
{
name: "code",
label: "{t}Code{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},

{
name: "web_state",
label: "{t}Web state{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},
{
name: "name",
label: "{t}Name{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},



{
name: "customers",
label: "{t}Notification requests{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}


]




function change_table_view(view, save_state) {


}
