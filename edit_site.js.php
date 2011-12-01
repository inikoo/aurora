<?php
include_once('common.php');
?>

var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var dialog_upload_header;


var id=<?php echo$_SESSION['state']['site']['id']?>;

var validate_scope_data=
{
    'site':{
	'slogan':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Site_Slogan','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Slogan')?>'}]}
	,'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Site_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Name')?>'}]}
	,'url':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Site_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Name')?>'}]}
	,'ftp':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Site_FTP','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid FTP Credentials')?>'}]}
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Contact Telephone','name':'telephone','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Name')?>'}]}
	,'address':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'address','dbname':'Site Contact Address','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Name')?>'}]}


	,'mals_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Site_Mals_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid URL')?>'}]}
	,'mals_url_multi':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Site_Mals_URL_Multi','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid URL')?>'}]}
	,'mals_id':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Site_Mals_ID','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid ID')?>'}]}

}};

var validate_scope_metadata={
'site':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
//,'billing_data':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}

};






function change_block(e){
    var ids = ["general","layout","style","sections","pages","headers","footers"]; 
	var block_ids = ["d_general","d_layout","d_style","d_sections","d_pages","d_headers","d_footers"]; 
	Dom.setStyle(block_ids,'display','none');
	Dom.setStyle('d_'+this.id,'display','');
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=site-editing&value='+this.id ,{});
}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
		
 var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //     ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=site&tableid=1");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		  
		 rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
		       rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		
		},
		
		
		fields: [
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['product']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['site']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['site']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		       this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);

		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['site']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['history']['f_value']?>'};



   var tableid=6; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				         ,{key:"go", label:"", width:20,action:"none"}
				       ,{key:"code",label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				       ,{key:"store_title",label:"<?php echo _('Header Title')?>", <?php echo($_SESSION['state']['site']['edit_pages']['view']=='page_header'?'':'hidden:true,')?>width:400,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				     	  ,{key:"link_title",label:"<?php echo _('Link Title')?>", <?php echo($_SESSION['state']['site']['edit_pages']['view']=='page_properties'?'':'hidden:true,')?>width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				     	  ,{key:"url",label:"<?php echo _('URL')?>", <?php echo($_SESSION['state']['site']['edit_pages']['view']=='page_properties'?'':'hidden:true,')?>width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				     	  ,{key:"page_title",label:"<?php echo _('Browser Title')?>",<?php echo($_SESSION['state']['site']['edit_pages']['view']=='page_html_head'?'':'hidden:true,')?> width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				     	  ,{key:"page_keywords",label:"<?php echo _('Keywords')?>",<?php echo($_SESSION['state']['site']['edit_pages']['view']=='page_html_head'?'':'hidden:true,')?> width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}

				     
				     ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'page_store'}		         
				       ];
				       
	 
				       
				       
	    //?tipo=customers&tid=0"
	    var request="ar_edit_sites.php?tipo=pages&site_key="+Dom.get('site_key').value+"&parent=site&parent_key="+Dom.get('site_key').value+"&tableid=6";
	    //alert(request)
	        this.dataSource6 = new YAHOO.util.DataSource(request);

//alert("ar_edit_sites.php?tipo=family_page_list&site_key="+Dom.get('site_key').value+"&parent=family&parent_key="+Dom.get('family_key').value+"&tableid=6")
	    this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource6.connXhrMode = "queueRequests";
	    this.dataSource6.responseSchema = {
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
			 "id"
			 ,"go","code","store_title","delete","link_title","url","page_title","page_keywords"

			 ]};

        this.table6 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource6
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['site']['edit_pages']['nr']?> ,containers : 'paginator6', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['site']['edit_pages']['order']?>",
							     dir: "<?php echo $_SESSION['state']['site']['edit_pages']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );
	    
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table6.table_id=tableid;
     this.table6.subscribe("renderEvent", myrenderEvent);


	    this.table6.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table6.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table6.subscribe("cellClickEvent", onCellClick);
		    
	    this.table6.filter={key:'<?php echo $_SESSION['state']['site']['edit_pages']['f_field']?>',value:'<?php echo $_SESSION['state']['site']['edit_pages']['f_value']?>'};


	

  var tableid=2; 
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				         ,{key:"go", label:"", width:20,action:"none"}
				       ,{key:"name",label:"<?php echo _('Name')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				   
				     
				     ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'page_header_store'}		         
				       ];
				       
	 
				       
				       
	    //?tipo=customers&tid=0"
	    var request="ar_edit_sites.php?tipo=page_headers&parent=site&parent_key="+Dom.get('site_key').value+"&tableid=2";
	    //alert(request)
	        this.dataSource2 = new YAHOO.util.DataSource(request);

//alert("ar_edit_sites.php?tipo=family_page_list&site_key="+Dom.get('site_key').value+"&parent=family&parent_key="+Dom.get('family_key').value+"&tableid=2")
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
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
			 "id"
			 ,"go","name","delete"

			 ]};

        this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource2
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['site']['edit_headers']['nr']?> ,containers : 'paginator2', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['site']['edit_headers']['order']?>",
							     dir: "<?php echo $_SESSION['state']['site']['edit_headers']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table2.table_id=tableid;
     this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table2.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table2.subscribe("cellClickEvent", onCellClick);
		    
	    this.table2.filter={key:'<?php echo $_SESSION['state']['site']['edit_headers']['f_field']?>',value:'<?php echo $_SESSION['state']['site']['edit_headers']['f_value']?>'};






};
    });


function show_dialog_upload_header(){

dialog_upload_header.show()

}
function close_upload_header(){
dialog_upload_header.hide();
}


function upload_header(e){
    YAHOO.util.Connect.setForm('upload_header_form', true,true);
    var request='ar_upload_page_content.php?tipo=upload_header';
   var uploadHandler = {
      upload: function(o) {
	   alert(o.responseText)
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){
	     
         window.location.reload()
                
	    }else
		alert(r.msg);
	    
	    

	}
    };

    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);



  };
function init(){
init_search('products_store');

 Event.addListener('show_upload_header', "click", show_dialog_upload_header);
Event.addListener("cancel_upload_header", "click", close_upload_header);
  Event.addListener('upload_header', "click", upload_header);
 dialog_upload_header = new YAHOO.widget.Dialog("dialog_upload_header", {context:["show_upload_header","tr","br"] ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_header.render();




 ids=['page_properties','page_html_head','page_header'];
 YAHOO.util.Event.addListener(ids, "click",change_edit_pages_view,{'table_id':6,'parent':'page'})


    var ids = ["general","layout","style","sections","pages","headers","footers"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
   
	   
	Event.addListener(["Mals","Inikoo"], "click", change_checkout_method);
	Event.addListener(["sidebar","mainpage"], "click", change_registration_method);
   
    var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_site_slogan);
    site_slogan_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Site_Slogan","Site_Slogan_Container", site_slogan_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

    var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_site_name);
    site_slogan_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Site_Name","Site_Name_Container", site_slogan_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;   
	
    var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_site_url);
    site_slogan_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Site_URL","Site_URL_Container", site_slogan_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;  
	
    var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_site_ftp);
    site_slogan_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Site_FTP","Site_FTP_Container", site_slogan_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;  
    
       var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_site_mals_id);
    site_slogan_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Site_Mals_ID","Site_Mals_ID_Container", site_slogan_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;  
    
       var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_site_mals_url);
    site_slogan_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Site_Mals_URL","Site_Mals_URL_Container", site_slogan_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;  
    
       var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_site_mals_url_multi);
    site_slogan_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Site_Mals_URL_Multi","Site_Mals_URL_Multi_Container", site_slogan_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;  
	
	   var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_site_address);
    site_slogan_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("address","address_Container", site_slogan_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;  
    
       var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_site_telephone);
    site_slogan_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("telephone","telephone_Container", site_slogan_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;  
	
	
	YAHOO.util.Event.addListener('save_edit_site', "click", save_edit_site);
    YAHOO.util.Event.addListener('reset_edit_site', "click", reset_edit_site);
    
    
     YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_show6', "click",show_filter,6);
 YAHOO.util.Event.addListener('clean_table_filter_hide6', "click",hide_filter,6);
 
 
 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 
 
  var oACDS6 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS6.queryMatchContains = true;
  oACDS6.table_id=6;
 var oAutoComp6 = new YAHOO.widget.AutoComplete("f_input6","f_container6", oACDS6);
 oAutoComp6.minQueryLength = 0; 
 
    
}

function save_edit_site(){

    save_edit_general_bulk('site');
}

function reset_edit_site(){
    reset_edit_general('site')
}

function validate_site_ftp(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('site','ftp',unescape(query));

 if(original_query==''){
    
     validate_scope_data.site.ftp.validated=true;
     validate_scope('site'); 
 }

}

function validate_site_url(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('site','url',unescape(query));

 if(original_query==''){
    
     validate_scope_data.site.url.validated=true;
     validate_scope('site'); 
 }

}

function validate_site_name(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('site','name',unescape(query));

 if(original_query==''){
    
     validate_scope_data.site.name.validated=true;
     validate_scope('site'); 
 }

}

function validate_site_mals_id(query){
 validate_general('site','mals_id',unescape(query));
}
function validate_site_mals_url(query){
 validate_general('site','mals_url',unescape(query));
}
function validate_site_mals_url_multi(query){
 validate_general('site','mals_url_multi',unescape(query));
}

function validate_site_address(query){
 validate_general('site','address',unescape(query));
}

function validate_site_telephone(query){
 validate_general('site','telephone',unescape(query));
}

function validate_site_slogan(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('site','slogan',unescape(query));

 if(original_query==''){
    
     validate_scope_data.site.slogan.validated=true;
     validate_scope('site'); 
 }

}

function change_registration_method(){
types=Dom.getElementsByClassName('site_registration_method', 'button', 'site_registration_method_buttons')
Dom.removeClass(types,'selected');

Dom.get('site_registration_method').value=this.id;
//alert(Dom.get('site_checkout_method').value);


site_id=Dom.get('site_key').value;
store_key=Dom.get('store_key').value;
var request='ar_edit_sites.php?tipo=edit_registration_method&site_key=' + site_id +'&store_key='+store_key + '&site_registration_method='+Dom.get('site_registration_method').value
	            //alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	            //alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				Dom.addClass(r.new_value,'selected');

            }
			else{
				Dom.addClass(Dom.get('site_registration_method').value,'selected');
			}
   			}
    });
}

function change_checkout_method(){
types=Dom.getElementsByClassName('site_checkout_method', 'button', 'site_checkout_method_buttons')
Dom.removeClass(types,'selected');

Dom.get('site_checkout_method').value=this.id;
//alert(Dom.get('site_checkout_method').value);


site_id=Dom.get('site_key').value;
store_key=Dom.get('store_key').value;
var request='ar_edit_sites.php?tipo=edit_checkout_method&site_key=' + site_id +'&store_key='+store_key + '&site_checkout_method='+Dom.get('site_checkout_method').value
	            //alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	        
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				Dom.addClass(r.new_value,'selected');
				
				if(r.new_value=='Mals'){
				Dom.setStyle('mals_tbody','display','');
				
				}else{
				
				Dom.setStyle('mals_tbody','display','none');
				
				Dom.get('Site_Mals_ID').value=Dom.get('Site_Mals_ID').getAttribute('ovalue');
				Dom.get('Site_Mals_URL').value=Dom.get('Site_Mals_URL').getAttribute('ovalue');
				Dom.get('Site_Mals_URL_Multi').value=Dom.get('Site_Mals_URL_Multi').getAttribute('ovalue');
validate_scope_data.site.mals_id.validated=true;
validate_scope_data.site.mals_id.changed=false;
validate_scope_data.site.mals_url.validated=true;
validate_scope_data.site.mals_url.changed=false;
validate_scope_data.site.mals_url_multi.validated=true;
validate_scope_data.site.mals_url_multi.changed=false;
								validate_scope('site')


				}
				

            }
			else{
				Dom.addClass(Dom.get('site_checkout_method').value,'selected');
			}
   			}
    });


}


YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
    
YAHOO.util.Event.onContentReady("rppmenu6", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu6", {trigger:"rtext_rpp6" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu6", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu6", {trigger:"filter_name6"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });    
    

