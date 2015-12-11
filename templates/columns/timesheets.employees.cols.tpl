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
    name: "name",
    label: "{t}Employee{/t}",
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

    name: "days",
    label: "{t}Days{/t}",
   editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ 
    
     events: {
            "click": function() {
                change_view('timesheets/day/'+this.model.get("date_key"))
            }
        },
    
    className: "aright link"} 
    ),
    headerCell: integerHeaderCell
    
    },
  
 {
    name: "clocking_records",
    label: "{t}Clockings{/t}",
     sortType: "toggle",
     cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},
 {
    name: "clocked_time",
    label: "{t}Clocked{/t}",
     sortType: "toggle",
     cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},
 {
    name: "unpaid_overtime",
    label: "{t}Unpaid overtime{/t}",
     sortType: "toggle",
     cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},
{
    name: "breaks_time",
    label: "{t}Breaks{/t}",
     sortType: "toggle",
     cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},
 {
    name: "work_time_hours",
    label: "{t}Paid time{/t}",
     sortType: "toggle",
     cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},



 {
    name: "paid_overtime",
    label: "{t}Paid overtime{/t}",
     sortType: "toggle",
     cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},

 {
    name: "worked_time",
    label: "{t}Worked{/t}",
     sortType: "toggle",
     cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},



]

function change_table_view(view,save_state){}