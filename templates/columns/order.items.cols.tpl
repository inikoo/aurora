var columns = [
{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


},{
    name: "product_pid",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


},{
    name: "code",
    label: "{t}Code{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({
      events: {
            "click": function() {
                change_view("order/{$data['key']}/product/"+this.model.get("product_pid"))
            }
        },
      className: "asset_code"
     }),
},{
    name: "description",
    label: "{t}Description{/t}",
    editable: false,
     cell: "html"
    
}, {
    name: "quantity",
    label: "{t}Quantity{/t}",
    editable: true,
    cell: "string"
}, {
    name: "net",
    label: "{t}Net{/t}",
    editable: true,
    cell: "string"
}
]


function change_table_view(view, save_state) {}
