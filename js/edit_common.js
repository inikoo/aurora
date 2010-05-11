function swap_radio(e,input_element){
    swap_this_radio(this,input_element);
}
function swap_this_radio(o,input_element){
    if(Dom.hasClass(o,'selected'))
	return;
    else{
	var parent=o.parentNode;
	elemets=Dom.getElementsByClassName('selected', 'span', parent);
	Dom.removeClass(elemets,'selected');
	Dom.addClass(o,'selected');
	
	Dom.get(input_element).value=o.getAttribute('radio_value');
    }
}
var CellEdit = function (callback, newValue) {
    var record = this.getRecord(),
    column = this.getColumn(),
    oldValue = this.value,
    datatable = this.getDataTable();
    recordIndex = datatable.getRecordIndex(record);
    
    if(column.object=='company' || column.object=='customer' || column.object=='contact' || column.object=='company_area'  || column.object=='company_department')
	ar_file='ar_edit_contacts.php';
    else if(column.object=='warehouse_area' || column.object=='part_location'|| column.object=='shelf_type' || column.object=='location')
	ar_file='ar_edit_warehouse.php';
    else if(column.object=='user' )
	ar_file='ar_edit_users.php';
    else if(column.object=='position' || column.object=='staff' )
	ar_file='ar_edit_hr.php';
    else if(column.object=='new_order' )
	ar_file='ar_edit_orders.php';
    else if(column.object=='supplier' || column.object=='product_supplier' )
	ar_file='ar_edit_suppliers.php';
else if(column.object=='new_porder'  )
	ar_file='ar_edit_porders.php';
    else
	ar_file='ar_edit_assets.php';
    //   alert(column.object)
    var request='tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
    alert(ar_file+'?'+request);

    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						
						if(r.key=='cost' && column.object=='product_supplier'){
						    var data = record.getData();
						    data['sph_key']=r.sp_current_key;
						    data['cost']=r.newvalue;
						    datatable.updateRow(recordIndex,data);
						}
						
						callback(true, r.newvalue);
					    } else {
						alert(r.msg);
						callback();
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					    callback();
					},
					    scope:this
					    },
				    request
						
				    );  
};
var onCellClick = function(oArgs) {
    var target = oArgs.target,
    column = this.getColumn(target),
    record = this.getRecord(target);
	
    var recordIndex = this.getRecordIndex(record);
		
		
    switch (column.action) {
   
    case 'delete':
	if(record.getData('delete')!=''){

	    var delete_type=record.getData('delete_type');




	    if (confirm('Are you sure, you want to '+delete_type+' this row?')) {
		if(column.object=='company' || column.object=='company_area' || column.object=='company_department')
		    ar_file='ar_edit_contacts.php';
		else if(column.object=='warehouse_area' || column.object=='location')
		    ar_file='ar_edit_warehouse.php';
		else if(column.object=='position')
		    ar_file='ar_edit_staff.php';    
		else if(column.object=='supplier_product' || column.object=='supplier')
		    ar_file='ar_edit_suppliers.php';        
		else
		    ar_file='ar_edit_assets.php';



		//	alert(ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record))

		YAHOO.util.Connect.asyncRequest(
						'GET',
						ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record),
						{
						    success: function (o) {
							alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200 && r.action=='deleted') {
								
							    this.deleteRow(target);
								
								
							}else if(r.state == 200 && r.action=='discontinued') {
								
							    var data = record.getData();
							    data['delete']=r.delete;
							    data['delete_type']=r.delete_type;
							    this.updateRow(recordIndex,data);
								   
								
								
							} else {
							    alert(r.msg);
							}
						    },
							failure: function (o) {
							alert(o.statusText);
						    },
							scope:this
							}
						);
	    }
	}
	break;
		    
    default:
		    
	this.onEventShowCellEditor(oArgs);
	break;
    }
};   
var highlightEditableCell = function(oArgs) {

    var target = oArgs.target;
    column = this.getColumn(target);
    record = this.getRecord(target);
   
    switch (column.action) {
    case 'delete':
    case 'pick_it':
	//for(x in target)
	//  alert(x+' '+target[x])
	
	//alert(record.getData('delete'))
	if(record.getData('delete')!='')
	    this.highlightRow(target);
	break;
    case('add_object'):
    case('remove_object'):
        case('edit_object'):

	this.highlightCell(target);
	break;
    default:
	
	if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable") ) {
	    this.highlightCell(target);
	}
    }
};
var unhighlightEditableCell = function(oArgs) {
    var target = oArgs.target;
    column = this.getColumn(target);

    switch (column.action) {
    case 'delete':
    case 'pick_it':
	this.unhighlightRow(target);
    case('add_object'):
    case('remove_object'):
    case('edit_object'):
	this.unhighlightCell(target);
	break;
    default:
	if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable")) {
	    this.unhighlightCell(target);
	}
    }
};
function radio_changed(o){
    parent=o.parentNode;

    Dom.removeClass(parent.getAttribute('prefix')+parent.getAttribute('value'),'selected');
    Dom.addClass(o,'selected');
  

    parent.setAttribute('value',o.getAttribute('name'));
}


function validate_scope(branch){

    if(validate_scope_metadata[branch]['type']=='new')
	validate_scope_new(branch)
	else
	    validate_scope_edit(branch)

 
		}


function validate_scope_new(branch){
    var errors=false;

    for(item in validate_scope_data[branch]){
	//  alert(item+ 'V:'+validate_scope_data[branch][item].validated+' D:'+validate_scope_data[branch][item].required);
	if(validate_scope_data[branch][item].validated==false   ||    (validate_scope_data[branch][item].required &&  Dom.get(validate_scope_data[branch][item].name).value=='' )  )
            errors=true;
    }
    
   
    if(errors)
	Dom.addClass('save_edit_'+branch,'disabled');
    else
	Dom.removeClass('save_edit_'+branch,'disabled');

  
 
}




function validate_scope_edit(branch){

    var changed=false;
    var errors=false;

    for(item in validate_scope_data[branch]){
    
        if(validate_scope_data[branch][item].changed==true)
            changed=true;
	if(validate_scope_data[branch][item].validated==false)
            errors=true;
    }
    
    if(changed ){
	Dom.get('reset_edit_'+branch).style.visibility='visible';
	if(!errors)
	    Dom.get('save_edit_'+branch).style.visibility='visible';
	else
	    Dom.get('save_edit_'+branch).style.visibility='hidden';

    }else{
        Dom.get('save_edit_'+branch).style.visibility='hidden';
	Dom.get('reset_edit_'+branch).style.visibility='hidden';

    }
 
}





function validate_general(branch,item,query){
    var data= validate_scope_data[branch][item];
    // alert(branch+' '+item+' '+data.name)
    var old_value=Dom.get(data.name).getAttribute('ovalue');
    if(old_value!=trim(query)){  
 
 
	if(old_value.toLowerCase()!=trim(query.toLowerCase())){  
	    validate_scope_data[branch][item].changed=true;

	    if(data.ar=='find'){
		var request=data.ar_request+query; 
		// alert(request)
		YAHOO.util.Connect.asyncRequest('POST',request ,{
			success:function(o) {
			    // alert(o.responseText)
			    var r =  YAHOO.lang.JSON.parse(o.responseText);
			    if(r.state==200){
				if(r.found==1){
				    Dom.get(data.name+'_msg').innerHTML=r.msg;
				    validate_scope_data[branch][item].validated=false;
				}else{
				    Dom.get(data.name+'_msg').innerHTML='';
				    validate_scope_data[branch][item].validated=true;
				    for(validator_index in data.validation){
					validator_data=data.validation[validator_index];
					var validator=new RegExp(validator_data.regexp,"i");
					if(!validator.test(query)){
	           
					    validate_scope_data[branch][item].validated=false;
					    Dom.get(data.name+'_msg').innerHTML=validator_data.invalid_msg;
					    break;
					}
				    }
				}
				validate_scope(branch); 
			    }else
				Dom.get('msg_div').innerHTML='<span class="error">'+r.msg+'</span>';
			}
	    
		    });
	    }else{
		Dom.get(data.name+'_msg').innerHTML='';
		validate_scope_data[branch][item].validated=true;
		for(validator_index in data.validation){
		    validator_data=data.validation[validator_index];
		    var validator=new RegExp(validator_data.regexp,"i");
		    //  alert(validator_data.regexp)
		    if(!validator.test(query)){

	                validate_scope_data[branch][item].validated=false;
			Dom.get(data.name+'_msg').innerHTML=validator_data.invalid_msg;
			break;
		    }
		}
	    }
	    validate_scope(branch); 
		    
		    
	}else{
	    validate_scope_data[branch][item].validated=true;
	    validate_scope_data[branch][item].changed=true;
	    validate_scope(branch); 
	
	}
		    


    }else{
	validate_scope_data[branch][item].validated=true;
	validate_scope_data[branch][item].changed=false;
	validate_scope(branch); 
    }
		    
 
}
function reset_edit_general(branch){
    //alert(branch)
    for(item in validate_scope_data[branch]){
	//alert(validate_scope_data[branch][item].name)
	var item_input=Dom.get(validate_scope_data[branch][item].name);

	item_input.value=item_input.getAttribute('ovalue');
	validate_scope_data[branch][item].changed=false;
	validate_scope_data[branch][item].validated=true;
	Dom.get(validate_scope_data[branch][item].name+'_msg').innerHTML='';
    }
    validate_scope(branch); 
};
function post_item_updated_actions(branch,key,newvalue){
    return true;
}
function post_create_actions(branch){

}
function save_edit_general(branch){
    
   
    operation='edit';
    scope_edit_ar_file=validate_scope_metadata[branch]['ar_file'];
    branch_key=validate_scope_metadata[branch]['key'];
    branch_key_name=validate_scope_metadata[branch]['key_name'];

    

    for(item in validate_scope_data[branch]){

	if(validate_scope_data[branch][item].changed){
	    var item_input=Dom.get(validate_scope_data[branch][item].name);
	    
	    

	
	    var updated_items=0;
	    
	    
	    var request=scope_edit_ar_file+'?tipo='+operation+'_'+branch+'&key=' + item+ '&newvalue=' + 
		encodeURIComponent(item_input.value) +  '&oldvalue=' + 
		encodeURIComponent(item_input.getAttribute('ovalue')) + 
		'&'+branch_key_name+'='+branch_key;
	    	    alert(request);

	    YAHOO.util.Connect.asyncRequest('POST',request ,{
		    success:function(o) {
			alert(o.responseText)
			    var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
			
			    validate_scope_data[branch][r.key].changed=false;
			    validate_scope_data[branch][r.key].validated=true;
			    Dom.get(validate_scope_data[branch][r.key].name).setAttribute('ovalue',r.newvalue);
			    Dom.get(validate_scope_data[branch][r.key].name).value=r.newvalue;
			    Dom.get(validate_scope_data[branch][r.key].name+'_msg').innerHTML='<img src="art/icons/accept.png"/>';
			
			    post_item_updated_actions(branch,r.key,r.newvalue);
			
		
			}else{
			    validate_scope_data[branch][r.key].changed=true;
			    validate_scope_data[branch][r.key].validated=false;
			    Dom.get(validate_scope_data[branch][r.key].name+'_msg').innerHTML=r.msg;
			
			}
		    
		    }
			    
		});
	}
    }
  
}
function save_new_general(branch){
    operation='create';
    var values=new Object();
    
    for(item in validate_scope_data[branch]){
	alert(validate_scope_data[branch][item].name)
	    var item_input=Dom.get(validate_scope_data[branch][item].name);
	values[validate_scope_data[branch][item].dbname]=item_input.value;
    }	


    //encodeURIComponent(item_input.value)
    jsonificated_values=YAHOO.lang.JSON.stringify(values);
	
	
    var request=scope_edit_ar_file+'?tipo='+operation+'_'+branch+'&parent='+parent+'&parent_key=' + parent_key+ '&values=' + 
	jsonificated_values
	alert(request)
		
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    // alert(o.responseText);
		    	   	   
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
			
			
			reset_edit_general(branch);
			post_create_actions(branch);
			
		
		    }else{
			alert(r.msg);
			
		    }
		    
		
			    
		}
	    });
	    
}
	
    
  



