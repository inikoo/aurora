
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

function change_block(e){
   
     Dom.setStyle(['description_block','user_block'],'display','none');
 	 Dom.get(this.id+'_block').style.display='';
	 Dom.removeClass(['description','user'],'selected');
	 Dom.addClass(this, 'selected');
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=employee-edit&value='+this.id ,{});
   
}

function change_description_block(e){
   	block_id=this.getAttribute('block_id')	
     Dom.setStyle(['d_description_block_id','d_description_block_position','d_description_block_contact','d_description_block_pin'],'display','none');
 
 Dom.setStyle('d_description_block_'+block_id,'display','')
	 Dom.removeClass(['description_block_id','description_block_position','description_block_contact','description_block_pin'],'selected');
	 Dom.addClass(this, 'selected');
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=employee-edit_description_block&value='+block_id ,{});
   
}


function init(){

	 var ids = ['description','user']; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
	 var ids = ['description_block_id','description_block_contact','description_block_position','description_block_pin']; 
    YAHOO.util.Event.addListener(ids, "click", change_description_block);


init_search('staff');

validate_scope_data={

    'staff_description':{
	'alias':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_Alias','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('label_invalid_alias').value}]}
	},
	 'staff_employment':{
	'is_working':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_Currently_Working','ar':false,'validation':false}
	,'staff_tye':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_Type','ar':false,'validation':false}
	},
	 'staff_contact':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('label_invalid_name').value}]}
	},
    'staff_pin':{
	'pin':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_PIN','ar':false,'validation':[{'regexp':"[a-z\\d]{0,4}",'invalid_msg':Dom.get('label_invalid_pin').value}]}
	,'pin_confirm':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_PIN_Confirm','ar':false,'validation':false}
	}
};
	
	validate_scope_metadata={
    'staff_description':{'type':'edit','ar_file':'ar_edit_staff.php','key_name':'staff_key','key':Dom.get('staff_key').value},
    'staff_employment':{'type':'edit','ar_file':'ar_edit_staff.php','key_name':'staff_key','key':Dom.get('staff_key').value},
    'staff_contact':{'type':'edit','ar_file':'ar_edit_staff.php','key_name':'staff_key','key':Dom.get('staff_key').value},

	'staff_pin':{'type':'edit','ar_file':'ar_edit_staff.php','key_name':'staff_key','key':Dom.get('staff_key').value}
    

};

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_staff_alias);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Staff_Alias","Staff_Alias_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_staff_name);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Staff_Name","Staff_Name_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;


    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_staff_pin);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Staff_PIN","Staff_PIN_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_staff_pin_confirm);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Staff_PIN_Confirm","Staff_PIN_Confirm_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    Event.addListener('save_edit_staff_description', "click", save_staff_description);
    Event.addListener('reset_edit_staff_description', "click", reset_staff_description);
    
    Event.addListener('save_edit_staff_contact', "click", save_staff_contact);
    Event.addListener('reset_edit_staff_contact', "click", reset_staff_contact);
    
        Event.addListener('save_edit_staff_employment', "click", save_staff_employment);
    Event.addListener('reset_edit_staff_employment', "click", reset_staff_employment);
    
    Event.addListener('save_edit_staff_pin', "click", save_pin);



}


function save_staff_description(){
	save_edit_general('staff_description');
}
function save_staff_contact(){
	save_edit_general('staff_contact');
}
function save_staff_employment(){
	save_edit_general('staff_employment');
}


function save_pin(){
	save_edit_general('staff_pin');
}

function reset_staff_description(){
 reset_edit_general('staff_description');

}
function reset_staff_contact(){
 reset_edit_general('staff_contact');

}
function reset_staff_employment(){
 reset_edit_general('staff_employment');

}

function validate_staff_alias(query){
	validate_general('staff_description','alias',unescape(query));
}

function validate_staff_name(query){
	validate_general('staff_contact','name',unescape(query));
}

function validate_staff_pin(query){
	validate_pin_with_confirmation();
	validate_general('staff_pin','pin',unescape(query));
	
}

function validate_staff_pin_confirm(query){
	validate_pin_with_confirmation();
	validate_general('staff_pin','pin_confirm',unescape(query));

}

function validate_pin_with_confirmation(){
	if(Dom.get('Staff_PIN').value != Dom.get('Staff_PIN_Confirm').value){
		validate_scope_data['staff_pin']['pin'].validated=false;
		validate_scope_data['staff_pin']['pin_confirm'].validated=false;
	}
	else{
		validate_scope_data['staff_pin']['pin'].validated=true;
		validate_scope_data['staff_pin']['pin_confirm'].validated=true;
	}

}



YAHOO.util.Event.onDOMReady(init);
