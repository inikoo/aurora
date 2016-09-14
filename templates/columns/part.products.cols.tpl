var columns = [{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


}, {
    name: "store_key",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "store",
    label: "{t}Store{/t}",
     renderable: {if ($data['parent']=='account' or $data['parent']=='warehouse' ) }true{else}false{/if},
    editable: false,
      sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view('products/'+this.model.get("store_key"))
            }
        },
        className: "link width_150",
    })
},



 {
    name: "code",
    label: "{t}Code{/t}   ",
    editable: false,
      sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view( 'part/{$data['key']}/product/' + this.model.get("id"))
            }
        },
        className: "link width_150",
    })
}, {
    name: "name",
    label: "{t}Name{/t}",
    editable: false,
      sortType: "toggle",
    cell: "string"
},
 {
    name: "price",
    label: "{t}Price{/t}",
    editable: false,
      sortType: "toggle",
     cell: Backgrid.StringCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
},
 {
    name: "web_state",
    label: "{t}Web state{/t}",
    editable: false,
      sortType: "toggle",
     cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
}

]


function change_table_view(view, save_state) {}
