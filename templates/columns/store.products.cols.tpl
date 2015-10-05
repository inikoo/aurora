var columns = [{
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
}, {
    name: "code",
    label: "Code",
    editable: false,
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view('product/' + this.model.get("id"))
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


function change_table_view(view, save_state) {}
