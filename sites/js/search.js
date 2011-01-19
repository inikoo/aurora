var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;

function go_to_result(){
    location.href=this.getAttribute('link')+this.getAttribute('key');
}


function select_prev_result(){
elements_array=Dom.getElementsByClassName('selected', 'tr', 'search_results_table');
tr=elements_array[0];
Dom.removeClass(tr,'selected');
Dom.addClass('tr_result'+tr.getAttribute('prev'),'selected');
}
function select_next_result(){
elements_array=Dom.getElementsByClassName('selected', 'tr', 'search_results_table');
tr=elements_array[0];
Dom.removeClass(tr,'selected');
Dom.addClass('tr_result'+tr.getAttribute('next'),'selected');
}

function goto_search_result(){
elements_array=Dom.getElementsByClassName('selected', 'tr', 'search_results_table');

tr=elements_array[0];
if(tr!= undefined)
location.href=tr.getAttribute('link')+tr.getAttribute('key');

}


function magic_search(query){


store_key=Dom.get('search').getAttribute('store_key');


    var ar_file='ar_search.php';

    var request='q='+escape(query)+'&store_key='+store_key;
   
    
    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					
						    Dom.get('search_results').removeChild(Dom.get('search_results_table'));
						   
						if(r.results==0){
						   
						    Dom.get('search_results').style.display='none';
						    oTbl=document.createElement("Table");
						    oTbl.id='search_results_table';
						    Dom.get('search_results').appendChild(oTbl);
                             Dom.get('clean_search').src='art/icons/zoom.png';
						}else{
						    
						     Dom.get('clean_search').src='art/icons/cross_bw.png';
			 			    Dom.get('search_results').style.display='';
						    


						    oTbl=document.createElement("Table");
						    Dom.addClass(oTbl,'search_result');
						    var link=r.link;
						    var first=true;
						    var result_number=1;
						    for(result_key in r.data){
							oTR= oTbl.insertRow(-1);


						

							
							

							var oTD= oTR.insertCell(0);
							Dom.addClass(oTD,'naked');
					
					  oTR.setAttribute('key',r.data[result_key ].key);
							    oTR.setAttribute('link',r.data[result_key ].link);
							   

							    var oTD= oTR.insertCell(1);
							    oTD.innerHTML=r.data[result_key ].image;
							    var oTD= oTR.insertCell(2);
							    oTD.innerHTML=r.data[result_key ].code;
							    var oTD= oTR.insertCell(3);
							    oTD.innerHTML=r.data[result_key ].description;
					            
				
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
						    oTbl.id='search_results_table';
						    Dom.get('search_results').appendChild(oTbl);
						 
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

function search_events(e){
   var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

var state=Dom.get('search').getAttribute('state');
     if (key == 13 )
	 goto_search_result();
	 else if(key == 40 ){
	 select_next_result();
	 Dom.get('search').setAttribute('state','ready');
	 }else if(key == 38 ){
	 select_prev_result();
	 Dom.get('search').setAttribute('state','ready');
	 }else if(key == 39  && state=='ready' ){// right arrow
	goto_search_result();
	 }else if(key == 37   ){// left arrow
	Dom.get('search').setAttribute('state','');
	 }
	 
	 
}

function clear_search(e){
Dom.get('search').value='';
Dom.get('search_results').style.display='none';
                             Dom.get('clean_search').src='art/icons/zoom.png';
}

function init(){
  
     var search_oACDS = new YAHOO.util.FunctionDataSource(magic_search);
     search_oACDS.queryMatchContains = true;
 

 var search_oAutoComp = new YAHOO.widget.AutoComplete("search","search_Container", search_oACDS);
 
 
 
     search_oAutoComp.minQueryLength = 0; 
     search_oAutoComp.queryDelay = 0.15;
     Event.addListener("search", "keyup",search_events)
     Event.addListener("clean_search", "click",clear_search);

 }
 

YAHOO.util.Event.onDOMReady(init); 
 
 
 
 
 
 