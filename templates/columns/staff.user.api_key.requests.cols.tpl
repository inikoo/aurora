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

    name: "date",
    label: "{t}Date{/t}",
   editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
    
    },
    {
    name: "ip",
    label: "{t}IP{/t}",
     sortType: "toggle",
    cell:'string'
},
 {
    name: "method",
    label: "{t}HTTP Method{/t}",
     sortType: "toggle",
    cell:'string'
},

{
    name: "formatted_id",
    label: "{t}ID{/t}",
    renderable: {if $data['object']=='api_key'}false{else}true{/if},
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
   
},
 {
    name: "scope",
    label: "{t}Scope{/t}",
     sortType: "toggle",
    cell:'string'
},
{
    name: "response",
    label: "{t}Response{/t}",
     sortType: "toggle",
    cell:'string'
},
{
    name: "response_code",
    label: "{t}Notes{/t}",
     sortType: "toggle",
    cell:'string'
},

 


]

function change_table_view(view,save_state){}