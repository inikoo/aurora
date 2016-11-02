var columns = [
{
name: "store_key",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.Cell.extend({
orderSeparator: '',
events: {
"click": function() {
change_view('customers/' + this.model.get("store_key") )
}
},
className: "link",

render: function () {
this.constructor.__super__.render.apply(this, arguments);


this.$el.empty();
var rawValue = this.model.get(this.column.get("name"));
var formattedValue = this.formatter.fromRaw(rawValue, this.model);
this.$el.append(formattedValue);
this.delegateEvents();


if(this.model.get('store_key')==''){
this.$el.removeClass('link');
}
return this;
}


})
}, {
name: "name",
label:"{t}Store Name{/t}",
editable: false,
cell: "string"
}, {
name: "contacts",
label:"{t}Total{/t}",
editable: false,
cell: "integer",
headerCell: integerHeaderCell
}, {
name: "new_contacts",
label:"{t}New{/t}",
editable: false,
cell: "integer",
headerCell: integerHeaderCell
}, {
name: "active_contacts",
label:"{t}Active{/t}",
editable: false,
cell: "integer",
headerCell: integerHeaderCell
}, {
name: "losing_contacts",
label:"{t}Loosing{/t}",
editable: false,
cell: "integer",
headerCell: integerHeaderCell
}, {
name: "lost_contacts",
label:"{t}Lost{/t}",
editable: false,
cell: "integer",
headerCell: integerHeaderCell
}


]
function change_table_view(view,save_state){}
