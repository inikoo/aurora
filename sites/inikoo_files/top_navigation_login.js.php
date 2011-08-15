var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;


function change_password(){

    var user_key=Dom.get('user_key').innerHTML;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;
	
	ep1=AESEncryptCtr(sha256_digest(Dom.get('change_password_password1').value),Dom.get('epwcp1').value,256);
  //   var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 
    ep2=Dom.get('epwcp2').value;
	
//alert(ep1)	
	
///	njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
    //njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
var url ='http://'+ window.location.host + window.location.pathname;

var data={'user_key':user_key,'store_key':store_key,'site_key':site_key,'ep1':ep1, 'ep2':ep2}

  var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request='../../inikoo_files/ar_register.php?tipo=change_password&values='+json_value;

  Dom.setStyle('tr_email_in_db_buttons','display','none');
    Dom.setStyle('tr_forgot_password_wait2','display','');

    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){

             


		        if(r.result=='ok'){
                Dom.setStyle('change_password_ok','display','');
                Dom.setStyle('change_password_form','display','none');
    		    }else{
		        Dom.setStyle('change_password_ok','display','none');
                Dom.setStyle('change_password_form','display','');
		        }
		    }else{
		          Dom.setStyle('change_password_ok','display','none');
                Dom.setStyle('change_password_form','display','');
		    }
			

		},failure:function(o){
		  //  alert(o)
		}
	    
	    });



}


function logout(){

//var    my_url = window.location.protocol + "://" + window.location.host  + window.location.pathname;
//var url =  window.location.host + "/" + window.location.pathname;
//window.location =my_url;
//alert(my_url)
window.location ='http://'+ window.location.host + window.location.pathname+'?logout=1';
}

function checkout(){

window.location=Dom.get('checkout').getAttribute('link')
}

function see_basket(){

window.location=Dom.get('see_basket').getAttribute('link')
}

function show_change_password_dialog(){
hide_dialogs()
  Dom.setStyle('dialog_change_password','display','block');


  Dom.setStyle('change_password_ok','display','none');
                Dom.setStyle('change_password_form','display','');
Dom.get('change_password_password1').focus();

}

function show_actions_dialog(){
hide_dialogs()
Dom.setStyle('dialog_actions','display','block')

}
function hide_actions_dialog(){

Dom.setStyle('dialog_actions','display','none')
}
function hide_change_password_dialog(){

Dom.setStyle('dialog_change_password','display','none')
}

function submit_change_password(){

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


function hide_dialogs(){
Dom.setStyle(['dialog_actions','dialog_change_password'],'display','none')
}


function init(){
Event.addListener("checkout", "click",checkout);
Event.addListener("see_basket", "click",see_basket);


Event.addListener("logout", "click",logout);

Event.addListener("hide_actions_dialog", "click",hide_actions_dialog);
Event.addListener("show_change_password_dialog", "click",show_change_password_dialog);
Event.addListener("show_actions_dialog", "click",show_actions_dialog);


Event.addListener(["hide_change_password_dialog","hide_change_password_dialog2"], "click",hide_change_password_dialog);


Event.addListener("submit_change_password", "click",submit_change_password);



}
Event.onDOMReady(init);
