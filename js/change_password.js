
function send_reset_password(){
Dom.setStyle('dialog_change_password_buttons','display','none')

Dom.setStyle('dialog_change_password_wait','display','')


 var data_to_update=new Object;
data_to_update['site_key']=Dom.get('site_key').value;
data_to_update['user_key']=Dom.get('user_key').value;
data_to_update['url'] ='http://'+ Dom.get('site_url').value + '/register.php';
 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));

var request='ar_register.php?tipo=send_reset_password&values='+jsonificated_values

	       
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	        
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				
			}
			else{
				
			}
			Dom.get('dialog_change_password_response_msg').innerHTML=r.msg;
			Dom.setStyle('dialog_change_password_response','display','')

Dom.setStyle('dialog_change_password_wait','display','none')
			
   		}
    });
} 

function show_dialog_change_password(){
region1 = Dom.getRegion('show_dialog_change_password'); 
    region2 = Dom.getRegion('dialog_change_password'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('dialog_change_password', pos);
Dom.setStyle('dialog_change_password_buttons','display','')
Dom.setStyle(['dialog_change_password_wait','dialog_change_password_response'],'display','none')

	dialog_change_password.show()

}

function show_dialog_set_password(){

region1 = Dom.getRegion('show_dialog_change_password'); 
    region2 = Dom.getRegion('dialog_set_password'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('dialog_set_password', pos);

	Dom.get('change_password_password1').value='';
	Dom.get('change_password_password2').value='';
		
 dialog_change_password.hide();
dialog_set_password.show();
Dom.get('change_password_password1').focus();
}


function submit_change_password(){

//Dom.setStyle('dialog_set_password_main','display','');
var error=false;


	if(  Dom.get('change_password_password1').value=='' &&  Dom.get('change_password_password1').value==Dom.get('change_password_password2').value ){
		Dom.addClass(['change_password_password1','change_password_password2'],'error');
		error=true;
		Dom.setStyle('change_password_error_no_password','display','')

	}else{
	Dom.removeClass(['change_password_password1','change_password_password2'],'error');
	Dom.setStyle('change_password_error_no_password','display','none')

	}



	if(!error){
		if( Dom.get('change_password_password1').value!=Dom.get('change_password_password2').value ){
			Dom.addClass(['change_password_password1','change_password_password2'],'error');
			if(!error)
				Dom.setStyle('change_password_error_password_not_march','display','')
				error=true;

		}else{
			Dom.removeClass(['change_password_password1','change_password_password2'],'error');
			Dom.setStyle('change_password_error_password_not_march','display','none')

		}
	}
	if(!error){
		if(!error &&   Dom.get('change_password_password1').value.length<6){
			Dom.addClass(['change_password_password1'],'error');

			if(!error)
				Dom.setStyle('change_password_error_password_too_short','display','')

			
			error=true;
		}else{
			Dom.removeClass(['change_password_password1'],'error');
			Dom.setStyle('change_password_error_password_too_short','display','none')

		}
	}



	if(!error)
	change_password()
}
	

function clear_change_password_messages(){
			Dom.setStyle(['change_password_error_password_too_short','change_password_error_password_not_march','change_password_error_no_password'],'display','none')
Dom.removeClass(['change_password_password1','change_password_password2'],'error');
Dom.setStyle('tr_change_password_error_message','display','none');
}


function change_password_changed(e){
clear_change_password_messages();
  var key;     
     if(window.event)
         Key = window.event.keyCode; //IE
     else
         Key = e.which; //firefox     

     if (Key == 13){
	 submit_change_password();
	 
	 }
}


function change_password(o){
//alert('change');//return;
    var user_key=Dom.get('user_key').value;
	

    //var store_key=Dom.get('store_key').value;
    //var site_key=Dom.get('site_key').value;
	
	//ep1=AESEncryptCtr(sha256_digest(Dom.get('change_password_password1').value),Dom.get('epwcp1').value,256);
	ep1=sha256_digest(Dom.get('change_password_password1').value);
  //   var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 
    ep2=Dom.get('epwcp2').value;
	
//alert(ep1)	
	
//	njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
    //njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
//var url ='http://'+ window.location.host + window.location.pathname;

var data={'user_key':user_key,'ep1':ep1, 'ep2':ep2}

  var json_value = encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request=' ar_edit_users.php?tipo=change_passwd&user_id='+user_key+'&ep1='+ep1+'&ep2='+ep2;
//alert(request);//return;
  Dom.setStyle('tr_email_in_db_buttons','display','none');
    Dom.setStyle('tr_forgot_password_wait2','display','');

    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){

             


		   
		        
		        dialog_set_password.hide();
		       
		      
		    }else{
		    Dom.setStyle('tr_change_password_error_message','display','')
                Dom.get('change_password_error_message').innerHTML=r.msg;
		      
		    }
			

		},failure:function(o){
		  //  alert(o)
		}
	    
	    });



}