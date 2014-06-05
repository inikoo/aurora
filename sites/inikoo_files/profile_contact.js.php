<?php 
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;
var dialog_quick_edit_Customer_Name;
var validate_scope_metadata;
var validate_scope_data;
var dialog_quick_edit_Customer_Contact;
var dialog_quick_edit_Customer_Telephone;
var dialog_quick_edit_Website;
var scope='customer_profile';
var number_of_categories=2;

var scope_key='';
<?php



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
	//print $sql;
	$res=mysql_query($sql);
	$r=mysql_fetch_assoc($res);
	$all_fields[]=$r['Custom Field Name'];
	$custom_fields[]='dialog_quick_edit_Customer_'.$r['Custom Field Name'];
	if($r['Custom Field Type']=='Enum')
		continue;

	$fields[]=$r['Custom Field Name'];
}



if(!empty($custom_fields)){
$vars=implode(',', $custom_fields);

print 'var '.$vars.';';
}

for($i=1; $i<=5; $i++)
print 'var dialog_badge_info_'.$i.';';
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
function save_quick_edit_website(){
    save_edit_general_bulk('customer_quick');
}
function save_quick_edit_tax_number(){
    save_edit_general_bulk('customer_quick');
}


<?php
if(!empty($fields)){
foreach($fields as $field){
print "function save_quick_edit_{$field}(){";
print "save_edit_general_bulk('customer_quick');}";

}
}
?>


function show_edit_tax_number(){
close_dialogs()
 region1 = Dom.getRegion('show_edit_tax_number');
    region2 = Dom.getRegion('dialog_quick_edit_Customer_Tax_Number');

    var pos = [region1.right, region1.top]

    Dom.setXY('dialog_quick_edit_Customer_Tax_Number', pos);

    dialog_quick_edit_Customer_Tax_Number.show();
}

function show_edit_name() {
close_dialogs()
    region1 = Dom.getRegion('show_edit_name');
    region2 = Dom.getRegion('dialog_quick_edit_Customer_Name');

    var pos = [region1.right, region1.top]

    Dom.setXY('dialog_quick_edit_Customer_Name', pos);

    dialog_quick_edit_Customer_Name.show();
}

function show_edit_address(){
close_dialogs()
 region1 = Dom.getRegion('show_edit_address');
    region2 = Dom.getRegion('dialog_quick_edit_addresss');

    var pos = [region1.right, region1.top]

    Dom.setXY('dialog_quick_edit_addresss', pos);

	edit_address(Dom.get('main_address_key').value,'contact_');

    dialog_quick_edit_addresss.show();
}


function show_edit_contact() {
close_dialogs()
    region1 = Dom.getRegion('show_edit_contact');
    region2 = Dom.getRegion('dialog_quick_edit_Customer_Contact');

    var pos = [region1.right, region1.top]

    Dom.setXY('dialog_quick_edit_Customer_Contact', pos);

    dialog_quick_edit_Customer_Contact.show();
}

function show_edit_telephone() {
close_dialogs()
    region1 = Dom.getRegion('show_edit_telephone');
    region2 = Dom.getRegion('dialog_quick_edit_Customer_Telephone');

    var pos = [region1.right, region1.top]

    Dom.setXY('dialog_quick_edit_Customer_Telephone', pos);


    //Dom.get('sticky_note_input').focus();

    dialog_quick_edit_Customer_Telephone.show();
}


function show_edit_website() {
close_dialogs()
    region1 = Dom.getRegion('show_edit_website');
    region2 = Dom.getRegion('dialog_quick_edit_Website');

    var pos = [region1.right, region1.top]

    Dom.setXY('dialog_quick_edit_Website', pos);




    dialog_quick_edit_Website.show();
}

function show_upload_image() {
close_dialogs()
    region1 = Dom.getRegion('show_upload_image');
    region2 = Dom.getRegion('dialog_image_upload');

    var pos = [region1.right, region1.top]

    Dom.setXY('dialog_image_upload', pos);


    //Dom.get('sticky_note_input').focus();

    dialog_image_upload.show();
}


function show_badge_info(e, no){
	region1 = Dom.getRegion(e); 
	var pos =[region1.right,region1.top]

Dom.setXY(no, pos);
eval(no).show();
	}

<?php
if(!empty($all_fields)){
foreach($all_fields as $field){
print "function show_edit_{$field}(){\n";
print "region1 = Dom.getRegion('show_edit_{$field}');\n"; 
print "region2 = Dom.getRegion('dialog_quick_edit_Customer_{$field}');\n"; 

print "var pos =[region1.right,region1.top]\n";

print "Dom.setXY('dialog_quick_edit_Customer_{$field}', pos);\n";


//Dom.get('sticky_note_input').focus();

print "dialog_quick_edit_Customer_{$field}.show();}\n";
}
}

?>
function validate_customer_name(query){
 validate_general('customer_quick','name',unescape(query));
}

function validate_customer_tax_number(query){
 validate_general('customer_quick','tax_number',unescape(query));

}

function validate_customer_contact(query){
 validate_general('customer_quick','contact',unescape(query));
}

function validate_customer_telephone(query){
 validate_general('customer_quick','telephone',unescape(query));
}


function validate_customer_website(query){
 validate_general('customer_quick','web',unescape(query));
}
<?php
if(!empty($fields)){
foreach($fields as $field){
print "function validate_customer_{$field}(query){";
print "validate_general('customer_quick','custom_field_customer_{$field}',unescape(query));}";
	
}
}
?>

function post_item_updated_actions(branch, r) {


    if (r.key == 'name') {
        Dom.get('customer_name').innerHTML = r.newvalue;
        Dom.get('customer_name_title').innerHTML = r.newvalue;

        dialog_quick_edit_Customer_Name.hide()

    } else if (r.key == 'contact') {
        Dom.get('customer_contact').innerHTML = r.newvalue;

        dialog_quick_edit_Customer_Contact.hide();

    } else if (r.key == 'telephone') {
        Dom.get('customer_telephone').innerHTML = r.newvalue;

        dialog_quick_edit_Customer_Telephone.hide()

    } else if (r.key == 'web') {
        Dom.get('customer_website').innerHTML = r.newvalue;

        dialog_quick_edit_Website.hide()

    }  else if (r.key == 'tax_number') {
        Dom.get('customer_tax_number').innerHTML = r.newvalue;

        dialog_quick_edit_Customer_Tax_Number.hide()
        show_dialog_check_tax_number(r.newvalue);
        
        
        

    } else {


        window.location.reload()

    }
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
 jsonificated_values=YAHOO.lang.JSON.stringify(data_to_update);


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
    
      alert("xx")
     alert(o)
    alert(o.selectedIndex)
    
    var current_category_key=o.getAttribute('ovalue');
    
  
    
    var category_key=o.options[o.selectedIndex].value;
    var subject='Customer';
    var subject_key=Dom.get('customer_key').value;
    
    var category_object=o.options[o.selectedIndex];
    
    
    if(Dom.get(category_object).getAttribute('other')==true){
        Dom.get('other_tbody_'+parent_category_key).style.display='';
        return;
    }
    
    
    if(category_key==''){
        
		var request='ar_edit_categories.php?tipo=disassociate_subject&category_key=' + current_category_key+ '&subject=' + subject +'&subject_key=' + subject_key 
        
    }else{
		var request='ar_edit_categories.php?tipo=associate_subject_to_category&category_key=' + category_key+ '&subject=' + subject +'&subject_key=' + subject_key 
        
        
    }
    
    

	
    YAHOO.util.Connect.asyncRequest('POST',request ,{
                                    success:function(o) {
                                    //alert(o.responseText);
                                    var r =  YAHOO.lang.JSON.parse(o.responseText);
                                    if(r.state==200){
                                    window.location.reload();                         
                                    }
                                    
                                    
                                    
                                    }
                                    });
    
    
    
}

function save_category_other_value(category_key,parent_category_key){


 
    var subject='Customer';
    var subject_key=Dom.get('customer_key').value;
    
    var request='ar_edit_categories.php?tipo=update_other_value&category_key=' + category_key+ '&subject_key=' + subject_key +"&other_value="+Dom.get('other_textarea_'+parent_category_key).value
  //  alert(request); return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
                                    success:function(o) {
                                    //alert(o.responseText);
                                    var r =  YAHOO.lang.JSON.parse(o.responseText);
                                    if(r.state==200){
                                    window.location.reload();
                                    }
                                    
                                    
                                    
                                    }
                                    });
    
}


function show_save_other(parent_category_key){
Dom.get('show_other_tbody_'+parent_category_key).style.display='none';
Dom.get('other_tbody_'+parent_category_key).style.display='';
}


function close_dialogs(){
dialog_quick_edit_Customer_Name.hide()
dialog_quick_edit_Customer_Contact.hide()
dialog_quick_edit_Customer_Telephone.hide()
dialog_quick_edit_Website.hide()
dialog_image_upload.hide()
dialog_quick_edit_addresss.hide()
dialog_quick_edit_Customer_Tax_Number.hide()
}



function reset_contact_address(){
dialog_quick_edit_addresss.hide()

}

function init(){

    
scope_key=Dom.get('user_key').value;

var regex_valid_tel="^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$";

	 validate_scope_data=
{
    'customer_quick':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Customer Name'}]}
	,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Contact','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Contact Name'}]}
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Telephone','validation':[{'regexp':regex_valid_tel,'invalid_msg':'Invalid Telephone'}]}
	,'web':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Website','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Website'}]}
	,'tax_number':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Tax_Number','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Tax Number'}]}

<?php
if(!empty($fields)){
foreach($fields as $field)
	print ",'custom_field_customer_{$field}':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_{$field}','validation':[{'regexp':\"[a-z\\d]+\",'invalid_msg':'Invalid {$field}'}]}";

}
?>
	//,'mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
    }};


	
 validate_scope_metadata={
'customer_quick':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':Dom.get('customer_key').value}
};
	
	

Event.addListener('show_edit_name', "click", show_edit_name);
Event.addListener('show_edit_contact', "click", show_edit_contact);
Event.addListener('show_edit_telephone', "click", show_edit_telephone);
Event.addListener('show_edit_website', "click", show_edit_website);
Event.addListener('show_upload_image', "click", show_upload_image);
Event.addListener('show_upload_image', "click", show_upload_image);
Event.addListener('show_edit_address', "click", show_edit_address);
Event.addListener('show_edit_tax_number', "click", show_edit_tax_number);



<?php
if(!empty($all_fields)){
foreach($all_fields as $field){
	print 'Event.addListener(\'show_edit_'.$field.'\', "click", show_edit_'.$field.');';
	print "\n";
}
}	
?>


dialog_quick_edit_Customer_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Name", {context:["customer_name","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Name.render();

dialog_quick_edit_Customer_Contact = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Contact", {context:["customer_contact","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Contact.render();

dialog_quick_edit_Customer_Telephone = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Telephone", {context:["customer_telephone","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Telephone.render();

dialog_quick_edit_Website = new YAHOO.widget.Dialog("dialog_quick_edit_Website", {context:["customer_website","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Website.render();

dialog_image_upload = new YAHOO.widget.Dialog("dialog_image_upload", {visible : false,close:true,underlay: "none",draggable:false});
dialog_image_upload.render();

dialog_quick_edit_addresss = new YAHOO.widget.Dialog("dialog_quick_edit_addresss", { visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_addresss.render();

dialog_quick_edit_Customer_Tax_Number = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Tax_Number", {visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Tax_Number.render();




<?php
if(!empty($all_fields)){
foreach($all_fields as $field){
print 'dialog_quick_edit_Customer_'.$field.' = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_'.$field.'", {context:["customer_'.$field.'","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});dialog_quick_edit_Customer_'.$field.'.render();';	
print "\n";
}
}

for($i=1; $i<=5; $i++){
print 'dialog_badge_info_'.$i.' = new YAHOO.widget.Dialog("dialog_badge_info_'.$i.'", {visible : false,close:true,underlay: "none",draggable:false});dialog_badge_info_'.$i.'.render();';	
print "\n";
}

?>



Event.addListener('close_quick_edit_name', "click", dialog_quick_edit_Customer_Name.hide,dialog_quick_edit_Customer_Name , true);
Event.addListener('close_quick_edit_contact', "click", dialog_quick_edit_Customer_Contact.hide,dialog_quick_edit_Customer_Contact , true);
Event.addListener('close_quick_edit_telephone', "click", dialog_quick_edit_Customer_Telephone.hide,dialog_quick_edit_Customer_Telephone , true);
Event.addListener('close_quick_edit_website', "click", dialog_quick_edit_Website.hide,dialog_quick_edit_Website , true);
Event.addListener('close_quick_edit_tax_number', "click", dialog_quick_edit_Customer_Tax_Number.hide,dialog_quick_edit_Customer_Tax_Number , true);


<?php
if(!empty($all_fields)){
foreach($all_fields as $field){
print 'Event.addListener(\'close_quick_edit_'.$field.'\', "click", dialog_quick_edit_Customer_'.$field.'.hide,dialog_quick_edit_Customer_'.$field.' , true);';
print "\n";
}
}
for($i=1; $i<=5; $i++){
print 'Event.addListener(\'close_badge_info_'.$i.'\', "click", dialog_badge_info_'.$i.'.hide,dialog_badge_info_'.$i.' , true);';
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

var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_website);
customer_name_oACDS.queryMatchContains = true;
var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Website","Customer_Website_Container", customer_name_oACDS);
customer_name_oAutoComp.minQueryLength = 0; 
customer_name_oAutoComp.queryDelay = 0.1;

var customer_tax_number_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_tax_number);
customer_tax_number_oACDS.queryMatchContains = true;
var customer_tax_number_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Tax_Number","Customer_Tax_Number_Container", customer_tax_number_oACDS);
customer_tax_number_oAutoComp.minQueryLength = 0; 
customer_tax_number_oAutoComp.queryDelay = 0.1;


<?php
if(!empty($fields)){
foreach($fields as $field){
print "var customer_{$field}_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_{$field});\n";
print "customer_{$field}_oACDS.queryMatchContains = true;\n";
print "var customer_{$field}_oAutoComp = new YAHOO.widget.AutoComplete(\"Customer_{$field}\",\"Customer_{$field}_Container\", customer_{$field}_oACDS);";
print "customer_{$field}_oAutoComp.minQueryLength = 0;\n"; 
print "customer_{$field}_oAutoComp.queryDelay = 0.1;\n";
print "\n";
}
}
?>


Event.addListener('uploadButton', "click", upload_image);

	var ids = ["contact_address_description","contact_address_country_d1","contact_address_country_d2","contact_address_town","contact_address_town_d2","contact_address_town_d1","contact_address_postal_code","contact_address_street","contact_address_internal","contact_address_building"]; 
	
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'contact_');
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'contact_');
	 
	YAHOO.util.Event.addListener('contact_save_address_button', "click",save_address,{prefix:'contact_',subject:'Customer',subject_key:Dom.get('customer_key').value,type:'contact'});

Event.addListener('contact_reset_address_button', "click", reset_contact_address);


}
Event.onDOMReady(init);



var upload_image = function(e){


	if(Dom.get('upload_image_input').value==''){
		return;
	}



    YAHOO.util.Connect.setForm('testForm', true);
    var request='ar_edit_images.php?tipo=upload_image&scope='+scope+'&scope_key='+scope_key;
   //alert(request);//return;
   var uploadHandler = {
      upload: function(o) {
	  // alert(o.responseText)
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){
	window.location.reload();

	    }else{
	    Dom.get('upload_image_input').value='';
		alert(r.msg);
	    }
	    

	}
    };

    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);



  };

function delete_image(o){



    // alert(scope_key)
    image_key=o.parentNode.getAttribute('image_id');
    var answer = confirm('Delete?');
    if (answer){

	

	 var request='ar_edit_images.php?tipo=update_image&key=delete&new_value=&image_key='+escape(image_key)+'&scope='+scope+'&scope_key='+scope_key;
	//alert(request);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		  
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
			window.location.reload();

		    }else
			alert(r.msg);
		}
		
	    });
    }


}




