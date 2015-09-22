 [{
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
     name: "number",
     label: "Number",
     editable: false,

     cell: "string"
 }, {
     name: "date",
     label: "Date",
     editable: false,
     cell: "string"
 }, {
     name: "customer",
     label: "Customer",
     editable: false,
     cell: Backgrid.StringCell.extend({
         orderSeparator: '',
         events: {
             "click": function() {
                 change_view('customer/' + this.model.get("customer_key"))
             }
         },
         className: "link",
     })
 }, {
     name: "total_amount",
     label: "Total",
     editable: false,
     cell: "html"
 }]
