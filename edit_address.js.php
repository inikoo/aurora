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
var Country_List=[<?php echo$country_list?>];
var Address_Changes=0;
var Address_Items_Changes=0;
var Address_Type_Changes=0;
var Address_Function_Changes=0;

var postcode_help='';
var postal_regex=new RegExp('.?');

var Address_Keys=["key","country","country_code","country_d1","country_d2","town","postal_code","town_d1","town_d2","fuzzy","street","building","internal","description"];
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
    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            // alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
                if (r.action=='changed') {
                    Dom.get(options.prefix+'current_address').innerHTML=r.new_main_address;
                    buttons=Dom.getElementsByClassName(options.prefix+'set_main', 'span',options.prefix+'address_showcase' );
                    for ( var i=buttons.length-1; i>=0; --i ) {
                        Dom.removeClass(buttons[i], 'hide');
                    }
                    Dom.addClass(options.prefix+'set_main'+address_key, 'hide');

                    if (options.type=='Delivery' && options.Subject=='Customer') {
                        if(Dom.get(options.prefix+'current_address_bis')!=undefined)
                        Dom.get(options.prefix+'current_address_bis').innerHTML=r.new_main_address_bis;
                        post_change_main_delivery_address();
                    }





                }

            } else {
                alert(r.msg);
            }
        }
    });


}
//function post_change_main_delivery_address(){}

var save_address=function(e,options) {


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
        for (i in items)
            value[items[i]]=Dom.get(address_prefix+'address_'+items[i]).value;

        var json_value = YAHOO.lang.JSON.stringify(value);
        var request='ar_edit_contacts.php?tipo=edit_address&value=' + json_value+'&id='+address_key+'&key='+options.type+'&subject='+options.subject+'&subject_key='+options.subject_key;
         // alert(request);
         // return;

        YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            //    alert(o.responseText)
                var r =  YAHOO.lang.JSON.parse(o.responseText);
                if(r.state==200){
                if (r.action=='updated') {
               
              
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

                    if (Dom.get('delivery_current_address_bis')!= undefined) {
                        Dom.get('delivery_current_address_bis').innerHTML=r.xhtml_delivery_address_bis;
                    }
                    if (Dom.get('billing_address')!= undefined) {
                        Dom.get('billing_address').innerHTML=r.xhtml_billing_address;
                    }

                    if (Dom.get('delivery_address_display'+r.key)!= undefined) {
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

                        display_element2=Dom.getElementsByClassName('small_button_edit' ,'span', display_element[0] );

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




                    for (i in r.updated_data) {
                        var address_item_value=r.updated_data[i];
                        if (address_item_value==null)address_item_value='';
                        Address_Data[r.key][i]=address_item_value;
                    }

                   edit_address(r.key,address_prefix)

                    cancel_edit_address(address_prefix);
                    save_address_elements++;
                    
                    
                    
                }
                }
                else{
                    alert(r.msg);
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

        //alert(request);
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

var create_address=function(options) {

    var address_prefix='';
    if (options.prefix!= undefined) {
        address_prefix=options.prefix;
    }



    var value=new Object();
    items=Address_Keys;
    for (i in items) {
        value[items[i]]=Dom.get(address_prefix+'address_'+items[i]).value;
    }

    var address_type_values=new Array();
    var elements_array=Dom.getElementsByClassName(address_prefix+'address_type', 'span');
    for ( var i in elements_array ) {
        var element=elements_array[i];
        var label=element.getAttribute('label');
        if (Dom.hasClass(element,'selected')) {
            address_type_values.push(label);
        }

    }
    value['type']=address_type_values;

    var address_function_values=new Array();
    var elements_array=Dom.getElementsByClassName(address_prefix+'address_function', 'span');
    for ( var i in elements_array ) {
        var element=elements_array[i];
        var label=element.getAttribute('label');
        if (Dom.hasClass(element,'selected')) {
            address_function_values.push(label);
        }

    }
    value['function']=address_function_values;



    var json_value = YAHOO.lang.JSON.stringify(value);



    var request='ar_edit_contacts.php?tipo=new_'+options.type+'_address&value=' + json_value+'&subject='+options.subject+'&subject_key='+options.subject_key;
    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            // 	alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='created') {



                var new_address_data=new Object;
                for (i in r.updated_data) {
                    var address_item_value=r.updated_data[i];
                    if (address_item_value==null)address_item_value='';
                    new_address_data[i]=address_item_value;
                }

                Address_Data[r.address_key]=new Object;
                Address_Data[r.address_key]=new_address_data;
                cancel_edit_address(address_prefix);

                var new_address_container = Dom.get(address_prefix+'address_container0').cloneNode(true);
                new_address_container.id = address_prefix+'address_container'+r.address_key;
                Dom.setStyle(new_address_container, 'display', '');
                display_element=Dom.getElementsByClassName('address_display' ,'div',  new_address_container);
                display_element[0].innerHTML=r.xhtml_address;
                display_element[0].id = address_prefix+'address_display'+r.address_key;
                display_element=Dom.getElementsByClassName('address_buttons' ,'div',  new_address_container);
                display_element[0].id = address_prefix+'address_buttons'+r.address_key;

                display_element=Dom.getElementsByClassName('small_button_edit' ,'span', display_element[0] );

                display_element[0].id = address_prefix+'set_main'+r.address_key;
                display_element[1].id = address_prefix+'delete_address_button'+r.address_key;
                display_element[2].id = address_prefix+'edit_address_button'+r.address_key;

                display_element[0].setAttribute('onClick',"change_main_address("+r.address_key+",{type:'Delivery',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                display_element[1].setAttribute('onCLick',"delete_address("+r.address_key+",{type:'Delivery',prefix:'"+address_prefix+"',Subject:'"+options.subject+"',subject_key:"+options.subject_key+"})");
                display_element[2].setAttribute('onCLick',"edit_address("+r.address_key+",'"+address_prefix+"')");


                Dom.get(address_prefix+'address_showcase').appendChild(new_address_container);




                if (Dom.get(address_prefix+"reset_address_button").getAttribute('close_if_reset')=='Yes') {
                    Dom.get(address_prefix+'address_form').style.display='none';
                    Dom.get(address_prefix+"reset_address_button").style.visibility='visible';

                }







                //new_address_container.parent.appendChild(new_address_container);
                // save_address_elements++;
            } else if (r.action=='nochange') {
                alert(r.msg);
            } else if (r.action=='error') {
                alert(r.msg);
            }



        }
    });

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
    data=Address_Data[index];
    for (key in data) {

        item=Dom.get(prefix+'address_'+key);
        item.value=item.getAttribute('ovalue')
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
            //alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='deleted') {

                Dom.get(options.prefix+'address_container'+address_key).style.display='none';


                if (Dom.get('delivery_current_address')!= undefined)
                    Dom.get('delivery_current_address').innerHTML=r.xhtml_delivery_address;
                if (Dom.get('delivery_current_address_bis')!= undefined)
                    Dom.get('delivery_current_address_bis').innerHTML=r.xhtml_delivery_address_bis;

            } else if (r.action=='error') {
                alert(r.msg);
            }
        }
    });



};


function edit_address(index,address_identifier) {
//alert(index+' x '+address_identifier)

    if (index==false)
        index=0;

    if (address_identifier==undefined)
        address_identifier='';

    Current_Address_Index=index;
    changes_address=0;

    if (address_identifier=='') {
        Dom.setStyle(['address_showcase','move_address_button','add_address_button'], 'display', 'none');


    }



    Dom.setStyle([address_identifier+'address_form'], 'display', '');

    // Dom.setStyle([address_identifier+'reset_address_button'], 'visibility', 'visible');
   //alert(address_identifier+"reset_address_button")
   Dom.get(address_identifier+"reset_address_button").setAttribute('address_key',index);

    data=Address_Data[index];

    for (key in data) {


        item=Dom.get(address_identifier+'address_'+key);
        //	      alert(key+'-> '+data[key]+' '+item)

        item.value=data[key];
        item.setAttribute('ovalue',data[key]);

        if (key=='country_code')
            update_address_labels(data[key],address_identifier);

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

var update_address_labels=function(country_code,suffix) {
    var labels=new Object();

    if (Country_Address_Labels[country_code]== undefined) {
        return
        } else
            labels=Country_Address_Labels[country_code];

    if (suffix==undefined)
        suffix='';
    for (index in Address_Keys) {
        key=Address_Keys[index];
        //	alert(Dom.get(suffix+'label_address_'+key)+' '+suffix+'label_address_'+key)
        if (labels[key]!=undefined) {
            if (labels[key].name!=undefined) {
                //alert(Dom.get(suffix+'label_address_'+key)+' '+suffix+'label_address_'+key)
                Dom.get(suffix+'label_address_'+key).innerHTML=labels[key].name;
            }

            if (labels[key].in_use!=undefined && !labels[key].in_use) {
                //	alert(Dom.get(suffix+'tr_address_'+key)+' '+suffix+'tr_address_'+key)
                Dom.setStyle(suffix+'tr_address_'+key,'display','none');
            } else {
                Dom.setStyle(suffix+'tr_address_'+key,'display','');


                if (labels[key].hide!=undefined && labels[key].hide) {
                    Dom.setStyle(suffix+'tr_address_'+key,'display','none');

                    if (key=='country_d1') {
                        Dom.setStyle(suffix+'show_'+key,'display','');
                    }

                } else {
                    Dom.setStyle(suffix+'tr_address_'+key,'display','');
                    if (key=='country_d1') {
                        Dom.setStyle(suffix+'show_'+key,'display','none');
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
    var address_prefix='';
    if (prefix!= undefined) {
        address_prefix=prefix;
    }

    Address_Items_Changes=0;
    var items=Address_Keys;
    for ( var i in items ) {
        key=items[i];
        // alert(key);
        // alert(key +' : '+address_prefix+'address_'+key)
        // alert(key +' '+Dom.get(address_prefix+'address_'+key).value);
        if (Dom.get(address_prefix+'address_'+key).value!=Dom.get(address_prefix+'address_'+key).getAttribute('ovalue')) {
            Address_Items_Changes++;
        }
    }


    render_after_address_item_change(address_prefix);


}


var render_after_address_item_change=function(prefix) {
    var address_prefix='';
    if (prefix!= undefined) {
        address_prefix=prefix;
    }

    Address_Changes=Address_Items_Changes+Address_Function_Changes+Address_Type_Changes;
    if (Address_Changes==0) {
        Dom.setStyle([address_prefix+'save_address_button', address_prefix+'reset_address_button'], 'visibility', 'hidden');


    } else {
        //alert("caca")
        Dom.setStyle([address_prefix+'save_address_button', address_prefix+'reset_address_button'], 'visibility', 'visible');
    }



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

var toggle_country_d1=function () {
    Dom.setStyle(['tr_address_country_d1','show_country_d2'],'display','');
    Dom.setStyle('show_country_d1','display','none');
    Dom.get('show_country_d2').innerHTML='x';

};
var toggle_country_d2=function () {
    if (Dom.get("show_country_d2").innerHTML=='x') {
        Dom.setStyle('show_country_d1','display','');
        Dom.setStyle('tr_address_country_d1','display','none');
    }

}


var toggle_town_d1=function () {

    Dom.setStyle('tr_address_town_d1','display','');
    Dom.setStyle('show_town_d1','display','none');
    Dom.get("show_town_d2").innerHTML='x';

}

var toggle_town_d2=function () {
    if (Dom.get("show_town_d2").innerHTML=='x') {
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

    if (this.suffix==undefined)
        this.suffix='';

    // update hidden form field with the selected item's ID
    Dom.get(this.suffix+"address_country_code").value = oData.code;
    Dom.get(this.suffix+"address_country_2acode").value = oData.code2a;

//alert(oData.postal_regex)
    postal_regex=new RegExp(oData.postal_regex,"i");
    postcode_help=oData.postcode_help;
//alert(postcode_help)
    myAC.getInputEl().value = oData.name + " (" + oData.code + ") ";
    update_address_labels(oData.code,this.suffix);

};

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

