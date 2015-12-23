var columns = [
{
    name: "billing_region",
    label: "{t}Billing region{/t}",
    editable: false,
         renderable: true,
     sortType: "billing_region",
    {if $sort_key=='id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.StringCell.extend({})
   
},
{
    name: "tax_code",
    label: "{t}Tax code{/t}",
    editable: false,
         renderable: true,
          defautOrder:-1,
     sortType: "tax_code",
    {if $sort_key=='id'}direction: '{if $sort_order==1}ascending{else}descending{/if}',{/if}
    
    cell: Backgrid.HtmlCell.extend({})
   
},

 {
  
 
    name: "invoices",
    label: "{t}Invoices{/t}",
    editable: false,
     sortType: "toggle",
      defautOrder:1,
    {if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
   cell: Backgrid.StringCell.extend({ 
    events: {
            "click": function() {
                  change_view('report/billingregion_taxcategory/invoices/'+this.model.get("request"))
               
            }
        },
   
   className: "link aright"
   
   
   } ),
    headerCell: integerHeaderCell

},
 {
  
 
    name: "refunds",
    label: "{t}Refunds{/t}",
    editable: false,
     sortType: "toggle",
      defautOrder:1,
    {if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
   cell: Backgrid.StringCell.extend({
     events: {
            "click": function() {
                  change_view('report/billingregion_taxcategory/refunds/'+this.model.get("request"))
            }
        },
    className: "link aright"} ),
    headerCell: integerHeaderCell

},
 {
    name: "customers",
    label: "{t}Customers{/t}",
    editable: false,
         renderable: false,
     sortType: "toggle",
      defautOrder:1,
    {if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
   cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

},
 {
    name: "net",
    label: "{t}Net{/t}",
    editable: false,
     sortType: "toggle",
      defautOrder:1,
    {if $sort_key=='net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
   cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

},
 {
    name: "tax",
    label: "{t}Tax{/t}",
    editable: false,
     sortType: "toggle",
      defautOrder:1,
    {if $sort_key=='tax'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
   cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

},
 {
    name: "total",
    label: "{t}Total{/t}",
    editable: false,
     sortType: "toggle",
      defautOrder:1,
    {if $sort_key=='total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
   cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

},



]

function change_table_view(view,save_state){}