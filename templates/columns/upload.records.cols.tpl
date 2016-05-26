var columns = [{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


}
,{
    name: "row",
    label: "{t}Row{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
     
     }),
   
},{
    name: "state",
    label: "{t}State{/t}",
    editable: false,
    cell: Backgrid.HtmlCell.extend({
    
     })
    
}





]


function change_table_view(view, save_state) {}
