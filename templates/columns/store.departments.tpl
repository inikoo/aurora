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
     name: "department_key",
     label: "",
     editable: false,
     renderable: false,
     cell: "string"
 },
  {
     name: "code",
     label: "Code",
     editable: false,
     cell: Backgrid.StringCell.extend({
         orderSeparator: '',
         events: {
             "click": function() {
                 change_view('department/' + this.model.get("department_key"))
             }
         },
         className: "link",
     })
 }, {
     name: "name",
     label: "Name",
     editable: false,
     cell: "string"
 }]
