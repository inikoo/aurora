var dialog_test_email_credentials;

function change_email_method(){
	types=Dom.getElementsByClassName('site_email_server', 'button', 'site_email_servers')
	Dom.removeClass(types,'selected');
	Dom.addClass(this.id,'selected');

	if(this.id=='other'){
		Dom.setStyle(['other_tbody','tr_email_login'],'display','');
		set_provider_as_other();	
	}
	else{
		Dom.setStyle(['other_tbody','tr_email_login'],'display','none');
		set_provider_as_gmail();
	}
	
}

function post_item_updated_actions(branch,r){
	window.location.reload();
}


function change_email_type(){
	types=Dom.getElementsByClassName('site_email_type', 'button', 'site_email_types')

	Dom.removeClass(types,'selected');
	Dom.addClass(this.id,'selected');


	if(this.id=='btn_plain'){
		Dom.get('email_type').value='Plain';
	}
	else if(this.id=='btn_html'){
		Dom.get('email_type').value='HTML';

	}

	//alert(Dom.get('email_type').value)
}

function change_email_provider(){
	types=Dom.getElementsByClassName('site_email_provider', 'button', 'site_email_providers')

	Dom.removeClass(types,'selected');
	Dom.addClass(this.id,'selected');
//alert(this.id)
id=["block_gmail", "block_other", "block_direct", "block_inikoo", "block_MadMimi"];
Dom.setStyle(id,'display','none');

	if(this.id=='gmail_btn'){
		Dom.setStyle('block_gmail','display','');
		Dom.get('Email_Provider').value='Gmail';
	}
	else if(this.id=='inikoo_btn'){
		Dom.setStyle('block_inikoo','display','');
		Dom.get('Email_Provider').value='Inikoo';

	}
	else if(this.id=='other_btn'){
		Dom.setStyle('block_other','display','');
		Dom.get('Email_Provider').value='Other';

	}
	else if(this.id=='php_mail_btn'){
		Dom.setStyle('block_direct','display','');
		Dom.get('Email_Provider').value='PHPMail';

	}
	else if(this.id=='madmimi_btn'){
		Dom.setStyle('block_MadMimi','display','');
		Dom.get('Email_Provider').value='MadMimi';

	}
	//alert(Dom.get('Email_Provider').value)
}



function update_email_credentials(key, value){
 var data_to_update=new Object;
 data_to_update[key]={'okey':key,'value':value}
 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));
site_id=Dom.get('site_id').value;
var request='ar_edit_sites.php?tipo=edit_email_credentials&site_key=' + site_id+'&values='+ jsonificated_values;
	           // alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	            //alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);

			if(r.state==200){
				window.location.reload()
			}
			else{
				//alert(r.msg);
			}

   		    }
    });

}

function set_provider_as_gmail(){


	if(Dom.get('Email_Provider').value=='Gmail'){
	return;
	}

	Dom.get('Email_Login').value='x';

	Dom.get('Incoming_Server').value='x';
	Dom.get('Outgoing_Server').value='x';
	Dom.get('Email_Provider').value='Gmail';
	
	
	validate_scope_data['email_credentials']['email_provider'].validated=true;
	validate_scope_data['email_credentials']['email_provider'].changed=true;
	
	
	
	
		validate_scope_data['email_credentials']['incoming_server'].validated=true;
	validate_scope_data['email_credentials']['incoming_server'].changed=true;
	validate_scope_data['email_credentials']['outgoing_server'].validated=true;
	validate_scope_data['email_credentials']['outgoing_server'].changed=true;
	validate_scope_data['email_credentials']['login'].validated=true;
	validate_scope_data['email_credentials']['login'].changed=true;
	
	validate_scope('email_credentials')
	
}

function set_provider_as_other(){
	if(Dom.get('Email_Provider').value=='Other'){
	return;
	}
	validate_scope_data['email_credentials']['incoming_server'].validated=false;
	validate_scope_data['email_credentials']['incoming_server'].changed=false;
	validate_scope_data['email_credentials']['outgoing_server'].validated=false;
	validate_scope_data['email_credentials']['outgoing_server'].changed=false;
	validate_scope_data['email_credentials']['login'].validated=false;
	validate_scope_data['email_credentials']['login'].changed=false;
	Dom.get('Incoming_Server').value='';
	Dom.get('Outgoing_Server').value='';
	Dom.get('Email_Login').value='';
	Dom.get('Email_Provider').value='Other';
	validate_scope('email_credentials')

}



function validate_email_address(query){
 validate_general('email_credentials','email',unescape(query));
}
function validate_api_email_address_MadMimi(query){
 validate_general('email_credentials_MadMimi','api_email_MadMimi',unescape(query));
}
function validate_api_key_MadMimi(query){
 validate_general('email_credentials_MadMimi','email_MadMimi',unescape(query));
}

function validate_email_MadMimi(query){
 validate_general('email_credentials_MadMimi','api_key_MadMimi',unescape(query));
}

function validate_email_address_direct_mail(query){
 validate_general('email_credentials_direct_mail','email_direct_mail',unescape(query));
}
function validate_email_address_other(query){
 validate_general('email_credentials_other','email_other',unescape(query));
}
function validate_email_address_inikoo_mail(query){
 validate_general('email_credentials_inikoo_mail','email_inikoo_mail',unescape(query));
}

function validate_access_key(query){
 validate_general('email_credentials','access_key',unescape(query));
}
function validate_secret_key(query){
 validate_general('email_credentials','secret_key',unescape(query));
}
function validate_login_other(query){
 validate_general('email_credentials_other','login',unescape(query));
}
function validate_password(query){
 validate_general('email_credentials','password',unescape(query));
}
function validate_password_other(query){
 validate_general('email_credentials_other','password',unescape(query));
}


function validate_incoming_server_other(query){
 validate_general('email_credentials_other','incoming_server',unescape(query));
}
function validate_outgoing_server_other(query){
 validate_general('email_credentials_other','outgoing_server',unescape(query));
}

	


function save_edit_email_credentials(){
    save_edit_general_bulk('email_credentials');
}
function save_edit_email_credentials_direct_mail(){
    save_edit_general_bulk('email_credentials_direct_mail');
}
function save_edit_email_credentials_inikoo_mail(){
    save_edit_general_bulk('email_credentials_inikoo_mail');
}
function save_edit_email_credentials_other(){
    save_edit_general_bulk('email_credentials_other');
}

function save_edit_email_credentials_MadMimi(){
    save_edit_general_bulk('email_credentials_MadMimi');
}
function reset_edit_email_credentials(){
    reset_edit_general('email_credentials');
}
function reset_edit_email_credentials_other(){
    reset_edit_general('email_credentials_other');
}
function reset_edit_email_credentials_MadMimi(){
    reset_edit_general('email_credentials_MadMimi');
}
function reset_edit_email_credentials_direct_mail(){
    reset_edit_general('email_credentials_direct_mail');
}
function reset_edit_email_credentials_inikoo_mail(){
    reset_edit_general('email_credentials_inikoo_mail');
}



function test_email_credentials(){
	
}

function show_dialog_test_email_credentials(o){

	//region1 = Dom.getRegion('test_email_credentials');
	region1 = Dom.getRegion(o); 
	region2 = Dom.getRegion('dialog_test_email_credentials'); 
	var pos =[region1.right-region2.width,region1.bottom+2]
	Dom.setXY('dialog_test_email_credentials', pos);
	dialog_test_email_credentials.show()
}

function delete_email_credentials(){

site_id=Dom.get('site_id').value;
var request='ar_edit_sites.php?tipo=delete_email_credentials&site_key=' + site_id
	           // alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	            //alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				window.location.reload()
			}
			else{
				alert(r.msg);
			}
   		    }
    });

}

function send_test_message() {
site_id=Dom.get('site_id').value;
    value={to:Dom.get('test_message_to').value, email_type:Dom.get('email_type').value};


    json_value = YAHOO.lang.JSON.stringify(value);
    var request='ar_edit_sites.php?tipo=test_email_credentials&values=' + json_value + '&site_key=' + site_id+'&promotion_name='+Dom.get('promotion_name').value;// + '&email_type=' + Dom.get('email_type').value;
 //alert(request)

 YAHOO.util.Connect.asyncRequest('POST',request , {
  
  
success:function(o) {
 // alert(o.responseText)
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
		alert(r.msg);
		dialog_test_email_credentials.hide();
            } else {
		alert(r.msg);
            }
        }

    });

}


function init_email_credentials(){
	//auto_fill_gmail_settings();


	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_api_email_address_MadMimi);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("API_Email_Address_MadMimi","API_Email_Address_MadMimi_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_api_key_MadMimi);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("API_Key_MadMimi","API_Key_MadMimi_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_email_MadMimi);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Address_MadMimi","Email_Address_MadMimi_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_email_address);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Address","Email_Address_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_email_address_direct_mail);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Address_direct_mail","Email_Address_direct_mail_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_email_address_other);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Address_other","Email_Address_other_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   


	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_email_address_inikoo_mail);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Address_inikoo_mail","Email_Address_inikoo_mail_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   



var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_login_other);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Login_other","Email_Login_other_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   


	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_password);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Password","Email_Password_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_password_other);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Password_other","Email_Password_other_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_incoming_server_other);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Incoming_Server_other","Incoming_Server_other_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_outgoing_server_other);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Outgoing_Server_other","Outgoing_Server_other_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   


	Event.addListener(["gmail","other"], "click", change_email_method);
	
	Event.addListener(["gmail_btn","inikoo_btn", "php_mail_btn", "other_btn", "madmimi_btn"], "click", change_email_provider);
	Event.addListener(["btn_plain","btn_html"], "click", change_email_type);

	Event.addListener('save_edit_email_credentials', "click", save_edit_email_credentials);
	Event.addListener('save_edit_email_credentials_direct_mail', "click", save_edit_email_credentials_direct_mail);
	Event.addListener('save_edit_email_credentials_other', "click", save_edit_email_credentials_other);
	Event.addListener('save_edit_email_credentials_inikoo_mail', "click", save_edit_email_credentials_inikoo_mail);
	Event.addListener('save_edit_email_credentials_MadMimi', "click", save_edit_email_credentials_MadMimi);

	Event.addListener('reset_edit_email_credentials', "click", reset_edit_email_credentials);
	Event.addListener('reset_edit_email_credentials_other', "click", reset_edit_email_credentials_other);
	Event.addListener('reset_edit_email_credentials_MadMimi', "click", reset_edit_email_credentials_MadMimi);
	Event.addListener('reset_edit_email_credentials_direct_mail', "click", reset_edit_email_credentials_direct_mail);
	Event.addListener('reset_edit_email_credentials_inikoo_mail', "click", reset_edit_email_credentials_inikoo_mail);

	Event.addListener(["delete_email_credentials", "delete_email_credentials_direct_mail", "delete_email_credentials_inikoo_mail", "delete_email_credentials_other", "delete_email_credentials_MadMimi"], "click", delete_email_credentials);
	//Event.addListener('test_email_credentials', "click", test_email_credentials);
	Event.addListener('test_email_credentials', "click", show_dialog_test_email_credentials);

	dialog_test_email_credentials = new YAHOO.widget.Dialog("dialog_test_email_credentials", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_test_email_credentials.render();
}

YAHOO.util.Event.onDOMReady(init_email_credentials);