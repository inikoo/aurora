var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},{
    name: "formated_id",
    label: "{t}ID{/t}",
    editable: false,
         renderable: false,

     sortType: "toggle",
    {if $sort_key=='id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view('employee/' + +this.model.get("id"))
            }
        },
        className: "link"
       
})
   
},
{
    name: "code",
    label: "{t}Code{/t}",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
          
        }
    })
}
,{
    name: "payroll_id",
    label: "{t}Payroll ID{/t}",
    editable: false,
     sortType: "toggle",
    {if $sort_key=='id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.StringCell.extend({
      
       
})
   
},





{
    name: "name",
    label: "{t}Name{/t}",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
           
        }
    })
},
{
    name: "birthday",
    label: "{t}Date of birth{/t}",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
           
        }
    })
},

{
    name: "official_id",
    label: "{t}Official Id{/t}",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
           
        }
    })
},
{
    name: "email",
    label: "{t}Email{/t}",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
           
        }
    })
},
{
    name: "telephone",
    label: "{t}Contact number{/t}",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
           
        }
    })
},
{
    name: "next_of_kind",
    label: "{t}Next of kind{/t}",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
           
        }
    })
},


{
    name: "job_title",
    label: "{t}Job title{/t}",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
           
        }
    })
},
{
    name: "roles",
    label: "{t}Roles{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='roles'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
},
{
    name: "from",
    label: "{t}Working since{/t}",
    editable: false,
    sortType: "frtoggleom",
    {if $sort_key=='from'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
},
{
    name: "until",
    label: "{t}End work{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='until'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
},{
    name: "supervisors",
    label: "{t}Supervisor{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='supervisors'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
},{
    name: "user_active",
    label: "{t}System user{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='user_active'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
},
{
     name: "user_login",
    label: "{t}User login{/t}",
    editable: false,
    sortType: "toggle",
    {if $sort_key=='user_login'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
},
{
    name: "user_number_logins",
    label: "{t}Number logins{/t}",
    editable: false,
    sortType: "user_last_login",
    {if $sort_key=='user_last_login'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
},{
    name: "user_last_login",
    label: "{t}Last login{/t}",
    editable: false,
    sortType: "user_last_login",
    {if $sort_key=='user_last_login'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: "string"
},

]

function change_table_view(view,save_state){

    $('.view').removeClass('selected');
    $('#view_'+view).addClass('selected');
    
    grid.columns.findWhere({ name: 'payroll_id'} ).set("renderable", false)
     grid.columns.findWhere({ name: 'formated_id'} ).set("renderable", false)

    grid.columns.findWhere({ name: 'code'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'name'} ).set("renderable", false)
    
        grid.columns.findWhere({ name: 'birthday'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'official_id'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'email'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'telephone'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'next_of_kind'} ).set("renderable", false)

    
    
    grid.columns.findWhere({ name: 'job_title'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'roles'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'supervisors'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'from'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'until'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'user_login'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'user_active'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'user_last_login'} ).set("renderable", false)
        grid.columns.findWhere({ name: 'user_number_logins'} ).set("renderable", false)

    
   
    if(view=='overview'){
         grid.columns.findWhere({ name: 'formated_id'} ).set("renderable", true)

   grid.columns.findWhere({ name: 'payroll_id'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'code'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'job_title'} ).set("renderable", true)
    }else if(view=='personal_info'){
         grid.columns.findWhere({ name: 'formated_id'} ).set("renderable", true)

     grid.columns.findWhere({ name: 'name'} ).set("renderable", true)

  grid.columns.findWhere({ name: 'birthday'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'official_id'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'email'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'telephone'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'next_of_kind'} ).set("renderable", true)
     }else if(view=='employment'){
     
        grid.columns.findWhere({ name: 'code'} ).set("renderable", true)
              grid.columns.findWhere({ name: 'roles'} ).set("renderable", true)
              grid.columns.findWhere({ name: 'job_title'} ).set("renderable", true)
              
              grid.columns.findWhere({ name: 'supervisors'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'from'} ).set("renderable", true)
        
        {if $tipo=='exemployees'}
            grid.columns.findWhere({ name: 'until'} ).set("renderable", true)
            
        {/if}
              

    }else if(view=='system_user'){
           grid.columns.findWhere({ name: 'formated_id'} ).set("renderable", true)
             grid.columns.findWhere({ name: 'user_login'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'user_active'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'user_last_login'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'user_number_logins'} ).set("renderable", true)

    }
    
    if(save_state){
     var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view
   
    $.getJSON(request, function(data) {});
    }

}