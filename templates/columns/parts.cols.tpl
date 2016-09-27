var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

}, {
    name: "associated",
    label: "",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({
        className: "width_20"
    })

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
                change_view('{if $data['parent']=='account'}{else if $data['parent']=='category'}category/{$data['key']}/{else}{$data['parent']}/{$data['parent_key']}/{/if}part/' + this.model.get("id"))
            }
        },
        className: "link"
       
})
   
},
{
    name: "sko_description",
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
    name: "dispatched",
    label: "{t}Dispatched{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sold'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_1yb",
    label: "{t}1YB{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sold'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_year0",
    label: new Date().getFullYear(),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_year0'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_year1",
    label: new Date().getFullYear()-1,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_year1'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_year2",
    label: new Date().getFullYear()-2,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_year2'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_year3",
    label: new Date().getFullYear()-3,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_year3'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales",
    label: "{t}Revenue{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_1yb",
    label: "{t}1YB{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_year0",
    label: new Date().getFullYear(),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_year0'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_year1",
    label: new Date().getFullYear()-1,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_year1'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_year2",
    label: new Date().getFullYear()-2,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_year2'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_year3",
    label: new Date().getFullYear()-3,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_year3'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "lost",
    label: "{t}Lost{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='lost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "bought",
    label: "{t}Bought{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='bought'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
    
    grid.columns.findWhere({ name: 'sko_description'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'stock'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_1yb'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'lost'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'bought'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_year0'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_year1'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_year2'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_year3'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year0'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year1'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year2'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year3'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'stock_status_label'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_per_week'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'weeks_available'} ).set("renderable", false)



  if(view=='overview'){
    grid.columns.findWhere({ name: 'sko_description'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
    $('#columns_period').removeClass('hide');

  
  }else if(view=='dispatched'){
    grid.columns.findWhere({ name: 'dispatched'} ).set("renderable", true)
      grid.columns.findWhere({ name: 'dispatched_1yb'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_year0'} ).set("renderable", true)
     grid.columns.findWhere({ name: 'dispatched_year1'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_year2'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_year3'} ).set("renderable", true)

    $('#columns_period').removeClass('hide');

  
  }else if(view=='revenue'){
    grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", true)
     grid.columns.findWhere({ name: 'sales_year0'} ).set("renderable", true)
     grid.columns.findWhere({ name: 'sales_year1'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_year2'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_year3'} ).set("renderable", true)
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