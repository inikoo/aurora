var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

function isValidEmail(email){
    var RegExp = /^((([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+(\.([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.))*([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/i
	if(RegExp.test(email)){
	    return true;
	}else{
	    return false;
	}
}

function show_login_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog','dialog_register','dialog_forgot_password'],'display','none');
Dom.setStyle('dialog_login','display','block');
}
function hide_login_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog'],'display','');
Dom.setStyle('dialog_login','display','none');
Dom.get('login_handle').value='';
Dom.get('login_password').value='';
}

function show_register_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog','dialog_login','dialog_forgot_password'],'display','none');
Dom.setStyle('dialog_register','display','block');
}
function hide_register_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog'],'display','');
Dom.setStyle('dialog_register','display','none');
}

function show_forgot_password_dialog(){
Dom.setStyle(['show_login_dialog','show_forgot_password_dialog','dialog_login','dialog_register'],'display','none');
Dom.setStyle('dialog_forgot_password','display','block');
}
function hide_forgot_password_dialog(){
Dom.setStyle(['show_login_dialog','show_forgot_password_dialog'],'display','');
Dom.setStyle('dialog_forgot_password','display','none');
}


function show_register_from_login(){
Dom.get('register_email').value=Dom.get('login_handle').value;
show_register_dialog();
}

function submit_login(){
var error=false;
if(Dom.get('login_password').value==''){
Dom.addClass('login_password','error');
error=true;
}else{
Dom.removeClass('login_password','error');

}
if(  !isValidEmail(Dom.get('login_handle').value)){
Dom.addClass('login_handle','error');
error=true;
}else{
Dom.removeClass('login_handle','error');

}

if(!error)
login()


}


function show_forgot_password_from_login(){
Dom.get('forgot_password_email').value=Dom.get('login_handle').value;
show_forgot_password_dialog();
}

function init(){
Event.addListener("show_login_dialog", "click", show_login_dialog);
Event.addListener("show_register_dialog", "click", show_register_dialog);
Event.addListener("hide_login_dialog", "click", hide_login_dialog);
Event.addListener("hide_register_dialog", "click", hide_register_dialog);

Event.addListener("submit_login", "click", submit_login);
Event.addListener("link_forgot_password_from_login", "click", show_forgot_password_from_login);
Event.addListener("link_register_from_login", "click", show_register_from_login);


}
Event.onDOMReady(init);
