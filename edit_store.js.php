<?php
include_once('common.php');
?>

var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();
var id=<?php echo$_SESSION['state']['store']['id']?>;
var editing='description';


var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


function update_form(){
    if(editing=='description'){
	this_errors=description_errors;
	this_num_changed=description_num_changed

    }
  
    if(this_num_changed>0){
	Dom.get(editing+'_save').style.display='';
	Dom.get(editing+'_reset').style.display='';

    }else{
	Dom.get(editing+'_save').style.display='none';
	Dom.get(editing+'_reset').style.display='none';

    }
    Dom.get(editing+'_num_changes').innerHTML=this_num_changed;

   
    errors_div=Dom.get(editing+'_errors');
    
    errors_div.innerHTML='';


    for (x in this_errors)
	{
	    // alert(errors[x]);
	    Dom.get(editing+'_save').style.display='none';
	    errors_div.innerHTML=errors_div.innerHTML+' '+this_errors[x];
	}




}


function changed(o){
    var ovalue=o.getAttribute('ovalue');
    var name=o.name;
    if(ovalue!=trim(o.value)){
	if(name=='code'){
	    if(o.value==''){
		description_errors.code="<?php echo _("The department code can not be empty")?>";
	    }else if(o.value.lenght>16){
		description_errors.code="<?php echo _("The product code can not have more than 16 characters")?>";
	    }else
		delete description_errors.code;
	}
	if(name=='name'){
	    if(o.value==''){
		description_errors.name="<?php echo _("The department name  can not be empty")?>";
	    }else if(o.value.lenght>255){
		description_errors.name="<?php echo _("The product code can not have more than 255  characters")?>";
	    }else
		delete description_errors.name;
	}
	


	if(o.getAttribute('changed')==0){
	    update_changes('+');
	    o.setAttribute('changed',1);
	}
    }else{
	if(o.getAttribute('changed')==1){
	    update_changes('-');
	    o.setAttribute('changed',0);
	}
    }
    update_form();
}

function update_changes(changed){
    if(editing=='description'){
	if(changed=='+')
	    description_num_changed++;
	else
	    description_num_changed--;
 
    }	
}


function reset(tipo){

    if(tipo=='description'){
	tag='name';
	Dom.get(tag).value=Dom.get(tag).getAttribute('ovalue');
	Dom.get(tag).setAttribute('changed',0);
	tag='code';
	Dom.get(tag).value=Dom.get(tag).getAttribute('ovalue');
	Dom.get(tag).setAttribute('changed',0);

	description_num_changed=0;
	Dom.get(editing+'_save').style.display='none';
	Dom.get(editing+'_reset').style.display='none';

	Dom.get(editing+'_num_changes').innerHTML=description_num_changed;
	description_warnings= new Object();
	description_errors= new Object();
	
    }
    update_form();
}

function save(tipo){

    if(tipo=='description'){
	var keys=new Array("code","name");
	for (x in keys)
	    {
		 key=keys[x];
		 element=Dom.get(key);
		if(element.getAttribute('changed')==1){

		    newValue=element.value;
		    oldValue=element.getAttribute('ovalue');
		  
		    var request='ar_edit_assets.php?tipo=edit_store&key=' + key+ '&newvalue=' + 
			encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
			'&id='+id;

		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				//								alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				    var element=Dom.get(r.key);
				    element.getAttribute('ovalue',r.newvalue);
				    element.value=r.newvalue;
				    element.setAttribute('changed',0);
				    description_num_changed--;
				 
				}else{
				    //Dom.get('description_errors').innerHTML='<span class="error">'+r.msg+'</span>';
				    description_errors[r.key]=r.msg;
				}
				update_form();	
			    }
			    
			});
		}
	    }
	
    }

}










function new_dept_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_name").value!=''){

	Dom.get("add_new_dept").style.display='';
    }else
	Dom.get("add_new_dept").style.display='none';



}

function save_new_dept(){
    var code=Dom.get('new_code').value;
    var name=Dom.get('new_name').value;
  //   var store_key=0;
	
//     for (var i=0; i<Dom.get("new_dept_form").store_key.length; i++)  {
// 	if (Dom.get("new_dept_form").store_key[i].checked)  {
// 	    store_key = Dom.get("new_dept_form").store_key[i].value;
// 	}
//     } 
    
    var request='ar_edit_assets.php?tipo=new_department&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
		    Dom.get('edit_messages').innerHTML='';
		    
		    
		}else
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	    });

}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				    ,{key:"code", label:"<?php echo _('Code')?>", width:230,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department'}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department' }
				    ,{key:"delete", label:"", width:70,sortable:false,className:"aleft",action:'delete',object:'department'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_departments&parent=store");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id','code','name','delete','delete_type'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['store']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							     key: "<?php echo$_SESSION['state']['store']['table']['order']?>",
							     dir: "<?php echo$_SESSION['state']['store']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
 // Set up editing flow
	    var highlightEditableCell = function(oArgs) {
		var elCell = oArgs.target;
		if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
		    this.highlightCell(elCell);
		}
	    };
	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", this.table0.onEventUnhighlightCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);


		





	};
    });







 function init(){
 var Dom   = YAHOO.util.Dom;





 }

YAHOO.util.Event.onDOMReady(init);

