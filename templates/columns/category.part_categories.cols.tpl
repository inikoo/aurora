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
    name: "revenue",
    label: "{t}Revenue{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='money_in'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "revenue_1y",
    label: "1YB",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='revenue_1y'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright "} ),
    headerCell: integerHeaderCell

},
{
    name: "revenue_year0",
    label: new Date().getFullYear(),
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='revenue_year0'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "revenue_year1",
    label: new Date().getFullYear()-1,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='revenue_year1'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "revenue_year2",
    label: new Date().getFullYear()-2,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='revenue_year2'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "revenue_year3",
    label: new Date().getFullYear()-3,
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='revenue_year3'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},

]

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

     grid.columns.findWhere({ name: 'revenue'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'revenue_1y'} ).set("renderable", false)
    
        grid.columns.findWhere({ name: 'revenue_year0'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'revenue_year1'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'revenue_year2'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'revenue_year3'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'subjects_active'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'subjects_no_active'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'label'} ).set("renderable", false)

    
    
    if(view=='overview'){
            $('#columns_period').removeClass('hide');

               grid.columns.findWhere({ name: 'revenue'} ).set("renderable", true)
   grid.columns.findWhere({ name: 'subjects_active'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'subjects_no_active'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'label'} ).set("renderable", true)

    }else if(view=='revenue'){
      $('#columns_period').removeClass('hide');
        grid.columns.findWhere({ name: 'revenue'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'revenue_1y'} ).set("renderable", true)
       grid.columns.findWhere({ name: 'revenue_year0'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'revenue_year1'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'revenue_year2'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'revenue_year3'} ).set("renderable", true)

  
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