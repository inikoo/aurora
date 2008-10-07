




YAHOO.widget.DataTable.MSG_EMPTY='<?=_('No records found')?>.';
YAHOO.widget.DataTable.MSG_ERROR='<?=_('Data error')?>.';
YAHOO.widget.DataTable.MSG_LOADING='<?=_('Loading data')?>...';



function reload(extra_arg){
    
    if (typeof(extra_arg)=='undefined')extra_arg='';
    
    
    var Dom = YAHOO.util.Dom;
    document.getElementById('loadingicon'+this.id).style.visibility='visible'; 
    var datasource=this.getDataSource();

    //  alert(this.get("sortedBy").dir);
    //    alert(newrequest);
    

    if(Dom.get('f_field'+this.id)==null)
	var extra='';
    else
    	var extra="&f_field="+Dom.get('f_field'+this.id).value+"&f_value="+Dom.get('f_input'+this.id).value;

	
    var newrequest="&sf="+this.offset+"&nr="+this.recordsperPage+extra+extra_arg;


    // Dom.get('paginatormenurender0').innerHTML=newrequest;
    


    var oCallback = {
	
	success: this.onDataReturnInitializeTable,
	failure: this.onDataReturnInitializeTable,
	scope: this,
	argument: {
	    // Pass in sort values so UI can be updated in callback function
	    sorting: {
		key: this.get("sortedBy").key,
		dir: this.get("sortedBy").dir
		
	    }
	}
    }

	datasource.sendRequest(newrequest,oCallback);    
}



	    // Override function for custom sorting
function mysort (oColumn) {
    







    if(oColumn.key === this.get("sortedBy").key) {
	sDir = (this.get("sortedBy").dir === YAHOO.widget.DataTable.CLASS_ASC) ?
	    YAHOO.widget.DataTable.CLASS_DESC : YAHOO.widget.DataTable.CLASS_ASC;
    }else
	sDir = oColumn.sortOptions.defaultDir;

    


   var newrequest="&sf=0&o="+oColumn.key+"&od="+sDir;

    // Dom.get('paginatormenurender0').innerHTML=newrequest;

    
   var oCallback = {
       success: this.onDataReturnInitializeTable,
       failure: this.onDataReturnInitializeTable,
       scope: this,
       argument: {
	    // Pass in sort values so UI can be updated in callback function
	   sorting: {
	       key: oColumn.key,
	       dir: sDir
	   }
       }
   }
   
   this.getDataSource().sendRequest(newrequest,oCallback);    
   
   
   //this.myreload();
   
   
};

function resetfilter(e,o){
    var Dom = YAHOO.util.Dom;

    if(Dom.get('f_input'+o.id).value!=''){
	Dom.get('f_input'+o.id).value='';

	o.myreload();
    }
}

function changeRecordsperPage(e,oe,value){
    
    var Dom = YAHOO.util.Dom;

    
    this.records=Number(this.records);
    if(value=='all'){
	if(this.records>=250)
	    value=500;
	else if (this.records>=100)
	    value=250;
	else if (this.records>=50)
	    value=100;
	else if (this.records>=25)
	    value=50;
	else
	     value=25;
    }




    if(this.recordsperPage!=value){
	Dom.get('paginator_rpp'+this.id).innerHTML=value;
	this.offset=0;
	this.recordsperPage=value;
	this.myreload();
    }
    
}
function changeFilter(e,oe,value){


    var Dom = YAHOO.util.Dom;

    Dom.get('f_field'+this.id).value=value.col;


    Dom.get('filterselector'+this.id).innerHTML=value.text;
    if(Dom.get('f_input'+this.id).value!='')
    	this.myreload();
    
}

function prevpage(e,o){

    o.offset=o.offset-o.recordsperPage;
    
    //    if(o.currentPage>1){
    //	o.currentPage=o.currentPage-1;
    o.myreload();
    //    }
    
}


function toption(e,o){
    var Dom = YAHOO.util.Dom;
    document.getElementById('loadingicon'+o.scope.id).style.visibility='visible'; 
    var datasource=o.scope.getDataSource();
    var newrequest="&sf=0&tview="+o.id;
    var oCallback = {
	success: o.scope.onDataReturnInitializeTable,
	failure: o.scope.onDataReturnInitializeTable,
	scope: o.scope,
	argument: {sorting: {key: o.scope.get("sortedBy").key,dir: o.scope.get("sortedBy").dir}
	}
    }
    if(this.className=='selected')
	this.className='';
    else
	this.className='selected'
	    
    datasource.sendRequest(newrequest,oCallback);    
}

function showdates(e,o){
    var Dom = YAHOO.util.Dom;

    


    
	if(Dom.get('text_dates'+o.id).style.display==''){
	    Dom.get('input_dates'+o.id).style.display='';
	    Dom.get('text_dates'+o.id).style.display='none';
	}
	else{
	    Dom.get('input_dates'+o.id).style.display='none';
	    Dom.get('text_dates'+o.id).style.display='';	
	}

}

function nextpage(e,o){



     o.offset=o.offset+o.recordsperPage;
     

 //o.currentPage=o.currentPage+1;
     
    o.myreload();
}
function showtable(e,o){
    if(this.getAttribute('state')==1){
	this.setAttribute('state',0);
	this.src="art/icons/control_eject_blue_down.png";
	o.getTheadEl().style.display='none';
	o.getTbodyEl().style.display='none';
    }else{
	this.setAttribute('state',1);
	this.src="art/icons/control_eject.png";
	o.getTbodyEl().style.display='';
	o.getTheadEl().style.display='';

    }

	

//     if(document.getElementById('yui-dt'+tableid+'-table').getElementsByTagName("thead")[0].style.display=="none"){
// 	document.getElementById('yui-dt'+tableid+'-table').getElementsByTagName("thead")[0].style.display="";
// 	document.getElementById('yui-dt'+tableid+'-table').getElementsByTagName("tbody")[1].style.display="";
// 	document.getElementById('show'+tableid).src="art/icons/control_eject.png";
// 	document.getElementById('show'+tableid).alt="<?=_('Hide items')?>";

//     }else{
// 	document.getElementById('yui-dt'+tableid+'-table').getElementsByTagName("thead")[0].style.display="none";
// 	document.getElementById('yui-dt'+tableid+'-table').getElementsByTagName("tbody")[1].style.display="none";
// 	document.getElementById('show'+tableid).src="art/icons/control_eject_blue_down.png";
// 	document.getElementById('show'+tableid).alt="<?=_('Show items')?>";
//     }

}
  var dataReturn= function (){

      var Dom = YAHOO.util.Dom;
      Dom.get('loadingicon'+this.id).style.visibility='hidden';
      
  }



      function mydoBeforeCallback (oRequest, oRawResponse, oParsedResponse) {

	  alert('caca');
	  var Dom = YAHOO.util.Dom;

    //   for(x in this)



    var records_perpage=Number(oRawResponse.resultset.records_perpage);
    var records_returned=Number(oRawResponse.resultset.records_returned);
    var offset= Number(oRawResponse.resultset.records_offset);
    var records=Number(oRawResponse.resultset.total_records);
    var id=scope.id;
    var pag=scope.paginatorMenu;
    var filtered=Number(oRawResponse.resultset.filtered);


    
    Dom.get('results'+id).innerHTML=oRawResponse.resultset.records_text;


    if(filtered>0)
	Dom.get('f_input'+id).style.backgroundColor="#b2ff9e";



    
    // alert(records+' x'+records_perpage+' x'+records_returned+' x'+filtered+' d'+offset);
    
    //    Dom.get('table'+id).style.display='';
    Dom.get('paginatormenu'+id).style.visibility='visible';

    
    if(records==0){
	
	
	if(filtered==0){
	    Dom.get('paginatormenu'+id).style.visibility='hidden';
	    Dom.get('loadingicon'+id).style.visibility='hidden';

	    Dom.get('results'+id).style.display='block';
	    Dom.get('paginator'+id).style.display='none';
	    Dom.get('filter'+id).style.display='none';
	    Dom.get('filtercontainer'+id).style.display='none';
	    Dom.get('filtertitle'+id).style.display='none';

	    // Dom.get('table'+id).style.display='none';
	    
	    
	}else{
	Dom.get('paginatormenu'+id).style.visibility='hidden';
	Dom.get('loadingicon'+id).style.visibility='hidden';
	Dom.get('f_input'+id).style.backgroundColor="#ff8080";
	Dom.get('results'+id).style.display='block';
	Dom.get('paginator'+id).style.display='none';
	}
	

	return oParsedResponse;
    }else if(records<=25 && filtered==0){
      Dom.get('paginatormenu'+id).style.visibility='hidden';
      Dom.get('loadingicon'+id).style.visibility='hidden';
      
      Dom.get('results'+id).style.display='block';
      Dom.get('paginator'+id).style.display='none';
      Dom.get('filter'+id).style.display='none';
      Dom.get('filtercontainer'+id).style.display='none';
      Dom.get('filtertitle'+id).style.display='none';


      
    }else{



    if(Dom.get('f_input'+id)!=null){
      if(filtered>0)
	Dom.get('f_input'+id).style.backgroundColor="#fff295";
      else
	Dom.get('f_input'+id).style.backgroundColor="#ffffff";
    }


    scope.recordsperPage=records_perpage;
    scope.offset=offset;
    scope.records=records;


    if(records<=25){
      
      pag.getItem(0).cfg.setProperty("className", 'noshow');
      pag.getItem(1).cfg.setProperty("className", 'noshow');
      pag.getItem(2).cfg.setProperty("className", 'noshow');
      pag.getItem(3).cfg.setProperty("className", 'noshow');
      pag.getItem(4).cfg.setProperty("className", 'noshow');
      pag.getItem(5).cfg.setProperty("className", '');
	
 }else if(records<=50){
	pag.getItem(0).cfg.setProperty("className", '');
	pag.getItem(1).cfg.setProperty("className", 'noshow');
	pag.getItem(2).cfg.setProperty("className", 'noshow');
	  pag.getItem(3).cfg.setProperty("className", 'noshow');
	  pag.getItem(4).cfg.setProperty("className", 'noshow');
	  pag.getItem(5).cfg.setProperty("className", '');
	  
    }else if(records>50 && records<=100){
	  pag.getItem(0).cfg.setProperty("className", '');
	  pag.getItem(1).cfg.setProperty("className", '');
	  pag.getItem(2).cfg.setProperty("className", 'noshow');
	  pag.getItem(3).cfg.setProperty("className", 'noshow');
	  pag.getItem(4).cfg.setProperty("className", 'noshow');
	  pag.getItem(5).cfg.setProperty("className", '');
      }else if(records>100 && records<=250){
	  pag.getItem(0).cfg.setProperty("className", '');
	  pag.getItem(1).cfg.setProperty("className", '');
	  pag.getItem(2).cfg.setProperty("className", '');
	  pag.getItem(3).cfg.setProperty("className", 'noshow');
	  pag.getItem(4).cfg.setProperty("className", 'noshow');
	  pag.getItem(5).cfg.setProperty("className", '');

      } else if(records>250 && records<=500){

	  pag.getItem(0).cfg.setProperty("className", '');
	  pag.getItem(1).cfg.setProperty("className", '');
	  pag.getItem(2).cfg.setProperty("className", '');
	  pag.getItem(3).cfg.setProperty("className", '');
	  pag.getItem(4).cfg.setProperty("className", 'noshow');
	  pag.getItem(5).cfg.setProperty("className", '');

      }else{
	  pag.getItem(0).cfg.setProperty("className", '');
	  pag.getItem(1).cfg.setProperty("className", '');
	  pag.getItem(2).cfg.setProperty("className", '');
	  pag.getItem(3).cfg.setProperty("className", '');
	  pag.getItem(4).cfg.setProperty("className", '');
	  pag.getItem(5).cfg.setProperty("className", 'noshow');
	  
      }



    if(records<records_perpage){
	Dom.get('results'+id).style.display='block';
	Dom.get('paginator'+id).style.display='none';
	//	Dom.get('paginatormenu'+id).style.display='none';
    }else{

	Dom.get('results'+id).style.display='block';
	Dom.get('paginator'+id).style.display='block';
	
	Dom.get('paginator_rpp'+id).innerHTML=records_perpage;
	Dom.get('pag'+id).innerHTML='<?=_('Showing')?> '+(offset+1)+'-'+(records_returned)+' <?=_('of')?> '+records;

	if(offset==0)
	    Dom.get('paginator_prev'+id).style.visibility='hidden';
	else
	    Dom.get('paginator_prev'+id).style.visibility='visible';
	if( records_returned== records)
	    Dom.get('paginator_next'+id).style.visibility='hidden';
	else
	    Dom.get('paginator_next'+id).style.visibility='visible';

    }

    }
      
    
    
    return oParsedResponse;

    
}




function mydoBeforeCallback_options (oRequest, oRawResponse, oParsedResponse,scope) {
   

}
