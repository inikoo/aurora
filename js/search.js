var Dom   = YAHOO.util.Dom;
var submit_search_on_enter=function(e,tipo){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

     if (key == 13)
	 submit_search(e,tipo);
};


var submit_search=function(e,data){

    if(typeof( data ) == 'string')
	var data={tipo:data,container:data};
    
    var q =Dom.get(data.container+'_search').value;
    if(q=='')
	return;
    var request='ar_search.php?tipo='+data.tipo+'&q='+escape(q);
    // alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
	
		if (r.state == 200){
		    window.location.href=r.url;
		}else if(r.state==400){
		    
		    Dom.get(data.container+'_search_msg').innerHTML=r.msg1;
		     Dom.get(data.container+'_search_sugestion').innerHTML=r.msg2;
		}else
		    Dom.get(data.container+'_search_msg').innerHTML=r.msg;
	    }
	});
}


function search_customers_in_store(query){
    search_customers(query,'store');
}


    function search_customers(query,scope){
    
     

    var ar_file='ar_search.php';
    var request='tipo=customers&q='+escape(query)+'&scope='+scope;
    
    var search_scope='customers';
    var result_categories={'emails':1,'names':1,'contacts':1,'locations':1,'tax_numbers':1}
    
    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    //   alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						if(r.data.results==0){
						    Dom.get(search_scope+'_search_results').style.display='none';
						    for (i in result_categories){
							Dom.get(search_scope+'_search_'+i).style.display='none';
							Dom.get(search_scope+'_search_'+i+'_results').innerHTML='';
							
						    }
						}else{
						    Dom.get(search_scope+'_search_results').style.display='';
						    for (i in result_categories){
							if(r.data[i]>0){
							    Dom.get(search_scope+'_search_'+i).style.display='';
							    Dom.get(search_scope+'_search_'+i+'_results').innerHTML=r.data[i+'_results'];
							}else{
							    Dom.get(search_scope+'_search_'+i).style.display='none';
							    Dom.get(search_scope+'_search_'+i+'_results').innerHTML='';
							}
						    }
						    

						}
						
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					    callback();
					},
					    scope:this
					    },
				    request
				    
				    );  
    
}