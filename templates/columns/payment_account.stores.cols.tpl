{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2017 at 12:09:51 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
*/*}


var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
}
, {
name: "access",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

}
, {
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
}, {
name: "name",
label: "{t}Name{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
},


{
name: "accepted",
label: "{t}Accepted{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
},
 {
name: "website",
label: "{t}Website{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
},


{
name: "shown_in_website",
label: "{t}Show in basket{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
},



]
function change_table_view(view,save_state){}
