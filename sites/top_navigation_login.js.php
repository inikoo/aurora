var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;



function logout(){

//var    my_url = window.location.protocol + "://" + window.location.host  + window.location.pathname;
//var url =  window.location.host + "/" + window.location.pathname;
//window.location =my_url;
//alert(my_url)
window.location ='http://'+ window.location.host + window.location.pathname+'?logout=1';
}



function init(){


Event.addListener("logout", "click",logout);



}
Event.onDOMReady(init);
