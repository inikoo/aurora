var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},

{
name: "type",
label: '{t}Type{/t}',
editable: false,
title: '{t}Online state{/t}',
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: ""
}),
headerCell: HeaderHtmlCell,

},



{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
},


{
name: "name",
label: "{t}Name{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
},




]
function change_table_view(view,save_state){}
