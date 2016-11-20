var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},
{
name: "scope_icon",
label:"",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='scope'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className:'width_20 align_center'

})
},
{
name: "device",
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({
className:'width_20 align_center'

})
},

{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({})
},

{
name: "type",
label: "{t}Type{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({})
},
{
name: "scope",
label: "{t}Category{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({})
},

{
name: "object",
label: "{t}Object{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({})
},

 {
name: "web_pages",
label:"{t}Web pages{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='webpages'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "versions",
label:"{t}Versions{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='versions'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]
function change_table_view(view,save_state){}
