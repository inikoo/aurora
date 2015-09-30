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
                change_view('orders/' + this.model.get("store_key") )
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
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
}, {
    name: "orders",
    label:"{t}Orders{/t}",
        editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

}, {
    name: "invoices",
    label:"{t}Invoices{/t}",
       editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

}, {
    name: "delivery_notes",
    label:"{t}Delivery Notes{/t}",
        editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

}, {
    name: "payments",
    label:"{t}Payments{/t}",
        editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell

}

]
function change_table_view(view,save_state){}
