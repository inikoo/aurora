var columns = [
 {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},

{
    name: "state",
    label: "",
    renderable: false,
    editable: false,
     sortType: "toggle",
    {if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.HtmlCell.extend({
      
        className: " width_20"
       
})
   
},
{
    name: "row",
    label: "{t}Row{/t}",
    renderable: true,
    editable: false,
     sortType: "toggle",
    {if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.HtmlCell.extend({
      
        className: "width_50"
       
})
   
},



{

    name: "date",
    label: "{t}Date{/t}",
   editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright width_200 padding_right_20"} ),
    headerCell: integerHeaderCell
    
    },
  
 {
    name: "msg",
    renderable:true,
     editable: false,
    label: "{t}Result{/t}",
     sortType: "toggle",
     cell: Backgrid.HtmlCell.extend({ className: "padding_left_20"} ),
},




]

function change_table_view(view,save_state){}