
var Contact_Changes=0;
var Contact_Items_Changes=0;
var Contact_Type_Changes=0;
var Contact_Function_Changes=0;


var save_contact=function(){

    var table='contact';
    if(Dom.get('contact_key').value==0)
	create_contact();
    else
	var contact_key=Dom.get('contact_key').value;
    
    save_contact_elements=0;

    

    if(Contact_Items_Changes>0){
	
	items=Contact_Keys;
	

	var value=new Object()
	for(i in items)
	    value[items[i]]=Dom.get('contact_'+items[i]).value;
    
	var json_value = YAHOO.lang.JSON.stringify(value); 
	var request='ar_edit_contacts.php?tipo=edit_'+escape(table)+ '&value=' + json_value+'&id='+contact_key+'&subject='+Subject+'&subject_key='+Subject_Key; 
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //	alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='updated'){
		    Dom.get('contact_display'+contact_key).innerHTML=r.xhtml_contact;
		    
		    for(i in r.updated_data){
			var contact_item_value=r.updated_data[i];
			if(contact_item_value==null)contact_item_value='';
			Contact_Data[contact_key][i]=contact_item_value;
		    }
		    cancel_edit_contact();
		    save_contact_elements++;
		}else if(r.action=='error'){
		    alert(r.msg);
		}
		
		
		
		}
	    });
    }
    
    if(Contact_Type_Changes>0){

	var contact_type_values=new Array();
	var elements_array=Dom.getElementsByClassName('contact_type', 'span');
	for( var i in elements_array ){
	    var element=elements_array[i];
	    var label=element.getAttribute('label');
	    if(Dom.hasClass(element,'selected')){
		contact_type_values.push(label);
	    }
	    
	}

	var json_value = YAHOO.lang.JSON.stringify(contact_type_values); 
	var request='ar_edit_contacts.php?tipo=edit_'+escape(table)+ '_type&value=' + json_value+'&id='+contact_key+'&subject='+Subject+'&subject_key='+Subject_Key; 
		
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.action=='updated'){
			
			
			Contact_Data[contact_key]['type']=r.updated_data;
			cancel_edit_contact();
			save_contact_elements++;
		    }else if(r.action=='error'){
			alert(r.msg);
		    }
		    
		    
		    
		}
	    });


    }
    
    
    
    
    
};


var create_contact=function(){
    
    
    var value=new Object();
    items=Contact_Keys;
    for(i in items)
	value[items[i]]=Dom.get('contact_'+items[i]).value;
    

    var contact_type_values=new Array();
	var elements_array=Dom.getElementsByClassName('contact_type', 'span');
	for( var i in elements_array ){
	    var element=elements_array[i];
	    var label=element.getAttribute('label');
	    if(Dom.hasClass(element,'selected')){
		contact_type_values.push(label);
	    }
	    
	}
    value['type']=contact_type_values;
    
     var contact_function_values=new Array();
	var elements_array=Dom.getElementsByClassName('contact_function', 'span');
	for( var i in elements_array ){
	    var element=elements_array[i];
	    var label=element.getAttribute('label');
	    if(Dom.hasClass(element,'selected')){
		contact_function_values.push(label);
	    }
	    
	}
    value['function']=contact_function_values;



    var json_value = YAHOO.lang.JSON.stringify(value); 
    

    
    var request='ar_edit_contacts.php?tipo=new_contact&value=' + json_value+'&subject='+Subject+'&subject_key='+Subject_Key; 
    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	       	//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){

		    

		    var new_contact_data=new Object;
		    for(i in r.updated_data){
			var contact_item_value=r.updated_data[i];
			if(contact_item_value==null)contact_item_value='';
			new_contact_data[i]=contact_item_value;
		    }

		    Contact_Data[r.contact_key]=new Object;
		    Contact_Data[r.contact_key]=new_contact_data;
		    cancel_edit_contact();
		    
		    var new_contact_container = Dom.get('contact_container0').cloneNode(true);
		    new_contact_container.id = 'contact_containe'+r.contact_key;
		    Dom.setStyle(new_contact_container, 'display', ''); 
		    display_element=Dom.getElementsByClassName('contact_display' ,'div',  new_contact_container);
		    display_element[0].innerHTML=r.xhtml_contact;
		    display_element[0].id = 'contact_display'+r.contact_key;
		    display_element=Dom.getElementsByClassName('contact_buttons' ,'div',  new_contact_container);
		    display_element[0].id = 'contact_buttons'+r.contact_key;
		    display_element=Dom.getElementsByClassName('small_button_edit' ,'span', display_element[0] );
		    display_element[0].id = 'contacts_contact_butto'+r.contact_key;
		    display_element[1].id = 'delete_contact_button'+r.contact_key;
		    display_element[2].id = 'edit_contact_butto'+r.contact_key;
		    display_element[0].setAttribute('contact_id',r.contact_key);
		    display_element[1].setAttribute('contact_id',r.contact_key);
		    display_element[2].setAttribute('contact_id',r.contact_key);


		    //new_contact_container.children[1][0].id='delete_contact_button'+r.contact_key;
		    //new_contact_container.children[1][0].setAttribute('contact_id',r.contact_key);
		    //new_contact_container.children[1][1].id='edit_contact_button'+r.contact_key;
		    //new_contact_container.children[1][1].setAttribute('contact_id',r.contact_key);
		    Dom.get('contact_showcase').appendChild(new_contact_container);

		    //new_contact_container.parent.appendChild(new_contact_container);
		    save_contact_elements++;
		}else if(r.action=='error'){
		    alert(r.msg);
		}
		
		
		
		}
	    });
   
}


var update_contact_buttons=function(){
    if(changes_contact>0){
	 Dom.setStyle(['save_edit_contact'], 'display', ''); 
    }

}


var cancel_edit_contact=function (){
    changes_contact=0;

    index=Dom.get("cancel_edit_contact_button").getAttribute('contact_key');
    Dom.setStyle(['contact_showcase','add_contact_button'], 'display', ''); 
    Dom.setStyle(['contact_form','cancel_edit_contact_button','save_contact_button'], 'display', 'none'); 
    Dom.get("cancel_edit_contact_button").setAttribute('contact_key','');
    Dom.get("contact_messages").innerHTML='';

    
    var elements_to_clean=['Contact_Name','Contact_Salutation','Contact_First_Name','Contact_Surname','Contact_Suffix','Contact_Title','Contact_Profession'];
    for (i in elements_to_clean){
	var element_to_clean=elements_to_clean[i];
	Dom.get(element_to_clean).value='';Dom.get(element_to_clean).setAttribute('ovalue','');
    }
    var elements_to_unselect=Dom.getElementsByClassName('Contact_Gender');
    Dom.removeClass(elements_to_unselect,'selected');
    var elements_to_unselect=Dom.getElementsByClassName('Contact_Salutation');
    Dom.removeClass(elements_to_unselect,'selected');
    Dom.addClass('Contact_Gender_Unknown','selected');

    elements_to_delete=Dom.getElementsByClassName('cloned_editor');
    for (i in elements_to_delete){
	var parent=elements_to_delete[i].parentNode;
	//alert(elements_to_delete.parentNode+' '+elements_to_delete.parent+' '+elements_to_delete)
	parent.removeChild(elements_to_delete[i]);
    }
    
 
	
 


};

var delete_contact=function (e,contact_button){


}






var edit_contact=function (e,contact_button){
    
   
    if(contact_button==false)
	index=0;
    else
	index=contact_button.getAttribute('contact_id')

    Current_Contact_Index=index;
    changes_contact=0;
    Dom.setStyle(['contact_showcase','move_contact_button','add_contact_button'], 'display', 'none'); 
    Dom.setStyle(['contact_form','cancel_edit_contact_button'], 'display', ''); 
    Dom.get("cancel_edit_contact_button").setAttribute('contact_key',index);
    

 
    data=Contact_Data[index];
   
    for (key in data){
	


	if(key=='Name_Data'){
	  
	    var contact_name_parts=data[key];
	    for (key2 in contact_name_parts){

		    if(key2=='Contact_Salutation'){
			Dom.addClass('Contact_Salutation_'+contact_name_parts[key2],'selected');
			
		    }
			
		    item2=Dom.get(key2);
		    item2.value=contact_name_parts[key2];
		    item2.setAttribute('ovalue',contact_name_parts[key2]);
		    
	    }
	}else if(key=='Contact_Gender'){
	    var elements_to_unselect=Dom.getElementsByClassName('Contact_Gender');
	    Dom.removeClass(elements_to_unselect,'selected');
	    Dom.addClass('Contact_Gender_'+data[key],'selected');
		
	}else if(key=='type'){
	    var contact_type=data[key];
	    for (contact_type_key in contact_type){

		Dom.addClass('contact_type_'+contact_type[contact_type_key],'selected')
	    }
	}else if(key=='Emails'){
	    var emails=data[key];
	    for (email_key in emails) {
		    var email_data=emails[email_key];
		    
		    var new_email_container = Dom.get('email_mould').cloneNode(true);
		    var the_parent=Dom.get('mobile_mould').parentNode;
		    var insertedElement = the_parent.insertBefore(new_email_container, Dom.get('mobile_mould'));
		    Dom.addClass(insertedElement,'cloned_editor');
		    Dom.setStyle(insertedElement,'display','');
		    insertedElement.id="tr_email"+email_key;
		    insertedElement.setAttribute('email_key',email_key);
		    var element_array=Dom.getElementsByClassName('Email', 'input',insertedElement);
		    element_array[0].value=email_data['Email'];
		    //var element_array=Dom.getElementsByClassName('Email_Contact_Name', 'input',insertedElement);
		    //element_array[0].value=email_data['Email_Contact_Name'];

		}
		
	    
	    
	}else if(key=='Addresses'){
	    var addresses=data[key];
	    for (address_key in addresses) {
		var address_data=addresses[address_key];
		var new_address_container = Dom.get('address_mould').cloneNode(true);
		var the_parent=Dom.get('last_tr').parentNode;
		var insertedElement = the_parent.insertBefore(new_address_container,Dom.get('last_tr') );
		var element_array=Dom.getElementsByClassName('tr_telecom', 'tr',insertedElement);
		element_array[0].id='telephone_mould'+address_key;
		element_array[1].id='fax_mould'+address_key;
		element_array[2].id='after_fax'+address_key;

		Dom.addClass(insertedElement,'cloned_editor');
		Dom.setStyle(insertedElement,'display','');
		var element_array=Dom.getElementsByClassName('Address', 'td',insertedElement);
		element_array[0].innerHTML=address_data['Address'];


		tels=address_data['Telephones'];
		for(tel_key in tels) {
		    var tel_data=tels[tel_key];
		    var new_tel_container = Dom.get('telephone_mould'+address_key).cloneNode(true);
		    var the_parent=Dom.get('fax_mould'+address_key).parentNode;

		    var insertedElement = the_parent.insertBefore(new_tel_container,Dom.get('fax_mould'+address_key) );
		    Dom.addClass(insertedElement,'cloned_editor');
		    Dom.setStyle(insertedElement,'display','');
		    var element_array=Dom.getElementsByClassName('Telephone', 'input',insertedElement);
		    element_array[0].value=tel_data['Telephone'];
		}
		faxes=address_data['Faxes'];
		for(fax_key in faxes) {
		    var fax_data=faxes[fax_key];
		    var new_fax_container = Dom.get('fax_mould'+address_key).cloneNode(true);
		    var the_parent=Dom.get('after_fax'+address_key).parentNode;
		    var insertedElement = the_parent.insertBefore(new_fax_container,Dom.get('after_fax'+address_key) );
		    Dom.addClass(insertedElement,'cloned_editor');
		    Dom.setStyle(insertedElement,'display','');
		    var element_array=Dom.getElementsByClassName('Fax', 'input',insertedElement);
		    element_array[0].value=fax_data['Fax'];
		}
		

	    }
		


	}else{
	   
	    item=Dom.get(key);
	    item.value=data[key];
	    item.setAttribute('ovalue',data[key]);

	}
	

	
    }
    
    
  }


    var update_contact_labels=function(country_code){
	var labels=new Object();
	
	if(Country_Contact_Labels[country_code]== undefined){
	    return
	}else
	    labels=Country_Contact_Labels[country_code];
	

	for (index in Contact_Keys){
	    key=Contact_Keys[index];
	    
	    if(labels[key]!=undefined){

		if(labels[key].name!=undefined){
		    Dom.get('label_contact_'+key).innerHTML=labels[key].name;
		}

		if(labels[key].in_use!=undefined && !labels[key].in_use){
		    
		    Dom.setStyle('tr_contact_'+key,'display','none');
		}else{
		    Dom.setStyle('tr_contact_'+key,'display','');
		    
		    
		    if(labels[key].hide!=undefined && labels[key].hide){
			Dom.setStyle('tr_contact_'+key,'display','none');
			
			if(key=='country_d1'){
			Dom.setStyle('show_'+key,'display','');
			}
			
		    }else{
			Dom.setStyle('tr_contact_'+key,'display','');
			if(key=='country_d1'){
			    Dom.setStyle('show_'+key,'display','none');
			}
			
		    }
		}
		
	    }
	}
	
    };


var on_contact_type_change=function(){
    Contact_Type_Changes=0
     var contact_type_values=new Array();
     var elements_array=Dom.getElementsByClassName('contact_type', 'span');
     var has_other=false;
     for( var i in elements_array ){
	 var element=elements_array[i];
	 var label=element.getAttribute('label');
	 if(Dom.hasClass(element,'selected')){
	     if(label=='Other')
		 has_other=true;
	     contact_type_values.push(label);
	 }

     }
     if(contact_type_values.length==0){
	 contact_type_values.push('Other');
	 Dom.addClass('contact_type_Other','selected');
     }
     if(has_other && contact_type_values.length>1){
	 contact_type_values.splice(contact_type_values.indexOf('Other'), 1);
	 Dom.removeClass('contact_type_Other','selected');
     }
     
     ovalue=Contact_Data[Current_Contact_Index]['type']
     if(!same_arrays(ovalue,contact_type_values))
	 Contact_Type_Changes++; 

     
     render_after_contact_item_change();
}

var on_contact_function_change=function(){
    Contact_Function_Changes=0
     var contact_function_values=new Array();
     var elements_array=Dom.getElementsByClassName('contact_function', 'span');
     var has_other=false;
     for( var i in elements_array ){
	 var element=elements_array[i];
	 var label=element.getAttribute('label');
	 if(Dom.hasClass(element,'selected')){
	     if(label=='Other')
		 has_other=true;
	     contact_function_values.push(label);
	 }

     }
     if(contact_function_values.length==0){
	 contact_function_values.push('Other');
	 Dom.addClass('contact_function_Other','selected');
     }
     if(has_other && contact_function_values.length>1){
	 contact_function_values.splice(contact_function_values.indexOf('Other'), 1);
	 Dom.removeClass('contact_function_Other','selected');
     }

     ovalue=Contact_Data[Current_Contact_Index]['function']
     if(!same_arrays(ovalue,contact_function_values))
	 Contact_Function_Changes++; 
     render_after_contact_item_change();
}




var on_contact_item_change=function(){
    
    Contact_Items_Changes=0;
     var items=Contact_Keys;
     for ( var i in items )
	 {
	     key=items[i];
	     // alert(key +' '+Dom.get('contact_'+key).value);
	     if(Dom.get('contact_'+key).value!=Dom.get('contact_'+key).getAttribute('ovalue')){
		 Contact_Items_Changes++; 
	     } 
	 }
     

     render_after_contact_item_change();

     
}


    var render_after_contact_item_change=function(){
	Contact_Changes=Contact_Items_Changes+Contact_Function_Changes+Contact_Type_Changes;
	
	if(Contact_Changes==0){
	    Dom.get('contact_messages').innerHTML='';
	    Dom.setStyle(['save_contact_button', 'cancel_save_contact_button'], 'display', 'none'); 
	}else if (Contact_Changes==1){
	    Dom.get('contact_messages').innerHTML=Contact_Changes+'<?php echo' '._('change')?>';
	    Dom.setStyle(['save_contact_button', 'cancel_save_contact_button'], 'display', ''); 
	}else{
	    Dom.get('contact_messages').innerHTML=Contact_Changes+'<?php echo' '._('changes')?>';
	    Dom.setStyle(['save_contact_button', 'cancel_save_contact_button'], 'display', ''); 
	}
    }


var toggle_contact_type=function (o){
    if(Dom.hasClass(o, 'selected')){
	Dom.removeClass(o, 'selected')
    }else{
	Dom.addClass(o, 'selected')
    }
     on_contact_type_change();
};

var toggle_contact_function=function (o){
    if(Dom.hasClass(o, 'selected')){
	Dom.removeClass(o, 'selected')
    }else{
	Dom.addClass(o, 'selected')
    }
     on_contact_function_change();
};
var show_description=function (){
    Dom.setStyle(['tr_contact_description','hide_description'],'display','');
    Dom.setStyle('show_description','display','none');
};

var hide_description=function (){
    Dom.setStyle(['tr_contact_description','hide_description'],'display','none');
    Dom.setStyle('show_description','display','');
};

var toggle_country_d1=function (){
    Dom.setStyle(['tr_contact_country_d1','show_country_d2'],'display','');
    Dom.setStyle('show_country_d1','display','none');
    Dom.get('show_country_d2').innerHTML='x';
   
};
var toggle_country_d2=function (){
    if(Dom.get("show_country_d2").innerHTML=='x'){
	Dom.setStyle('show_country_d1','display','');
	Dom.setStyle('tr_contact_country_d1','display','none');
    }
    
}


 var toggle_town_d1=function (){
     
     Dom.setStyle('tr_contact_town_d1','display','');
     Dom.setStyle('show_town_d1','display','none');
     Dom.get("show_town_d2").innerHTML='x';

  }
 
var toggle_town_d2=function (){
    if(Dom.get("show_town_d2").innerHTML=='x'){
	Dom.setStyle('show_town_d1','display','');
	Dom.setStyle('tr_contact_town_d1','display','none');
	
    }
  }

var match_country = function(sQuery) {
        // Case insensitive matching
        var query = sQuery.toLowerCase(),
            contact,
            i=0,
            l=Country_List.length,
            matches = [];
        
        // Match against each name of each contact
        for(; i<l; i++) {
            contact = Country_List[i];
            if((contact.name.toLowerCase().indexOf(query) > -1) ||
	       (contact.code.toLowerCase().indexOf(query) > -1))  {
                matches[matches.length] = contact;
            }
        }

        return matches;
    };


 var contact_name_changed=function (o){
     
     parse_name(o.value);
 };


     
var parse_name=function(name){
    
    var salutation=trim(Dom.get('Contact_Salutation').value);
    var first_name=trim(Dom.get('Contact_First_Name').value);
    var surname=trim(Dom.get('Contact_Surname').value);
    var suffix=trim(Dom.get('Contact_Suffix').value);
    
    number_components=0;
    if(salutation!='')number_components++;
    if(surname!='')number_components++;
    if(first_name!='')number_components++;
    if(suffix!='')number_components++;
    
    name= trim(name);

    if(name==''){
	set_salutation('');
	Dom.get('Contact_First_Name').value='';
	Dom.get('Contact_Surname').value='';
	return;
    }

    var proposed_name_components = name.split(/\s+/); 
    
    proposed_number_components=proposed_name_components.length;
   

    if(set_salutation(proposed_name_components[0]))
	    proposed_name_components.splice(0, 1);


   

   
    proposed_number_components=proposed_name_components.length;

    //  alert(proposed_number_components+' '+proposed_name_components);
    if(proposed_number_components==0){
	Dom.get('Contact_First_Name').value='';
	Dom.get('Contact_Surname').value='';
    }else if(proposed_number_components==1){


	if(surname!=''){
	    Dom.get('Contact_Surname').value=proposed_name_components[0];
	    Dom.get('Contact_First_Name').value='';
	}else{
	    Dom.get('Contact_First_Name').value=proposed_name_components[0];
	    Dom.get('Contact_Surname').value='';
	}


    }else if(proposed_number_components==2){

	if(surname==proposed_name_components[0]+' '+proposed_name_components[1]){
	    Dom.get('Contact_Surname').value=proposed_name_components[0]+' '+proposed_name_components[1];
	    Dom.get('Contact_First_Name').value='';
	}else if(first_name==proposed_name_components[0]+' '+proposed_name_components[1]){
	    Dom.get('Contact_First_Name').value=proposed_name_components[0]+' '+proposed_name_components[1];
	    Dom.get('Contact_Surname').value='';
	}else{
	    Dom.get('Contact_First_Name').value=proposed_name_components[0];
	    Dom.get('Contact_Surname').value=proposed_name_components[1];
	}
	    

	    




    }else if(proposed_number_components>2){

	
	Dom.get('Contact_Surname').value=proposed_name_components[proposed_number_components-1];
	proposed_name_components.splice(proposed_number_components-1, 1);
	first_name='';
	for (i in proposed_name_components){
	    first_name=first_name+' '+proposed_name_components[i];
	}
	Dom.get('Contact_First_Name').value=trim(first_name);

    }
	    
    
};


var is_salutation=function(string){
    
    if(string.match(/^(mr|mrs|miss)$/i))
	return true;
    else
	return false;
}


var set_salutation=function(string){
    var elements_to_unselect=Dom.getElementsByClassName('Contact_Salutation','span');
    Dom.removeClass(elements_to_unselect,'selected');
    if(is_salutation(string)){
	
	string=ucwords(string);

	Dom.addClass('Contact_Salutation_'+string,'selected');
	Dom.get('Contact_Salutation').value=string;
	return true;
    }else{
	Dom.get('Contact_Salutation').value='';
	return false;
	
    }
}




function calculate_num_changed_in_personal(){
    var changes=0;
   
    var items=['Contact_First_Name','Contact_Salutation','Contact_Surname'];
    
    for (i in items){
	var item=Dom.get(items[i]);
	if(item.getAttribute('ovalue')!=item.value)
	    changes++;
    }
    
    Dom.get("personal_num_changes").innerHTML=changes;

}


function update_salutation(o){
    if(Dom.hasClass(o, 'selected'))
	return;
    Dom.removeClass(current_salutation, 'selected');
    Dom.addClass(o, 'selected');

    Dom.get('Contact_Salutation').value=o.getAttribute('label');
    current_salutation=o.id;
    calculate_num_changed_in_personal();
    update_full_name();

}

function update_full_name(){
    var full_address=trim(Dom.get("Contact_Salutation").value+' '+Dom.get("Contact_First_Name").value+' '+Dom.get("Contact_Surname").value);
    Dom.get("Contact_Name").value=full_address;

}
