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
 }, {
     name: "public_id",
     label: "{t}Number{/t}",
     editable: false,
    sortType: "toggle",
     cell: Backgrid.StringCell.extend({
         events: {
             "click": function() {
                 {if $data['object']!=''}   
                 change_view("{$data['object']}/{$data['key']}/order/" + this.model.get("id")  )
                 {elseif $data['parent']=='store' }
                   change_view("orders/{$data['parent_key']}/" + this.model.get("id")  )
               {else}
                   change_view("{$data['parent']}/{$data['parent_key']}/order/" + this.model.get("id")  )
               
                 {/if}
             }
         },
         className: "link",
     })
 }, {
     name: "date",
     label: "{t}Date{/t}",
     editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
 }, {
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
         className: "link",
     })
 }, {
     name: "dispatch_state",
     label: "{t}Dispatch Status{/t}",
     editable: false,
 sortType: "toggle",
     cell: "html"
 }, {
     name: "payment_state",
     label: "{t}Payment State{/t}",
     editable: false,
 sortType: "toggle",
     cell: "html"
 }, {
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
