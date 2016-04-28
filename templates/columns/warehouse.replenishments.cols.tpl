var columns = [
{
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

}, 
{
    name: "location_key",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},{
    name: "location",
    label: "{t}Location{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
       
        events: {
            "click": function() {
                 change_view('location/' + this.model.get("locate_key"))
            }
        },
        className: "link",
       initialize: function () {
        Backgrid.Cell.prototype.initialize.apply(this, arguments);
    },

    render: function () {
        this.$el.empty();
        var rawValue = this.model.get(this.column.get("name"));
        var formattedValue = this.formatter.fromRaw(rawValue, this.model);
        this.$el.append(formattedValue);
        this.delegateEvents();
        return this;
    }
       
})
   
},{
    name: "part",
    label: "{t}Part{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                 change_view('part/' + this.model.get("part_sku"))
            }
        },
        className: "link"
       
})
   
}, {
    name: "stock",
    label: "{t}Stock data{/t}",
    sortType: "toggle",
    {if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    editable: false,
    
    cell: "html"
}, {
    name: "pl_data",
    label: "{t}Part location data{/t}",
    sortType: "toggle",
    {if $sort_key=='pl_data'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    editable: false,
    headerCell: integerHeaderCell,

    cell: "rhtml"
}

]

function change_table_view(view,save_state){}