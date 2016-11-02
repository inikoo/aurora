var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "handle",
label: "{t}Handle{/t}",
renderable: {if $data['object']=='user'}false{else}true{/if},
editable: false,
sortType: "toggle",
{if $sort_key=='handle'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({
orderSeparator: '',
events: {
"click": function() {
change_view('user/staff/' +this.model.get("id"))
}
},
className: "link"

})

},
{
name: "ip",
label: "IP",
sortType: "toggle",
cell:'string'
}, {
name: "login_date",
label: "{t}Login date{/t}",
editable: false,
defautOrder:1,
sortType: "toggle",
{if $sort_key=='login_date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "logout_date",
label: "{t}Logout date{/t}",
editable: false,
defautOrder:1,
sortType: "toggle",
{if $sort_key=='logout_date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]

function change_table_view(view,save_state){}