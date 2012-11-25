var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var select_category_head_from_list_action
var dialog_category_heads_list;



function select_subject_from_list(oArgs){
alert("selection subject from list")
}

function select_category_head_from_list(oArgs){

category_key=tables.table5.getRecord(oArgs.target).getData('key')
request='ar_edit_categories.php?tipo='+select_category_head_from_list_action+'&category_key='+category_key+'&subject_key='+select_category_head_from_list_subject_key
YAHOO.util.Connect.asyncRequest(
                    'GET',
                request, {
                           success: function (o) {
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200) {
                			var table=tables.table3;
 							var datasource=tables.dataSource3;
                			datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
 							dialog_category_heads_list.hide()
 							Dom.get('number_category_subjects_not_assigned').innerHTML=r.number_category_subjects_not_assigned
 							Dom.get('number_category_subjects_assigned').innerHTML=r.number_category_subjects_assigned

 							
 							
                        }else {
                            alert(r.msg);
                        }
                    },
					failure: function (fail) {
                        alert(fail.statusText);
                    },scope:this});


}



var onCellClick = function(oArgs) {
    var target = oArgs.target,
    column = this.getColumn(target),
    record = this.getRecord(target);

    var recordIndex = this.getRecordIndex(record);
   //alert(column.object); return;
    switch (column.action) {
case 'assign_here':



request='ar_edit_categories.php?tipo=associate_subject_to_category&category_key='+Dom.get('category_key').value+'&subject_key='+record.getData('subject_key')

YAHOO.util.Connect.asyncRequest(
                    'GET',
                request, {
                           success: function (o) {
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200) {
                			var table=tables.table3;
 							var datasource=tables.dataSource3;
                			datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
 							dialog_category_heads_list.hide()
 							Dom.get('number_category_subjects_not_assigned').innerHTML=r.number_category_subjects_not_assigned
 							Dom.get('number_category_subjects_assigned').innerHTML=r.number_category_subjects_assigned

                        }else {
                            alert(r.msg);
                        }
                    },
					failure: function (fail) {
                        alert(fail.statusText);
                    },scope:this});




break;
case 'assign':
   select_category_head_from_list_action='associate_subject_to_category';
   select_category_head_from_list_subject_key=record.getData('subject_key');
   
   region1 = Dom.getRegion(target); 
   
    region2 = Dom.getRegion('dialog_category_heads_list'); 
	var pos =[region1.right-region2.width,region1.bottom]
	
	Dom.setXY('dialog_category_heads_list', pos);

dialog_category_heads_list.show()
   
   
  
   break;
    case 'delete':
        if (record.getData('delete')!='') {

            var delete_type=record.getData('delete_type');
if(delete_type== undefined)
    delete_type='delete';


            if (confirm('Are you sure, you want to '+delete_type+' this row?')) {
        
		    
     		         ar_file='ar_edit_categories.php';
     		 


         //alert(ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record))
                YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record), {
              
               success: function (o) {
                 //   alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200 && r.action=='deleted') {

                            this.deleteRow(target);


 
                var table=this;
                var datasource=this.getDataSource();
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
 
                    post_delete_actions(column.object);
 
//alert(datatable)


                        } else if (r.state == 200 && r.action=='discontinued') {

                            var data = record.getData();
                            //data['delete']=r.delete;
                            data['delete_type']=r.delete_type;
                            this.updateRow(recordIndex,data);
                        } else {
                            alert(r.msg);
                        }
                    },
failure: function (fail) {
                        alert(fail.statusText);
                    },
scope:this
                }
                );
            }
        }
        break;
case 'dialog':
show_cell_dialog(this,oArgs);
break;


case 'edit':

		if(column.object=='post_to_send' )
			ar_file='ar_edit_contacts.php';



        //alert(ar_file+'?tipo=edit_'+column.object + myBuildUrl(this,record))
                YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo=edit_'+column.object + myBuildUrl(this,record), {
                success: function (o) {
                    //alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200 && r.action=='edited') {
				var table=this;
				var datasource=this.getDataSource();
				datasource.sendRequest('',table.onDataReturnInitializeTable, table);   


                        } else {
                            alert(r.msg);
                        }
                    },
failure: function (fail) {
                        alert(fail.statusText);
                    },
scope:this
                }
                );


break;



    default:

        this.onEventShowCellEditor(oArgs);
        break;
    }
};



function cancel_new_category(){
Dom.get('new_category_name').value='';
Dom.setStyle('new_category_no_name_msg','display','none')

dialog_new_category.hide();

}

function dialog_new_category_show(){

dialog_new_category.show();
Dom.get('new_category_name').focus();
}

function post_create_actions(){

}

function save_new_category(){

var name=Dom.get("new_category_name").value;
var store_key=Dom.get("new_category_store_key").value;
var warehouse_key=Dom.get("new_category_warehouse_key").value;

var parent_key=Dom.get("new_category_parent_key").value;
var subject=Dom.get("new_category_subject").value;

if(name==''){
Dom.setStyle('new_category_no_name_msg','display','')
return;
}else{
Dom.setStyle('new_category_no_name_msg','display','none')

}

var ar_file='ar_edit_categories.php'; 
    	var request='tipo=new_category&subject='+subject+'&name='+name+'&store_key='+store_key+'&warehouse_key='+warehouse_key+'&parent_key='+parent_key;

//alert(request);
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
					    
					   
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
							/*
							table_id=1;
							var table=tables['table'+table_id];
    						var datasource=tables['dataSource'+table_id];
    						var request='&table_id='+table_id;
    						datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     
					
							table_id=2;
							var table=tables['table'+table_id];
							if(table!= undefined){
								var datasource=tables['dataSource'+table_id];
    							var request='&table_id=_history';
    							datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   
							*/
							post_create_actions()
							cancel_new_category()
				
				}
						else{
							alert(r.msg)
						}
						
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  
}
YAHOO.util.Event.onContentReady("dialog_new_category", function () {
	dialog_new_category = new YAHOO.widget.Dialog("dialog_new_category", {context:["new_category","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});

dialog_new_category.render();

Event.addListener("new_category", "click", dialog_new_category_show,true);
Event.addListener("new_category_cancel", "click", cancel_new_category , true);
Event.addListener("new_category_save", "click", save_new_category , true);
	
    });
    


var total_parts_checked=0;




function validate_name(query){
 validate_general('category','name',unescape(query));
}
function validate_label(query){
 validate_general('category','label',unescape(query));
}
function validate_subcategory_name(query){
 validate_general('subcategory','subcategory_name',unescape(query));
}
function reset_new_category(){
 reset_edit_general('category');
}
function reset_edit_category(){
    reset_edit_general('category')
}
function save_edit_subcategory(){
    save_edit_general('subcategory');
}
function reset_edit_subcategory(){
    reset_edit_general('subcategory')
}
function reset_new_subcategory(){
 reset_edit_general('subcategory');
}
function save_display_category(key,value, id){

var request='ar_edit_categories.php?tipo=edit_category&okey=' + key+ '&key=' + key+ '&newvalue=' + value +'&category_key=' + id
	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
					Dom.removeClass([r.key+' Yes',r.key+' No'],'selected');
   		            Dom.addClass(r.key+' '+r.newvalue,'selected');
            }else{
                alert(r.msg)
          
            
        }
    }
    });

}
function post_item_updated_actions(branch,r){
key=r.key;
newvalue=r.newvalue;
if(key=='name'){
     Dom.get('title_name').innerHTML=newvalue;}


 
 var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
var table_id=1


    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

  
    var request='&tableid='+table_id+'&sf=0';
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);  
 
}
function post_create_actions(branch){
var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 
 var table=tables.table0;
 var datasource=tables.dataSource0;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
}
function cancel_add_category(){
   reset_new_category();
  }
function cancel_add_subcategory(){
   reset_new_subcategory();
  }

function edit_category_init(){

 dialog_subject_no_assigned_list = new YAHOO.widget.Dialog("dialog_subject_no_assigned_list",  {visible : false,close:true,underlay: "none",draggable:false});
    dialog_subject_no_assigned_list.render();
     dialog_category_heads_list = new YAHOO.widget.Dialog("dialog_category_heads_list",  {visible : false,close:true,underlay: "none",draggable:false});
    dialog_category_heads_list.render();

}

YAHOO.util.Event.onDOMReady(edit_category_init);
