var dialog_test_email_credentials;

function change_checkout_method(){
	types=Dom.getElementsByClassName('site_email_server', 'button', 'site_email_servers')
	Dom.removeClass(types,'selected');
	Dom.addClass(this.id,'selected');

	if(this.id=='other'){
		Dom.setStyle('other_tbody','display','');
		reset_gmail_settings();	
	}
	else{
		Dom.setStyle('other_tbody','display','none');
		auto_fill_gmail_settings();
	}
}

function auto_fill_gmail_settings(){
	validate_scope_data['email_credentials']['incoming_server'].validated=true;
	validate_scope_data['email_credentials']['incoming_server'].changed=true;
	validate_scope_data['email_credentials']['outgoing_server'].validated=true;
	validate_scope_data['email_credentials']['outgoing_server'].changed=true;
	Dom.get('Incoming_Server').value=Dom.get('incoming_server').value;
	Dom.get('Outgoing_Server').value=Dom.get('outgoing_server').value;
}

function reset_gmail_settings(){
	validate_scope_data['email_credentials']['incoming_server'].validated=false;
	validate_scope_data['email_credentials']['incoming_server'].changed=false;
	validate_scope_data['email_credentials']['outgoing_server'].validated=false;
	validate_scope_data['email_credentials']['outgoing_server'].changed=false;
	Dom.get('Incoming_Server').value='';
	Dom.get('Outgoing_Server').value='';
}

function validate_email_address(query){
 validate_general('email_credentials','email',unescape(query));
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
function validate_forgot_body_plain(query){
 validate_general('email_credentials','forgot_body_plain',unescape(query));
}
function validate_forgot_body_html(query){
 validate_general('email_credentials','forgot_body_html',unescape(query));
}
function validate_forgot_subject(query){
 validate_general('email_credentials','forgot_subject',unescape(query));
}
function validate_welcome_body_plain(query){
 validate_general('email_credentials','welcome_body_plain',unescape(query));
}
function validate_welcome_body_html(query){
 validate_general('email_credentials','welcome_body_html',unescape(query));
}
function validate_welcome_subject(query){
 validate_general('email_credentials','welcome_subject',unescape(query));
}
function validate_welcome_source(query){
 validate_general('email_credentials','welcome_source',unescape(query));
}

	


function save_edit_email_credentials(){
    save_edit_general('email_credentials');
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
    value={test_message:Dom.get('test_message').value};


    json_value = YAHOO.lang.JSON.stringify(value);
    var request='ar_edit_sites.php?tipo=test_email_credentials&values=' + json_value + '&site_key=' + site_id;
// alert(request)

 YAHOO.util.Connect.asyncRequest('POST',request , {
  
  
success:function(o) {
  //alert(o.responseText)
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
		dialog_test_email_credentials.hide();
            } else {
		alert(r.msg);
            }
        }

    });

}


function init(){
	auto_fill_gmail_settings();

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_email_address);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Email_Address","Email_Address_Container", site_slogan_oACDS);
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

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_welcome_body_plain);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("welcome_body_plain","welcome_body_plain_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_welcome_body_html);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("welcome_body_html","welcome_body_html_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_welcome_subject);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("welcome_subject","welcome_subject_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_forgot_body_plain);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("forgot_password_body_plain","forgot_password_body_plain_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_forgot_body_html);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("forgot_password_body_html","forgot_password_body_html_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_forgot_subject);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("forgot_password_subject","forgot_password_subject_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_welcome_source);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("welcome_source","welcome_source_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 

	Event.addListener(["gmail","other"], "click", change_checkout_method);


	Event.addListener('save_edit_email_credentials', "click", save_edit_email_credentials);
	//Event.addListener('reset_edit_email_credentials', "click", reset_edit_email_credentials);

	Event.addListener('delete_email_credentials', "click", delete_email_credentials);
	//Event.addListener('test_email_credentials', "click", test_email_credentials);
	Event.addListener('test_email_credentials', "click", show_dialog_test_email_credentials);

	dialog_test_email_credentials = new YAHOO.widget.Dialog("dialog_test_email_credentials", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_test_email_credentials.render();
}

YAHOO.util.Event.onDOMReady(init);