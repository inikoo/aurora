<?php
include_once('common.php');
include_once('set_locales.php');
include_once('country_address_labels.js.php');
$sql="select `Country Key`,`Country Name`,`Country Code`,`Country 2 Alpha Code`,`Country Postal Code Regex`,`Country Postal Code Format` from kbase.`Country Dimension`";
$result=mysql_query($sql);
$country_list='';



while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
    if ($row['Country Postal Code Format']=='') {
        $postal_help=_('No postal code required in').' '.$row['Country Name'];
    } else {
        $postal_help=_('The valid postal code format is').': '.$row['Country Postal Code Format'];

    }

    $country_list.=',{"id":"'.$row['Country Key'].'","name":"'.$row['Country Name'].'","code":"'.$row['Country Code'].'","code2a":"'.$row['Country 2 Alpha Code'].'","postal_regex":"'.addslashes($row['Country Postal Code Regex']).'",postcode_help:"'.$postal_help.'"}'."\n";
}




mysql_free_result($result);
$country_list=preg_replace('/^\,/','',$country_list);


?>

var dialog_country_list;

var Country_List=[<?php echo$country_list?>];
var Address_Changes=0;
var Address_Items_Changes=0;
var Address_Type_Changes=0;
var Address_Function_Changes=0;

var postcode_help='';
var postal_regex=new RegExp('.?');

var Address_Keys=["use_tel","telephone","use_contact","contact","key","country","country_code","country_d1","country_d2","town","postal_code","town_d1","town_d2","fuzzy","street","building","internal","description"];
var Address_Meta_Keys=["type","function"];







function cancel_edit_address(prefix) {
// Dom.setStyle([address_identifier+'address_form'], 'display', 'none');
    if (Dom.get(prefix+"reset_address_button").getAttribute('close_if_reset')=='Yes') {
        Dom.get(prefix+'address_form').style.display='none';
        Dom.get(prefix+"reset_address_button").style.visibility='visible';

    } else {
        Dom.get(prefix+"reset_address_button").style.visibility='hidden';
        Dom.get(prefix+"save_address_button").style.visibility='hidden';

    }


};

function change_main_address(address_key,options) {

    var request='ar_edit_contacts.php?tipo=set_main_address&value=' +address_key+'&key='+options.type+'&subject='+options.Subject+'&subject_key='+options.subject_key;
	alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            // alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
                if (r.action=='changed') {
                    Dom.get(options.prefix+'current_address').innerHTML=r.new_main_address;
                    buttons=Dom.getElementsByClassName(options.prefix+'set_main', 'button',options.prefix+'address_showcase' );
           
              for ( var i=buttons.length-1; i>=0; --i ) {
                 
                        Dom.removeClass(buttons[i], 'hide');
                    }
                    Dom.addClass(options.prefix+'set_main'+address_key, 'hide');

                    if (options.type=='Delivery' && options.Subject=='Customer') {
                       // if(Dom.get(options.prefix+'current_address_bis')!=undefined)
                       // Dom.get(options.prefix+'current_address_bis').innerHTML=r.new_main_address_bis;
                        post_change_main_delivery_address();
                    }

                    if (options.type=='Billing' && options.Subject=='Customer') {
           
                        post_change_main_billing_address();
                    }




                }

            } else {
                alert(r.msg);
            }
        }
    });


}
function post_change_main_delivery_address(){}
function post_change_main_billing_address(){}




var create_address=function(options) {

    var address_prefix='';
    if (options.prefix!= undefined) {
        address_prefix=options.prefix;
    }



    var value=new Object();
    items=Address_Keys;
	count=0;
    for (i in items) {
		if(items.length <= count++)
			break;
        value[items[i]]=Dom.get(address_prefix+'address_'+items[i]).value;
    }

    var address_type_values=new Array();
    var elements_array=Dom.getElementsByClassName(address_prefix+'address_type', 'span');
	count=0;
	for ( var i in elements_array ) {
		if(elements_array.length <= count++)
				break;
        var element=elements_array[i];
        var label=element.getAttribute('label');
        if (Dom.hasClass(element,'selected')) {
            address_type_values.push(label);
        }

    }
    value['type']=address_type_values;

    var address_function_values=new Array();
    var elements_array=Dom.getElementsByClassName(address_prefix+'address_function', 'span');
	count=0
    for ( var i in elements_array ) {
			if(elements_array.length <= count++)
				break;
        var element=elements_array[i];
        var label=element.getAttribute('label');
        if (Dom.hasClass(element,'selected')) {
            address_function_values.push(label);
        }

    }
    value['function']=address_function_values;



        var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(value));



    var request='ar_edit_contacts.php?tipo=new_'+options.type+'_address&value=' + json_value+'&subject='+options.subject+'&subject_key='+options.subject_key;
//alert(request)  
  
  YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
          //  alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='created') {

				count=0
                var new_address_data=new Object;
                for (i in r.updated_data) {
						if(r.updated_data.length <= count++)
							break;
                    var address_item_value=r.updated_data[i];
                    if (address_item_value==null)address_item_value='';
                    new_address_data[i]=address_item_value;
                }

                Address_Data[r.address_key]=new Object;
                Address_Data[r.address_key]=new_address_data;
                cancel_edit_address(address_prefix);




                if(address_prefix=='delivery_'){

                var new_address_container = Dom.get(address_prefix+'address_container0').cloneNode(true);
                new_address_container.id = address_prefix+'address_container'+r.address_key;
                Dom.setStyle(new_address_container, 'display', '');
                
                
                
                
                  display_element=Dom.getElementsByClassName(address_prefix+'address_tel_label' ,'span',  new_address_container);
                display_element[0].id = address_prefix+'address_tel_label'+r.address_key;
                display_element=Dom.getElementsByClassName(address_prefix+'address_tel' ,'span',  new_address_container);
                Dom.setAttribute(display_element[0],'id',address_prefix+'address_tel'+r.address_key);
                
             
           
                                
                      
                display_element=Dom.getElementsByClassName('address_display' ,'div',  new_address_container);
                
                
                
                display_element[0].innerHTML=r.xhtml_address;
                display_element[0].id = address_prefix+'address_display'+r.address_key;
                display_element=Dom.getElementsByClassName('address_buttons' ,'div',  new_address_container);
                display_element[0].id = address_prefix+'address_buttons'+r.address_key;

                display_element=Dom.getElementsByClassName('small_button_edit' ,'button', display_element[0] );

                display_element[0].id = address_prefix+'set_main'+r.address_key;
                display_element[1].id = address_prefix+'delete_address_button'+r.address_key;
                display_element[2].id = address_prefix+'edit_address_button'+r.address_key;



                display_element[0].setAttribute('onClick',"change_main_address("+r.address_key+",{type:'Delivery',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                
                display_element[1].setAttribute('onCLick',"delete_address("+r.address_key+",{type:'Delivery',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                display_element[2].setAttribute('onCLick',"display_edit_delivery_address("+r.address_key+",'"+address_prefix+"')");






                Dom.get(address_prefix+'address_showcase').appendChild(new_address_container);



                if (Dom.get(address_prefix+"reset_address_button").getAttribute('close_if_reset')=='Yes') {
                    Dom.get(address_prefix+'address_form').style.display='none';
                    Dom.get(address_prefix+"reset_address_button").style.visibility='visible';

                }

     
              if(r.updated_data.telephone!=''){
              Dom.setStyle(address_prefix+'address_tel_label'+r.address_key,'visibility','visible');
              Dom.get(address_prefix+'address_tel'+r.address_key).innerHTML=r.updated_data.telephone;
              }
               post_create_delivery_address_function(r);   
              }
              else if(address_prefix=='billing_'){

                var new_address_container = Dom.get(address_prefix+'address_container0').cloneNode(true);
                new_address_container.id = address_prefix+'address_container'+r.address_key;
                Dom.setStyle(new_address_container, 'display', '');
                
                
                
                
                  display_element=Dom.getElementsByClassName(address_prefix+'address_tel_label' ,'span',  new_address_container);
                display_element[0].id = address_prefix+'address_tel_label'+r.address_key;
                display_element=Dom.getElementsByClassName(address_prefix+'address_tel' ,'span',  new_address_container);
                Dom.setAttribute(display_element[0],'id',address_prefix+'address_tel'+r.address_key);
                
             
           
                                
                      
                display_element=Dom.getElementsByClassName('address_display' ,'div',  new_address_container);
                
                
                
                display_element[0].innerHTML=r.xhtml_address;
                display_element[0].id = address_prefix+'address_display'+r.address_key;
                display_element=Dom.getElementsByClassName('address_buttons' ,'div',  new_address_container);
                display_element[0].id = address_prefix+'address_buttons'+r.address_key;

                display_element=Dom.getElementsByClassName('small_button_edit' ,'button', display_element[0] );

                display_element[0].id = address_prefix+'set_main'+r.address_key;
                display_element[1].id = address_prefix+'delete_address_button'+r.address_key;
                display_element[2].id = address_prefix+'edit_address_button'+r.address_key;

                display_element[0].setAttribute('onClick',"change_main_address("+r.address_key+",{type:'billing',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                display_element[1].setAttribute('onCLick',"delete_address("+r.address_key+",{type:'billing',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                display_element[2].setAttribute('onCLick',"display_edit_billing_address("+r.address_key+",'"+address_prefix+"')");

				//alert(address_prefix+'address_showcase');
                Dom.get(address_prefix+'address_showcase').appendChild(new_address_container);



                if (Dom.get(address_prefix+"reset_address_button").getAttribute('close_if_reset')=='Yes') {
                    Dom.get(address_prefix+'address_form').style.display='none';
                    Dom.get(address_prefix+"reset_address_button").style.visibility='visible';

                }

     
              if(r.updated_data.telephone!=''){
              Dom.setStyle(address_prefix+'address_tel_label'+r.address_key,'visibility','visible');
              Dom.get(address_prefix+'address_tel'+r.address_key).innerHTML=r.updated_data.telephone;
              }
             post_create_billing_address_function(r);     
              }
            
              else if(address_prefix=='xbilling_'){
              
              Dom.get('billing_address').innerHTML=r.xhtml_address;
              Dom.get('show_edit_billing_address').setAttribute('address_key',r.address_key)
            post_create_billing_address_function(r);   
           }
             
                



                //new_address_container.parent.appendChild(new_address_container);
                // save_address_elements++;
            } 
            else if (r.action=='nochange') {
              if(address_prefix=='delivery_'){
               post_create_delivery_address_function(r);   
                alert(r.msg);
              }
            
              
            } else if (r.action=='error') {
                alert(r.msg);
            }



        }
    });

}



function  save_address(e,options) {
    var address_prefix='';
    if (options.prefix!= undefined) {
        address_prefix=options.prefix;
    }



    var table='address';
//alert(Dom.get(address_prefix+'address_key').value)

    if (Dom.get(address_prefix+'address_key').value==0) {
         create_address(options);
        
    
        return;
    } else
        var address_key=Dom.get(address_prefix+'address_key').value;

    save_address_elements=0;



    if (Address_Items_Changes>0) {
        items=Address_Keys;
        var value=new Object()
		count=0
        for (i in items){
			if(items.length<=count++)
				break;
    value[items[i]]=Dom.get(address_prefix+'address_'+items[i]).value;
}


        var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(value));


var request='ar_edit_contacts.php?tipo=edit_address&value=' + json_value+'&id='+address_key+'&key='+options.type+'&subject='+options.subject+'&subject_key='+options.subject_key;
         
   //alert(request);
                    cancel_edit_address(address_prefix);
if(address_prefix=='delivery_'){
hide_new_delivery_address();
}
if(address_prefix=='billing_'){
hide_new_billing_address();
}
//alert(request);
        YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
          //  alert(o.responseText)
                var r =  YAHOO.lang.JSON.parse(o.responseText);
                if(r.state==200){
                if (r.action=='updated') {
					branch='address';
					post_item_updated_actions(branch,r)
			  //Dom.setStyle('dialog_quick_edit_Customer_Main_Address','display','none')
              
               // window.location.reload( false );

                
                    if (Dom.get(address_prefix+'address_display'+r.key)!=undefined)
                        Dom.get(address_prefix+'address_display'+r.key).innerHTML=r.xhtml_address;


                    if (r.is_main=='Yes') {

                        Dom.get(address_prefix+'current_address').innerHTML=r.xhtml_address;



                    }


                    if (r.is_main_delivery=='Yes') {
                        Dom.get('delivery_current_address').innerHTML=r.xhtml_address;
                       post_change_main_delivery_address();
                    }

                 
                                if (r.is_main_billing=='Yes') {
                              //  alert("caca");
                        Dom.get('billing_current_address').innerHTML=r.xhtml_address;
                       post_change_main_billing_address();
                    }



                    if (Dom.get('delivery_current_address_bis')!= undefined) {
                        Dom.get('delivery_current_address_bis').innerHTML=r.xhtml_delivery_address_bis;
                    }
                    if (Dom.get('billing_address')!= undefined) {
                        Dom.get('billing_address').innerHTML=r.xhtml_billing_address;
                    }


                  
                    
                    
if(address_prefix=='delivery_'){
                    if (Dom.get('delivery_address_display'+r.key)!= undefined) {
                        Dom.get('delivery_address_tel'+r.key).innerHTML=r.updated_data.telephone;
                        if(r.updated_data.telephone==''){
                            Dom.setStyle('delivery_address_tel_label'+r.key,'visibility','hidden');
                        }else{
                            Dom.setStyle('delivery_address_tel_label'+r.key,'visibility','visible');
                        }
                         Dom.get('delivery_address_display'+r.key).innerHTML=r.xhtml_address;
                    }
                    
                    
                    if (r.deleted_address>0 && Dom.get('delivery_address_container'+r.key)!=undefined) {
                        Dom.get('delivery_address_container'+r.key).style.display='none';
                        Dom.get('delivery_address_container'+r.key).parentNode.removeChild(Dom.get('delivery_address_container'+r.key));
                    }



                    if (r.created_address>0  && Dom.get('delivery_address_container0')!=undefined) {


                        var new_address_data=new Object;
                        for (i in r.updated_data) {
                            var address_item_value=r.updated_data[i];
                            if (address_item_value==null)address_item_value='';
                            new_address_data[i]=address_item_value;
                        }

                        Address_Data[r.key]=new Object;
                        Address_Data[r.key]=new_address_data;
                        cancel_edit_address(address_prefix);

                        var new_address_container = Dom.get('delivery_address_container0').cloneNode(true);
                        new_address_container.id = 'delivery_address_container'+r.key;
                        Dom.setStyle(new_address_container, 'display', '');
                        display_element=Dom.getElementsByClassName('address_display' ,'div',  new_address_container);
                        display_element[0].innerHTML=r.xhtml_address;
                        display_element[0].id = 'delivery_address_display'+r.key;
                        display_element=Dom.getElementsByClassName('address_buttons' ,'div',  new_address_container);
                        display_element[0].id = 'delivery_address_buttons'+r.key;

                        display_element2=Dom.getElementsByClassName('small_button_edit' ,'button', display_element[0] );

                        display_element2[0].id = 'delivery_set_main'+r.key;
                        display_element2[1].id = 'delivery_delete_address_button'+r.key;
                        display_element2[2].id = 'delivery_edit_address_button'+r.key;

                        display_element2[0].setAttribute('onClick',"change_main_address("+r.key+",{type:'Delivery',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                        display_element2[1].setAttribute('onCLick',"delete_address("+r.key+",{type:'Delivery',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                        display_element2[2].setAttribute('onCLick',"edit_address("+r.key+",'"+address_prefix+"')");
                        display_element2[1].style.display='none';
                        display_element2[2].style.display='none';

                        billing= document.createElement('span');



                        Dom.get('delivery_address_showcase').appendChild(new_address_container);
                        billing.innerHTML='<img src="art/icons/lock.png" alt="lock"> <span  class="state_details" > <?php echo _('Billing')?></span>'
                                          display_element[0].appendChild(billing);
                        
                       
                            
                                          
                    }
}


if(address_prefix=='billing_'){



                    if (Dom.get('billing_address_display'+r.key)!= undefined) {
                        Dom.get('billing_address_tel'+r.key).innerHTML=r.updated_data.telephone;
                        if(r.updated_data.telephone==''){
                            Dom.setStyle('billing_address_tel_label'+r.key,'visibility','hidden');
                        }else{
                            Dom.setStyle('billing_address_tel_label'+r.key,'visibility','visible');
                        }
                         Dom.get('billing_address_display'+r.key).innerHTML=r.xhtml_address;
                         
                         if( Dom.get('delivery_address_display'+r.key) !=undefined){
                            Dom.get('delivery_address_display'+r.key).innerHTML=r.xhtml_address;
                         }
                         
                         
                    }
                    
                    
                    if (r.deleted_address>0 && Dom.get('billing_address_container'+r.key)!=undefined) {
                        Dom.get('billing_address_container'+r.key).style.display='none';
                        Dom.get('billing_address_container'+r.key).parentNode.removeChild(Dom.get('billing_address_container'+r.key));
                    }



                    if (r.created_address>0  && Dom.get('billing_address_container0')!=undefined) {


                        var new_address_data=new Object;
                        for (i in r.updated_data) {
                            var address_item_value=r.updated_data[i];
                            if (address_item_value==null)address_item_value='';
                            new_address_data[i]=address_item_value;
                        }

                        Address_Data[r.key]=new Object;
                        Address_Data[r.key]=new_address_data;
                        cancel_edit_address(address_prefix);

                        var new_address_container = Dom.get('billing_address_container0').cloneNode(true);
                        new_address_container.id = 'billing_address_container'+r.key;
                        Dom.setStyle(new_address_container, 'display', '');
                        display_element=Dom.getElementsByClassName('address_display' ,'div',  new_address_container);
                        display_element[0].innerHTML=r.xhtml_address;
                        display_element[0].id = 'billing_address_display'+r.key;
                        display_element=Dom.getElementsByClassName('address_buttons' ,'div',  new_address_container);
                        display_element[0].id = 'billing_address_buttons'+r.key;

                        display_element2=Dom.getElementsByClassName('small_button_edit' ,'button', display_element[0] );

                        display_element2[0].id = 'billing_set_main'+r.key;
                        display_element2[1].id = 'billing_delete_address_button'+r.key;
                        display_element2[2].id = 'billing_edit_address_button'+r.key;

                        display_element2[0].setAttribute('onClick',"change_main_address("+r.key+",{type:'Delivery',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                        display_element2[1].setAttribute('onCLick',"delete_address("+r.key+",{type:'Delivery',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                        display_element2[2].setAttribute('onCLick',"edit_address("+r.key+",'"+address_prefix+"')");
                        display_element2[1].style.display='none';
                        display_element2[2].style.display='none';

                        billing= document.createElement('span');



                        Dom.get('billing_address_showcase').appendChild(new_address_container);
                        billing.innerHTML='<img src="art/icons/lock.png" alt="lock"> <span  class="state_details" > <?php echo _('Billing')?></span>'
                                          display_element[0].appendChild(billing);
                        
                       
                            
                                          
                    }
                    
                    
                     post_edit_billing_address();
}



                    for (i in r.updated_data) {
						
                        var address_item_value=r.updated_data[i];
                        if (address_item_value==null)address_item_value='';
                        Address_Data[r.key][i]=address_item_value;
                    }

                   edit_address(r.key,address_prefix)

                    save_address_elements++;
                    
                    
                    
                }
    
                 post_edit_address();
    }
                else{
                  //  alert(r.msg);
                }
            }
        });
    }

    if (Address_Type_Changes>0) {

        var address_type_values=new Array();
        var elements_array=Dom.getElementsByClassName('address_type', 'span');
        for ( var i in elements_array ) {
            var element=elements_array[i];
            var label=element.getAttribute('label');
            if (Dom.hasClass(element,'selected')) {
                address_type_values.push(label);
            }

        }

        var json_value = YAHOO.lang.JSON.stringify(address_type_values);
        var request='ar_edit_contacts.php?tipo=edit_'+escape(options.tipo)+ '_type&value=' + json_value+'&id='+address_key+'&subject='+Subject+'&subject_key='+Subject_Key;

   //     alert(request);
        //return;
        YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
                //alert(o.responseText);
                var r =  YAHOO.lang.JSON.parse(o.responseText);
                if (r.action=='updated') {


                    Address_Data[address_key]['type']=r.updated_data;
                    cancel_edit_address(address_prefix);
                    save_address_elements++;
                } else if (r.action=='error') {
                    alert(r.msg);
                }



            }
        });


    }





};

function post_edit_billing_address(){}

function post_edit_address(){

}



function post_create_address_function(r){

}


var update_address_buttons=function() {
    if (changes_address>0) {
        Dom.setStyle(['save_edit_address'], 'display', '');
    }

};

function reset_address(e,prefix) {



    changes_address=0;
    index=Dom.get(prefix+"reset_address_button").getAttribute('address_key');
    
    
    
    
    Dom.setStyle(['address_showcase','move_address_button','add_address_button'], 'display', '');
    Dom.setStyle(['address_form','cancel_edit_address','save_address_button'], 'display', 'none');
    //    Dom.get("address_messages").innerHTML='';
    
    
    if(index){
    data=Address_Data[index];
    }else{
    count=0
    var data=new Object;
      for ( var i in Address_Keys ) {
		if(data.length<=count++) 
			break;
            data[Address_Keys[i]]='';
      }
    }
	
	count=0;
	for (key in data) {
		count++;
	}
    len=0;
	//alert(count)
    for (key in data) {
		if(count-1<=len++) 
			break;
		//alert('count:' +len + prefix+'address_'+key);
        item_=Dom.get(prefix+'address_'+key);
		//alert(item_)
        item_.value=item_.getAttribute('ovalue')
                   //item.setAttribute('ovalue','');

                   var elements_array=Dom.getElementsByClassName(prefix+'address_function', 'span');
        for ( var i in elements_array ) {
            Dom.removeClass(elements_array[i],'selected');
        }

        if (key=='function') {
            var address_function=data[key];
            for (address_function_key in address_function) {
                Dom.addClass(prefix+'address_function_'+address_function[address_function_key],'selected')
            }
        }
        var elements_array=Dom.getElementsByClassName(prefix+'address_type', 'span');
        for ( var i in elements_array ) {
            Dom.removeClass(elements_array[i],'selected');
        }
        if (key=='type') {
            var address_type=data[key];
            for (address_type_key in address_type) {

                Dom.addClass(prefix+'address_type_'+address_type[address_type_key],'selected')
            }
        }


    }
    
    
    Address_Type_Changes=0;
    Address_Items_Changes=0;
    Address_Function_Changes=0;
    render_after_address_item_change(prefix);


    if (Dom.get(prefix+"reset_address_button").getAttribute('close_if_reset')=='Yes') {
        Dom.get(prefix+'address_form').style.display='none';
        Dom.get(prefix+"reset_address_button").style.visibility='visible';

    }

};

var delete_address=function (address_key,options) {
    var request='ar_edit_contacts.php?tipo=delete_address&value=' +address_key+'&key='+options.type+'&subject='+options.Subject+'&subject_key='+options.subject_key;

    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
//           alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='deleted') {


                
                Dom.setStyle(options.prefix+'address_container'+address_key,'display','none');
                if(options.prefix=='billing_'){
                    if( Dom.get('delivery_address_container'+address_key)!=undefined){
                         Dom.setStyle('delivery_address_container'+address_key,'display','none')
                    }
                
                }

                if (Dom.get('delivery_current_address')!= undefined)
                    Dom.get('delivery_current_address').innerHTML=r.xhtml_delivery_address;
            
                if (Dom.get('billing_current_address')!= undefined)
                    Dom.get('billing_current_address').innerHTML=r.xhtml_billing_address;



            } else if (r.action=='error') {
                alert(r.msg);
            }
        }
    });



};



function edit_address(index,address_identifier) {


    if (index==false)
        index=0;

    if (address_identifier==undefined)
        address_identifier='';

    Current_Address_Index=index;
    changes_address=0;

    if (address_identifier=='') {
        Dom.setStyle(['address_showcase','move_address_button','add_address_button'], 'display', 'none');
    }

//      -->  Dom.get(address_prefix+'address_key').value=



    
    
    Dom.setStyle([address_identifier+'address_form'], 'display', '');
    Dom.setStyle([address_identifier+'address_components'], 'display', '');

    Dom.get(address_identifier+"reset_address_button").setAttribute('address_key',index);


    data=Address_Data[index];


//i=0;
//var json_value = YAHOO.lang.JSON.stringify(data);
//alert(json_value)
    for (key in data) {

   //alert(i++)
  // alert(address_identifier+'address_'+key)
        item_=Dom.get(address_identifier+'address_'+key);
		//alert('after');
        item_.value=data[key];
        item_.setAttribute('ovalue',data[key]);

        if (data[key]!='') {
        if (key=='country_d2' ) {
                Dom.setStyle(address_identifier+'tr_address_country_d1','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d2','display','')
                
            }else if (key=='country_d3' ) {
                Dom.setStyle(address_identifier+'tr_address_country_d1','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d2','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d3','display','')
                
            }else if (key=='country_d4' ) {
                Dom.setStyle(address_identifier+'tr_address_country_d1','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d2','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d3','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d4','display','')
                
            }else if (key=='country_d5' ) {
                Dom.setStyle(address_identifier+'show_country_subregions','display','none')
                Dom.setStyle(address_identifier+'tr_address_country_d1','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d2','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d3','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d4','display','')
                Dom.setStyle(address_identifier+'tr_address_country_d5','display','')
                
            }else if ( key=='town_d2') {
                Dom.setStyle(address_identifier+'show_town_subdivisions','display','none')
                Dom.setStyle(address_identifier+'tr_address_town_d1','display','')
                Dom.setStyle(address_identifier+'tr_address_town_d2','display','')
            }else if ( key=='telephone' ) {
               if(data['use_tel'])
                Dom.setStyle(address_identifier+'tr_address_'+key,'display','')

            }
            
            else if (Dom.get(address_identifier+'tr_address_'+key)!=undefined) {
                Dom.setStyle(address_identifier+'tr_address_'+key,'display','')
            }
        }

		
        if (key=='country_code') {
            update_address_labels(data[key],address_identifier);
        }
        if (key=='function') {
            var address_function=data[key];
            for (address_function_key in address_function) {
                Dom.addClass(address_identifier+'address_function_'+address_function[address_function_key],'selected')
            }
        }
        if (key=='type') {
            var address_type=data[key];
            for (address_type_key in address_type) {
                Dom.addClass(address_identifier+'address_type_'+address_type[address_type_key],'selected')
            }
        }
    }

};

var update_address_labels=function(country_code,prefix) {
    var labels=new Object();

    if (Country_Address_Labels[country_code]== undefined) {
        return
    } else {
        labels=Country_Address_Labels[country_code];
    }
    if (prefix==undefined)
        prefix='';
    for (index in Address_Keys) {
        key=Address_Keys[index];
        //	alert(Dom.get(prefix+'label_address_'+key)+' '+prefix+'label_address_'+key)
        if (labels[key]!=undefined) {
            if (labels[key].name!=undefined) {
                //alert(Dom.get(prefix+'label_address_'+key)+' '+prefix+'label_address_'+key)
                Dom.get(prefix+'label_address_'+key).innerHTML=labels[key].name;
            }

            if (labels[key].in_use!=undefined && !labels[key].in_use) {
                //	alert(Dom.get(prefix+'tr_address_'+key)+' '+prefix+'tr_address_'+key)
                Dom.setStyle(prefix+'tr_address_'+key,'display','none');
            } else {
                Dom.setStyle(prefix+'tr_address_'+key,'display','');


                if (labels[key].hide!=undefined && labels[key].hide) {
                    Dom.setStyle(prefix+'tr_address_'+key,'display','none');

                    if (key=='country_d1') {
                        Dom.setStyle(prefix+'show_'+key,'display','');
                    }

                } else {
                    Dom.setStyle(prefix+'tr_address_'+key,'display','');
                    if (key=='country_d1') {
                        Dom.setStyle(prefix+'show_'+key,'display','none');
                    }

                }
            }

        }
    }
};

var on_address_type_change=function() {

    Address_Type_Changes=0
                         var address_type_values=new Array();
    var elements_array=Dom.getElementsByClassName('address_type', 'span');
    var has_other=false;
    for ( var i in elements_array ) {
        var element=elements_array[i];
        var label=element.getAttribute('label');
        if (Dom.hasClass(element,'selected')) {
            if (label=='Other')
                has_other=true;
            address_type_values.push(label);
        }

    }
    if (address_type_values.length==0) {
        address_type_values.push('Other');
        Dom.addClass('address_type_Other','selected');
    }
    if (has_other && address_type_values.length>1) {
        address_type_values.splice(address_type_values.indexOf('Other'), 1);
        Dom.removeClass('address_type_Other','selected');
    }

    ovalue=Address_Data[Current_Address_Index]['type']
           if (!same_arrays(ovalue,address_type_values))
               Address_Type_Changes++;


    render_after_address_item_change();
}

var on_address_function_change=function() {

    Address_Function_Changes=0
                             var address_function_values=new Array();
    var elements_array=Dom.getElementsByClassName('address_function', 'span');
    var has_other=false;
    for ( var i in elements_array ) {
        var element=elements_array[i];
        var label=element.getAttribute('label');
        if (Dom.hasClass(element,'selected')) {
            if (label=='Other')
                has_other=true;
            address_function_values.push(label);
        }

    }
    if (address_function_values.length==0) {
        address_function_values.push('Other');
        Dom.addClass('address_function_Other','selected');
    }
    if (has_other && address_function_values.length>1) {
        address_function_values.splice(address_function_values.indexOf('Other'), 1);
        Dom.removeClass('address_function_Other','selected');
    }

    ovalue=Address_Data[Current_Address_Index]['function']
           if (!same_arrays(ovalue,address_function_values))
               Address_Function_Changes++;
    render_after_address_item_change();
}

var on_address_item_change_when_creating=function() {



}


var on_address_item_change=function(o,prefix) {

//alert('on change');

    var address_prefix='';
    if (prefix!= undefined) {
        address_prefix=prefix;
    }

    Address_Items_Changes=0;
    var items=Address_Keys;
//alert(YAHOO.lang.JSON.stringify(items));
count=0
    for ( var i in items ) {
		if(items.length<=count++)
			break;
		//count++
        key_=items[i];
		//alert(i + ':' + key);
       //  alert(address_prefix+'address_'+key);
        // alert(i+' -> '+key +' : '+address_prefix+'address_'+key)
        // alert(key +' '+Dom.get(address_prefix+'address_'+key).value);
		//alert(Dom.get(address_prefix+'address_'+key_).value);
		//alert(Dom.get(address_prefix+'address_'+key_).getAttribute('ovalue'));
		//alert(key_)
		//alert(address_prefix+'address_'+key_)
        if (Dom.get(address_prefix+'address_'+key_).value!=Dom.get(address_prefix+'address_'+key_).getAttribute('ovalue')) {
            Address_Items_Changes++;
        }
		
    }
	//alert(Address_Items_Changes)
    render_after_address_item_change(address_prefix);


}


var render_after_address_item_change=function(prefix) {
//alert('render');
    var address_prefix='';
    if (prefix!= undefined) {
        address_prefix=prefix;
    }

    Address_Changes=Address_Items_Changes+Address_Function_Changes+Address_Type_Changes;
    if (Address_Changes==0) {
    
      Dom.setStyle([address_prefix+'save_address_button'], 'visibility', 'hidden');


    } else {
        
        Dom.setStyle([address_prefix+'save_address_button', address_prefix+'reset_address_button'], 'visibility', 'visible');
    }

//alert('render_end')

}


var toggle_address_type=function (o) {
    if (Dom.hasClass(o, 'selected')) {
        Dom.removeClass(o, 'selected')
    } else {
        Dom.addClass(o, 'selected')
    }
    on_address_type_change();
};

var toggle_address_function=function (o) {
    if (Dom.hasClass(o, 'selected')) {
        Dom.removeClass(o, 'selected')
    } else {
        Dom.addClass(o, 'selected')
    }
    on_address_function_change();
};
var show_description=function () {
    Dom.setStyle(['tr_address_description','hide_description'],'display','');
    Dom.setStyle('show_description','display','none');
};

var hide_description=function () {
    Dom.setStyle(['tr_address_description','hide_description'],'display','none');
    Dom.setStyle('show_description','display','');
};




function show_town_subdivisions(prefix){
 Dom.setStyle(prefix+'tr_address_town_d1','display','');
  Dom.setStyle(prefix+'tr_address_town_d2','display','');
   Dom.setStyle(prefix+'show_town_subdivisions','display','none');
}

function show_country_subregions(prefix){
 Dom.setStyle(prefix+'tr_address_country_d1','display','');
  Dom.setStyle(prefix+'tr_address_country_d2','display','');
    Dom.setStyle(prefix+'tr_address_country_d3','display','');
  Dom.setStyle(prefix+'tr_address_country_d4','display','');
  Dom.setStyle(prefix+'tr_address_country_d5','display','');

   Dom.setStyle(prefix+'show_country_subregions','display','none');
}

var match_country = function(sQuery) {
    // Case insensitive matching
    var query = sQuery.toLowerCase(),
                contact,
                i=0,
                  l=Country_List.length,
                    matches = [];

    // Match against each name of each contact
    for (; i<l; i++) {
        contact = Country_List[i];
        if ((contact.name.toLowerCase().indexOf(query) > -1) ||
        (contact.code.toLowerCase().indexOf(query) > -1))  {
            matches[matches.length] = contact;
        }
    }

    return matches;
};

var onCountrySelected = function(sType, aArgs) {
    var myAC = aArgs[0]; // reference back to the AC instance
    var elLI = aArgs[1]; // reference to the selected LI element
    var oData = aArgs[2]; // object literal of selected item's result data


    if (this.prefix==undefined)
        this.prefix='';

      myAC.getInputEl().value = oData.name + " (" + oData.code + ") ";
  //    alert("xx"+myAC.getInputEl().id)
change_country(this.prefix,oData)



};
function select_default_country(prefix,code){
 var request='ar_regions.php?tipo=country_info_from_2alpha&2alpha=' +code+'&prefix='+prefix;
    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            //alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
               Dom.get('address_country').value=r.data['Country Name'] + " (" + r.data['Country Code'] + ") ";

              change_country(r.prefix,{
              'code':r.data['Country Code'],
              'code2a':r.data['Country 2 Alpha Code'],
              'postal_regex':r.data['Country Postal Code Regex'],
              'postcode_help':r.data['Country Postal Code Format']
              
              });
              
              
              
            } else {
                
            }
        }
    });

}

function select_country_from_list(oArgs){
record=tables.table100.getRecord(oArgs.target)
var data={
    'code':record.getData('code3a'),
    'code2a':record.getData('code2a'),
    'postal_regex':record.getData('postal_regex'),
    'postcode_help':record.getData('postcode_help')
    
    }
               Dom.get(tables.table100.prefix+'address_country').value= record.getData('plain_name')+ " (" + record.getData('code3a') + ") ";

  change_country(tables.table100.prefix,data);
    dialog_country_list.hide();
    hide_filter(true,2)
}


function change_country(prefix,oData){

// alert(prefix)
Dom.setStyle(prefix+'address_components','display','')
    Dom.setStyle(prefix+'default_country_selector','display','none');
    Dom.setStyle(prefix+'show_country_subregions','display','')
  Dom.get(prefix+"address_country_code").value = oData.code;
    Dom.get(prefix+"address_country_2acode").value = oData.code2a;
  


    postal_regex=new RegExp(oData.postal_regex,"i");
    postcode_help=oData.postcode_help;
    update_address_labels(oData.code,prefix);
//alert(Dom.get(prefix+'address_street'))
 
 

 on_address_item_change(false,prefix)
 
Dom.get(prefix+'address_street').focus()
  Dom.get(prefix+"address_country").value = oData.name+ " (" + oData.code + ") ";
 
}

function show_country_options(prefix){
 Dom.setStyle(prefix+'country_options','display','')
    Dom.setStyle(prefix+'show_country_options','display','none')
}

function countries_format_results(oResultData, sQuery, sResultMatch) {

    var query = sQuery.toLowerCase(),
                name = oResultData.name,
                       code = oResultData.code,
                              query = sQuery.toLowerCase(),
                                      nameMatchIndex = name.toLowerCase().indexOf(query),
                                                       codeMatchIndex = code.toLowerCase().indexOf(query),
                                                                        displayname, displaycode;


    if (nameMatchIndex > -1) {
        displayname = countries_highlightMatch(name, query, nameMatchIndex);
    } else {
        displayname = name;
    }

    if (codeMatchIndex > -1) {
        displaycode = countries_highlightMatch(code, query, codeMatchIndex);
    } else {
        displaycode = code;
    }
    return displayname + " (" + displaycode + ")";
}

var countries_highlightMatch = function(full, snippet, matchindex) {
    return full.substring(0, matchindex) +
           "<span class='match'>" +
           full.substr(matchindex, snippet.length) +
           "</span>" +
           full.substring(matchindex + snippet.length);
};



function show_countries_list(o,prefix){
Event.addListener('clean_table_filter_show100', "click",show_filter,100);

//alert(tables.table100.prefix);return;
tables.table100.prefix=prefix
  var y=(Dom.getY(o))-160
    var x=(Dom.getX(o))-25
 Dom.setX('dialog_country_list', x)
    Dom.setY('dialog_country_list', y)
dialog_country_list.show();
}




function init_address(){
dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", { visible : false,close:true,underlay: "none",draggable:false});
  dialog_country_list.render();


}
YAHOO.util.Event.onDOMReady(init_address);
