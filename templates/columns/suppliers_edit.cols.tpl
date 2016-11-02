var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "link",
label: "{t}Supplier{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({
orderSeparator: '',
events: {
"click": function() {
change_view('supplier{if $data.object=='list'}/list/{$data.key}{/if}/' + this.model.get("id"))
}
},
className: "link width_150"

})
},
{
name: "checkbox",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},
{
name: "operations",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},


{
name: "code",
label: "Code",
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {
"click": "enterEditMode"
},
className: "width_200"
})
},
{
name: "name",
label: "{t}Company name{/t}",
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {
"click": "enterEditMode"
},
className: "width_400"
})
},

{
name: "contact",
label: "{t}Contact{/t}",

sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {
"click": "enterEditMode"
}
})
}, {
name: "email",
label: "{t}Email{/t}",
sortType: "toggle",
cell: Backgrid.StringCell.extend({

})
},
{
name: "mobile",
label: "{t}Mobile{/t}",
sortType: "toggle",
cell: Backgrid.StringCell.extend({
className: "width_200"
})
},
{
name: "telephone",
label: "{t}Telephone{/t}",
sortType: "toggle",
cell: Backgrid.StringCell.extend({
className: "width_200"
})
}

]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'code'} ).set("renderable", false)

grid.columns.findWhere({ name: 'name'} ).set("renderable", false)

grid.columns.findWhere({ name: 'contact'} ).set("renderable", false)
grid.columns.findWhere({ name: 'email'} ).set("renderable", false)
grid.columns.findWhere({ name: 'telephone'} ).set("renderable", false)
grid.columns.findWhere({ name: 'mobile'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'code'} ).set("renderable", true)

grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'contact'} ).set("renderable", true)

}else if(view=='contact'){
grid.columns.findWhere({ name: 'email'} ).set("renderable", true)
grid.columns.findWhere({ name: 'mobile'} ).set("renderable", true)
grid.columns.findWhere({ name: 'telephone'} ).set("renderable", true)
}else if(view=='parts'){
grid.columns.findWhere({ name: 'supplier_parts'} ).set("renderable", true)
grid.columns.findWhere({ name: 'surplus'} ).set("renderable", true)
grid.columns.findWhere({ name: 'optimal'} ).set("renderable", true)
grid.columns.findWhere({ name: 'low'} ).set("renderable", true)
grid.columns.findWhere({ name: 'critical'} ).set("renderable", true)
grid.columns.findWhere({ name: 'out_of_stock'} ).set("renderable", true)

}else if(view=='sales'){
$('#columns_period').removeClass('hide');
grid.columns.findWhere({ name: 'revenue'} ).set("renderable", true)
grid.columns.findWhere({ name: 'revenue_1y'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_year0'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_year1'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_year2'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_year3'} ).set("renderable", true)


}else if(view=='orders'){
grid.columns.findWhere({ name: 'last_purchase_order'} ).set("renderable", true)
grid.columns.findWhere({ name: 'pending_po'} ).set("renderable", true)

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}