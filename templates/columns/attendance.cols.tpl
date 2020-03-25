{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  25 March 2020  17:54::03  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

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
name: "status",
label: "{t}Status{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: " width_100 padding_right_10"} ),
},

{
name: "name",
label: "{t}Employee{/t}",
renderable: {if $data['object']=='employee'}false{else}true{/if},
editable: false,
sortType: "toggle",
{if $sort_key=='alias'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

className: "padding_left_10 "

})

},


{
name: "clocking_records",
label: "{t}Clockings{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


]

function change_table_view(view,save_state){}