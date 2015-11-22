var columns = [
 {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},
 {
    name: "staff_key",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},

{
    name: "alias",
    label: "{t}Staff{/t}",
    renderable: {if $data['object']=='employee'}false{else}true{/if},
    editable: false,
     sortType: "toggle",
    {if $sort_key=='alias'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view('employee/'+this.model.get("staff_key"))
            }
        },
        className: "link"
       
})
   
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
    name: "type",
    label: "{t}Type{/t}",
     sortType: "toggle",
    cell:'string'
},

{
    name: "source",
    label: "{t}Source{/t}",
     sortType: "toggle",
    cell:'string'
},

 {
    name: "action_type",
    label: "{t}Action{/t}",
     sortType: "toggle",
     cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},



]

function change_table_view(view,save_state){}