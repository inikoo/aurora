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
    editable: false,
     cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view('{if $data.module=='products'}products/{$data._object->get('Category Store Key')}/{else if $data.module=='customers'}customers/{$data._object->get('Category Store Key')}/{elseif $data.module=='invoices_server'}invoices/all/{/if}category/' + this.model.get("id") )
            }
        },
        className: "link",
        
         render: function () {
      this.constructor.__super__.render.apply(this, arguments);
        if(this.model.get('id')==''){
            this.$el.removeClass('link');
        }
      return this;
    }
        
        
    })
}, 
{
    name: "label",
    label:"{t}Label{/t}",
    editable: false,
    cell: "string"
}, 


 {
    name: "subjects_active",
    label: "{t}Parts{/t}",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='supplier_parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
 {
    name: "subjects_no_active",
    label: "{t}Discontinued{/t}",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='supplier_parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
 
 {
    name: "surplus",
    label: "{t}Surplus{/t}",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='surplus'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
 {
    name: "optimal",
    label: "{t}Optimal{/t}",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='optimal'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
 {
    name: "low",
    label: "{t}Low{/t}",
    editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='low'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},
{
    name: "critical",
    label: "{t}Critical{/t}",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='critical'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
 {
    name: "out_of_stock",
    label: "{t}Out of Stock{/t}",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='out_of_stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
 {
    name: "stock_error",
    label: "{t}Error{/t}",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='stock_error'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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

    

    grid.columns.findWhere({ name: 'surplus'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'optimal'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'low'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'critical'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'out_of_stock'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'stock_error'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'subjects_active'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'subjects_no_active'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'label'} ).set("renderable", false)

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
   
    
    if(view=='overview'){
        $('#columns_period').removeClass('hide');

        grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'subjects_active'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'subjects_no_active'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'label'} ).set("renderable", true)

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
       grid.columns.findWhere({ name: 'surplus'} ).set("renderable", true)
         grid.columns.findWhere({ name: 'optimal'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'low'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'critical'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'out_of_stock'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'stock_error'} ).set("renderable", true)


    }
    
    
    if(save_state){
     var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view
   
    $.getJSON(request, function(data) {});
    }

}