var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;
var dialog_quick_edit_Customer_Name;
var validate_scope_metadata;
var validate_scope_data;
var dialog_quick_edit_Customer_Contact;
var dialog_quick_edit_Customer_Telephone;
 
<?php

include_once('../common.php');

$custom_fields=array();
$sql=sprintf("show columns from `Customer Custom Field Dimension`");
$result=mysql_query($sql);
mysql_fetch_assoc($result);
while($row=mysql_fetch_assoc($result)){
	$sql=sprintf("select * from `Customer Custom Field Dimension` where `Customer Key`=%d", $customer->id);

	$res=mysql_query($sql);
	$r=mysql_fetch_assoc($res);
	$val=$r[$row['Field']];


	$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Key`=%d", $row['Field']);
	$res=mysql_query($sql);
	$r=mysql_fetch_assoc($res);
	$all_fields[]=$r['Custom Field Name'];
	$custom_fields[]='dialog_quick_edit_Customer_'.$r['Custom Field Name'];
	if($r['Custom Field Type']=='Enum')
		continue;

	$fields[]=$r['Custom Field Name'];
}

	$vars=implode(',', $custom_fields);

print 'var '.$vars.';';

?>
    


function save_quick_edit_name(){
    save_edit_general_bulk('customer_quick');
}

function save_quick_edit_contact(){
    save_edit_general_bulk('customer_quick');
}

function save_quick_edit_telephone(){
    save_edit_general_bulk('customer_quick');
}

<?php
foreach($fields as $field){
print "function save_quick_edit_{$field}(){";
print "save_edit_general_bulk('customer_quick');}";

}
?>

function show_edit_name(){

 region1 = Dom.getRegion('show_edit_name'); 
    region2 = Dom.getRegion('dialog_quick_edit_Customer_Name'); 

 var pos =[region1.right,region1.top]

    Dom.setXY('dialog_quick_edit_Customer_Name', pos);


//Dom.get('sticky_note_input').focus();


	dialog_quick_edit_Customer_Name.show();
}

function show_edit_contact(){

 region1 = Dom.getRegion('show_edit_contact'); 
    region2 = Dom.getRegion('dialog_quick_edit_Customer_Contact'); 

 var pos =[region1.right,region1.top]

    Dom.setXY('dialog_quick_edit_Customer_Contact', pos);


//Dom.get('sticky_note_input').focus();


	dialog_quick_edit_Customer_Contact.show();
}

function show_edit_telephone(){

 region1 = Dom.getRegion('show_edit_telephone'); 
    region2 = Dom.getRegion('dialog_quick_edit_Customer_Telephone'); 

 var pos =[region1.right,region1.top]

    Dom.setXY('dialog_quick_edit_Customer_Telephone', pos);


//Dom.get('sticky_note_input').focus();


	dialog_quick_edit_Customer_Telephone.show();
}



<?php
foreach($all_fields as $field){
print "function show_edit_{$field}(){\n";
print "region1 = Dom.getRegion('show_edit_{$field}');\n"; 
print "region2 = Dom.getRegion('dialog_quick_edit_Customer_{$field}');\n"; 

print "var pos =[region1.right,region1.top]\n";

print "Dom.setXY('dialog_quick_edit_Customer_{$field}', pos);\n";


//Dom.get('sticky_note_input').focus();

print "dialog_quick_edit_Customer_{$field}.show();}\n";
}


?>
function validate_customer_name(query){
 validate_general('customer_quick','name',unescape(query));
}

function validate_customer_contact(query){
 validate_general('customer_quick','contact',unescape(query));
}

function validate_customer_telephone(query){
 validate_general('customer_quick','telephone',unescape(query));
}

<?php
foreach($fields as $field){
print "function validate_customer_{$field}(query){";
print "validate_general('customer_quick','custom_field_customer_{$field}',unescape(query));}";
	
}
?>

function post_item_updated_actions(branch,r){
	window.location.reload()
}

function save_custom_enum(key,value){

 var data_to_update=new Object;
 data_to_update['custom_field_customer_'+key]={'okey':'custom_field_customer_'+key,'value':value}
var customer_id=Dom.get('customer_key').value;
 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer_quick&values='+ jsonificated_values+"&customer_key="+customer_id


//var request='ar_edit_contacts.php?tipo=edit_customer&key=' + key+ '&newvalue=' + value +'&customer_key=' + customer_id
	//alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
//alert(o.responseText)
				var ra =  YAHOO.lang.JSON.parse(o.responseText);
				  for (x in ra){
               r=ra[x]
				if(r.state==200){
			
  /*
 
            if (r.newvalue=='No' || r.newvalue=='Yes') {
                           Dom.removeClass([r.key+'_No',r.key+'_Yes'],'selected');

               Dom.addClass(r.key+'_'+r.newvalue,'selected');

		

            }else{
                alert(r.msg)
            }*/
window.location.reload()
            }
        }
    }
    });

}

function save_comunications(key,value){

 var data_to_update=new Object;
 data_to_update[key]={'okey':key,'value':value}
var customer_id=Dom.get('customer_key').value;
 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer&values='+ jsonificated_values+"&customer_key="+customer_id


//var request='ar_edit_contacts.php?tipo=edit_customer&key=' + key+ '&newvalue=' + value +'&customer_key=' + customer_id
	//alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
//alert(o.responseText)
				var ra =  YAHOO.lang.JSON.parse(o.responseText);
				  for (x in ra){
               r=ra[x]
				if(r.state==200){
			
  
 
            if (r.newvalue=='No' || r.newvalue=='Yes') {
                           Dom.removeClass([r.key+'_No',r.key+'_Yes'],'selected');

               Dom.addClass(r.key+'_'+r.newvalue,'selected');

            }else{
                alert(r.msg)
            }
            }
        }
    }
    });

}


function save_category(o) {

var parent_category_key=o.getAttribute('cat_key');
var category_key=o.options[o.selectedIndex].value;
var subject='Customer';
var subject_key=Dom.get('customer_key').value;

//if(Dom.hasClass(o,'selected'))
//    var operation_type='disassociate_subject_to_category_radio';
//else


if(category_key==''){
var request='ar_edit_categories.php?tipo=disassociate_subject_from_all_sub_categories&category_key=' + parent_category_key+ '&subject=' + subject +'&subject_key=' + subject_key 

}else{
var request='ar_edit_categories.php?tipo=associate_subject_to_category_radio&category_key=' + category_key+ '&subject=' + subject +'&subject_key=' + subject_key +"&parent_category_key="+parent_category_key+"&cat_id="+o.id


}


	alert(request);
	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
			//alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				}


        
    }
                                                                 });



}


function init(){
var regex_valid_tel="^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$";

	 validate_scope_data=
{
    'customer_quick':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Customer Name'}]}
	,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Contact','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Contact Name'}]}
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Telephone','validation':[{'regexp':regex_valid_tel,'invalid_msg':'Invalid Telephone'}]}

<?php
foreach($fields as $field)
	print ",'custom_field_customer_{$field}':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_{$field}','validation':[{'regexp':\"[a-z\\d]+\",'invalid_msg':'Invalid {$field}'}]}";
?>
	//,'mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
    }};


	
 validate_scope_metadata={
'customer_quick':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':Dom.get('customer_key').value}
};
	
	

Event.addListener('show_edit_name', "click", show_edit_name);
Event.addListener('show_edit_contact', "click", show_edit_contact);
Event.addListener('show_edit_telephone', "click", show_edit_telephone);

<?php

foreach($all_fields as $field){
	print 'Event.addListener(\'show_edit_'.$field.'\', "click", show_edit_'.$field.');';
	print "\n";
}
	
?>


dialog_quick_edit_Customer_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Name", {context:["customer_name","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Name.render();

dialog_quick_edit_Customer_Contact = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Contact", {context:["customer_contact","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Contact.render();

dialog_quick_edit_Customer_Telephone = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Telephone", {context:["customer_telephone","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Telephone.render();

<?php

foreach($all_fields as $field){
print 'dialog_quick_edit_Customer_'.$field.' = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_'.$field.'", {context:["customer_'.$field.'","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});dialog_quick_edit_Customer_'.$field.'.render();';	
print "\n";
}

?>



Event.addListener('close_quick_edit_name', "click", dialog_quick_edit_Customer_Name.hide,dialog_quick_edit_Customer_Name , true);
Event.addListener('close_quick_edit_contact', "click", dialog_quick_edit_Customer_Contact.hide,dialog_quick_edit_Customer_Contact , true);
Event.addListener('close_quick_edit_telephone', "click", dialog_quick_edit_Customer_Telephone.hide,dialog_quick_edit_Customer_Telephone , true);


<?php

foreach($all_fields as $field){
print 'Event.addListener(\'close_quick_edit_'.$field.'\', "click", dialog_quick_edit_Customer_'.$field.'.hide,dialog_quick_edit_Customer_'.$field.' , true);';
print "\n";
}

?>

var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_name);
customer_name_oACDS.queryMatchContains = true;
var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Name","Customer_Name_Container", customer_name_oACDS);
customer_name_oAutoComp.minQueryLength = 0; 
customer_name_oAutoComp.queryDelay = 0.1;

var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_contact);
customer_name_oACDS.queryMatchContains = true;
var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Contact","Customer_Contact_Container", customer_name_oACDS);
customer_name_oAutoComp.minQueryLength = 0; 
customer_name_oAutoComp.queryDelay = 0.1;

var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_telephone);
customer_name_oACDS.queryMatchContains = true;
var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Telephone","Customer_Telephone_Container", customer_name_oACDS);
customer_name_oAutoComp.minQueryLength = 0; 
customer_name_oAutoComp.queryDelay = 0.1;

<?php

foreach($fields as $field){
print "var customer_{$field}_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_{$field});\n";
print "customer_{$field}_oACDS.queryMatchContains = true;\n";
print "var customer_{$field}_oAutoComp = new YAHOO.widget.AutoComplete(\"Customer_{$field}\",\"Customer_{$field}_Container\", customer_{$field}_oACDS);";
print "customer_{$field}_oAutoComp.minQueryLength = 0;\n"; 
print "customer_{$field}_oAutoComp.queryDelay = 0.1;\n";
print "\n";
}

?>


}
Event.onDOMReady(init);