//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW


function show_filter(e,table_id){

Dom.get('clean_table_filter_show'+table_id).style.display='none';
Dom.get('clean_table_filter'+table_id).style.display='';
Dom.get('f_input'+table_id).focus()
}

function hide_filter(e,table_id){
Dom.get('clean_table_filter_show'+table_id).style.display='';
Dom.get('clean_table_filter'+table_id).style.display='none';
remove_filter(table_id)
}

var remove_filter= function (tableid){
    var Dom   = YAHOO.util.Dom;
    Dom.get('f_input'+tableid).value='';
    var table=tables['table'+tableid]

    var datasource=tables['dataSource'+tableid];
    table.filter.value=Dom.get('f_input'+tableid).value;
    var request='&f_field=' +table.filter.key + '&f_value=' + table.filter.value;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}




var myRowsPerPageDropdown = function(){return true};
var mydoBeforeSortColumn = function(){return true};
var mydoBeforePaginatorChange = function(e){

return true

};


var mydoBeforeLoadData = function(oRequest, oResponse, oPayload) {
    //alert(oResponse.meta.RecordOffset)
  // alert(oResponse.meta.rowsPerPage)
  
 // alert("a")
  
    if(oPayload!=undefined){
        oPayload.pagination = {
            rowsPerPage: parseInt(oResponse.meta.rowsPerPage)||5,
            recordOffset: parseInt(oResponse.meta.RecordOffset)||0
        };
       }
        return true;
    };




var myhandleDataReturnPayload= function(oRequest, oResponse, oPayload) {

//alert("x")

    oPayload = oPayload ||  {
	totalRecords:0,
	pagination:{
	alwaysVisible:false
	 //   ,rowsPerPage:3
	  //  ,rowsPerPage:parseInt(oResponse.meta.rowsPerPage),
	   // recordOffset: 12
	},
	sortedBy:
	{key:oResponse.meta.sort_key,
	 dir:oResponse.meta.sort_dir},
	SelectedRows:null,
	SelectedCells:null};

    oPayload.filter_msg=oResponse.meta.filter_msg;
   //oPayload.pagination.alwaysVisible=false;
  // oPayload.pagination.setState({alwaysVisible:false});



   //alert(oResponse.meta.RecordOffset)
   
  // alert(oResponse.meta.rtext+' '+oResponse.meta.tableid)
   
    if(oResponse.meta.rtext != undefined){
     YAHOO.util.Dom.get('rtext'+oResponse.meta.tableid).innerHTML=oResponse.meta.rtext;
    
   // alert(YAHOO.util.Dom.get('rtext'+oResponse.meta.tableid).innerHTML);
    }
    
    if(oResponse.meta.rtext_rpp != undefined){

	YAHOO.util.Dom.get('rtext_rpp'+oResponse.meta.tableid).innerHTML=oResponse.meta.rtext_rpp;
    }else{
    alert("error no rtext_rpp return properly table"+oResponse.meta.tableid)
    }

    YAHOO.util.Dom.get('filter_msg'+oResponse.meta.tableid).innerHTML=oPayload.filter_msg

//alert(oResponse.meta.rowsPerPage+' '+oResponse.meta.totalRecords)

    oPayload.totalRecords = parseInt(oResponse.meta.totalRecords);
        oPayload.rowsPerPage = parseInt(oResponse.meta.rowsPerPage);

    if(oPayload.totalRecords<=oPayload.rowsPerPage){

   // YAHOO.util.Dom.setStyle('paginator'+oResponse.meta.tableid,'color','red');
// alert(oResponse.meta.rowsPerPage+' '+oResponse.meta.totalRecords)
    
    }
    if(oPayload.totalRecords==0){
	    var table=YAHOO.util.Dom.get('table'+oResponse.meta.tableid).getElementsByTagName("table")[0];
	    table.tHead.style.display='none';
	    table.tBodies[0].getElementsByTagName("tr")[0].getElementsByTagName("td")[0].innerHTML='';
	    Dom.get(table).style.display='none';
	    if(YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid)!=null){
	        YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid).style.visibility='hidden';
	    }
	    
    }else{
  //  Dom.setStyle('paginator'+oResponse.meta.tableid,'display','none')
 //   alert(Dom.get('paginator'+oResponse.meta.tableid).innerHTML)
 
    
	    var table=YAHOO.util.Dom.get('table'+oResponse.meta.tableid).getElementsByTagName("table")[0];
	    table.tHead.style.display='';
	     Dom.get(table).style.display='';
	    if(YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid)!=null)
	        YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid).style.visibility='visible';
	 
	        
       
    }
  
  
    return oPayload;
};






var myRequestBuilder_page_thumbnails = function(oState, oSelf) {
    // Get states or use defaults

    oState = oState || {pagination:null, sortedBy:null};

    var sort = (oState.sortedBy) ? oState.sortedBy.key : "myDefaultColumnKey";

  //  var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "" : "desc";
var dir=oState.sortedBy.dir;

   var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
    var results = (oState.pagination) ? oState.pagination.rowsPerPage : 5;

    // Build custom request
    var request= "&o=" + sort +
    "&od=" + dir +
    "&sf=" + startIndex +
    "&nr=" + results;
//alert(oState.sortedBy.dir)
get_page_thumbnails(oSelf.table_id,request)

    return request;
};

var myRequestBuilder_thumbnails = function(oState, oSelf) {
    // Get states or use defaults

    oState = oState || {pagination:null, sortedBy:null};

    var sort = (oState.sortedBy) ? oState.sortedBy.key : "myDefaultColumnKey";

  //  var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "" : "desc";
var dir=oState.sortedBy.dir;

   var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
    var results = (oState.pagination) ? oState.pagination.rowsPerPage : 5;

    // Build custom request
    var request= "&o=" + sort +
    "&od=" + dir +
    "&sf=" + startIndex +
    "&nr=" + results;
//alert(oState.sortedBy.dir)
get_thumbnails(oSelf.table_id,request)

    return request;
};


var myRequestBuilder = function(oState, oSelf) {
    // Get states or use defaults

    oState = oState || {pagination:null, sortedBy:null};

    var sort = (oState.sortedBy) ? oState.sortedBy.key : "myDefaultColumnKey";

  //  var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "" : "desc";
var dir=oState.sortedBy.dir;

   var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
    var results = (oState.pagination) ? oState.pagination.rowsPerPage : 5;

    // Build custom request
    var request= "&o=" + sort +
    "&od=" + dir +
    "&sf=" + startIndex +
    "&nr=" + results;
//alert(oState.sortedBy.dir)
get_thumbnails(oSelf.table_id,request)

    return request;
};


var myRequestBuilderwithTotals = function(oState, oSelf) {
    // Get states or use defaults

    oState = oState || {pagination:null, sortedBy:null};

    var sort = (oState.sortedBy) ? oState.sortedBy.key : "myDefaultColumnKey";

//    var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "" : "desc";
var dir=oState.sortedBy.dir;

   
   var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
    var results = (oState.pagination) ? oState.pagination.rowsPerPage : 5;

    // Build custom request
    var request= "&o=" + sort +
    "&od=" + dir +
    "&sf=" + startIndex +
    "&nr=" + results;
get_thumbnails(oSelf.table_id,request)
    return request;
};




var mygetTerms =function (query) {




    if(this.table_id==undefined)
	var table_id=0;
    else
	var table_id=this.table_id;

    var Dom   = YAHOO.util.Dom;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

    table.filter.value=Dom.get('f_input'+table_id).value;
    var request='&tableid='+table_id+'&sf=0&f_field=' +table.filter.key + '&f_value=' + table.filter.value;
 
  datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
};

var change_filter=function (key,label,tableid){
    var Dom   = YAHOO.util.Dom;
    var table=tables['table'+tableid];
    if(table.filter.value!=''){
	var datasource=tables['dataSource'+tableid];
	table.filter.value=Dom.get('f_input'+tableid).value;
	var request='&f_field=' +table.filter.key+'&f_value=';
	datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
    }
    table.filter.value='';
    table.filter.key=key;
    Dom.get('f_input'+tableid).value='';
    Dom.get('filter_name'+tableid).innerHTML=label;
}

var change_rpp=function (rpp,tableid){
    var Dom   = YAHOO.util.Dom;
    var table=tables['table'+tableid];
  
    table.get('paginator').setRowsPerPage(rpp)

}


var change_rpp_with_totals=function (rpp,tableid){
    var Dom   = YAHOO.util.Dom;
     var table=tables['table'+tableid];

    table.get('paginator').setRowsPerPage(rpp+1)

}


function change_table_type(e,data){

table_id=data.table_id;
parent=data.parent;
if(Dom.hasClass(this, 'selected'))
 return;
 var elements=Dom.getElementsByClassName('selected', 'span', 'table_type');
 Dom.removeClass(elements, 'selected');
 Dom.addClass(this, 'selected');

 if(this.id=='table_type_list'){
 tipo='list';
 Dom.get('thumbnails'+table_id).style.display='none'
  Dom.get('table'+table_id).style.display=''
Dom.get('list_options'+table_id).style.display=''
 
 }else{
 tipo='thumbnails';
  Dom.get('thumbnails'+table_id).style.display=''
 Dom.get('table'+table_id).style.display='none'
Dom.get('list_options'+table_id).style.display='none'
 
 }
 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys='+parent+'-table_type&value='+escape(tipo),{});
}


function myrenderEvent(){


ostate=this.getState();
paginator=ostate.pagination

if(paginator.totalRecords<=paginator.rowsPerPage){
Dom.setStyle('paginator'+this.table_id,'display','none')
}
}



function get_thumbnails(table_id,extra_arguments) {



if(extra_arguments==undefined)
extra_arguments='';
if(Dom.get('thumbnails'+table_id)==undefined)
return;

table=tables['table'+table_id];

    if(table.request==undefined)
    return;

YAHOO.util.Connect.asyncRequest('POST',table.request+extra_arguments , {success:function(o) {
//alert(o.responseText)
        var r =  YAHOO.lang.JSON.parse(o.responseText);
        if (r.resultset.state==200) {
            var container=Dom.get('thumbnails'+table_id);
            container.innerHTML='';
            var counter=0;
            for (x in r.resultset.data) {
                if (r.resultset.data[x].item_type=='item') {
                
             
					
					var table = new YAHOO.util.Element(document.createElement('table'));
					
					Dom.addClass(table,'item_container');
					var tr = new YAHOO.util.Element(document.createElement('tr'));
					var td = new YAHOO.util.Element(document.createElement('td'));
					Dom.addClass(td,'image_container');
					var img = new YAHOO.util.Element(document.createElement('img'));
                    img.set('src', r.resultset.data[x].image);
                    img.set('alt', '');
					
			img.appendTo(td);
					
					td.appendTo(tr);
					tr.appendTo(table);
					
					var tr = new YAHOO.util.Element(document.createElement('tr'));
					var td = new YAHOO.util.Element(document.createElement('td'));
										Dom.addClass(td,'item_caption');

					
					td.appendTo(tr);
					 td.set('innerHTML', r.resultset.data[x].code);
					tr.appendTo(table);
					
					table.appendTo(container);

					
                  
                    counter++
                }
					
            }
            var div = new YAHOO.util.Element(document.createElement('div'));
					Dom.setStyle(div,'clear','both')
										div.appendTo(container);


        }

    }

                                                              });
}



function get_page_thumbnails(table_id,extra_arguments) {



if(extra_arguments==undefined)
extra_arguments='';
if(Dom.get('thumbnails'+table_id)==undefined)
return;

table=tables['table'+table_id];

    if(table.request==undefined)
    return;
//    parent=data.parent;
  //  tipo=data.tipo;
    
   // if (data.table_id!= undefined) {
   // var table_id=data.table_id;
   // }else{
   // var table_id=0;
   // }
    
   // var request='ar_assets.php?tipo='+tipo+'&parent='+parent;
   // if (data.parent_key!= undefined) {
    //    request+='&parent_key='+data.parent_key
     //        }
//     alert(table.request+extra_arguments)
YAHOO.util.Connect.asyncRequest('POST',table.request+extra_arguments , {success:function(o) {
//alert(o.responseText)
        var r =  YAHOO.lang.JSON.parse(o.responseText);
        if (r.resultset.state==200) {
            var container=Dom.get('thumbnails'+table_id);
            container.innerHTML='';
            var counter=0;
            for (x in r.resultset.data) {
                if (r.resultset.data[x].item_type=='item') {
                
               
                   //var div1 = new YAHOO.util.Element(document.createElement('div'));
                    //Dom.addClass(div1,'image_container');
                    //var img = new YAHOO.util.Element(document.createElement('img'));
                    //img.set('src', r.resultset.data[x].image);
                    //img.set('alt', r.resultset.data[x].image);
                    //var div = new YAHOO.util.Element(document.createElement('div'));
                    //Dom.addClass(div,'item_container');
                    //img.appendTo(div1);
					//	div1.appendTo(div);

                    //var p = new YAHOO.util.Element(document.createElement('p'));
                    //p.set('innerHTML', r.resultset.data[x].code);
                    // Dom.addClass(p,'item_caption');
					//p.appendTo(div);
					  //div.appendTo(container);
					
					var table = new YAHOO.util.Element(document.createElement('table'));
					
					Dom.addClass(table,'item_container');
					var tr = new YAHOO.util.Element(document.createElement('tr'));
					var td = new YAHOO.util.Element(document.createElement('td'));
					Dom.addClass(td,'image_container');
				
					
			//img.appendTo(td);
					Dom.setStyle(td,'background-image',r.resultset.data[x].image);
					
										Dom.setStyle(td,'background-image',"url('"+r.resultset.data[x].image+"')");
										Dom.setStyle(td,'background-size',"100% auto");
										Dom.setStyle(td,'background-repeat',"no-repeat");
										Dom.setStyle(td,'height',"126px");

					td.appendTo(tr);
					tr.appendTo(table);
					
					var tr = new YAHOO.util.Element(document.createElement('tr'));
					var td = new YAHOO.util.Element(document.createElement('td'));
										Dom.addClass(td,'item_caption');

					
					td.appendTo(tr);
					 td.set('innerHTML', r.resultset.data[x].code);
					tr.appendTo(table);
					
					table.appendTo(container);

					
                  
                    counter++
                }
					
            }
            var div = new YAHOO.util.Element(document.createElement('div'));
					Dom.setStyle(div,'clear','both')
										div.appendTo(container);


        }

    }

                                                              });
}


function change_period(e,data){


    tipo=this.id;
    Dom.removeClass(Dom.getElementsByClassName('table_option','button' , data.subject+'_period_options'),'selected')
    Dom.addClass(tipo,"selected");	
    
    var table=tables['table'+data.table_id];
    var datasource=tables['dataSource'+data.table_id];
    var request='&period=' + this.getAttribute('period');
  // alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}

function change_avg(e,data){
    tipo=this.id;
  Dom.removeClass(Dom.getElementsByClassName('table_option','button' , data.subject+'_avg_options'),'selected')
    Dom.addClass(tipo,"selected");	
    var table=tables['table'+data.table_id];
    var datasource=tables['dataSource'+data.table_id];
    var request='&avg=' + this.getAttribute('avg');
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}



