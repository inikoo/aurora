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


function change_email_provider(){
	types=Dom.getElementsByClassName('site_email_provider', 'button', 'site_email_providers')

	Dom.removeClass(types,'selected');
	Dom.addClass(this.id,'selected');
//alert(this.id)
Dom.setStyle(['smtp_1','smtp_2','other_tbody', 'amazon','php_mail'],'display','none');
	if(this.id=='smtp_btn'){
		Dom.setStyle(['smtp_1', 'smtp_2'],'display','');
		update_email_credentials('email_provider','Gmail')
	}
	else if(this.id=='amazon_btn'){
		Dom.setStyle(['amazon'],'display','');
		update_email_credentials('email_provider','Amazon')
	}
	else{
		Dom.setStyle(['php_mail'],'display','');
		update_email_credentials('email_provider','PHPMail')

	}
	
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

function validate_access_key(query){
 validate_general('email_credentials','access_key',unescape(query));
}
function validate_secret_key(query){
 validate_general('email_credentials','secret_key',unescape(query));
}
function validate_login(query){
 validate_general('email_credentials','login',unescape(query));
}
function validate_password(query){
 validate_general('email_credentials','password',unescape(query));
}
function validate_incoming_server(query){
 validate_general('email_credentials','incoming_server',unescape(query));
}
function validate_outgoing_server(query){
 validate_general('email_credentials','outgoing_server',unescape(query));
}

	


function save_edit_email_credentials(){
    save_edit_general_bulk('email_credentials');
}
function reset_edit_email_credentials(){
    reset_edit_general('email_credentials');
}


function test_email_credentials(){
	
}

function show_dialog_test_email_credentials(){

	region1 = Dom.getRegion('test_email_credentials'); 
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
    value={to:Dom.get('test_message_to').value};


    json_value = YAHOO.lang.JSON.stringify(value);
    var request='ar_edit_sites.php?tipo=test_email_credentials&values=' + json_value + '&site_key=' + site_id;
// alert(request)

 YAHOO.util.Connect.asyncRequest('POST',request , {
  
  
success:function(o) {
//  alert(o.responseText)
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
		dialog_test_email_credentials.hide();
            } else {
		alert(r.msg);
            }
        }

    });

}


function init_email_credentials(){
	//auto_fill_gmail_settings();





	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_email_address);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Address","Email_Address_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_access_key);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Access_Key","Access_Key_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_secret_key);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Secret_Key","Secret_Key_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0;
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;


var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_login);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Login","Email_Login_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   


	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_password);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Password","Email_Password_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_incoming_server);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Incoming_Server","Incoming_Server_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_outgoing_server);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Outgoing_Server","Outgoing_Server_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   


	Event.addListener(["gmail","other"], "click", change_email_method);
	
	Event.addListener(["smtp_btn","amazon_btn", "php_mail_btn"], "click", change_email_provider);


	Event.addListener('save_edit_email_credentials', "click", save_edit_email_credentials);
	Event.addListener('reset_edit_email_credentials', "click", reset_edit_email_credentials);

	Event.addListener('delete_email_credentials', "click", delete_email_credentials);
	//Event.addListener('test_email_credentials', "click", test_email_credentials);
	Event.addListener('test_email_credentials', "click", show_dialog_test_email_credentials);

	dialog_test_email_credentials = new YAHOO.widget.Dialog("dialog_test_email_credentials", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_test_email_credentials.render();
}

YAHOO.util.Event.onDOMReady(init_email_credentials);