$columans = [{
    name: "id",
    label: "ID",
    editable: false,
    cell: Backgrid.IntegerCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view('customers/' + this.model.get("store_key") + '/' + this.$el.html())
            }
        },
        className: "link"
    })
}, {
    name: "store_key",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "name",
    label: "Name",
    cell: Backgrid.StringCell.extend({
        events: {
            "dblclick": "enterEditMode"
        }
    })
}, {
    name: "location",
    label: "Location",
    editable: false,
    cell: "html"
}, {
    name: "contact_since",
    label: "Since",
    editable: false,
    cell: "html"
}, {
    name: "activity",
    label: "Status",
    editable: false,
    cell: "string"
}, {
    name: "last_order",
    label: "Last Order",
    editable: false,
    cell: "string"
}, {
    name: "invoices",
    label: "Invoices",
    editable: false,
    cell: "integer"
}]
