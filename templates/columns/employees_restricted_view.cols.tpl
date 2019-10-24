{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Wed 23 Oct 2019 16:41:17 +0800 MYT, Kuala Lumpur Malaysia
 Copyright (c) 2019 Inikoo

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
name: "code",
label: "{t}Code{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ events: { }})
}
,{


name: "payroll_id",
label: "{t}Payroll ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='payroll_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),

},


{
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
events: {

}
})
},



{
name: "type",
label: "{t}Type{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='type'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"
},
{
name: "job_title",
label: "{t}Job title{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
events: {

}
})
},






]

function change_table_view(view,save_state){


}

