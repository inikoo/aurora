<?php 
include_once('common.php');
?>

var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;	

var dialog_map;
var dialog_map_select;


var temp_map = new Array();

var temp_map = [];
for (var i = 0; i<13; i++) temp_map[i] = '0';

function get_record_data(index){
  var ar_file='ar_import_csv.php';
    var request=ar_file+"?tipo=get_external_data&index="+index+"&scope="+Dom.get('scope').value; 
	//alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	
	  success:function(o) {
	//alert(o.responseText)
	  
	//Dom.get('call_table').innerHTML=o.responseText;
		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		  
		  Dom.get('call_table').innerHTML=r.result
		}else{
		    alert(r.msg);
		}
	    }
	});

}


function get_default(index) {

 
 var qstring = window.location.toString();
 arr=qstring.split("?");
 var str=arr[1];

//alert(v);
 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {

                document.getElementById('call_table').innerHTML=req.responseText; 
           }
      }
 };
// alert("ar_import_csv.php?tipo=import_csv&v="+v+"&"+str)
 req.open("GET", "ar_import_csv.php?tipo=import_csv&v="+v+"&"+str);
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null);
}

function getPrev(v,limit) {
 var v;
 var qstring = window.location.toString();
 arr=qstring.split("?");
 var str=arr[1];

	//alert(document.getElementById('ignore_message').innerHTML);

	document.getElementById('ignore_message').innerHTML="";
	var prevArray = new Array();

	for(var l=0; l<limit; l++)
	{
	  prevArray.push(document.getElementById('assign_field_'+l).value);
	}

 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {
		//alert(req.responseText);
                document.getElementById('call_table').innerHTML=req.responseText; 
           }
      }
 };
v = v-1;//alert(v);
 //req.open("GET", "getPage.php?data="+r+"&val="+val); 
 req.open("GET", "ar_import_csv.php?tipo=import_csv&v="+v+"&prevArray="+prevArray+"&"+str);
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null); 
}

function getNext(v,num) {
 var v;
 var qstring = window.location.toString();
 arr=qstring.split("?");
 var str=arr[1];
	//alert(document.getElementById('ignore_message').innerHTML);
document.getElementById('ignore_message').innerHTML="";

 var myArray = new Array();

	for(var k=0; k<num; k++)
	{
	  myArray.push(document.getElementById('assign_field_'+k).value);
	}
 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {
		//alert(req.responseText);
                document.getElementById('call_table').innerHTML=req.responseText; 
           }
      }
 };
	v=v+1;

 req.open("GET", "ar_import_csv.php?tipo=import_csv&v="+v+"&myArray="+myArray+"&"+str);
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null);
}


function next_record(index){
    var ar_file='ar_import_csv.php';
    var request=ar_file+"?tipo=import_csv&v="+index+"&myArray="+myArray+"&"+str; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		   Dom.setStyle(['ignore_record_label','unignore'],'display','');
		   Dom.setStyle(['ignore'],'display','none');
		}else{
		    alert(r.msg);
		}
	    }
	});
}

function ignore_record(index){
    var ar_file='ar_import_csv.php';
    var request=ar_file+'?tipo=ignore_record&index='+index; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		   Dom.setStyle(['ignore_record_label','unignore'],'display','');
		   Dom.setStyle(['ignore'],'display','none');
		}else{
		    alert(r.msg);
		}
	    }
	});
}

function read_record(index){
    var ar_file='ar_import_csv.php';
    var request=ar_file+'?tipo=read_record&index='+index; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		   Dom.setStyle(['ignore_record_label','unignore'],'display','none');
		   Dom.setStyle(['ignore'],'display','');
		}else{
		    alert(r.msg);
		}
	    }
	});
}

function option_changed(key,option_key){
	temp_map[key]=option_key;

	//alert(temp_map);
   var ar_file='ar_import_csv.php';
    var request=ar_file+'?tipo=change_option&key='+key+'&option_key='+option_key; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{});
}

function insert_data(){
window.location.href='insert_csv.php';
}

function new_map(){
dialog_map.show();
Dom.get('map_name').value='';
}

function browse_maps(){
dialog_map_select.show();
//alert('browser maps');
/*
	var ar_file='ar_import_csv.php';
    var request=ar_file+"?tipo=browse_maps&scope="+Dom.get('scope').value+"&scope_key="+Dom.get('scope_key').value;
	alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	
	  success:function(o) {
	//alert(o.responseText)
	  
	//Dom.get('call_table').innerHTML=o.responseText;
		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		  
		  Dom.get('maps').innerHTML=r.map_data
		}else{
		    //alert(r.msg);
		}
	    }
	});
	*/
}

function save_map(){
	alert('save');
	
	var ar_file='ar_import_csv.php';
    var request=ar_file+"?tipo=save_map&scope="+Dom.get('scope').value+"&scope_key="+Dom.get('scope_key').value+"&meta_data="+temp_map+"&name="+Dom.get('map_name').value;
	alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	
	  success:function(o) {
	//alert(o.responseText)
	  
	//Dom.get('call_table').innerHTML=o.responseText;
		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		  
		  //Dom.get('call_table').innerHTML=r.result
		}else{
		    //alert(r.msg);
		}
	    }
	});
    dialog_map.hide();
}

function select_map(oArgs){
	var ar_file='ar_import_csv.php';
	var request=ar_file+"?tipo=change_map&index=0&scope="+Dom.get('scope').value+"&map_key="+tables.table5.getRecord(oArgs.target).getData('code'); 
	alert(request);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
	 success:function(o) {
	//alert(o.responseText)
	  
	//Dom.get('call_table').innerHTML=o.responseText;
		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		 Dom.get('call_table').innerHTML=r.result
		}else{
		    //alert(r.msg);
		}
	    }
	
	
	});
	
/*
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+'d('+tables.table5.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '')+')';
    Dom.get('product_ordered_or').value=product_ordered_or;
	*/
    dialog_map_select.hide();
    hide_filter(true,5)
}

YAHOO.util.Event.addListener(window, "load", function() {

    tables = new function() {


	var store_key=Dom.get('scope_key').value;

	var tableid=5; 
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			
                    {key:"code", label:"<?php echo _('Name')?>",width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Map')?>",width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				];
			       
	    //this.dataSource5 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=department_list&store_key=1&tableid=5&nr=20&sf=0");
		this.dataSource5 = new YAHOO.util.DataSource("ar_import_csv.php?tipo=browse_maps&scope="+Dom.get('scope').value+"&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    
		//alert("ar_import_csv.php?tipo=browse_maps&scope="+Dom.get('scope').value+"&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0")
		this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    	    this.dataSource5.table_id=tableid;

	    this.dataSource5.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code","name"
			 ]};


	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	  
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);





 this.table5.subscribe("rowMouseoverEvent", this.table5.onEventHighlightRow);
       this.table5.subscribe("rowMouseoutEvent", this.table5.onEventUnhighlightRow);
      this.table5.subscribe("rowClickEvent", select_map);
           
           this.table5.table_id=tableid;
           this.table5.subscribe("renderEvent", myrenderEvent);



	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table5.filter={key:'code',value:''};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
// --------------------------------------Department table ends here----------------------------------------------------------


/*

	*/
	};

    });

function init(){

//alert(Dom('scope').value);
//scope_key=Dom('scope_key').value;


Event.addListener('new_map', "click",new_map);
Event.addListener('browse_maps', "click",browse_maps);
Event.addListener('save_map', "click",save_map);

dialog_map = new YAHOO.widget.Dialog("dialog_map", {context:["new_map","tr","tl"] ,visible : false,close:true,underlay: "none",draggable:false});
dialog_map.render();

dialog_map_select = new YAHOO.widget.Dialog("dialog_map_select", {context:["browse_maps","tr","tl"] ,visible : false,close:true,underlay: "none",draggable:false});
dialog_map_select.render();


get_record_data(0);
Event.addListener(['insert_data'], "click",insert_data);


}

YAHOO.util.Event.onDOMReady(init);






