function calculate_num_changed_in_email(){
    var changed=new Object();
    var to_delete=0;
    var invalid=0;
    var new_email=0;
    var elements_array=Dom.getElementsByClassName('Email', 'input');
    for( var i in elements_array ){
	var input_element=elements_array[i];
	var email_key=input_element.getAttribute('email_key');

	if(email_key.match('new')){

	    if(input_element.value!='' && input_element.getAttribute('valid')==1 && input_element.getAttribute('to_delete')==0 )
		new_email++;
	}else if(input_element.getAttribute('to_delete')==1){
	    to_delete++;
	}else if(email_key>0  && input_element.getAttribute('ovalue')!=input_element.value){
	  
		changed[email_key]=1;
		if(input_element.getAttribute('valid')==0)
		    invalid++;
	 
	    
	}
    }
    var elements_array=Dom.getElementsByClassName('Email_Description', 'input');
    for( var i in elements_array ){
	var input_element=elements_array[i];
	var email_key=input_element.getAttribute('email_key');
	if(email_key>0  && input_element.getAttribute('ovalue')!=input_element.value)
	    changed[email_key]=1;
    }
     var elements_array=Dom.getElementsByClassName('Email_Contact_Name', 'input');
    for( var i in elements_array ){
	var input_element=elements_array[i];
	var email_key=input_element.getAttribute('email_key');
	if(email_key>0  && input_element.getAttribute('ovalue')!=input_element.value)
	    changed[email_key]=1;
    }
    


    var changes=0;
    for(i in changed)
	changes++;
    
    Contact_Email_Changes=changes-invalid+to_delete+new_email;
    Contact_Emails_to_edit=changes-invalid;
    Contact_Emails_to_delete=to_delete;
    Contact_Emails_to_add=new_email;


    render_after_contact_item_change();


}


function email_change(){
    calculate_num_changed_in_email();
    render_after_contact_item_change();

}


function show_details_email(o){
    var action=o.getAttribute('action');
    var email_key=o.getAttribute('email_key');

    if(action=='Show'){
	o.innerHTML='Hide Details';
	o.setAttribute('action','Hide');
	Dom.setStyle("Email_Details"+email_key,'display','');
    }else{
	o.innerHTML='Edit Details';
	o.setAttribute('action','Show');
	Dom.setStyle("Email_Details"+email_key,'display','none');

    }

}

function validate_email(o){
    var email=o.value;
    var email_key=o.getAttribute('email_key');
    
    if(isValidEmail(email)){
	o.setAttribute('valid',1);
	Dom.removeClass(o,'invalid');
    }else{
	o.setAttribute('valid',0);
	Dom.addClass(o,'invalid');
    }

}


function mark_email_to_delete(o){

    var email_key=o.getAttribute('email_key');
    var email=Dom.get('Email'+email_key).value;
    
   
    Dom.setStyle(["email_to_delete"+email_key,'undelete_email_button'+email_key],'display','');
    Dom.setStyle(["Email"+email_key,'delete_email_button'+email_key,"Email_Details"+email_key],'display','none');
    
    Dom.setStyle("Email"+email_key,'display','none');
    Dom.get('email_to_delete'+email_key).innerHTML=email;
    Dom.get('Email'+email_key).setAttribute('to_delete',1);
    //Dom.setStyle('[show_details_email_button'+email_key,"Email_Details"+email_key],'display','none');
    //Dom.get('show_details_email_button'+email_key).innerHTML='Edit Details';
    //Dom.get('show_details_email_button'+email_key).setAttribute('action','Show');
   
    calculate_num_changed_in_email();

}

function unmark_email_to_delete(o){
    var email=o.value;
    var email_key=o.getAttribute('email_key');
    Dom.setStyle(["email_to_delete"+email_key,'undelete_email_button'+email_key],'display','none');
    Dom.setStyle(["Email"+email_key,'delete_email_button'+email_key],'display','');
    
    //Dom.setStyle('[show_details_email_button'+email_key,"Email_Details"+email_key],'display','');
    Dom.get('email_to_delete'+email_key).innerHTML='';
    Dom.get('Email'+email_key).setAttribute('to_delete',0);
    calculate_num_changed_in_email();
}

function add_email(){
    
    if(Number_New_Empty_Emails==0){
	clone_email('new'+Number_New_Emails);
	Number_New_Empty_Emails++;
	Number_New_Emails++;
    }
}

function clone_email(email_key){
   

     var new_email_container = Dom.get('email_mould').cloneNode(true);
     var the_parent=Dom.get('mobile_mould').parentNode;
		    
     var insertedElement = the_parent.insertBefore(new_email_container, Dom.get('mobile_mould'));
     Dom.addClass(insertedElement,'cloned_editor');
     Dom.setStyle(insertedElement,'display','');
     insertedElement.id="tr_email"+email_key;
     insertedElement.setAttribute('email_key',email_key);
     
     var element_array=Dom.getElementsByClassName('show_details_email', 'span',insertedElement);
     element_array[0].setAttribute('email_key',email_key);
     element_array[0].id='show_details_email_button'+email_key;
     
     var element_array=Dom.getElementsByClassName('Email', 'input',insertedElement);
     element_array[0].setAttribute('email_key',email_key);
     element_array[0].id='Email'+email_key;

      var element_array=Dom.getElementsByClassName('Email_Is_Main', 'input',insertedElement);
      element_array[0].setAttribute('email_key',email_key);
      element_array[0].id='Email_Is_Main'+email_key;
     
     var element_array=Dom.getElementsByClassName('Email_Contact_Name', 'input',insertedElement);
     element_array[0].setAttribute('email_key',email_key);
     element_array[0].id='Email_Contact_Name'+email_key;
     
     var element_array=Dom.getElementsByClassName('Email_Description', 'input',insertedElement);
     element_array[0].setAttribute('email_key',email_key);
     element_array[0].id='Email_Description'+email_key;

     var element_array=Dom.getElementsByClassName('email_to_delete', 'span',insertedElement);
     element_array[0].setAttribute('email_key',email_key);
     element_array[0].id='email_to_delete'+email_key;
     
     var element_array=Dom.getElementsByClassName('delete_email', 'span',insertedElement);
     element_array[0].setAttribute('email_key',email_key);
     element_array[0].id='delete_email_button'+email_key;
     
     var element_array=Dom.getElementsByClassName('undelete_email', 'span',insertedElement);
     element_array[0].setAttribute('email_key',email_key);
     element_array[0].id='undelete_email_button'+email_key;
     

     
     var element_array=Dom.getElementsByClassName('Email_Description', 'span',insertedElement);
     for(i in  element_array){
	 var label=element_array[i].getAttribute('label');
	 element_array[i].id="Email_Description_"+label+email_key;

     }
		    
     var element_array=Dom.getElementsByClassName('edit', 'table',insertedElement);
     element_array[0].id="Email_Details"+email_key;
}


function update_is_main_email(o){
    
   
    if(o.value=='Yes'){
	alert('is valid')
	o.checked=true;
	return;
    }




    email_key=o.getAttribute('email_key');
    if(Dom.get('Email'+email_key).getAttribute('valid')==0 || Dom.get('Email'+email_key).value==''){
       
	o.checked=false;
	return;
    }

    var elements_array=Dom.getElementsByClassName('Email_Is_Main', 'input');
    for (i in elements_array){
	var input_element=elements_array[i];
	var email_key=input_element.getAttribute('email_key');
	if( email_key!=null && (email_key.match('new') || email_key>0) ){
	    if(input_element.value=='Yes')
		old_is_main_key=email_key;
	    input_element.value='No';
	    input_element.checked=false;
	    
	}
    }
    
    
    o.value='Yes';
    o.checked=true;
    calculate_num_changed_in_email();
}