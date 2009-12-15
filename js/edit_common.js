

function swap_radio(){
    swap_this_radio(this);
}

function swap_this_radio(o){
    if(Dom.hasClass(o,'selected'))
	return;
    else{
	var parent=o.parentNode;
	elemets=Dom.getElementsByClassName('selected', 'span', parent);
	Dom.removeClass(elemets,'selected');
	Dom.addClass(o,'selected');
	Dom.get('shelf_type_type').value=o.getAttribute('radio_value');
    }
}



  var CellEdit = function (callback, newValue) {
      
    var record = this.getRecord(),
    column = this.getColumn(),
    oldValue = this.value,
    datatable = this.getDataTable();
    
    if(column.object=='company' || column.object=='customer' || column.object=='contact' )
	ar_file='ar_edit_contacts.php';
    else if(column.object=='warehouse_area' || column.object=='part_location'|| column.object=='shelf_type')
	ar_file='ar_edit_warehouse.php';
    else if(column.object=='user' )
	ar_file='ar_edit_users.php';
     else if(column.object=='new_order' )
	ar_file='ar_edit_orders.php';
       else if(column.object=='supplier' || column.object=='product_supplier' )
	ar_file='ar_edit_suppliers.php';
    else
	ar_file='ar_edit_assets.php';
    //   alert(column.object)
    var request='tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
    //alert('R:'+request);

    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    // alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						
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
		switch (column.action) {
		case 'delete':
		    if(record.getData('delete')!=''){

		    if (confirm('Are you sure, you want to delete this row?')) {
			if(column.object=='company')
			    ar_file='ar_edit_contacts.php';
			else if(column.object=='warehouse_area' || column.object=='location')
			    ar_file='ar_edit_warehouse.php';
			else
			    ar_file='ar_edit_assets.php';



				alert(ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record))

			YAHOO.util.Connect.asyncRequest(
							'GET',
							ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record),
							{
							    success: function (o) {
								
								if (o.responseText == 'Ok') {
								    this.deleteRow(target);
								} else {
								    alert(o.responseText);
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
	//for(x in target)
	//  alert(x+' '+target[x])
	
	//alert(record.getData('delete'))
	if(record.getData('delete')!='')
	    this.highlightRow(target);
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
		    this.unhighlightRow(target);
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
var changed=false;
var errors=false;

for(item in validate_scope_data[branch]){
    
        if(validate_scope_data[branch][item].changed==true)
            changed=true;
         if(validate_scope_data[branch][item].validated==false)
            errors=true;
 }
    
    if(changed ){
	Dom.get('reset_edit_'+scope).style.visibility='visible';
	if(!errors)
	    Dom.get('save_edit_'+scope).style.visibility='visible';
	else
	    Dom.get('save_edit_'+scope).style.visibility='hidden';

    }else{
        Dom.get('save_edit_'+scope).style.visibility='hidden';
	Dom.get('reset_edit_'+scope).style.visibility='hidden';

    }
    
    
    
}
function validate_general(branch,item,query){
 var data= validate_scope_data[branch][item];

 var old_code=Dom.get(data.name).getAttribute('ovalue');
 
  if(old_code!=trim(query)){  
 
 
  if(old_code.toLowerCase()!=trim(query.toLowerCase())){  
  validate_scope_data[branch][item].changed=true;
if(data.ar=='find'){
  var request=data.ar_request+query; 
 // alert(request)
  YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	      //alert(o.responseText)
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
    for(item in validate_scope_data[branch]){
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



function save_edit_general(branch){
    
    for(item in validate_scope_data[branch]){
	if(validate_scope_data[branch][item].changed){
	var item_input=Dom.get(validate_scope_data[branch][item].name);
	
	
	
	
	var updated_items=0;
	
	
	var request=scope_edit_ar_file+'?tipo=edit_'+scope+'&key=' + item+ '&newvalue=' + 
	    encodeURIComponent(item_input.value) +  '&oldvalue=' + 
	    encodeURIComponent(item_input.getAttribute('ovalue')) + 
	    '&'+scope_key_name+'='+scope_key;
	//		alert(request)
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //		   	    alert(o.responseText)
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



