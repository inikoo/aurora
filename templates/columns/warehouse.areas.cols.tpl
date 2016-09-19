var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},{
    name: "code",
    label: "{t}Code{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.HtmlCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
            }
        },
        className: ""
       
})
   
},{
    name: "name",
    label: "{t}Name{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.HtmlCell.extend({
        orderSeparator: '',
        events: {
          
        },
       
})
   
}
, {
    name: "locations",
    label: "{t}Locations{/t}",
     defautOrder:1,
    editable: false,
    sortType: "toggle",
    {if $sort_key=='locations'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}
, {
    name: "parts",
    label: "{t}Parts{/t}",
     defautOrder:1,
    editable: false,
    sortType: "toggle",
    {if $sort_key=='parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}

]

function change_table_view(view,save_state){}