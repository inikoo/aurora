var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},{
name: "access",
label: "",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
})

},
{
name: "status",
label: "",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
})

},

{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({

}),

}, {
name: "name",
label:"{t}Website name{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
} ),
}, {
name: "url",
label:"URL",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='url'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
} )
}, {
name: "online_users",
label:"{t}Online{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='online_users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},{
name: "users",
label:"{t}Registered{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},

{
name: "gsc_position",
label:'',
title:'{t}Google organic search average position (1 month){/t}',
html_label: '<i class="fab fa-google" style="color:#4885ed"></i> {t}Rank{/t}',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='gsc_position'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "gsc_impressions",
label:'',
title:'{t}Google organic search impressions (1 month){/t}',
html_label: '<i class="fab fa-google" style="color:#4885ed"></i> {t}Impressions{/t}',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='gsc_impressions'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "gsc_clicks",
label:'',
title:'{t}Google organic search impressions (1 month){/t}',
html_label: '<i class="fab fa-google" style="color:#4885ed"></i> {t}Clicks{/t}',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='gsc_clicks'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},

{
name: "gsc_ctr",
label:'',
title:'{t}Clickthrough rate (1 month){/t}',
html_label: '<i class="fab fa-google" style="color:#4885ed"></i> {t}CTR{/t}',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='gsc_ctr'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},

{
name: "online_webpages",
label:"{t}Online webpages{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='online_webpages'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "offline_webpages",
label:"{t}Offline webpages{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='offline_webpages'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]



function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'code'} ).set("renderable", false)
grid.columns.findWhere({ name: 'url'} ).set("renderable", false)
grid.columns.findWhere({ name: 'users'} ).set("renderable", false)

grid.columns.findWhere({ name: 'online_users'} ).set("renderable", false)

grid.columns.findWhere({ name: 'gsc_position'} ).set("renderable", false)
grid.columns.findWhere({ name: 'gsc_impressions'} ).set("renderable", false)
grid.columns.findWhere({ name: 'gsc_clicks'} ).set("renderable", false)
grid.columns.findWhere({ name: 'gsc_ctr'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'users'} ).set("renderable", true)

grid.columns.findWhere({ name: 'online_users'} ).set("renderable", true)
grid.columns.findWhere({ name: 'gsc_position'} ).set("renderable", true)
grid.columns.findWhere({ name: 'gsc_ctr'} ).set("renderable", true)
grid.columns.findWhere({ name: 'pages'} ).set("renderable", true)

}else if(view=='gsc'){
grid.columns.findWhere({ name: 'gsc_position'} ).set("renderable", true)
grid.columns.findWhere({ name: 'gsc_impressions'} ).set("renderable", true)
grid.columns.findWhere({ name: 'gsc_clicks'} ).set("renderable", true)
grid.columns.findWhere({ name: 'gsc_ctr'} ).set("renderable", true)

}else if(view=='ga'){

}else if(view=='webpages'){
grid.columns.findWhere({ name: 'online_webpages'} ).set("renderable", true)
grid.columns.findWhere({ name: 'offline_webpages'} ).set("renderable", true)


}else if(view=='users'){
grid.columns.findWhere({ name: 'online_users'} ).set("renderable", false)

grid.columns.findWhere({ name: 'users'} ).set("renderable", true)


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}