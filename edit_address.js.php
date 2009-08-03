<?php
include_once('country_address_labels.js.php')
?>

var Address_Changes=0;
var Address_Items_Changes=0;
var Address_Type_Changes=0;
var Address_Function_Changes=0;


var save_address=function(){

    var table='address';
    if(Dom.get('address_key').value==0)
	create_address();
    else
	var address_key=Dom.get('address_key').value;
    
    save_address_elements=0;

    

    if(Address_Items_Changes>0){
	
	items=Address_Keys;
	

	var value=new Object()
	for(i in items)
	    value[items[i]]=Dom.get('address_'+items[i]).value;
    
	var json_value = YAHOO.lang.JSON.stringify(value); 
	var request='ar_edit_contacts.php?tipo=edit_'+escape(table)+ '&value=' + json_value+'&id='+address_key+'&subject='+Subject+'&subject_key='+Subject_Key; 
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //	alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='updated'){
		    Dom.get('address_display'+address_key).innerHTML=r.xhtml_address;
		    
		    for(i in r.updated_data){
			var address_item_value=r.updated_data[i];
			if(address_item_value==null)address_item_value='';
			Address_Data[address_key][i]=address_item_value;
		    }
		    cancel_edit_address();
		    save_address_elements++;
		}else if(r.action=='error'){
		    alert(r.msg);
		}
		
		
		
		}
	    });
    }
    
    if(Address_Type_Changes>0){

	var address_type_values=new Array();
	var elements_array=Dom.getElementsByClassName('address_type', 'span');
	for( var i in elements_array ){
	    var element=elements_array[i];
	    var label=element.getAttribute('label');
	    if(Dom.hasClass(element,'selected')){
		address_type_values.push(label);
	    }
	    
	}

	var json_value = YAHOO.lang.JSON.stringify(address_type_values); 
	var request='ar_edit_contacts.php?tipo=edit_'+escape(table)+ '_type&value=' + json_value+'&id='+address_key+'&subject='+Subject+'&subject_key='+Subject_Key; 
		
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.action=='updated'){
			
			
			Address_Data[address_key]['type']=r.updated_data;
			cancel_edit_address();
			save_address_elements++;
		    }else if(r.action=='error'){
			alert(r.msg);
		    }
		    
		    
		    
		}
	    });


    }
    
    
    
    
    
};


var create_address=function(){
    
    
    var value=new Object();
    items=Address_Keys;
    for(i in items)
	value[items[i]]=Dom.get('address_'+items[i]).value;
    

    var address_type_values=new Array();
	var elements_array=Dom.getElementsByClassName('address_type', 'span');
	for( var i in elements_array ){
	    var element=elements_array[i];
	    var label=element.getAttribute('label');
	    if(Dom.hasClass(element,'selected')){
		address_type_values.push(label);
	    }
	    
	}
    value['type']=address_type_values;
    
     var address_function_values=new Array();
	var elements_array=Dom.getElementsByClassName('address_function', 'span');
	for( var i in elements_array ){
	    var element=elements_array[i];
	    var label=element.getAttribute('label');
	    if(Dom.hasClass(element,'selected')){
		address_function_values.push(label);
	    }
	    
	}
    value['function']=address_function_values;



    var json_value = YAHOO.lang.JSON.stringify(value); 
    

    
    var request='ar_edit_contacts.php?tipo=new_address&value=' + json_value+'&subject='+Subject+'&subject_key='+Subject_Key; 
    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	       	//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){

		    

		    var new_address_data=new Object;
		    for(i in r.updated_data){
			var address_item_value=r.updated_data[i];
			if(address_item_value==null)address_item_value='';
			new_address_data[i]=address_item_value;
		    }

		    Address_Data[r.address_key]=new Object;
		    Address_Data[r.address_key]=new_address_data;
		    cancel_edit_address();
		    
		    var new_address_container = Dom.get('address_container0').cloneNode(true);
		    new_address_container.id = 'address_containe'+r.address_key;
		    Dom.setStyle(new_address_container, 'display', ''); 
		    display_element=Dom.getElementsByClassName('address_display' ,'div',  new_address_container);
		    display_element[0].innerHTML=r.xhtml_address;
		    display_element[0].id = 'address_display'+r.address_key;
		    display_element=Dom.getElementsByClassName('address_buttons' ,'div',  new_address_container);
		    display_element[0].id = 'address_buttons'+r.address_key;
		    display_element=Dom.getElementsByClassName('small_button_edit' ,'span', display_element[0] );
		    display_element[0].id = 'contacts_address_butto'+r.address_key;
		    display_element[1].id = 'delete_address_button'+r.address_key;
		    display_element[2].id = 'edit_address_butto'+r.address_key;
		    display_element[0].setAttribute('address_id',r.address_key);
		    display_element[1].setAttribute('address_id',r.address_key);
		    display_element[2].setAttribute('address_id',r.address_key);


		    //new_address_container.children[1][0].id='delete_address_button'+r.address_key;
		    //new_address_container.children[1][0].setAttribute('address_id',r.address_key);
		    //new_address_container.children[1][1].id='edit_address_button'+r.address_key;
		    //new_address_container.children[1][1].setAttribute('address_id',r.address_key);
		    Dom.get('address_showcase').appendChild(new_address_container);

		    //new_address_container.parent.appendChild(new_address_container);
		    save_address_elements++;
		}else if(r.action=='error'){
		    alert(r.msg);
		}
		
		
		
		}
	    });
   
}


var update_address_buttons=function(){
    if(changes_address>0){
	 Dom.setStyle(['save_edit_address'], 'display', ''); 
    }

}


var cancel_edit_address=function (){
    changes_address=0;
    index=Dom.get("cancel_edit_address").getAttribute('address_key');
    Dom.setStyle(['address_showcase','move_address_button','add_address_button'], 'display', ''); 
    Dom.setStyle(['address_form','cancel_edit_address','save_address_button'], 'display', 'none'); 
    Dom.get("cancel_edit_address").setAttribute('address_key','');
    Dom.get("address_messages").innerHTML='';

    data=Address_Data[index];
    for (key in data){
	
	item=Dom.get('address_'+key);
	item.value='';
	item.setAttribute('ovalue','');

	//alert(key);
	
	var elements_array=Dom.getElementsByClassName('address_function', 'span');
	for( var i in elements_array ){
	    Dom.removeClass(elements_array[i],'selected');
	}

	if(key=='function'){
	    var address_function=data[key];
	    for (address_function_key in address_function){
		Dom.addClass('address_function_'+address_function[address_function_key],'selected')
	    }
	}
	var elements_array=Dom.getElementsByClassName('address_type', 'span');
	for( var i in elements_array ){
	    Dom.removeClass(elements_array[i],'selected');
	}
	if(key=='type'){
	    var address_type=data[key];
	    for (address_type_key in address_type){

		Dom.addClass('address_type_'+address_type[address_type_key],'selected')
	    }
	}

	
    }


};

var delete_address=function (e,address_button){


}


var edit_address=function (e,address_button){
    if(address_button==false)
	index=0;
    else
	index=address_button.getAttribute('address_id')

    Current_Address_Index=index;
    changes_address=0;
    Dom.setStyle(['address_showcase','move_address_button','add_address_button'], 'display', 'none'); 
    Dom.setStyle(['address_form','cancel_edit_address'], 'display', ''); 
    Dom.get("cancel_edit_address").setAttribute('address_key',index);
   

 
    data=Address_Data[index];
   
    for (key in data){
	item=Dom.get('address_'+key);
	item.value=data[key];
	item.setAttribute('ovalue',data[key]);
	
	if(key=='country_code')
	    update_address_labels(data[key]);

	if(key=='function'){
	    var address_function=data[key];
	    for (address_function_key in address_function){
		Dom.addClass('address_function_'+address_function[address_function_key],'selected')
	    }
	}
	if(key=='type'){
	    var address_type=data[key];
	    for (address_type_key in address_type){

		Dom.addClass('address_type_'+address_type[address_type_key],'selected')
	    }
	}
	

	
    }
    
    
  }


    var update_address_labels=function(country_code){
	var labels=new Object();
	
	if(Country_Address_Labels[country_code]== undefined){
	    return
	}else
	    labels=Country_Address_Labels[country_code];
	

	for (index in Address_Keys){
	    key=Address_Keys[index];
	    
	    if(labels[key]!=undefined){

		if(labels[key].name!=undefined){
		    Dom.get('label_address_'+key).innerHTML=labels[key].name;
		}

		if(labels[key].in_use!=undefined && !labels[key].in_use){
		    
		    Dom.setStyle('tr_address_'+key,'display','none');
		}else{
		    Dom.setStyle('tr_address_'+key,'display','');
		    
		    
		    if(labels[key].hide!=undefined && labels[key].hide){
			Dom.setStyle('tr_address_'+key,'display','none');
			
			if(key=='country_d1'){
			Dom.setStyle('show_'+key,'display','');
			}
			
		    }else{
			Dom.setStyle('tr_address_'+key,'display','');
			if(key=='country_d1'){
			    Dom.setStyle('show_'+key,'display','none');
			}
			
		    }
		}
		
	    }
	}
	
    };


var on_address_type_change=function(){
    Address_Type_Changes=0
     var address_type_values=new Array();
     var elements_array=Dom.getElementsByClassName('address_type', 'span');
     var has_other=false;
     for( var i in elements_array ){
	 var element=elements_array[i];
	 var label=element.getAttribute('label');
	 if(Dom.hasClass(element,'selected')){
	     if(label=='Other')
		 has_other=true;
	     address_type_values.push(label);
	 }

     }
     if(address_type_values.length==0){
	 address_type_values.push('Other');
	 Dom.addClass('address_type_Other','selected');
     }
     if(has_other && address_type_values.length>1){
	 address_type_values.splice(address_type_values.indexOf('Other'), 1);
	 Dom.removeClass('address_type_Other','selected');
     }
     
     ovalue=Address_Data[Current_Address_Index]['type']
     if(!same_arrays(ovalue,address_type_values))
	 Address_Type_Changes++; 

     
     render_after_address_item_change();
}

var on_address_function_change=function(){
    Address_Function_Changes=0
     var address_function_values=new Array();
     var elements_array=Dom.getElementsByClassName('address_function', 'span');
     var has_other=false;
     for( var i in elements_array ){
	 var element=elements_array[i];
	 var label=element.getAttribute('label');
	 if(Dom.hasClass(element,'selected')){
	     if(label=='Other')
		 has_other=true;
	     address_function_values.push(label);
	 }

     }
     if(address_function_values.length==0){
	 address_function_values.push('Other');
	 Dom.addClass('address_function_Other','selected');
     }
     if(has_other && address_function_values.length>1){
	 address_function_values.splice(address_function_values.indexOf('Other'), 1);
	 Dom.removeClass('address_function_Other','selected');
     }

     ovalue=Address_Data[Current_Address_Index]['function']
     if(!same_arrays(ovalue,address_function_values))
	 Address_Function_Changes++; 
     render_after_address_item_change();
}




var on_address_item_change=function(){
    
    Address_Items_Changes=0;
     var items=Address_Keys;
     for ( var i in items )
	 {
	     key=items[i];
	     // alert(key +' '+Dom.get('address_'+key).value);
	     if(Dom.get('address_'+key).value!=Dom.get('address_'+key).getAttribute('ovalue')){
		 Address_Items_Changes++; 
	     } 
	 }
     

     render_after_address_item_change();

     
}


    var render_after_address_item_change=function(){
	Address_Changes=Address_Items_Changes+Address_Function_Changes+Address_Type_Changes;
	
	if(Address_Changes==0){
	    Dom.get('address_messages').innerHTML='';
	    Dom.setStyle(['save_address_button', 'cancel_save_address_button'], 'display', 'none'); 
	}else if (Address_Changes==1){
	    Dom.get('address_messages').innerHTML=Address_Changes+'<?php echo' '._('change')?>';
	    Dom.setStyle(['save_address_button', 'cancel_save_address_button'], 'display', ''); 
	}else{
	    Dom.get('address_messages').innerHTML=Address_Changes+'<?php echo' '._('changes')?>';
	    Dom.setStyle(['save_address_button', 'cancel_save_address_button'], 'display', ''); 
	}
    }


var toggle_address_type=function (o){
    if(Dom.hasClass(o, 'selected')){
	Dom.removeClass(o, 'selected')
    }else{
	Dom.addClass(o, 'selected')
    }
     on_address_type_change();
};

var toggle_address_function=function (o){
    if(Dom.hasClass(o, 'selected')){
	Dom.removeClass(o, 'selected')
    }else{
	Dom.addClass(o, 'selected')
    }
     on_address_function_change();
};
var show_description=function (){
    Dom.setStyle(['tr_address_description','hide_description'],'display','');
    Dom.setStyle('show_description','display','none');
};

var hide_description=function (){
    Dom.setStyle(['tr_address_description','hide_description'],'display','none');
    Dom.setStyle('show_description','display','');
};

var toggle_country_d1=function (){
    Dom.setStyle(['tr_address_country_d1','show_country_d2'],'display','');
    Dom.setStyle('show_country_d1','display','none');
    Dom.get('show_country_d2').innerHTML='x';
   
};
var toggle_country_d2=function (){
    if(Dom.get("show_country_d2").innerHTML=='x'){
	Dom.setStyle('show_country_d1','display','');
	Dom.setStyle('tr_address_country_d1','display','none');
    }
    
}


 var toggle_town_d1=function (){
     
     Dom.setStyle('tr_address_town_d1','display','');
     Dom.setStyle('show_town_d1','display','none');
     Dom.get("show_town_d2").innerHTML='x';

  }
 
var toggle_town_d2=function (){
    if(Dom.get("show_town_d2").innerHTML=='x'){
	Dom.setStyle('show_town_d1','display','');
	Dom.setStyle('tr_address_town_d1','display','none');
	
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