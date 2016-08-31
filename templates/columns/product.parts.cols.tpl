var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

}, {
    name: "stock_status",
    label: "",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({
        className: "width_20"
    })

},{
    name: "reference",
    label: "{t}Reference{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view('part/' + this.model.get("id"))
            }
        },
        className: "link"
       
})
   
},

{
    name: "picking_ratio",
    label: "{t}Picking{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='picking_ratio'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "strong"} ),

},

{
    name: "picking_note",
    label: "{t}Picking note{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
       
       
})
   
},

{
    name: "package_description",
    label: "{t}SKO description{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
       
       
})
   
},
{
    name: "stock_status_label",
    label: "{t}Stock status{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
       
       
})
   
},
{
    name: "stock",
    label: "{t}Stock{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},


{
    name: "dispatched_per_week",
    label: "{t}Dispatched/w{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "weeks_available",
    label: "{t}Weeks available{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
]

function change_table_view(view,save_state){

    $('.view').removeClass('selected');
    $('#view_'+view).addClass('selected');
    

    close_columns_period_options()
    $('#columns_period').addClass('hide');
    
    grid.columns.findWhere({ name: 'package_description'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'stock'} ).set("renderable", false)

    grid.columns.findWhere({ name: 'stock_status_label'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_per_week'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'weeks_available'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'picking_ratio'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'picking_note'} ).set("renderable", false)



  if(view=='overview'){
    grid.columns.findWhere({ name: 'package_description'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'picking_ratio'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'picking_note'} ).set("renderable", true)


    $('#columns_period').removeClass('hide');

  
  }else if(view=='stock'){
    grid.columns.findWhere({ name: 'stock_status_label'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
          grid.columns.findWhere({ name: 'dispatched_per_week'} ).set("renderable", true)

        grid.columns.findWhere({ name: 'weeks_available'} ).set("renderable", true)


  
  }
  
    if(save_state){
     var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view
   
    $.getJSON(request, function(data) {});
    }

}