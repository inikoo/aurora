var columns = [
{
name: "id",
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
{if $data['parent']=='store'}
    change_view('/payment_account/{$data['parent_key']}/' + this.model.get("id") )

{else}
    change_view('{$data['object']}/{$data['key']}/payment_account/' + this.model.get("id") )
{/if}
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


if(this.model.get('id')==''){
this.$el.removeClass('link');
}
return this;
}


})
}, {
name: "name",
label:"{t}Name{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.StringCell.extend({  }),
}, {
name: "transactions",
label: "{t}Transactions{/t}",
defautOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='transactions'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "payments",
label: "{t}Payments{/t}",
defautOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='payments'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "refunds",
label: "{t}Refunds{/t}",
defautOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "balance",
label: "{t}Balance{/t}",
defautOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='balance'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}


]
function change_table_view(view,save_state){}
