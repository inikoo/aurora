function cancel_new_category(){
Dom.get('new_category_name').value='';
Dom.setStyle('new_category_no_name_msg','display','none')

dialog_new_category.hide();

}

function dialog_new_category_show(){

dialog_new_category.show();
Dom.get('new_category_name').focus();
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
    
					}
					cancel_new_category()
				
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
