function validate_general(branch,items,query) {

   //alert(branch+' :: '+items+' -- '+query)
    if (validate_scope_metadata[branch]['type']=='new') {  
        validate_general_new(branch,items,query)
    } else {
        validate_general_edit(branch,items,query)
    }
}


function isPositiveInteger(val) {
    if (val==null) {
        return false;
    }
    if (val.length==0 || val=='0') {
        return false;
    }
    for (var i = 0; i < val.length; i++) {
        var ch = val.charAt(i)
        if (ch < "0" || ch > "9") {
            return false
               }
           }
           return true;
}


function isInteger(val) {
    if (val==null) {
        return false;
    }
    if (val.length==0) {
        return false;
    }
    for (var i = 0; i < val.length; i++) {
        var ch = val.charAt(i)
        if (i == 0 && ch == "-") {
            continue
        }
        if (ch < "0" || ch > "9") {
            return false
               }
           }
           return true
              }


function isValidNumber(val) {
    if (val==null) {
        return false;
    }
    if (val.length==0) {
        return false;
    }
    var DecimalFound = false
    for (var i = 0; i < val.length; i++) {
        var ch = val.charAt(i)
        if (i == 0 && ch == "-") {
            continue
        }
        if (ch == "." && !DecimalFound) {
            DecimalFound = true
                           continue
                       }
        if (ch < "0" || ch > "9") {
            return false
               }
           }
           return true
              }
              
              
              
 function isPositiveNumber(val) {
    if (val==null) {
        return false;
    }
    if (val.length==0) {
        return false;
    }
    var DecimalFound = false
    for (var i = 0; i < val.length; i++) {
        var ch = val.charAt(i)
       
        if (ch == "." && !DecimalFound) {
            DecimalFound = true
                           continue
                       }
        if (ch < "0" || ch > "9") {
            return false
               }
           }
           return true
              }             
              
              

function swap_radio(e,input_element) {
    swap_this_radio(this,input_element);
}
function swap_this_radio(o,input_element) {
    if (Dom.hasClass(o,'selected'))
        return;
    else {
        var parent=o.parentNode;
        elemets=Dom.getElementsByClassName('selected', 'span', parent);
        Dom.removeClass(elemets,'selected');
        Dom.addClass(o,'selected');

        Dom.get(input_element).value=o.getAttribute('radio_value');
    }
}

var select_option_table=function(o) {
    Dom.addClass(o,'selected');

}

var select_radio_option_table=function(o) {
    if (Dom.hasClass(o,'selected')) {
        Dom.removeClass(o,'selected');
    } else {
        Dom.addClass(o,'selected');
    }
}

var save_option_table=function(args) {

    fields_to_export_data=Dom.getElementsByClassName('selectable_option', 'td', Dom.get(args.table));
    var fields_to_export=new Object;
    for (x in fields_to_export_data) {
//alert(fields_to_export_data[x].getAttribute('name'))
//fields_to_export[fields_to_export_data[x].getAttribute('name')]=1;
        if (Dom.hasClass(fields_to_export_data[x],'selected')) {
            flag=1;
        } else {
            flag=0;
        }



        YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys='+args.session_address+'-'+fields_to_export_data[x].getAttribute('name')+'&value=' + escape(flag), {} );

    }




}


function option_selected(branch,items){

    var data= validate_scope_data[branch][items];
select=Dom.get(data.name);

 if(select.value==select.getAttribute('ovalue')){
    validate_scope_data[branch][items].changed=false;
 }else{
     validate_scope_data[branch][items].changed=true;

 }

validate_scope(branch);


}


var CellEdit = function (callback, newValue) {



    var record = this.getRecord(),
                 column = this.getColumn(),
                          oldValue = this.value,
                                     datatable = this.getDataTable();
    recordIndex = datatable.getRecordIndex(record);
    if (column.object=='customer_field' || column.object=='company' || column.object=='customer' || column.object=='contact' || column.object=='company_area' || column.object=='company_department' || column.object=='company_position')
        ar_file='ar_edit_contacts.php';
    else if (column.object=='warehouse_area' || column.object=='part_location'|| column.object=='shelf_type' || column.object=='location')
        ar_file='ar_edit_warehouse.php';
    else if (column.object=='user' )
        ar_file='ar_edit_users.php';
    else if (column.object=='position' || column.object=='staff'|| column.object=='company_staff' )
        ar_file='ar_edit_hr.php';
    else if (column.object=='new_order' )
        ar_file='ar_edit_orders.php';
    else if (column.object=='supplier' || column.object=='product_supplier' )
        ar_file='ar_edit_suppliers.php';
    else if (column.object=='new_porder'  )
        ar_file='ar_edit_porders.php';
     else if (column.object=='new_porder'  )
        ar_file='ar_edit_porders.php';    
     else if (column.object=='family_page_properties' || column.object=='page_product_list' || column.object=='store_page_properties'  || column.object=='department_page_properties'  || column.object=='site_page_properties'  )
        ar_file='ar_edit_sites.php';    
    
    
    
        
    else if (column.object=='ind_staff' || column.object=='ind_positions' || column.object=='ind_department')
        ar_file='ar_edit_staff.php';
      else if (column.object=='subcategory')
        ar_file='ar_edit_categories.php';    
    else
        ar_file='ar_edit_assets.php';

    var request='tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
//  alert(ar_file+'?'+request);//return;
    YAHOO.util.Connect.asyncRequest(
        'POST',
    ar_file, {
success:function(o) {
            //    alert(o.responseText);

            var r = YAHOO.lang.JSON.parse(o.responseText)


            if (r.state == 200) {

                if (r.key=='cost' && column.object=='product_supplier') {
                    var data = record.getData();
                    data['sph_key']=r.sp_current_key;
                    data['cost']=r.newvalue;
                    datatable.updateRow(recordIndex,data);
                }else if(r.key=='available' && column.object=='supplier_product_part'){
                
                	datatable.updateCell(record,'available_state',r.available_state);

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
  //var  datatable = this.getDataTable();
   //alert(column.object)
    switch (column.action) {

   
    case 'delete':
        if (record.getData('delete')!='') {

            var delete_type=record.getData('delete_type');
if(delete_type== undefined)
    delete_type='delete';


            if (confirm('Are you sure, you want to '+delete_type+' this row?')) {
                if (column.object=='company' || column.object=='company_area' || column.object=='customer_history' || column.object=='customer_list')
                    ar_file='ar_edit_contacts.php';
		else if(column.object=='widget_list' )
			ar_file='ar_dashboard.php';
                else if (column.object=='warehouse_area' || column.object=='location')
                    ar_file='ar_edit_warehousrecordIndexe.php';
                else if (column.object=='position')
                    ar_file='ar_edit_staff.php';
                else if (column.object=='supplier_product' || column.object=='supplier')
                    ar_file='ar_edit_suppliers.php';
                else if (column.object=='ind_staff'  || column.object=='ind_positions' || column.object=='ind_department' )
     		        ar_file='ar_edit_staff.php';
		        else if (column.object=='subcategory')
     		         ar_file='ar_edit_categories.php';
     		        else if (column.object=='page_store' || column.object=='page_header'  || column.object=='page_footer')
     		         ar_file='ar_edit_sites.php';    
                else if (column.object=='order_list' || column.object=='invoice_list'|| column.object=='dn_list')
     		         ar_file='ar_edit_orders.php';
     		        else if (column.object=='email_campaign_recipient' || column.object=='email_campaign_objetive'  || column.object=='color_scheme'     || column.object=='template_header_image'     || column.object=='template_postcard' )
     		         ar_file='ar_edit_marketing.php';         
     		   else
                    ar_file='ar_edit_assets.php';



          //alert(ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record));return;
                YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record), {
                success: function (o) {
                    //alert(o.responseText);
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

case 'add':

		if(column.object=='widget_list' )
			ar_file='ar_dashboard.php';



        //alert(ar_file+'?tipo=add_'+column.object + myBuildUrl(this,record));return;
                YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo=add_'+column.object + myBuildUrl(this,record), {
                success: function (o) {
                    //alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200 && r.action=='added') {
			  alert(r.msg);


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



function show_cell_dialog(){
}

function post_delete_actions(column){

}

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
        if (record.getData('delete')!='')
            this.highlightRow(target);
        break;
    case('add_object'):
     case('check_all_object'):
    case('remove_object'):
    case('edit_object'):
    case('edit_object'):
        case('edit_pending'):    

        this.highlightCell(target);
        break;
    case('dialog'):
     this.highlightCell(target);
    
    break;    
    default:

        if (YAHOO.util.Dom.hasClass(target, "yui-dt-editable") ) {
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
        if (YAHOO.util.Dom.hasClass(target, "yui-dt-editable")) {
            this.unhighlightCell(target);
        }
    }
};
function radio_changed(o) {
    parent=o.parentNode;
    Dom.removeClass(parent.getAttribute('prefix')+parent.getAttribute('value'),'selected');
    Dom.addClass(o,'selected');


    parent.setAttribute('value',o.getAttribute('name'));
}


function checkbox_changed(o){

if(    Dom.hasClass(o,'selected')){
    Dom.removeClass(o,'selected');
}else{
    Dom.addClass(o,'selected');
}

}

function validate_scope(branch) {
    if (validate_scope_metadata[branch]['type']=='new') {
        validate_scope_new(branch)
    } else {
        validate_scope_edit(branch)
    }
}


function is_valid_scope(branch){
 var valid=true;
  for (items in validate_scope_data[branch]) {
  //alert(branch +' '+items+' '+validate_scope_data[branch][items].name+' '+validate_scope_data[branch][items].validated) 
        if (validate_scope_data[branch][items].validated==false   ||    (validate_scope_data[branch][items].required &&  Dom.get(validate_scope_data[branch][items].name).value=='' )  ){
            valid=false;
            break;
          }  
    }
 return valid;   
}


function validate_scope_edit(branch) {



  var errors=false;
  var changed=false;

    for (items in validate_scope_data[branch]) {
    
       //alert(branch +' xxx items:  '+items+' Dom id:   '+validate_scope_data[branch][items].name) 

        if (validate_scope_data[branch][items].validated==false   ||    (validate_scope_data[branch][items].required &&  Dom.get(validate_scope_data[branch][items].name).value=='' )  )
            errors=true;
        if (validate_scope_data[branch][items].changed==true)
            changed=true;
            
         //   alert(errors+' '+changed)
    }



Dom.setStyle('save_edit_'+branch,'visibility','visible');
Dom.setStyle('reset_edit_'+branch,'visibility','visible');

if(changed){	
        Dom.setStyle('save_edit_'+branch,'visibility','visible');
        Dom.setStyle('reset_edit_'+branch,'visibility','visible');

}else{
        Dom.setStyle('save_edit_'+branch,'visibility','hidden');
        Dom.setStyle('reset_edit_'+branch,'visibility','hidden');

}


    if (errors) {
        //alert('x')
        Dom.addClass('save_edit_'+branch,'disabled');
    } else {
alert('save_edit_'+branch)
        Dom.removeClass('save_edit_'+branch,'disabled');
    }
//alert(branch)
}




function validate_scope_new(branch) {
    var changed=false;
    var errors=false;
    for (items in validate_scope_data[branch]) {
	//alert(items + ':' + validate_scope_data[branch][items].required + ':' + validate_scope_data[branch][items].validated);
        if (validate_scope_data[branch][items].required==true && validate_scope_data[branch][items].validated==false) {
             //alert(branch+' , '+items+" error")
            errors=true;
        }
    }

    if (errors) {
        Dom.addClass('save_new_'+branch,'disabled');
    } else {
        Dom.removeClass('save_new_'+branch,'disabled');
    }
}





function ar_validation(branch,items,query) {
	
    var data= validate_scope_data[branch][items];
    var request=data.ar_request+query;
 //   alert(data.ar_request)
 // alert(request)
   YAHOO.util.Connect.asyncRequest('POST',request , {success:function(o) {
      // alert(o.responseText)
        var r =  YAHOO.lang.JSON.parse(o.responseText);
        if (r.state==200) {
            if (r.found==1) {
                Dom.get(data.name+'_msg').innerHTML=r.msg;
                validate_scope_data[branch][items].validated=false;
            } else {
                
                client_validation(branch,items,query)
            }
            validate_scope(branch);
        } else{
            Dom.get(data.name+'_msg').innerHTML='<span class="error">'+r.msg+'</span>';
            }
   
   }

            });

}


function regex_validation(regexp,query) {

    var validator=new RegExp(regexp,"i");
    if (!validator.test(query)) {
       /// alert("Err "+query);
        return false;
    } else {
    //alert('ok')
        return true;
    }
}

function numeric_validation(type,query) {
    var valid=false;
    switch ( type ) {
    
    
    case 'money':
    case 'number':
        if (isValidNumber(query))
            valid=true
                  break;
    case 'integer':
        if (isInteger(query))
            valid=true
                  break;
    case 'positive integer':
        if (isPositiveInteger(query))
            valid=true
                  break;

 case 'positive':
 
        if (isPositiveNumber(query))
            valid=true
                  break;                 

    }
    return valid;
}

function client_validation(branch,items,query) {
    var data= validate_scope_data[branch][items];
    
    if(Dom.get(data.name+'_msg')==undefined)
    message_div=false;
    else
    message_div=true;
    
    if(message_div)Dom.get(data.name+'_msg').innerHTML='';
    validate_scope_data[branch][items].validated=true;
    var valid=true;
    for (validator_index in data.validation) {

        if (!valid)
            break;

        validator_data=data.validation[validator_index];

        if (validator_data.regexp != undefined) {

            valid=regex_validation(validator_data.regexp,query)
            
            
        } else if (validator_data.numeric != undefined) {
            valid=numeric_validation(validator_data.numeric,query)
              }
          }
    if (!valid) {
        validate_scope_data[branch][items].validated=false;

        if(message_div)Dom.get(data.name+'_msg').innerHTML=validator_data.invalid_msg;
    }

}


function validate_general_new(branch,items,query) {
	
    var data= validate_scope_data[branch][items];
    //alert(data)
    if (''!=trim(query.toLowerCase())    ) {
        validate_scope_data[branch][items].changed=true;

        if (data.ar=='find') {
            ar_validation(branch,items,query)
            return;
        } else {
            client_validation(branch,items,query)
        }
    } else {
        validate_scope_data[branch][items].validated=false;
        validate_scope_data[branch][items].changed=false;
    }
    validate_scope(branch);
}

function validate_general_edit(branch,items,query) {

//alert(branch+' I:'+items+' q:'+query);


    var data= validate_scope_data[branch][items];

    var old_value=Dom.get(data.name).getAttribute('ovalue');

    if (old_value!=trim(query) ) {

		
        if (old_value.toLowerCase()!=trim(query.toLowerCase())    ) {
            validate_scope_data[branch][items].changed=true;

            if (data.ar=='find') {
                 ar_validation(branch,items,query)
            }
            else {
                client_validation(branch,items,query)

            }

            validate_scope(branch);


        } else {
            validate_scope_data[branch][items].validated=true;
            validate_scope_data[branch][items].changed=true;
            validate_scope(branch);

        }
    } else {
        validate_scope_data[branch][items].validated=true;
        validate_scope_data[branch][items].changed=false;
        validate_scope(branch);
    }


}
function reset_edit_general(branch) {
    //alert(branch)
    for (items in validate_scope_data[branch]) {
        //alert(validate_scope_data[branch][items].name)
        var item_input=Dom.get(validate_scope_data[branch][items].name);

    // alert(validate_scope_data[branch][items].name)
        item_input.value=item_input.getAttribute('ovalue');
        
        validate_scope_data[branch][items].changed=false;
        validate_scope_data[branch][items].validated=true;
       // alert(validate_scope_data[branch][items].name+'_msg')
        Dom.get(validate_scope_data[branch][items].name+'_msg').innerHTML='';
    }
    validate_scope(branch);
};


function cancel_new_general(branch) {

    for (items in validate_scope_data[branch]) {
        //alert(validate_scope_data[branch][items].name)
        var item_input=Dom.get(validate_scope_data[branch][items].name);
        //alert(validate_scope_data[branch][items].name)
        if (validate_scope_data[branch][items].default_!=undefined) {
            if (validate_scope_data[branch][items].type=='select') {

                for ( i in item_input.options) {
                   // alert(item_input.options[i].defaultSelected+' '+item_input.options[i].value)
                    if (item_input.options[i].defaultSelected) {
                        item_input.selectedIndex=item_input.options[i].index;
                        break;
                    }

                }
            } else {
                item_input.value=validate_scope_data[branch][items].default_;
            }
        } else {
            item_input.value='';
        }

        validate_scope_data[branch][items].changed=false;
        validate_scope_data[branch][items].validated=false;
        if (Dom.get(validate_scope_data[branch][items].name+'_msg')!=undefined) {
            Dom.get(validate_scope_data[branch][items].name+'_msg').innerHTML='';
        }
    }
    Dom.addClass('save_new_'+branch,'disabled');
  
  // Dom.setStyle('cancel_new_'+branch,'visibility','hidden');
    if(Dom.get('show_new_'+branch+'_dialog_button')!=undefined){
    Dom.setStyle('show_new_'+branch+'_dialog_button','display','');
    }
    Dom.get("new_"+branch+"_dialog_msg").innerHTML
    Dom.setStyle('new_'+branch+'_dialog','display','none');

};



function post_item_updated_actions(branch,r) {
    return true;
}

function save_edit_general(branch) {


if(Dom.hasClass('save_edit_'+branch,'disabled')){

if(Dom.get("edit_"+branch+"_invalid_msg")!=undefined){

Dom.setStyle("edit_"+branch+"_dialog_msg",'display','');
            Dom.get("edit_"+branch+"_dialog_msg").innerHTML=Dom.get("edit_"+branch+"_invalid_msg").innerHTML;
}

return;
}

  operation='edit';
    scope_edit_ar_file=validate_scope_metadata[branch]['ar_file'];
    branch_key=validate_scope_metadata[branch]['key'];
    branch_key_name=validate_scope_metadata[branch]['key_name'];


    for (items in validate_scope_data[branch]) {
		
        if (validate_scope_data[branch][items].changed && validate_scope_data[branch][items].validated) {
            var item_input=Dom.get(validate_scope_data[branch][items].name);
			



            var updated_items=0;

            if (validate_scope_data[branch][items].dbname!=undefined) {
                item_name=validate_scope_data[branch][items].dbname;
            } else {
                item_name=items;
            }


	   var xx_value=item_input.value;
	  // xx_value='xx';
	   postData = 'tipo='+operation+'_'+branch+'&okey='+items+'&key=' + item_name+ '&newvalue=' +encodeURIComponent(xx_value)+'&'+branch_key_name+'='+branch_key;
                        
                        
	   if(validate_scope_metadata[branch]['dynamic_second_key']!= undefined){
	     second_key=validate_scope_metadata[branch]['dynamic_second_key'];
	    second_name_name='second_key';
	     if(validate_scope_metadata[branch]['second_key_name']!= undefined){
	         second_name_name=validate_scope_metadata[branch]['second_key_name']
	     }
	    
	        postData=postData+'&'+second_name_name+'='+Dom.get(validate_scope_metadata[branch]['dynamic_second_key']).value;
	   }
	   
	   
	 //  alert(item_input.value.length);
	   
alert(scope_edit_ar_file+'?'+postData)
            YAHOO.util.Connect.asyncRequest('POST',scope_edit_ar_file , 
            {
            success:function(o) {
//alert(o.responseText);
                    var r =  YAHOO.lang.JSON.parse(o.responseText);
                    if (r.state==200) {
           
                    
                        validate_scope_data[branch][r.key].changed=false;
                        validate_scope_data[branch][r.key].validated=true;
                        Dom.get(validate_scope_data[branch][r.key].name).setAttribute('ovalue',r.newvalue);
                        Dom.get(validate_scope_data[branch][r.key].name).value=r.newvalue;
                       //  alert(validate_scope_data[branch][r.key].name+'_msg')
                        Dom.get(validate_scope_data[branch][r.key].name+'_msg').innerHTML='<img src="art/icons/accept.png"/>';
                      
                        post_item_updated_actions(branch,r);


                    } 
                    else {
                        validate_scope_data[branch][r.key].changed=true;
                        validate_scope_data[branch][r.key].validated=false;
                        Dom.get(validate_scope_data[branch][r.key].name+'_msg').innerHTML=r.msg;
                    }
                    validate_scope_edit(branch)
                },
 failure: function(o) {alert(o.statusText)}
            },postData
            );
        }
    }

}


function save_edit_general_bulk(branch) {
    operation='edit';
    scope_edit_ar_file=validate_scope_metadata[branch]['ar_file'];
    branch_key=validate_scope_metadata[branch]['key'];
    branch_key_name=validate_scope_metadata[branch]['key_name'];


if(Dom.hasClass('save_edit_'+branch,'disabled')){

if(Dom.get("edit_"+branch+"_invalid_msg")!=undefined){

Dom.setStyle("edit_"+branch+"_dialog_msg",'display','');
            Dom.get("edit_"+branch+"_dialog_msg").innerHTML=Dom.get("edit_"+branch+"_invalid_msg").innerHTML;
}

return;
}

 //alert(scope_edit_ar_file);alert(branch_key);alert(branch_key_name);
 var data_to_update=new Object;
    for (items in validate_scope_data[branch]) {
        if (validate_scope_data[branch][items].changed && validate_scope_data[branch][items].validated) {
            var item_input=Dom.get(validate_scope_data[branch][items].name);
            //alert(validate_scope_data[branch][items].name+'_msg')
			Dom.get(validate_scope_data[branch][items].name+'_msg').innerHTML='<img src="art/loading.gif"/>';
            var updated_items=0;

            if (validate_scope_data[branch][items].dbname!=undefined) {
                item_name=validate_scope_data[branch][items].dbname;
            } else {
                item_name=items;
            }
          
            data_to_update[item_name]={'okey':items,'value':item_input.value}
        }
		else if(!validate_scope_data[branch][items].changed){
			if(branch=='customer_quick' || branch=='billing_quick'){
				//eval('dialog_quick_edit_'+validate_scope_data[branch][items].name).hide();
				//data_to_update['a']={'okey':'a','value':'a'};
				//alert('else if')
			}
		}
		
		if(branch=='customer_quick' || branch=='billing_quick'){
			//change_comment();
			//save_comment();
		}
    }

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));
var request=scope_edit_ar_file

var postData='tipo='+operation+'_'+branch+'&values='+ jsonificated_values+'&'+branch_key_name+'='+branch_key;
//alert(request+'?'+postData);return;
 YAHOO.util.Connect.asyncRequest('POST',request , {
    success:function(o) {
  //alert(o.responseText);

            var ra =  YAHOO.lang.JSON.parse(o.responseText);
        
            count=ra.length;
			i=0;
            for (x in ra){
			if(count<=i++)
			break;
			
              r=ra[x];

             if (r.state==200) {

                        validate_scope_data[branch][r.key].changed=false;
                        validate_scope_data[branch][r.key].validated=true;
                        Dom.get(validate_scope_data[branch][r.key].name).setAttribute('ovalue',r.newvalue);
                        Dom.get(validate_scope_data[branch][r.key].name).value=r.newvalue;
                        Dom.get(validate_scope_data[branch][r.key].name+'_msg').innerHTML='<img src="art/icons/accept.png"/>';
						display_add_other(r);
                        post_item_updated_actions(branch,r);


                    } 
                    else {
                        validate_scope_data[branch][r.key].changed=true;
                        validate_scope_data[branch][r.key].validated=false;
                        Dom.get(validate_scope_data[branch][r.key].name+'_msg').innerHTML=r.msg;
						post_item_updated_actions(branch,r);
                    }
                    
             
             
}
validate_scope_edit(branch)

        },
    failure: function(o) {alert(o.statusText)},      
    },postData
    
    );
  

   
}

function display_add_other(r){
	switch(r.key){
		case 'telephone': 
		case 'mobile':
		case 'fax':
		Dom.setStyle('display_add_other_'+r.key,'display','')
		break;
		default: break;
	}

	if(r.action.match(/deleted/gi)){
		if(r.key.match(/email/gi)){
			var email_id=r.key.split('email');
			Dom.setStyle('tr_other_email'+email_id[1],'display','none');
			
		}
		else if(r.key.match(/telephone/gi)){

			var telephone_id=r.key.split('telephone');
			
			Dom.setStyle('tr_other_telephone'+telephone_id[1],'display','none');
			
		}
		else if(r.key.match(/fax/gi)){

			var fax_id=r.key.split('fax');
			Dom.setStyle('tr_other_fax'+fax_id[1],'display','none');
			
			
		}
		else if(r.key.match(/mobile/gi)){
			var mobile_id=r.key.split('mobile');
			Dom.setStyle('tr_other_mobile'+mobile_id[1],'display','none');
			
		}
		
	}
	if(r.action.match(/updated/gi)){
		window.location.reload();
	}
}

function save_new_general(branch) {

if(Dom.hasClass('save_new_'+branch,'disabled')){

if(Dom.get("new_"+branch+"_invalid_msg")!=undefined){

Dom.setStyle("new_"+branch+"_dialog_msg",'display','');
            Dom.get("new_"+branch+"_dialog_msg").innerHTML=Dom.get("new_"+branch+"_invalid_msg").innerHTML;
}

return;
}


    operation='create';
    var values=new Object();

    for (items in validate_scope_data[branch]) {
        //
        var item_input=Dom.get(validate_scope_data[branch][items].name);


        values[validate_scope_data[branch][items].dbname]=item_input.value;
	//alert(validate_scope_data[branch][items].dbname+' --- '+item_input.value)
    }

    scope_edit_ar_file=validate_scope_metadata[branch]['ar_file'];
    parent_key=validate_scope_metadata[branch]['key'];
    parent=validate_scope_metadata[branch]['key_name'];
//alert(parent_key);

    jsonificated_values=YAHOO.lang.JSON.stringify(values);

	//alert(scope_edit_ar_file);
    var request=scope_edit_ar_file+'?tipo='+operation+'_'+branch+'&parent='+parent+'&parent_key=' + parent_key+ '&values=' + 	jsonificated_values;
	alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
alert(o.responseText);

            var r =  YAHOO.lang.JSON.parse(o.responseText);

            if(r.msg!=undefined){
            Dom.setStyle("new_"+branch+"_dialog_msg",'display','');
            Dom.get("new_"+branch+"_dialog_msg").innerHTML=r.msg;
            }
            
         
            if (r.state==200) {

                if (r.action=='created') {

                   post_new_create_actions(branch,r);
		   // alert(branch)
			

                }
                else if(r.action='staff_created'){
			post_action(branch,r);	
		}
            } else {
                if (r.action=='found') {
                    post_new_found_actions(branch,r);

                } else {
                    post_new_error_actions(branch,r);

                }
            }



        }
    });

}

function post_new_create_actions(branch,response) {

    cancel_new_general(branch)
}
function post_new_found_actions(branch,response) {
}
function post_new_error_actions(branch,response) {

}


function SelectUrl()
{
	if(document.getElementById('template1').checked == false && document.getElementById('template2').checked == false)
	{
		alert('Please select a template');
		return false;
		
	}

}

