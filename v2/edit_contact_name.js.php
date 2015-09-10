
function calculate_num_changed_in_name(){
    var changes=0;
   
    var items=Contact_Name_Keys;
    
    for (i in items){

	var item=Dom.get(items[i]);

	if(item.getAttribute('ovalue')!=item.value)
	    changes++;
    }
    
    if(changes>1)
	changes=1;
    Contact_Name_Changes=changes;
    

    render_after_contact_item_change();
}






var contact_name_changed=function (o){
    parse_name(o.value);
    calculate_num_changed_in_name();
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



function update_salutation(o){
    if(Dom.hasClass(o, 'selected'))
	return;
    current_salutation=Dom.get('Contact_Salutation_'+Dom.get('Contact_Salutation').value);
    //alert();
    Dom.removeClass(current_salutation, 'selected');
    Dom.addClass(o, 'selected');
    Dom.get('Contact_Salutation').value=o.getAttribute('label');
    current_salutation=o.id;
    calculate_num_changed_in_name();
    update_full_name();
}

function name_component_change(){
    calculate_num_changed_in_name();
    update_full_name();
}

function update_full_name(){
    var full_address=trim(Dom.get("Contact_Salutation").value+' '+Dom.get("Contact_First_Name").value+' '+Dom.get("Contact_Surname").value);
    Dom.get("Contact_Name").value=full_address;

}




