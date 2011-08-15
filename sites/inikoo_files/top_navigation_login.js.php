var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;


function change_password(){

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

}

function show_actions_dialog(){
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


function init(){
Event.addListener("checkout", "click",checkout);
Event.addListener("see_basket", "click",see_basket);


Event.addListener("logout", "click",logout);

Event.addListener("hide_actions_dialog", "click",hide_actions_dialog);
Event.addListener("show_change_password_dialog", "click",show_change_password_dialog);
Event.addListener("show_actions_dialog", "click",show_actions_dialog);


Event.addListener("hide_change_password_dialog", "click",hide_change_password_dialog);


Event.addListener("submit_change_password", "click",submit_change_password);



}
Event.onDOMReady(init);
