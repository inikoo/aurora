{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  02 February 2020  18:58::17  +0800, Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},
{
name: "code",
label: "{t}Code{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},



{
name: "stock_status",
label: "{t}Stock{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

} ),

},

{
name: "name",
label: "{t}Name{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},


{
name: "price",
label: "{t}Price{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell

},
{
name: "rrp",
label: "{t}RRP{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell

},
{
name: "operations",
label: "",
defaultOrder:1,
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},



]




function change_table_view(view, save_state) {



$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');




grid.columns.findWhere({ name: 'stock_status'} ).set("renderable", false)
grid.columns.findWhere({ name: 'price'} ).set("renderable", false)
grid.columns.findWhere({ name: 'rrp'} ).set("renderable", false)
grid.columns.findWhere({ name: 'name'} ).set("renderable", false)



if(view=='overview'){
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)

grid.columns.findWhere({ name: 'price'} ).set("renderable", true)
grid.columns.findWhere({ name: 'rrp'} ).set("renderable", true)
grid.columns.findWhere({ name: 'stock_status'} ).set("renderable", true)

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {

});
}


}
