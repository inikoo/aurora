var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "staff_key",
label: "",
editable: false,
renderable: false,
cell: "string",

},


{
name: "name",
label: "{t}Employee{/t}",
renderable: {if $data['object']=='employee'}false{else}true{/if},
editable: false,
sortType: "toggle",
{if $sort_key=='alias'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view('employee/'+this.model.get("staff_key"))
}
},
className: "link"

})

},

{

name: "days",
label: "{t}Days{/t}",
editable: false,
defautOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({

events: {
"click": function() {
change_view('timesheets/day/'+this.model.get("date_key"))
}
},

className: "aright link"}
),
headerCell: integerHeaderCell

},

{
name: "clocking_records",
label: "{t}Clockings{/t}",
sortType: "toggle",
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time",
label: "{t}Clocked{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "unpaid_overtime",
label: "{t}nOKD overtime{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "breaks_time",
label: "{t}Breaks{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_hours",
label: "{t}Paid time{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "paid_overtime",
label: "{t}OKD overtime{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "worked_time",
label: "{t}Worked{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time_monday",
label: "{t}Monday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time_tuesday",
label: "{t}Tuesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time_wednesday",
label: "{t}Wednesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time_thursday",
label: "{t}Thursday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time_friday",
label: "{t}Friday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time_saturday",
label: "{t}Saturday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time_sunday",
label: "{t}Sunday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time_workweek",
label: "{t}Work week{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clocked_time_weekend",
label: "{t}Weekend{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_monday",
label: "{t}Monday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_tuesday",
label: "{t}Tuesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_wednesday",
label: "{t}Wednesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_thursday",
label: "{t}Thursday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_friday",
label: "{t}Friday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_saturday",
label: "{t}Saturday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_sunday",
label: "{t}Sunday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_workweek",
label: "{t}Work week{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "work_time_weekend",
label: "{t}Weekend{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "paid_overtime_monday",
label: "{t}Monday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "paid_overtime_tuesday",
label: "{t}Tuesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "paid_overtime_wednesday",
label: "{t}Wednesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "paid_overtime_thursday",
label: "{t}Thursday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "paid_overtime_friday",
label: "{t}Friday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "paid_overtime_saturday",
label: "{t}Saturday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "paid_overtime_sunday",
label: "{t}Sunday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "paid_overtime_workweek",
label: "{t}Work week{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "paid_overtime_weekend",
label: "{t}Weekend{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "unpaid_overtime_monday",
label: "{t}Monday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "unpaid_overtime_tuesday",
label: "{t}Tuesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "unpaid_overtime_wednesday",
label: "{t}Wednesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "unpaid_overtime_thursday",
label: "{t}Thursday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "unpaid_overtime_friday",
label: "{t}Friday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "unpaid_overtime_saturday",
label: "{t}Saturday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "unpaid_overtime_sunday",
label: "{t}Sunday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "unpaid_overtime_workweek",
label: "{t}Work week{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "unpaid_overtime_weekend",
label: "{t}Weekend{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "worked_time_monday",
label: "{t}Monday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "worked_time_tuesday",
label: "{t}Tuesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "worked_time_wednesday",
label: "{t}Wednesday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "worked_time_thursday",
label: "{t}Thursday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "worked_time_friday",
label: "{t}Friday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "worked_time_saturday",
label: "{t}Saturday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "worked_time_sunday",
label: "{t}Sunday{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "worked_time_workweek",
label: "{t}Work week{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "worked_time_weekend",
label: "{t}Weekend{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'days'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocking_records'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocked_time'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unpaid_overtime'} ).set("renderable", false)
grid.columns.findWhere({ name: 'breaks_time'} ).set("renderable", false)
grid.columns.findWhere({ name: 'work_time_hours'} ).set("renderable", false)
grid.columns.findWhere({ name: 'paid_overtime'} ).set("renderable", false)
grid.columns.findWhere({ name: 'worked_time'} ).set("renderable", false)

grid.columns.findWhere({ name: 'worked_time_monday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'worked_time_tuesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'worked_time_wednesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'worked_time_thursday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'worked_time_friday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'worked_time_saturday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'worked_time_sunday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'worked_time_workweek'} ).set("renderable", false)
grid.columns.findWhere({ name: 'worked_time_weekend'} ).set("renderable", false)

grid.columns.findWhere({ name: 'clocked_time_monday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocked_time_tuesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocked_time_wednesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocked_time_thursday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocked_time_friday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocked_time_saturday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocked_time_sunday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocked_time_workweek'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocked_time_weekend'} ).set("renderable", false)

grid.columns.findWhere({ name: 'unpaid_overtime_monday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unpaid_overtime_tuesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unpaid_overtime_wednesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unpaid_overtime_thursday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unpaid_overtime_friday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unpaid_overtime_saturday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unpaid_overtime_sunday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unpaid_overtime_workweek'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unpaid_overtime_weekend'} ).set("renderable", false)


grid.columns.findWhere({ name: 'paid_overtime_monday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'paid_overtime_tuesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'paid_overtime_wednesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'paid_overtime_thursday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'paid_overtime_friday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'paid_overtime_saturday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'paid_overtime_sunday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'paid_overtime_workweek'} ).set("renderable", false)
grid.columns.findWhere({ name: 'paid_overtime_weekend'} ).set("renderable", false)


grid.columns.findWhere({ name: 'work_time_monday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'work_time_tuesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'work_time_wednesday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'work_time_thursday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'work_time_friday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'work_time_saturday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'work_time_sunday'} ).set("renderable", false)
grid.columns.findWhere({ name: 'work_time_workweek'} ).set("renderable", false)
grid.columns.findWhere({ name: 'work_time_weekend'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'days'} ).set("renderable", false)
grid.columns.findWhere({ name: 'clocking_records'} ).set("renderable", true)
grid.columns.findWhere({ name: 'clocked_time'} ).set("renderable", true)
grid.columns.findWhere({ name: 'unpaid_overtime'} ).set("renderable", true)
grid.columns.findWhere({ name: 'breaks_time'} ).set("renderable", true)
grid.columns.findWhere({ name: 'work_time_hours'} ).set("renderable", true)
grid.columns.findWhere({ name: 'paid_overtime'} ).set("renderable", true)
grid.columns.findWhere({ name: 'worked_time'} ).set("renderable", true)

}else if(view=='worked_per_day'){
grid.columns.findWhere({ name: 'worked_time_monday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'worked_time_tuesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'worked_time_wednesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'worked_time_thursday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'worked_time_friday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'worked_time_saturday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'worked_time_sunday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'worked_time_workweek'} ).set("renderable", true)
grid.columns.findWhere({ name: 'worked_time_weekend'} ).set("renderable", true)

}else if(view=='clocked_per_day'){
grid.columns.findWhere({ name: 'clocked_time_monday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'clocked_time_tuesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'clocked_time_wednesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'clocked_time_thursday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'clocked_time_friday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'clocked_time_saturday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'clocked_time_sunday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'clocked_time_workweek'} ).set("renderable", true)
grid.columns.findWhere({ name: 'clocked_time_weekend'} ).set("renderable", true)

}else if(view=='unpaid_overtime_per_day'){
grid.columns.findWhere({ name: 'unpaid_overtime_monday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'unpaid_overtime_tuesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'unpaid_overtime_wednesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'unpaid_overtime_thursday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'unpaid_overtime_friday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'unpaid_overtime_saturday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'unpaid_overtime_sunday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'unpaid_overtime_workweek'} ).set("renderable", true)
grid.columns.findWhere({ name: 'unpaid_overtime_weekend'} ).set("renderable", true)

}else if(view=='paid_overtime_per_day'){
grid.columns.findWhere({ name: 'paid_overtime_monday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'paid_overtime_tuesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'paid_overtime_wednesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'paid_overtime_thursday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'paid_overtime_friday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'paid_overtime_saturday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'paid_overtime_sunday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'paid_overtime_workweek'} ).set("renderable", true)
grid.columns.findWhere({ name: 'paid_overtime_weekend'} ).set("renderable", true)

}else if(view=='work_time_per_day'){
grid.columns.findWhere({ name: 'work_time_monday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'work_time_tuesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'work_time_wednesday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'work_time_thursday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'work_time_friday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'work_time_saturday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'work_time_sunday'} ).set("renderable", true)
grid.columns.findWhere({ name: 'work_time_workweek'} ).set("renderable", true)
grid.columns.findWhere({ name: 'work_time_weekend'} ).set("renderable", true)

}
if (save_state) {
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}


}