var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},

 {
    name: "date",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},

{
    name: "day",
    label: "{t}Date{/t}",
        renderable:{if $data['tab']=='inventory.stock.history.daily'}true{else}false{/if},

    editable: false,
     cell: Backgrid.Cell.extend({
     events: {
            "click": function() {
                change_view('inventory/stock_history/day/' + this.model.get("date"))
            }
        },
              className: "link aright width_150"  
              
              
      
              
                 
    }),
         headerCell: integerHeaderCell,
     sortType: "toggle",
}, {
    name: "year",
    label: "{t}Year{/t}",
    editable: false,
    renderable:{if $data['tab']=='inventory.stock.history.annually'}true{else}false{/if},
     cell: Backgrid.Cell.extend({
              className: "aright width_150"     
    }),
         headerCell: integerHeaderCell,
     sortType: "toggle",
}, {
    name: "month_year",
    label: "{t}Month{/t}",
    editable: false,
    renderable:{if $data['tab']=='inventory.stock.history.monthy'}true{else}false{/if},
     cell: Backgrid.Cell.extend({
              className: "aright width_150"     
    }),
         headerCell: integerHeaderCell,
     sortType: "toggle",
}, {
    name: "week_year",
    label: "{t}Week{/t}",
    editable: false,
    renderable:{if $data['tab']=='inventory.stock.history.weekly'}true{else}false{/if},
     cell: Backgrid.Cell.extend({
              className: "aright width_150"     
    }),
         headerCell: integerHeaderCell,
     sortType: "toggle",
},


{
    name: "parts",
    label: "{t}Parts{/t}",
     editable: false,
     sortable: false,
    
    cell: Backgrid.HtmlCell.extend({
        className: "aright width_150"     
       
}),
            headerCell: integerHeaderCell,

},
{
    name: "locations",
    label: "{t}Locations{/t}",
     editable: false,
     sortable: false,
    
    cell: Backgrid.HtmlCell.extend({
        className: "aright width_150"     
       
}),
            headerCell: integerHeaderCell,

},
{
    name: "value",
    label: "{t}Value{/t}",
   editable: false,
 sortable: false,
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},
{
    name: "commercial_value",
    label: "{t}Commercial Value{/t}",
   editable: false,
 sortable: false,
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){}