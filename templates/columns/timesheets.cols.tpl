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
    name: "formatted_id",
    label: "{t}Id{/t}",
    renderable: true,
    editable: false,
     sortType: "toggle",
    {if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.HtmlCell.extend({
        events: {
            "click": function() {
                 {if $data['object']==''}
                                change_view('hr/timesheet/'+this.model.get("id"))

                {else}
                change_view('{$data['object']}/{$data['key']}/timesheet/'+this.model.get("id"))
                 {/if}   
            }
        },
        className: "link width_20"
       
})
   
},

{
    name: "alert",
    label: "",
    renderable: true,
    editable: false,
     sortType: "toggle",
    {if $sort_key=='alerts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.HtmlCell.extend({
       
        className: "width_20"
       
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
    name: "clocking_records",
    renderable:false,
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