

// Patch for width and/or minWidth Column values bug in non-scrolling DataTables
(function(){var B=YAHOO.widget.DataTable,A=YAHOO.util.Dom;B.prototype._setColumnWidth=function(I,D,J){I=this.getColumn(I);if(I){J=J||"hidden";if(!B._bStylesheetFallback){var N;if(!B._elStylesheet){N=document.createElement("style");N.type="text/css";B._elStylesheet=document.getElementsByTagName("head").item(0).appendChild(N)}if(B._elStylesheet){N=B._elStylesheet;var M=".yui-dt-col-"+I.getId();var K=B._oStylesheetRules[M];if(!K){if(N.styleSheet&&N.styleSheet.addRule){N.styleSheet.addRule(M,"overflow:"+J);N.styleSheet.addRule(M,"width:"+D);K=N.styleSheet.rules[N.styleSheet.rules.length-1]}else{if(N.sheet&&N.sheet.insertRule){N.sheet.insertRule(M+" {overflow:"+J+";width:"+D+";}",N.sheet.cssRules.length);K=N.sheet.cssRules[N.sheet.cssRules.length-1]}else{B._bStylesheetFallback=true}}B._oStylesheetRules[M]=K}else{K.style.overflow=J;K.style.width=D}return }B._bStylesheetFallback=true}if(B._bStylesheetFallback){if(D=="auto"){D=""}var C=this._elTbody?this._elTbody.rows.length:0;if(!this._aFallbackColResizer[C]){var H,G,F;var L=["var colIdx=oColumn.getKeyIndex();","oColumn.getThEl().firstChild.style.width="];for(H=C-1,G=2;H>=0;--H){L[G++]="this._elTbody.rows[";L[G++]=H;L[G++]="].cells[colIdx].firstChild.style.width=";L[G++]="this._elTbody.rows[";L[G++]=H;L[G++]="].cells[colIdx].style.width="}L[G]="sWidth;";L[G+1]="oColumn.getThEl().firstChild.style.overflow=";for(H=C-1,F=G+2;H>=0;--H){L[F++]="this._elTbody.rows[";L[F++]=H;L[F++]="].cells[colIdx].firstChild.style.overflow=";L[F++]="this._elTbody.rows[";L[F++]=H;L[F++]="].cells[colIdx].style.overflow="}L[F]="sOverflow;";this._aFallbackColResizer[C]=new Function("oColumn","sWidth","sOverflow",L.join(""))}var E=this._aFallbackColResizer[C];if(E){E.call(this,I,D,J);return }}}else{}};B.prototype._syncColWidths=function(){var J=this.get("scrollable");if(this._elTbody.rows.length>0){var M=this._oColumnSet.keys,C=this.getFirstTrEl();if(M&&C&&(C.cells.length===M.length)){var O=false;if(J&&(YAHOO.env.ua.gecko||YAHOO.env.ua.opera)){O=true;if(this.get("width")){this._elTheadContainer.style.width="";this._elTbodyContainer.style.width=""}else{this._elContainer.style.width=""}}var I,L,F=C.cells.length;for(I=0;I<F;I++){L=M[I];if(!L.width){this._setColumnWidth(L,"auto","visible")}}for(I=0;I<F;I++){L=M[I];var H=0;var E="hidden";if(!L.width){var G=L.getThEl();var K=C.cells[I];if(J){var N=(G.offsetWidth>K.offsetWidth)?G.firstChild:K.firstChild;if(G.offsetWidth!==K.offsetWidth||N.offsetWidth<L.minWidth){H=Math.max(0,L.minWidth,N.offsetWidth-(parseInt(A.getStyle(N,"paddingLeft"),10)|0)-(parseInt(A.getStyle(N,"paddingRight"),10)|0))}}else{if(K.offsetWidth<L.minWidth){E=K.offsetWidth?"visible":"hidden";H=Math.max(0,L.minWidth,K.offsetWidth-(parseInt(A.getStyle(K,"paddingLeft"),10)|0)-(parseInt(A.getStyle(K,"paddingRight"),10)|0))}}}else{H=L.width}if(L.hidden){L._nLastWidth=H;this._setColumnWidth(L,"1px","hidden")}else{if(H){this._setColumnWidth(L,H+"px",E)}}}if(O){var D=this.get("width");this._elTheadContainer.style.width=D;this._elTbodyContainer.style.width=D}}}this._syncScrollPadding()}})();
// Patch for initial hidden Columns bug
(function(){var A=YAHOO.util,B=YAHOO.env.ua,E=A.Event,C=A.Dom,D=YAHOO.widget.DataTable;D.prototype._initTheadEls=function(){var X,V,T,Z,I,M;if(!this._elThead){Z=this._elThead=document.createElement("thead");I=this._elA11yThead=document.createElement("thead");M=[Z,I];E.addListener(Z,"focus",this._onTheadFocus,this);E.addListener(Z,"keydown",this._onTheadKeydown,this);E.addListener(Z,"mouseover",this._onTableMouseover,this);E.addListener(Z,"mouseout",this._onTableMouseout,this);E.addListener(Z,"mousedown",this._onTableMousedown,this);E.addListener(Z,"mouseup",this._onTableMouseup,this);E.addListener(Z,"click",this._onTheadClick,this);E.addListener(Z.parentNode,"dblclick",this._onTableDblclick,this);this._elTheadContainer.firstChild.appendChild(I);this._elTbodyContainer.firstChild.appendChild(Z)}else{Z=this._elThead;I=this._elA11yThead;M=[Z,I];for(X=0;X<M.length;X++){for(V=M[X].rows.length-1;V>-1;V--){E.purgeElement(M[X].rows[V],true);M[X].removeChild(M[X].rows[V])}}}var N,d=this._oColumnSet;var H=d.tree;var L,P;for(T=0;T<M.length;T++){for(X=0;X<H.length;X++){var U=M[T].appendChild(document.createElement("tr"));P=(T===1)?this._sId+"-hdrow"+X+"-a11y":this._sId+"-hdrow"+X;U.id=P;for(V=0;V<H[X].length;V++){N=H[X][V];L=U.appendChild(document.createElement("th"));if(T===0){N._elTh=L}P=(T===1)?this._sId+"-th"+N.getId()+"-a11y":this._sId+"-th"+N.getId();L.id=P;L.yuiCellIndex=V;this._initThEl(L,N,X,V,(T===1))}if(T===0){if(X===0){C.addClass(U,D.CLASS_FIRST)}if(X===(H.length-1)){C.addClass(U,D.CLASS_LAST)}}}if(T===0){var R=d.headers[0];var J=d.headers[d.headers.length-1];for(X=0;X<R.length;X++){C.addClass(C.get(this._sId+"-th"+R[X]),D.CLASS_FIRST)}for(X=0;X<J.length;X++){C.addClass(C.get(this._sId+"-th"+J[X]),D.CLASS_LAST)}var Q=(A.DD)?true:false;var c=false;if(this._oConfigs.draggableColumns){for(X=0;X<this._oColumnSet.tree[0].length;X++){N=this._oColumnSet.tree[0][X];if(Q){L=N.getThEl();C.addClass(L,D.CLASS_DRAGGABLE);var O=D._initColumnDragTargetEl();N._dd=new YAHOO.widget.ColumnDD(this,N,L,O)}else{c=true}}}for(X=0;X<this._oColumnSet.keys.length;X++){N=this._oColumnSet.keys[X];if(N.resizeable){if(Q){L=N.getThEl();C.addClass(L,D.CLASS_RESIZEABLE);var G=L.firstChild;var F=G.appendChild(document.createElement("div"));F.id=this._sId+"-colresizer"+N.getId();N._elResizer=F;C.addClass(F,D.CLASS_RESIZER);var e=D._initColumnResizerProxyEl();N._ddResizer=new YAHOO.util.ColumnResizer(this,N,L,F.id,e);var W=function(f){E.stopPropagation(f)};E.addListener(F,"click",W)}else{c=true}}}if(c){}}else{}}for(var a=0,Y=this._oColumnSet.keys.length;a<Y;a++){if(this._oColumnSet.keys[a].hidden){var b=this._oColumnSet.keys[a];var S=b.getThEl();b._nLastWidth=S.offsetWidth-(parseInt(C.getStyle(S,"paddingLeft"),10)|0)-(parseInt(C.getStyle(S,"paddingRight"),10)|0);this._setColumnWidth(b.getKeyIndex(),"1px")}}if(B.webkit&&B.webkit<420){var K=this;setTimeout(function(){K._elThead.style.display=""},0);this._elThead.style.display="none"}}})();




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



      function mydoBeforeCallback (oRequest, oRawResponse, oParsedResponse,scope) {


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
