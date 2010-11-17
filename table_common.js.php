<?php 

include_once('set_locales.php');
?>
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW


function show_filter(e,table_id){

Dom.get('clean_table_filter_show'+table_id).style.display='none';
Dom.get('clean_table_filter'+table_id).style.display='';
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
var mydoBeforePaginatorChange = function(e){return true};


var mydoBeforeLoadData = function(oRequest, oResponse, oPayload) {
    // alert(oResponse.meta.RecordOffset)
     if(oPayload!=undefined){
        oPayload.pagination = {
            rowsPerPage: parseInt(oResponse.meta.rowsPerPage)||5,
            recordOffset: parseInt(oResponse.meta.RecordOffset)||0
        };
       }
        return true;
    };




var myhandleDataReturnPayload= function(oRequest, oResponse, oPayload) {
    oPayload = oPayload ||  {
	totalRecords:0,
	pagination:{
	alwaysVisible:false
	    //rowsPerPage:3
	    //rowsPerPage:parseInt(oResponse.meta.rowsPerPage),
	    //recordOffset: 12
	},
	sortedBy:
	{key:oResponse.meta.sort_key,
	 dir:oResponse.meta.sort_dir},
	SelectedRows:null,
	SelectedCells:null} ;

    oPayload.filter_msg=oResponse.meta.filter_msg;
  // oPayload.pagination = {  rowsPerPage:parseInt(oResponse.meta.rowsPerPage),recordOffset:0 }
   
   //alert(oResponse.meta.RecordOffset)
   
  // alert(oResponse.meta.rtext+' '+oResponse.meta.tableid)
   
    if(oResponse.meta.rtext != undefined)

      YAHOO.util.Dom.get('rtext'+oResponse.meta.tableid).innerHTML=oResponse.meta.rtext;
    
    
    if(oResponse.meta.rtext_rpp != undefined){

	YAHOO.util.Dom.get('rtext_rpp'+oResponse.meta.tableid).innerHTML=oResponse.meta.rtext_rpp;
    }

    YAHOO.util.Dom.get('filter_msg'+oResponse.meta.tableid).innerHTML=oPayload.filter_msg

    oPayload.totalRecords = parseInt(oResponse.meta.totalRecords);
    
    if(oPayload.totalRecords==0){
	    var table=YAHOO.util.Dom.get('table'+oResponse.meta.tableid).getElementsByTagName("table")[0];
	    table.tHead.style.display='none';
	    table.tBodies[0].getElementsByTagName("tr")[0].getElementsByTagName("td")[0].innerHTML='';
	    Dom.get(table).style.display='none';
	    if(YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid)!=null){
	        YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid).style.visibility='hidden';
	    }
	    
    }else{
	    var table=YAHOO.util.Dom.get('table'+oResponse.meta.tableid).getElementsByTagName("table")[0];
	    table.tHead.style.display='';
	     Dom.get(table).style.display='';
	    if(YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid)!=null)
	        YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid).style.visibility='visible';
       
      // alert(oPayload.totalRecords+' xx '+oResponse.meta.rowsPerPage+' d '+oPayload.totalRecords<=oResponse.meta.rowsPerPage)
       //if(oPayload.totalRecords<=oResponse.meta.rowsPerPage){
       
       //Dom.get('paginator'+oResponse.meta.tableid).style.display='none';
       
       //alert("caca");
       //}
       
      //Dom.get('yui-rec10').style.display='none';
       
    }
    //var data={code:'<?php echo _('Totals')?>'};
    //tables.table0.addRow(data);
    return oPayload;
};








var myRequestBuilder = function(oState, oSelf) {
    // Get states or use defaults

    
    oState = oState || {pagination:null, sortedBy:null};

    var sort = (oState.sortedBy) ? oState.sortedBy.key : "myDefaultColumnKey";

    var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "" : "desc";

   var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
    var results = (oState.pagination) ? oState.pagination.rowsPerPage : 5;

    // Build custom request
    var request= "&o=" + sort +
    "&od=" + dir +
    "&sf=" + startIndex +
    "&nr=" + results;


    return request;
};


var myRequestBuilderwithTotals = function(oState, oSelf) {
    // Get states or use defaults

    
    oState = oState || {pagination:null, sortedBy:null};

    var sort = (oState.sortedBy) ? oState.sortedBy.key : "myDefaultColumnKey";

    var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "" : "desc";

   var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
    var results = (oState.pagination) ? oState.pagination.rowsPerPage : 5;

    // Build custom request
    var request= "&o=" + sort +
    "&od=" + dir +
    "&sf=" + startIndex +
    "&nr=" + results;
//alert(request)

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


function get_thumbnails(data) {
    parent=data.parent;
    tipo=data.tipo;
    var table_id=0;
    var request='ar_assets.php?tipo='+tipo+'&parent='+parent;
    if (data.parent_key!= undefined) {
        request+='&parent_key='+data.parent_key
             }
YAHOO.util.Connect.asyncRequest('POST',request , {success:function(o) {
//alert(o.responseText)
        var r =  YAHOO.lang.JSON.parse(o.responseText);
        if (r.resultset.state==200) {
            var container=Dom.get('thumbnails'+table_id);
            for (x in r.resultset.data) {
                if (r.resultset.data[x].type=='item') {
                    var img = new YAHOO.util.Element(document.createElement('img'));
                    img.set('src', r.resultset.data[x].image);
                    img.set('alt', r.resultset.data[x].image);
                    var internal_span = new YAHOO.util.Element(document.createElement('span'));
                    internal_span.set('innerHTML', r.resultset.data[x].code);

                    var div = new YAHOO.util.Element(document.createElement('div'));
                    Dom.addClass(div,'product_container');
                    img.appendTo(div);
                    internal_span.appendTo(div);



                    div.appendTo(container);
                }

            }

        }

    }

                                                              });
}
