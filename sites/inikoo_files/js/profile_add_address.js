var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;
var Address_Keys=["use_tel","telephone","use_contact","contact","key","country","country_code","country_d1","country_d2","town","postal_code","town_d1","town_d2","fuzzy","street","building","internal","description"];


function  save_address(e,options) {
	create_address(options)
}


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
	alert(address_prefix+'address_'+items[i]+':'+Dom.get(address_prefix+'address_'+items[i]).value);
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
//alert(request);
  
  YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
          //  alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='created') {
		window.location='profile.php?view=address_book'
            } 
            else if (r.action=='nochange') {
		alert(r.msg);	
            } else if (r.action=='error') {
                alert(r.msg);
            }



        }
    });

}

function init(){
id=["delivery_save_address_button", "billing_save_address_button"]	
if(Dom.get('prefix').value == 'delivery_'){
	address_type='Delivery'
}
else
	address_type='Billing'
YAHOO.util.Event.addListener(id, "click",save_address,{prefix:Dom.get('prefix').value,subject:'Customer',subject_key:Dom.get('customer_key').value,type:address_type});


}
Event.onDOMReady(init);