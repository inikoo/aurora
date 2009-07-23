var save_address=function(){
    
    if(!Dom.get('address_key').value)
	create_address();
    else
	var address_key=Dom.get('address_key').value;
    items=Address_Keys;
    var table='address';
    save_address_elements=0;
    for ( var i in items )
	{
	    var key=items[i];
	    var value=Dom.get(items[i]).value;
	    var request='ar_edit_contacts.php?tipo=edit_'+escape(table)+'&key=' + key + '&value=' + escape(value)+'&id='+address_key; 

  YAHOO.util.Connect.asyncRequest('POST',request ,{
		    success:function(o) {
			//alert(o.responseText);
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.action=='updated'){
			    Dom.get(items[i]).value=r.value;
			    Dom.get(items[i]).getAttribute('ovalue')=Dom.get(items[i]).value;
			    save_address_elements++;
			}else if(r.action=='error'){
			    alert(r.msg);
			}
			    

			
		    }
		});
	} 

}


    var create_address=function(){

	alert('creating address');
    }


var update_address_buttons=function(){
    if(changes_address>0){
	 Dom.setStyle(['save_edit_address'], 'display', ''); 
    }

}


var cancel_edit_address=function (){
    changes_address=0;
    index=Dom.get("cancel_edit_address").getAttribute('address_index');
    Dom.setStyle(['address_showcase','move_address_button','add_address_button'], 'display', ''); 
    Dom.setStyle(['address_form','cancel_edit_address'], 'display', 'none'); 
    Dom.get("cancel_edit_address").setAttribute('address_index','');
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


var edit_address=function (e,index){
    
    Current_Address_Index=index;
    changes_address=0;
    Dom.setStyle(['address_showcase','move_address_button','add_address_button'], 'display', 'none'); 
    Dom.setStyle(['address_form','cancel_edit_address'], 'display', ''); 
    Dom.get("cancel_edit_address").setAttribute('address_index',index);
    
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
	
	if(Country_Address_Labels[0][country_code]== undefined){
	    return
	}else
	    labels=Country_Address_Labels[0][country_code];
	

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

var update_address=function(){
     var changes=0;
    
     var items=Address_Keys;
     for ( var i in items )
	 {
	     key=items[i];
	     // alert(key +' '+Dom.get('address_'+key).value);
	     if(Dom.get('address_'+key).value!=Dom.get('address_'+key).getAttribute('ovalue')){
		 changes++; 
	     } 
	 }
     
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
	 changes++; 


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
	 changes++; 

	 

    if(changes==0){
	Dom.get('address_messages').innerHTML='';
	Dom.setStyle(['save_address_button', 'cancel_save_address_button'], 'display', 'none'); 
    }else if (changes==1){
	Dom.get('address_messages').innerHTML=changes+'<?=' '._('change')?>';
	Dom.setStyle(['save_address_button', 'cancel_save_address_button'], 'display', ''); 
    }else{
	Dom.get('address_messages').innerHTML=changes+'<?=' '._('changes')?>';
	Dom.setStyle(['save_address_button', 'cancel_save_address_button'], 'display', ''); 
    }
}


var toggle_address_type=function (o){
    if(Dom.hasClass(o, 'selected')){
	Dom.removeClass(o, 'selected')
    }else{
	Dom.addClass(o, 'selected')
    }
     update_address();
};

var toggle_address_function=function (o){
    if(Dom.hasClass(o, 'selected')){
	Dom.removeClass(o, 'selected')
    }else{
	Dom.addClass(o, 'selected')
    }
     update_address();
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