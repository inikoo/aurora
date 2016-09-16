var columns= [{
     name: "id",
     label: "",
     editable: false,
     cell: "integer",
     renderable: false


 }, {
     name: "country_code",
     label: "{t}Country{/t}",
      sortType: "toggle",
     editable: false,
     cell: Backgrid.HtmlCell.extend({
        
         events: {
            
         },
         className: "",
     })
 },
 
  {
     name: "customer",
     label: "{t}Customer{/t}",
      sortType: "toggle",
     editable: false,
     cell: Backgrid.HtmlCell.extend({
        
         events: {
            
         },
         className: "",
     })
 },
  {
     name: "tax_number",
     label: "{t}Tax Number{/t}",
      sortType: "toggle",
     editable: false,
     cell: Backgrid.HtmlCell.extend({
        
     })
 },
 
  {
     name: "invoices",
     label: "{t}Invoices{/t}",
     editable: false,
     defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
 },
  {
     name: "refunds",
     label: "{t}Refunds{/t}",
     editable: false,
     defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
 },
  {
     name: "net",
     label: "{t}Net{/t}",
     editable: false,
     defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
 },
  {
     name: "tax",
     label: "{t}Tax{/t}",
     editable: false,
     defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
 },
 
 {
     name: "total",
     label: "{t}Total{/t}",
     editable: false,
     defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
 }]

function change_table_view(view,save_state){}
