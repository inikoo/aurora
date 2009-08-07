
function calculate_num_changed_in_telecom(){
    var changed=new Object();
        var to_delete=0;
    var invalid=0;
    var new_telecom=0;
    var elements_array=Dom.getElementsByClassName('Telecom', 'input');
    for( var i in elements_array ){
	var input_element=elements_array[i];
	var telecom_key=input_element.getAttribute('telecom_key');

	if(telecom_key.match('new')){

	    if(input_element.value!='' && input_element.getAttribute('valid')==1 && input_element.getAttribute('to_delete')==0 )
		new_telecom++;
	}else if(input_element.getAttribute('to_delete')==1){
	    to_delete++;
	}else if(telecom_key>0  && input_element.getAttribute('ovalue')!=input_element.value){
	  
		changed[telecom_key]=1;
		if(input_element.getAttribute('valid')==0)
		    invalid++;
	 
	    
	}
    }
    var elements_array=Dom.getElementsByClassName('Telecom_Description', 'input');
    for( var i in elements_array ){
	var input_element=elements_array[i];
	var telecom_key=input_element.getAttribute('telecom_key');
	if(telecom_key>0  && input_element.getAttribute('ovalue')!=input_element.value)
	    changed[telecom_key]=1;
    }
     var elements_array=Dom.getElementsByClassName('Telecom_Contact_Name', 'input');
    for( var i in elements_array ){
	var input_element=elements_array[i];
	var telecom_key=input_element.getAttribute('telecom_key');
	if(telecom_key>0  && input_element.getAttribute('ovalue')!=input_element.value)
	    changed[telecom_key]=1;
    }
    var elements_array=Dom.getElementsByClassName('Telecom_Is_Main', 'input');
    for( var i in elements_array ){
	var input_element=elements_array[i];
	var telecom_key=input_element.getAttribute('telecom_key');
	if(telecom_key>0  && input_element.getAttribute('ovalue')!=input_element.value){
	    changed[telecom_key]=1;
	    break;
	}
	
    }


    var changes=0;
    for(i in changed)
	changes++;
    
    Contact_Telecom_Changes=changes-invalid+to_delete+new_telecom;
    Contact_Telecoms_to_edit=changes-invalid;
    Contact_Telecoms_to_delete=to_delete;
    Contact_Telecoms_to_add=new_telecom;


    render_after_contact_item_change();


}


function telecom_change(){
    calculate_num_changed_in_telecom();
    render_after_contact_item_change();

}


function show_details_telecom(o){
    var action=o.getAttribute('action');
    var telecom_key=o.getAttribute('telecom_key');

    if(action=='Show'){
	o.innerHTML='Hide Details';
	o.setAttribute('action','Hide');
	Dom.setStyle("Telecom_Details"+telecom_key,'display','');
    }else{
	o.innerHTML='Edit Details';
	o.setAttribute('action','Show');
	Dom.setStyle("Telecom_Details"+telecom_key,'display','none');

    }

}

function validate_telecom(o){
    var telecom=o.value;
    var telecom_key=o.getAttribute('telecom_key');
    
    if(isValidTelecom(telecom)){
	o.setAttribute('valid',1);
	Dom.removeClass(o,'invalid');
    }else{
	o.setAttribute('valid',0);
	Dom.addClass(o,'invalid');
    }

}


function mark_telecom_to_delete(o){

    var telecom_key=o.getAttribute('telecom_key');
    var telecom=Dom.get('Telecom'+telecom_key).value;
    
   
    Dom.setStyle(["telecom_to_delete"+telecom_key,'undelete_telecom_button'+telecom_key],'display','');
    Dom.setStyle(["Telecom"+telecom_key,'delete_telecom_button'+telecom_key,"Telecom_Details"+telecom_key],'display','none');
    
    Dom.setStyle("Telecom"+telecom_key,'display','none');
    Dom.get('telecom_to_delete'+telecom_key).innerHTML=telecom;
    Dom.get('Telecom'+telecom_key).setAttribute('to_delete',1);
    //Dom.setStyle('[show_details_telecom_button'+telecom_key,"Telecom_Details"+telecom_key],'display','none');
    //Dom.get('show_details_telecom_button'+telecom_key).innerHTML='Edit Details';
    //Dom.get('show_details_telecom_button'+telecom_key).setAttribute('action','Show');
   
    calculate_num_changed_in_telecom();

}

function unmark_telecom_to_delete(o){
    var telecom=o.value;
    var telecom_key=o.getAttribute('telecom_key');
    Dom.setStyle(["telecom_to_delete"+telecom_key,'undelete_telecom_button'+telecom_key],'display','none');
    Dom.setStyle(["Telecom"+telecom_key,'delete_telecom_button'+telecom_key],'display','');
    
    //Dom.setStyle('[show_details_telecom_button'+telecom_key,"Telecom_Details"+telecom_key],'display','');
    Dom.get('telecom_to_delete'+telecom_key).innerHTML='';
    Dom.get('Telecom'+telecom_key).setAttribute('to_delete',0);
    calculate_num_changed_in_telecom();
}

function add_telecom(description){
    
    if(Number_New_Empty_Telecoms==0){

	var telecom_key='new'+Number_New_Telecoms;
	clone_telecom(telecom_key);
	Dom.get('Telecom_Contact_Name'+telecom_key).value=Dom.get('Contact_Name').value;
	if(description== undefined)
	    description='Work';
	Dom.get('Telecom_Description'+telecom_key).value=description;
	Dom.get('Telecom_Is_Main'+telecom_key).value='No';
	Number_New_Empty_Telecoms++;
	Number_New_Telecoms++;
    }
}

function clone_telecom(mould_key,telecom_key){
   
  

     var new_telecom_container = Dom.get(mould_key).cloneNode(true);
     var the_parent=Dom.get(mould_key).parentNode;
		    
     var insertedElement = the_parent.insertBefore(new_telecom_container, Dom.get(mould_key));
     Dom.addClass(insertedElement,'cloned_editor');
     Dom.setStyle(insertedElement,'display','');
     insertedElement.id="tr_telecom"+telecom_key;
     insertedElement.setAttribute('telecom_key',telecom_key);
     
     var element_array=Dom.getElementsByClassName('show_details_telecom', 'span',insertedElement);
     element_array[0].setAttribute('telecom_key',telecom_key);
     element_array[0].id='show_details_telecom_button'+telecom_key;
     
     var element_array=Dom.getElementsByClassName('Telecom', 'input',insertedElement);
     element_array[0].setAttribute('telecom_key',telecom_key);
     element_array[0].id='Telecom'+telecom_key;

      var element_array=Dom.getElementsByClassName('Telecom_Is_Main', 'input',insertedElement);
      element_array[0].setAttribute('telecom_key',telecom_key);
      element_array[0].id='Telecom_Is_Main'+telecom_key;
     
     var element_array=Dom.getElementsByClassName('Telecom_Contact_Name', 'input',insertedElement);
     element_array[0].setAttribute('telecom_key',telecom_key);
     element_array[0].id='Telecom_Contact_Name'+telecom_key;
     
     var element_array=Dom.getElementsByClassName('Telecom_Description', 'input',insertedElement);
     element_array[0].setAttribute('telecom_key',telecom_key);
     element_array[0].id='Telecom_Description'+telecom_key;

     var element_array=Dom.getElementsByClassName('telecom_to_delete', 'span',insertedElement);
     element_array[0].setAttribute('telecom_key',telecom_key);
     element_array[0].id='telecom_to_delete'+telecom_key;
     
     var element_array=Dom.getElementsByClassName('delete_telecom', 'span',insertedElement);
     element_array[0].setAttribute('telecom_key',telecom_key);
     element_array[0].id='delete_telecom_button'+telecom_key;
     
     var element_array=Dom.getElementsByClassName('undelete_telecom', 'span',insertedElement);
     element_array[0].setAttribute('telecom_key',telecom_key);
     element_array[0].id='undelete_telecom_button'+telecom_key;
     

     
     var element_array=Dom.getElementsByClassName('Telecom_Description', 'span',insertedElement);
     for(i in  element_array){
	 var label=element_array[i].getAttribute('label');
	 element_array[i].id="Telecom_Description_"+label+telecom_key;

     }
		    
     var element_array=Dom.getElementsByClassName('edit', 'table',insertedElement);
     element_array[0].id="Telecom_Details"+telecom_key;
}


function update_is_main_telecom(o){
    
   
    if(o.value=='Yes'){
	alert('is valid')
	o.checked=true;
	return;
    }




    telecom_key=o.getAttribute('telecom_key');
    if(Dom.get('Telecom'+telecom_key).getAttribute('valid')==0 || Dom.get('Telecom'+telecom_key).value==''){
       
	o.checked=false;
	return;
    }

    var elements_array=Dom.getElementsByClassName('Telecom_Is_Main', 'input');
    for (i in elements_array){
	var input_element=elements_array[i];
	var telecom_key=input_element.getAttribute('telecom_key');
	if( telecom_key!=null && (telecom_key.match('new') || telecom_key>0) ){
	    if(input_element.value=='Yes')
		old_is_main_key=telecom_key;
	    input_element.value='No';
	    input_element.checked=false;
	    
	}
    }
    
    
    o.value='Yes';
    o.checked=true;
    calculate_num_changed_in_telecom();
}

function set_main_telecom(main_telecom_key){

    var telecoms=data['Telecoms'];
    for (i in telecoms){
	if(i==main_telecom_key)
	    data['Telecoms'][i]['Telecom_Is_Main']='Yes';
	else
	    data['Telecoms'][i]['Telecom_Is_Main']='No';
    }
	
}
