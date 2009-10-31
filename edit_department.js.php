<?php include_once('common.php');?>
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var department_id=<?php echo$_SESSION['state']['department']['id']?>;
var editing='<?php echo $_SESSION['state']['department']['edit']?>';
var can_add_family=false;


function change_block(e){
     if(editing!=this.id){
     
     if(this.id=='pictures' || this.id=='discounts'){
	    Dom.get('info_name').style.display='';
	}else
	    Dom.get('info_name').style.display='none';
     
     
	 Dom.get('d_families').style.display='none';
	 Dom.get('d_description').style.display='none';
	 Dom.get('d_discounts').style.display='none';
	 Dom.get('d_pictures').style.display='none';
	 Dom.get('d_web').style.display='none';
	 Dom.get('d_'+this.id).style.display='';
	 Dom.removeClass(editing,'selected');
	 Dom.addClass(this, 'selected');
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-edit&value='+this.id );
	editing=this.id;
    }
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

		    newvalue=element.value;
		    oldValue=element.getAttribute('ovalue');
		    
		    var request='ar_edit_assets.php?tipo=edit_department&key=' + key+ '&newvalue=' + 
			encodeURIComponent(newvalue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
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
    if(Dom.get("new_code").value!='' && Dom.get("new_name").value!=''  && Dom.get("new_special_char").value!='' ){
       	Dom.removeClass('save_new_family','disabled');
	can_add_family=true;
    }else{
	Dom.removeClass('save_new_family','disabled');
	can_add_family=false;
    }
}

function save_new_family(){

    var msg_div='new_family_messages';

    var code=Dom.get('new_code').value;
    var name=Dom.get('new_name').value;
    var special_char=Dom.get('new_special_char').value;
    var description=Dom.get('new_description').innerHTML;
    var request='ar_edit_assets.php?tipo=new_family&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name)+'&description='+encodeURIComponent(name)+'&special_char='+encodeURIComponent(special_char);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
		    Dom.get(msg_div).innerHTML='';

		    Dom.get('new_code').value='';
		    Dom.get('new_name').value='';
		    Dom.get('special_char').value='';
		    hide_add_family_dialog();



		}else
		    Dom.get(msg_div).innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	    });

}
function cancel_add_family(){
    Dom.get('new_code').value='';
    Dom.get('new_name').value='';
    
    hide_add_family_dialog(); 
}

function hide_add_family_dialog(){
    Dom.get('new_family_dialog').style.display='none';
    Dom.get('add_family').style.display='';
    Dom.get('save_new_family').style.display='none';
    Dom.get('cancel_add_family').style.display='none';
}

function show_add_family_dialog(){
    
    Dom.get('new_family_dialog').style.display='';
    Dom.get('add_family').style.display='none';

    Dom.get('save_new_family').style.display='';

    Dom.addClass('save_new_family','disabled');
    Dom.get('cancel_add_family').style.display='';
    Dom.get('new_code').focus();

}






YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				    ,{key:"edit", label:"<?php echo _('Key')?>", width:30,action:"none"}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family'}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family'}
				    ,{key:"delete", label:"",width:100,className:"aleft",action:"delete",object:'family'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_families&parent=department");
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
			 'delete','delete_type','id','edit'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['tables']['departments_list'][2]?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['tables']['departments_list'][0]?>",
									 dir: "<?php echo$_SESSION['tables']['departments_list'][1]?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;





	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);
	    
	    this.table0.view='<?php echo$_SESSION['state']['department']['view']?>';

		





	};
    });




function init(){
 
    function mygetTerms(query) {multireload();};
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    
    var ids = ["description","families","discounts","pictures","web"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    YAHOO.util.Event.addListener('add_family', "click", show_add_family_dialog);
    YAHOO.util.Event.addListener('save_new_family', "click",save_new_family);
    YAHOO.util.Event.addListener('cancel_add_family', "click", cancel_add_family);


}

YAHOO.util.Event.onDOMReady(init);
