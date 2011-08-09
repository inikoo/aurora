var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;



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

function init(){
Event.addListener("checkout", "click",checkout);
Event.addListener("see_basket", "click",see_basket);


Event.addListener("logout", "click",logout);



}
Event.onDOMReady(init);
