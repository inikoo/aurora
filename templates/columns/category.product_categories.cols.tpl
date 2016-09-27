var columns = [
 {
    name: "id",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
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
    name: "status",
    label:"{t}Status{/t}",
    editable: false,
    cell: "html"
}, 
{
    name: "products",
    label:"{t}Products{/t}",
    editable: false,
        defautOrder:1,
    sortType: "toggle",
            {if $sort_key=='products'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
 
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}, 


{
    name: "active",
    label:"{t}Active{/t}",
    editable: false,
        defautOrder:1,
    sortType: "toggle",
            {if $sort_key=='active'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
 
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}, 
{
    name: "suspended",
    label:"{t}Suspended{/t}",
    editable: false,
        defautOrder:1,
    sortType: "toggle",
            {if $sort_key=='suspended'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
 
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}, 
{
    name: "discontinuing",
    label:"{t}Discontinuing{/t}",
    editable: false,
        defautOrder:1,
    sortType: "toggle",
            {if $sort_key=='discontinuing'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
 
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}, 
{
    name: "discontinued",
    label:"{t}Discontinued{/t}",
    editable: false,
        defautOrder:1,
    sortType: "toggle",
            {if $sort_key=='discontinued'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
 
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}, 

{
    name: "sales",
    label: "{t}Sales{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='money_in'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
{
    name: "sales_1yb",
    label: "1YB",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='sales_1y'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright "} ),
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
]
function change_table_view(view,save_state){

    $('.view').removeClass('selected');
    $('#view_'+view).addClass('selected');
    
    close_columns_period_options()
    $('#columns_period').addClass('hide');



    grid.columns.findWhere({ name: 'label'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'status'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'products'} ).set("renderable", false)

    grid.columns.findWhere({ name: 'active'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'suspended'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'discontinuing'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'discontinued'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year0'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year1'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year2'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'sales_year3'} ).set("renderable", false)   
    
    if(view=='overview'){
        grid.columns.findWhere({ name: 'label'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'products'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
    }else if(view=='status'){
        grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'active'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'suspended'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'discontinuing'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'discontinued'} ).set("renderable", true)
    }else if(view=='sales'){
        $('#columns_period').removeClass('hide');
        grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'sales_year0'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'sales_year1'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'sales_year2'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'sales_year3'} ).set("renderable", true)
      
    }else if(view=='stock'){
        

    }
    
    if(save_state){
     var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view
   
    $.getJSON(request, function(data) {});
    }

}
