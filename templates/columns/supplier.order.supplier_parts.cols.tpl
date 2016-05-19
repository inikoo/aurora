var columns = [{
    name: "id",
    label: "",
    editable: false,
    renderable: false,
    cell: "string",

}, {
    name: "supplier_code",
    label: "{t}Supplier{/t}",
    editable: false,
      renderable: {if $data['object']=='supplier' }false{else}true{/if},
    sortType: "toggle",

    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view('/supplier/' + this.model.get("supplier_key"))
            }
        },
        className: "link"

    })

}, {
    name: "status",
    label: "",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({
        className: "width_20"
    })

}, {
    name: "reference",
    label: "{t}Reference{/t}",
    editable: false,
    sortType: "toggle",

    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view('/supplier/' + this.model.get("supplier_key") + '/part/' + this.model.get("id"))
            }
        },
        className: "link"

    })

}


, {
    name: "part_reference",
    label: "{t}Part{/t}",
    editable: false,
    sortType: "toggle",

    cell: Backgrid.StringCell.extend({
        events: {
            "click": function() {
                change_view('part/' + this.model.get("part_sku"))
            }
        },
        className: "link"

    })

}, {
    name: "description",
    label: "{t}Description{/t}",
    editable: false,
    sortType: "toggle",

    cell: Backgrid.StringCell.extend({


    })

},
{
    name: "part_description",
    label: "{t}Part{/t}",
    editable: false,
    sortType: "toggle",

    cell: Backgrid.HtmlCell.extend({


    })

}
,
{
    name: "stock",
    label: "{t}Stock{/t}",
   editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
}
, {
    name: "cost",
    label: "{t}Cost{/t}",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({})

}, {
    name: "packing",
    label: "{t}Packing{/t}",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.HtmlCell.extend({})

}

]


function change_table_view(view,save_state){

    $('.view').removeClass('selected');
    $('#view_'+view).addClass('selected');
    
  //  grid.columns.findWhere({ name: 'formatted_sku'} ).set("renderable", false)
     grid.columns.findWhere({ name: 'part_description'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'description'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'part_reference'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'stock'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'cost'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'packing'} ).set("renderable", false)

    if(view=='overview'){
               grid.columns.findWhere({ name: 'part_description'} ).set("renderable", true)
               grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
               grid.columns.findWhere({ name: 'cost'} ).set("renderable", true)
               grid.columns.findWhere({ name: 'packing'} ).set("renderable", true)

    }else if(view=='parts'){
            grid.columns.findWhere({ name: 'part_reference'} ).set("renderable", true)

        grid.columns.findWhere({ name: 'description'} ).set("renderable", true)
            grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)

    }else if(view=='reorder'){
        grid.columns.findWhere({ name: 'part_reference'} ).set("renderable", true)
                 grid.columns.findWhere({ name: 'status'} ).set("renderable", true)

                    grid.columns.findWhere({ name: 'packing'} ).set("renderable", true)

    }
    
    if(save_state){
     var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view
   
    $.getJSON(request, function(data) {});
    }

}