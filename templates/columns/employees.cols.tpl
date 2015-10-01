var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},{
    name: "formated_id",
    label: "{t}ID{/t}",
    editable: false,
     sortType: "toggle",
    {if $sort_key=='id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view('employee/' + +this.model.get("id"))
            }
        },
        className: "link"
       
})
   
},
 {
    name: "name",
    label: "{t}Name{/t}",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view('employee/' + +this.model.get("id"))
            }
        }
    })
}, {
    name: "position",
    label: "{t}Position{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='groups'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
}

]

function change_table_view(view,save_state){}