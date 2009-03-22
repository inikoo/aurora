<?include_once('../common.php');?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var department_id=<?=$_SESSION['state']['department']['id']?>;
editing='description';

function new_dept_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_code").value!='')
	Dom.get("add_new_dept").style.display='';
    else
	Dom.get("add_new_dept").style.display='';


}
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();

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

    // Dom.get(editing+'_save_div').style.display='';
    errors_div=Dom.get(editing+'_errors');
    // alert(errors);
    errors_div.innerHTML='';


    for (x in this_errors)
	{
	    // alert(errors[x]);
	    Dom.get(editing+'_save').style.display='none';
	    errors_div.innerHTML=errors_div.innerHTML+' '+this_errors[x];
	}




}


function edit_dept_changed(o){
    var ovalue=o.getAttribute('ovalue');
    var name=o.name;
    if(ovalue!=o.value){
	if(name=='code'){
	    if(o.value==''){
		description_errors.code="<?=_("The department code can not be empty")?>";
	    }else if(o.value.lenght>16){
		description_errors.code="<?=_("The product code can not have more than 16 characters")?>";
	    }else
		delete description_errors.code;
	}
	if(name=='name'){
	    if(o.value==''){
		description_errors.name="<?=_("The department name  can not be empty")?>";
	    }else if(o.value.lenght>255){
		description_errors.name="<?=_("The product code can not have more than 255  characters")?>";
	    }else
		delete description_errors.name;
	}
	


	if(o.getAttribute('changed')==0){
	    description_num_changed++;
	    o.setAttribute('changed',1);
	}
    }else{
	if(o.getAttribute('changed')==1){
	    description_num_changed--;
	    o.setAttribute('changed',0);
	}
    }
    update_form();
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
		    
		    var request='ar_assets.php?tipo=edit_department&key=' + key+ '&newvalue=' + 
			encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
			'&id='+department_id;

		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				//alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				    var element=Dom.get(r.key);
				    element.getAttribute('ovalue',r.newvalue);
				    element.value=r.newvalue;
				    element.setAttribute('changed',0);
				    description_num_changed--;
				 
				}else{
				    Dom.get('description_errors').innerHTML='<span class="error">'+r.msg+'</span>';
				    
				}
				update_form();	
			    }
			    
			});
		}
	    }
	
    }

}


function new_family_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_name").value!=''){
	Dom.get("add_new_family").style.display='';
    }else
	Dom.get("add_new_family").style.display='none';
}

function save_new_family(){

    var msg_div='add_family_messages';

    var code=Dom.get('new_code').value;
    var name=Dom.get('new_name').value;
    var description=Dom.get('new_description').innerHTML;
    var request='ar_assets.php?tipo=new_family&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name)+'&description='+encodeURIComponent(name);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
		    Dom.get(msg_div).innerHTML='';
		}else
		    Dom.get(msg_div).innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	    });

}








YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var onCellClick = function(oArgs) {
		var target = oArgs.target,
		column = this.getColumn(target),
		record = this.getRecord(target);
		switch (column.action) {
		case 'delete':
		    if (confirm('Are you sure?')) {

			YAHOO.util.Connect.asyncRequest(
                    'GET',
                    'ar_assets?tipo=delete_family' + myBuildUrl(this,record),
                    {
                        success: function (o) {

                            if (o.responseText == 'Ok') {
                                this.deleteRow(target);
                            } else {
                                alert(o.responseText);
                            }
                        },
			    failure: function (o) {
                            alert(o.statusText);
                        },
			    scope:this
			    }
							);
		    }
		    break;
		default:
		    this.onEventShowCellEditor(oArgs);
		    break;
		}
	    };
	    



	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				    ,{key:"code", label:"<?=_('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?=_('Name')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"delete", label:"",width:100,className:"aleft",action:"delete"}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=edit_families&parent=department");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "code",
			 "name",
			 'delete','delete_type','id'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?=$_SESSION['tables']['departments_list'][2]?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['tables']['departments_list'][0]?>",
									 dir: "<?=$_SESSION['tables']['departments_list'][1]?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    this.table0.subscribe('cellClickEvent',onCellClick);

	    
	    this.table0.view='<?=$_SESSION['state']['department']['view']?>';

		





	};
    });

function init(){
 var Event = YAHOO.util.Event;
    var Dom   = YAHOO.util.Dom;
    
    function mygetTerms(query) {multireload();};
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    


    


}

YAHOO.util.Event.onDOMReady(init);
