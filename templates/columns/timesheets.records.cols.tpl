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
    name: "used",
    label: "",
            renderable: {if $data['object']=='timesheet'}true{else}false{/if},

     editable: false,
     sortType: "toggle",
   
    cell: Backgrid.HtmlCell.extend({
      className: "width_20"
    })
    
},



{
    name: "formated_timesheet_id",
    label: "{t}Timesheet{/t}",
    editable: false,
    
        renderable: {if $data['object']=='timesheet'}false{else}true{/if},

    
     sortType: "toggle",
    {if $sort_key=='formated_timesheet_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                {if $data['object']==''}
                                change_view('hr/timesheet/'+this.model.get("timesheet_key"))

                {else}
                change_view('{$data['object']}/{$data['key']}/timesheet/'+this.model.get("timesheet_key"))
                 {/if}   
            }
        },
        className: "link"
       
})
   
},

{
    name: "alias",
    label: "{t}Staff{/t}",
    renderable: {if $data['object']=='employee' or  $data['object']=='timesheet' }false{else}true{/if},
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
            renderable: {if $data['object']=='timesheet'}false{else}true{/if},

   editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
    
    },
    
    {

    name: "time",
    label: "{t}Time{/t}",
            renderable: {if $data['object']!='timesheet'}false{else}true{/if},

   editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='time'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright width_100 padding_right_20"} ),
    headerCell: Backgrid.HeaderCell.extend({ className: "aright padding_right_20"})
    
    },
    
    {
    name: "type",
    label: "{t}Type{/t}",
     editable: false,
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({ className: " padding_left_20 "} ),
      headerCell: Backgrid.HeaderCell.extend({ className: "padding_left_20"})
  
    
},

{
    name: "source",
    label: "{t}Source{/t}",
     editable: false,
     sortType: "toggle",
    cell:'string'
},

 {
    name: "action_type",
    label: "{t}Action{/t}",
     editable: false,
     sortType: "toggle",
     cell: Backgrid.HtmlCell.extend({
      className: "width_200"
    })
},
{
    name: "ignored",
    renderable:false,
     editable: false,
    label: "{t}Ignored{/t}",
     sortType: "toggle",
    cell:'string'
},

]

function change_table_view(view,save_state){}