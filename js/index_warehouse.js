
function search_order_picking_aid(){
value=Dom.get('order_picking_aid').value
if(value==''){

return;
}

     var request='ar_search.php?tipo=search_order_picking_aid&public_id='+value;
// alert(request);return;
 
  Dom.setStyle('order_picking_aid_msg','display','none');
    Dom.setStyle('order_picking_aid_waiting','display','');

    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
					         

		   				window.location ='order_pick_aid.php?id='+r.id+'&refresh=1';

		        
		       
		    }
		    else{
		     Dom.get('order_picking_aid_msg').innerHTML=r.msg;
		        Dom.setStyle('order_picking_aid_msg','display','');
    Dom.setStyle('order_picking_aid_waiting','display','none');
    
		    }
			

		},failure:function(o){
		  //  alert(o)
		}
	    
	    });

}

function search_order_picking_aid_on_enter(e){

 Dom.get('order_picking_aid_msg').innerHTML='';


  var key;     
     if(window.event)
         Key = window.event.keyCode; //IE
     else
         Key = e.which; //firefox     

     if (Key == 13){
	 search_order_picking_aid();
	 
	 }
}

function init_warehouse(){
Dom.get('order_picking_aid').focus()
Event.addListener("search_order_picking_aid", "click", search_order_picking_aid);
Event.addListener('order_picking_aid', "keydown", search_order_picking_aid_on_enter);

}

YAHOO.util.Event.onDOMReady(init_warehouse);
