var columns = [
 {
    name: "id",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}
, {
    name: "access",
    label: "",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({
        className: "width_20"
    })

}
, {
    name: "code",
    label: "{t}Store{/t}",
    editable: false,
     cell: Backgrid.Cell.extend({
        events: {
            "click": function() {
                change_view('store/' + this.model.get("store_key") )
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
    name: "family",
    label:"{t}Family{/t}",
    editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({  }),
}

]
function change_table_view(view,save_state){}
