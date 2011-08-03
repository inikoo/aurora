var Dom   = YAHOO.util.Dom;
var Event =YAHOO.util.Event;




function upDate(){

//alert("aaaa");

	qty="0"; sub="0.00";
	querystring=parent.document.URL.substring(document.URL.indexOf('?')+1);
	
	if (querystring.charAt(0)!="q")
	{
		querystring="";
	}
	if (querystring){
		today=new Date();
		millisecs_in_half_hour=1800000;
		expireDate = new Date(today.getTime() + millisecs_in_half_hour);
		document.cookie=querystring+"&exp="+expireDate+";path=/;expires="+expireDate.toGMTString();
	}else{
		if (document.cookie !=""){
			thisCookie=document.cookie.split("; ");
			for (i=0; i<thisCookie.length; i++) {
			if (thisCookie[i].split("=")[0]=="qty"){
				querystring=thisCookie[i];
				expireDate=thisCookie[i].split("exp=")[1];
			}
			}
		}
	}
	if (querystring){
		querystring=querystring.split("&");
		qty=querystring[0].split("=")[1]; if (qty==""){qty="0";}qty=parseInt(qty);
		sub=querystring[1].split("=")[1]; if (sub==""){sub="0.00";}
	}
	update=document.write("Items in cart: "+qty+"<br>Subtotal: "+sub+"");
	Dom.get('basket_stat').innerHTML=update
	return update;
}

function SC() {

/*
   value={
		/*
		userid:Dom.get('userid').value,
		product:Dom.get('product').value,
		return_address:Dom.get('return').value,
		price:Dom.get('price').value,
		qty:Dom.get('qty').value,
		action:Dom.get('action').value
		
		userid:"80372684",
		product:"SSPW-02 12x Stone Paperweights - Sacred Symbols",
		return_address:"http://localhost/kaktus/sites/test/",
		price:"7.80",
		qty:"1",
		action:"http://ww6.aitsafe.com/cf/add.cfm"
    };
*/
		userid="80372684",
		product="SSPW-02 12x Stone Paperweights - Sacred Symbols",
		//return_address="http://localhost/kaktus/sites/test/",
		return_address="http://localhost/kaktus/sites/ar_basket.php",
		price="7.80",
		qty="1",
		action="http://ww6.aitsafe.com/cf/add.cfm"

		
    //json_value = YAHOO.lang.JSON.stringify(value);
    //var request='ar_basket.php?tipo=update_basket&values=' + json_value;
	var request=action+'?product='+product+'&price='+price+'&userid='+userid+'&qty='+qty+'&return='+return_address+'&nocart=';
 alert(request)

 YAHOO.util.Connect.asyncRequest('POST',request ,{});
 
 alert("xx")

}

function init_basket() {
//alert("hoila")
   //upDate();
  Event.addListener( "SC", "click",SC);
}
Event.onDOMReady(init_basket);