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
    name: "sales_total",
    label: "{t}Total revenue{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},

{
    name: "dispatched_total",
    label: "{t}Total dispatched{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},

{
    name: "customer_total",
    label: "{t}Total customers{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='customer_total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},

{
    name: "percentage_repeat_customer_total",
    label: "{t}% Repeat customers{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='percentage_repeat_customer_total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
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
    name: "dispatched_year4",
    label: new Date().getFullYear()-3,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_year4'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
    name: "sales_year4",
    label: new Date().getFullYear()-3,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_year4'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_quarter0",
  label: get_quarter_label(0),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_quarter0'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_quarter1",
  label: get_quarter_label(1),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_quarter1'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_quarter2",
   label: get_quarter_label(2),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_quarter2'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_quarter3",
   label: get_quarter_label(3),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_quarter3'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "dispatched_quarter4",
   label: get_quarter_label(4),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='dispatched_quarter4'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_quarter0",
   label: get_quarter_label(0),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_quarter0'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_quarter1",
   label: get_quarter_label(1),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_quarter1'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_quarter2",
  label: get_quarter_label(2),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_quarter2'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_quarter3",
   label: get_quarter_label(3),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_quarter3'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_quarter4",
    label: get_quarter_label(4),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_quarter4'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
}
]

function get_quarter_label(index) {
    var d = new Date();
    d.setMonth(d.getMonth() - 3 * index);
    return getQuarter(d) + 'Q ' + d.getFullYear().toString().substr(2, 2)
}

function getQuarter(d) {
    d = d || new Date(); 
    var q = [1, 2, 3, 4];
    return q[Math.floor(d.getMonth() / 3)];
}


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
      
    grid.columns.findWhere({ name: 'dispatched_year0'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_year1'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_year2'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_year3'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_year4'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year0'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year1'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year2'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year3'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year4'} ).set("renderable", false)

    grid.columns.findWhere({ name: 'dispatched_quarter0'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_quarter1'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_quarter2'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_quarter3'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_quarter4'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_quarter0'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_quarter1'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_quarter2'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_quarter3'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_quarter4'} ).set("renderable", false)

    
    grid.columns.findWhere({ name: 'stock_status_label'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'dispatched_per_week'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'weeks_available'} ).set("renderable", false)

    grid.columns.findWhere({ name: 'sales_total'} ).set("renderable", false)  
    grid.columns.findWhere({ name: 'dispatched_total'} ).set("renderable", false)  
    grid.columns.findWhere({ name: 'customer_total'} ).set("renderable", false)  
    grid.columns.findWhere({ name: 'percentage_repeat_customer_total'} ).set("renderable", false)  



  if(view=='overview'){
    $('#columns_period').removeClass('hide');
    grid.columns.findWhere({ name: 'sko_description'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
  }else if(view=='performance'){
    $('#columns_period').removeClass('hide');
    grid.columns.findWhere({ name: 'sales_total'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_total'} ).set("renderable", true)  
    grid.columns.findWhere({ name: 'customer_total'} ).set("renderable", true)  
    grid.columns.findWhere({ name: 'percentage_repeat_customer_total'} ).set("renderable", true)  
    grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_per_week'} ).set("renderable", true)
  }else if(view=='sales'){
    $('#columns_period').removeClass('hide');
    grid.columns.findWhere({ name: 'dispatched'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_1yb'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", true)
  }else if(view=='dispatched_y'){
    grid.columns.findWhere({ name: 'dispatched_year0'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_year1'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_year2'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_year3'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_year4'} ).set("renderable", true)
  }else if(view=='revenue_y'){
    grid.columns.findWhere({ name: 'sales_year0'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_year1'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_year2'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_year3'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_year4'} ).set("renderable", true)
  }else if(view=='dispatched_q'){
    grid.columns.findWhere({ name: 'dispatched_quarter0'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_quarter1'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_quarter2'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_quarter3'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'dispatched_quarter4'} ).set("renderable", true)
  }else if(view=='revenue_q'){
    grid.columns.findWhere({ name: 'sales_quarter0'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_quarter1'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_quarter2'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_quarter3'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'sales_quarter4'} ).set("renderable", true)
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