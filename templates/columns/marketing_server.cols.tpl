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
change_view('store/' + this.model.get("id") )
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
} ,{
name: "name",
label:"{t}Store Name{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({  }),
},
{
name: "campaigns",
label: "{t}Campaigns{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='campaigns'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.Cell.extend({
orderSeparator: '',
events: {
"click": function() {
change_view('campaigns/' + this.model.get("id") )
}
},
className: "link aright",


}),
headerCell: integerHeaderCell

},
{
name: "deals",
label: "{t}Offers{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='deals'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.Cell.extend({
orderSeparator: '',
events: {
"click": function() {
change_view('deals/' + this.model.get("id") )
}
},
className: "link aright",


}),
headerCell: integerHeaderCell

},
]
function change_table_view(view,save_state){}
