var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},
 {
    name: "user_key",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},
{
    name: "formatted_id",
    label: "{t}ID{/t}",
    editable: false,
     sortType: "toggle",
    {if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view('account/user/'+this.model.get("user_key")+'/api_key/' +this.model.get("id"))
            }
        },
        className: "link"
       
})
   
}, {
    name: "active",
    label: "{t}Active{/t}",
     sortType: "toggle",
    cell:'string'
},
 {
    name: "scope",
    label: "{t}Scope{/t}",
     sortType: "toggle",
    cell:'string'
},

 {
    name: "from",
    label: "{t}Created{/t}",
   editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='from'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
    
    },


]

function change_table_view(view,save_state){}