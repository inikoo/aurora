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


function go_to_result(){
    location.href=this.getAttribute('link')+this.getAttribute('key');
}


    function search_customers(query,scope){
    
     

    var ar_file='ar_search.php';
    var request='tipo=customers&q='+escape(query)+'&scope='+scope;
    //alert(request)
    var search_scope='customers';
    var result_categories={'emails':1,'names':1,'contacts':1,'locations':1,'tax_numbers':1}
    
    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					
						    Dom.get(search_scope+'_search_results').removeChild(Dom.get(search_scope+'_search_results_table'));

						if(r.data.results==0){
						 //    Dom.get(search_scope+'_search_results').style.display='none';
// 						    for (i in result_categories){
// 							Dom.get(search_scope+'_search_'+i).style.display='none';
// 							Dom.get(search_scope+'_search_'+i+'_results').innerHTML='';
							
// 						    }
						    
						    Dom.get(search_scope+'_search_results').style.display='none';
						    //Dom.get(search_scope+'_search_results').innerHTML=''
						    oTbl=document.createElement("Table");
						    oTbl.id=search_scope+'_search_results_table';
						    Dom.get(search_scope+'_search_results').appendChild(oTbl);

						}else{
						    
						    
			 			    Dom.get(search_scope+'_search_results').style.display='';
						    


						    oTbl=document.createElement("Table");
						    Dom.addClass(oTbl,'search_result');
						    var link=r.link;
						    var first=true;
						    for(result_key in r.data){
							oTR= oTbl.insertRow(-1);
							oTR.setAttribute('key',result_key);
							oTR.setAttribute('link',link);

							
							oTR.onclick = go_to_result;
							var oTD= oTR.insertCell(0);
							Dom.addClass(oTD,'naked');
						
							if(first){
							    oTD.innerHTML='<img src="art/icons/arrow_right.png" alt="go">';

							first=false;
							}

							var oTD= oTR.insertCell(1);
							oTD.innerHTML=r.data[result_key ].key;
							var oTD= oTR.insertCell(2);
							oTD.innerHTML=r.data[result_key ].name;
							var oTD= oTR.insertCell(3);
							oTD.innerHTML=r.data[result_key ].address

						    }
						    oTbl.id=search_scope+'_search_results_table';
						    Dom.get(search_scope+'_search_results').appendChild(oTbl);
						 
// 						    
						    

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