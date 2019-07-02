var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "user",
label: "{t}User Handle{/t}",
editable: false,
cell: Backgrid.Cell.extend({


})
} , {
name: "customer",
label: "{t}Customer{/t}",
editable: false,
cell: Backgrid.Cell.extend({

})
},{
name: "sessions",
label: "{t}Sessions{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},{
name: "last_login",
label: "{t}Last Login{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]


function change_table_view(view, save_state) {

}
