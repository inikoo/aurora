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

function search_part(query){
    search(query,'part','');
}

function search_customers_in_store(query){
    search(query,'customers','store');
}
function search_products_in_store(query){
    search(query,'products','store');
}

function search_all(query){
    search(query,'all','');
}

function search_products(query){
    search(query,'products','all_stores');
}

function search_locations_in_warehouse(query){
    search(query,'locations','warehouse');

}



function go_to_result(){
    location.href=this.getAttribute('link')+this.getAttribute('key');
}


function search(query,subject,scope){





    var ar_file='ar_search.php';

    var request='tipo='+subject+'&q='+escape(query)+'&scope='+scope;
   
    
    
    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					   // alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					
						    Dom.get(subject+'_search_results').removeChild(Dom.get(subject+'_search_results_table'));
						    //alert(r.results)
						if(r.results==0){
						   
						    Dom.get(subject+'_search_results').style.display='none';
						    oTbl=document.createElement("Table");
						    oTbl.id=subject+'_search_results_table';
						    Dom.get(subject+'_search_results').appendChild(oTbl);
                             Dom.get(subject+'_clean_search').src='art/icons/zoom.png';
						}else{
						    
						     Dom.get(subject+'_clean_search').src='art/icons/cross_bw.png';
			 			    Dom.get(subject+'_search_results').style.display='';
						    


						    oTbl=document.createElement("Table");
						    Dom.addClass(oTbl,'search_result');
						    var link=r.link;
						    var first=true;
						    var result_number=1;
						    for(result_key in r.data){
							oTR= oTbl.insertRow(-1);


						

							
							

							var oTD= oTR.insertCell(0);
							Dom.addClass(oTD,'naked');
						
						//	if(first){
						//	    oTD.innerHTML='<img src="art/icons/arrow_right.png" alt="go">';

						//	first=false;
						//	}

							if(subject=='customers'){
							    oTR.setAttribute('key',result_key);
							    oTR.setAttribute('link',link);
							    var oTD= oTR.insertCell(1);
							    oTD.innerHTML=r.data[result_key ].key;
							    var oTD= oTR.insertCell(2);
							    oTD.innerHTML=r.data[result_key ].name;
							    var oTD= oTR.insertCell(3);
							    oTD.innerHTML=r.data[result_key ].address;
							}else if(subject=='part'){
							    oTR.setAttribute('key',r.data[result_key ].sku);
							    oTR.setAttribute('link',r.data[result_key ].link);
							   	oTR.setAttribute('sku',r.data[result_key ].fsku);
							    oTR.setAttribute('description',r.data[result_key ].description);


							    var oTD= oTR.insertCell(1);
							    oTD.innerHTML=r.data[result_key ].fsku;
							    var oTD= oTR.insertCell(2);
							    oTD.innerHTML=r.data[result_key ].description ;
							  

							}else if(subject=='all'){
							    oTR.setAttribute('key',r.data[result_key ].key);
							    oTR.setAttribute('link',r.data[result_key ].link);
							   

							    var oTD= oTR.insertCell(1);
							    oTD.innerHTML=r.data[result_key ].image;
							    var oTD= oTR.insertCell(2);
							    oTD.innerHTML=r.data[result_key ].name;
							    var oTD= oTR.insertCell(3);
							    oTD.innerHTML=r.data[result_key ].description;

							}else if(subject=='products'){
							    oTR.setAttribute('key',r.data[result_key ].key);
							    oTR.setAttribute('link',r.data[result_key ].link);
							   

							    var oTD= oTR.insertCell(1);
							    oTD.innerHTML=r.data[result_key ].image;
							    var oTD= oTR.insertCell(2);
							    oTD.innerHTML=r.data[result_key ].code;
							    var oTD= oTR.insertCell(3);
							    oTD.innerHTML=r.data[result_key ].description;

							}else if(subject=='locations'){
							    oTR.setAttribute('key',r.data[result_key ].key);
							    oTR.setAttribute('link',r.data[result_key ].link);
							    
							      var oTD= oTR.insertCell(1);
							    oTD.innerHTML=r.data[result_key ].code;
							    var oTD= oTR.insertCell(2);
							    oTD.innerHTML=r.data[result_key ].area;
							     var oTD= oTR.insertCell(3);
							    oTD.innerHTML=r.data[result_key ].use;
							    
							    if(r.data[result_key ].type=='Part'){
							   
							    var oTD= oTR.insertCell(4);
							    oTD.innerHTML=r.data[result_key ].sku+' ('+r.data[result_key ].used_in+')';
							    }else{
							    var oTD= oTR.insertCell(4);
							    oTD.innerHTML='';
							   
							  }

							}
							oTR.setAttribute('prev',result_number-1);
						oTR.setAttribute('next',result_number+1);
					if(first){
                                Dom.addClass(oTR,'selected');
							first=false;
							oTR.setAttribute('prev',1);
							}
							if(r.results==result_number){
						oTR.setAttribute('next',1);
}							
							oTR.setAttribute('id','tr_result'+result_number);
							 
							 
							oTR.onclick = go_to_result;
							
							result_number++;
							
						    }
						    oTbl.id=subject+'_search_results_table';
						    Dom.get(subject+'_search_results').appendChild(oTbl);
						 
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




function search_events(e,subject){
   var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

var state=Dom.get(subject+'_search').getAttribute('state');
     if (key == 13 )
	 goto_search_result(subject);
	 else if(key == 40 ){
	 select_next_result(subject);
	 Dom.get(subject+'_search').setAttribute('state','ready');
	 }else if(key == 38 ){
	 select_prev_result(subject);
	 Dom.get(subject+'_search').setAttribute('state','ready');
	 }else if(key == 39  && state=='ready' ){// right arrow
	goto_search_result(subject);
	 }else if(key == 37   ){// left arrow
	Dom.get(subject+'_search').setAttribute('state','');
	 }
	 
	 
}

function select_prev_result(subject){
elements_array=Dom.getElementsByClassName('selected', 'tr', subject+'_search_results_table');
tr=elements_array[0];
Dom.removeClass(tr,'selected');
Dom.addClass('tr_result'+tr.getAttribute('prev'),'selected');
}
function select_next_result(subject){
elements_array=Dom.getElementsByClassName('selected', 'tr', subject+'_search_results_table');
tr=elements_array[0];
Dom.removeClass(tr,'selected');
Dom.addClass('tr_result'+tr.getAttribute('next'),'selected');
}

function goto_search_result(subject){
elements_array=Dom.getElementsByClassName('selected', 'tr', subject+'_search_results_table');

tr=elements_array[0];
if(tr!= undefined)
location.href=tr.getAttribute('link')+tr.getAttribute('key');

}
function clear_search(e,subject){
Dom.get(subject+'_search').value='';
Dom.get(subject+'_search_results').style.display='none';
						   
                             Dom.get(subject+'_clean_search').src='art/icons/zoom.png';
}