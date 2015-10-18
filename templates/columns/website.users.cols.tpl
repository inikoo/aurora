var columns = [
{
    name: "id",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "site_key",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "customer_key",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "user",
    label: "{t}User Handle{/t}",
    editable: false,
      cell: Backgrid.Cell.extend({
        events: {
            "click": function() {
                change_view( '{$data['object']}/{$data['key']}/user/' + this.model.get("id"))
            }
        },
        className: "link",  
        
    })
} , {
    name: "customer",
    label: "{t}Customer{/t}",
    editable: false,
 cell: Backgrid.Cell.extend({
        events: {
            "click": function() {
                change_view( 'customer/' + this.model.get("customer_key"))
            }
        },
        className: "link",  
        
    })
},{
    name: "sessions",
    label: "{t}Sessions{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell
},{
    name: "last_login",
    label: "{t}Last Login{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell
} 

]


function change_table_view(view, save_state) {}
