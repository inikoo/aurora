var columns= [{
     name: "id",
     label: "",
     editable: false,
     cell: "integer",
     renderable: false


 }, {
     name: "store_key",
     label: "",
     editable: false,
     renderable: false,
     cell: "string"
 },{
     name: "customer_key",
     label: "",
     editable: false,
     renderable: false,
     cell: "string"
 },
 
   {
     name: "store_code",
     label: "{t}Store{/t}",
      sortType: "toggle",
     editable: false,
     cell: Backgrid.HtmlCell.extend({
        
     })
 },
  {
     name: "number",
     label: "{t}Number{/t}",
     editable: false,
   sortType: "toggle",
    {if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

     cell: Backgrid.StringCell.extend({
         events: {
             "click": function() {
                 change_view('invoices/'+this.model.get("store_key")+'/' + this.model.get("id")  )
             }
         },
         className: "link",
     })
 },
  {
     name: "customer",
     label: "{t}Customer{/t}",
      sortType: "toggle",
     editable: false,
     cell: Backgrid.StringCell.extend({
        
         events: {
             "click": function() {
                 change_view('customer/' + this.model.get("customer_key")  )
             }
         },
         className: "link padding_left_20",
     })
 },
  {
     name: "billing_country_flag",
     label: "",
      sortType: "toggle",
     editable: false,
     cell: Backgrid.HtmlCell.extend({
        
         className: "link width_20",
     })
 },
  {
     name: "date",
     label: "{t}Date{/t}",
     editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "width_100 padding_right_20 aright"} ),
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
     name: "total_amount",
     label: "{t}Total{/t}",
     editable: false,
     defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
 }]

function change_table_view(view,save_state){}
