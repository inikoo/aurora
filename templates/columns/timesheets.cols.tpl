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
    name: "formated_id",
    label: "{t}Id{/t}",
    renderable: true,
    editable: false,
     sortType: "toggle",
    {if $sort_key=='formated_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view('timesheet/'+this.model.get("id"))
            }
        },
        className: "link"
       
})
   
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
    name: "clocked_hours",
    label: "{t}Clocked hours{/t}",
     sortType: "toggle",
     cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},



]

function change_table_view(view,save_state){}