<?php
include_once('common.php');
?>

var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var dialog_upload_header;
var dialog_upload_footer;
var dialog_upload_menu;
var dialog_upload_search;

var dialog_upload_header_files;
var dialog_upload_footer_files;
var dialog_upload_menu_files;
var dialog_upload_search_files;



var id=<?php echo$_SESSION['state']['site']['id']?>;

var scope='favicon';
var scope_key=id;

var validate_scope_data=
{
    'site_properties':{
	'slogan':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Site_Slogan','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Slogan')?>'}]}
	,'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Site_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Name')?>'}]}
	,'url':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Site_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Name')?>'}]}
	
	
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Contact Telephone','name':'telephone','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Name')?>'}]}
	,'address':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'address','dbname':'Site Contact Address','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Name')?>'}]}
},
 'site_checkout':{
	'mals_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Site_Mals_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid URL')?>'}]}
	,'mals_url_multi':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Site_Mals_URL_Multi','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid URL')?>'}]}
	,'mals_id':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Site_Mals_ID','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid ID')?>'}]}
},
 'site_ftp':{
	'ftp_server':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site FTP Server','name':'Site_FTP_Server','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid FTP Server')?>'}]}
	,'ftp_user':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site FTP User','name':'Site_FTP_User','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid FTP User')?>'}]}
	,'ftp_password':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site FTP Password','name':'Site_FTP_Password','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid FTP Password')?>'}]}
	,'ftp_directory':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site FTP Directory','name':'Site_FTP_Directory','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid FTP Directory')?>'}]}
	,'ftp_port':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site FTP Port','name':'ftp_port','ar':false,'validation':[{'regexp':"[\\d]+",'invalid_msg':'<?php echo _('Invalid FTP Port')?>'}]}
},
 'site_client_area':{
	'newsletter_label':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Newsletter Custom Label','name':'Site_Newsletter_Custom_Label','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Label')?>'}]}
	,'email_marketing_label':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Email Marketing Custom Label','name':'Site_Email_Marketing_Custom_Label','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Label')?>'}]}
	,'postal_marketing_label':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Postal Marketing Custom Label','name':'Site_Postal_Marketing_Custom_Label','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Label')?>'}]}

,'facebook_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Facebook URL','name':'Site_Facebook_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}

,'twitter_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Twitter URL','name':'Site_Twitter_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}

,'skype_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Skype URL','name':'Site_Skype_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}
,'linkedin_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site LinkedIn URL','name':'Site_LinkedIn_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}
,'flickr_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Flickr URL','name':'Site_Flickr_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}
,'digg_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Digg URL','name':'Site_Digg_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}
,'blog_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Blog URL','name':'Site_Blog_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}
,'youtube_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Youtube URL','name':'Site_Youtube_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}
,'rss_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site RSS URL','name':'Site_RSS_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}
,'google_url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Site Google URL','name':'Site_Google_URL','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid uRL')?>'}]}
,'registration_disclaimer':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'registration_disclaimer','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Site Disclaimer Text')?>'}]}
},

'site_menu':{
		'html':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'dbname':'Site Menu HTML','name':'site_menu_html','ar':false}
		,'css':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'dbname':'Site Menu CSS','name':'site_menu_css','ar':false}
		,'javascript':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'dbname':'Site Menu Javascript','name':'site_menu_javascript','ar':false}
},
'site_search':{
		'html':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'dbname':'Site Search HTML','name':'site_search_html','ar':false}
		,'css':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'dbname':'Site Search CSS','name':'site_search_css','ar':false}
		,'javascript':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'dbname':'Site Search Javascript','name':'site_search_javascript','ar':false}
},

'email_forgot':{
	'forgot_body_plain':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'forgot_password_body_plain','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Forgot Password Email Plain Body Text')?>'}]}
	,'forgot_body_html':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'forgot_password_body_html','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Forgot Password Email HTML Body Text')?>'}]}
	,'forgot_subject':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'forgot_password_subject','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Forgot Password Email Subject')?>'}]}
},
'email_welcome':{
	'welcome_body_plain':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'welcome_body_plain','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Welcome Email Plain Body Text')?>'}]}
	,'welcome_body_html':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'welcome_body_html','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Welcome Email HTML Body Text')?>'}]}
	,'welcome_subject':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'welcome_subject','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Welcome Email Subject')?>'}]}
},
'welcome_message':{
	'welcome_source':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'welcome_source','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Welcome Source')?>'}]}
}, 'email_credentials':{
	'email':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Address','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Email Address')?>'}]}
	,'password':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Password','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Password')?>'}]}
	,'email_provider':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Provider','ar':false,'validation':false,'invalid_msg':''}
}, 'email_credentials_direct_mail':{
	'email_direct_mail':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Address_direct_mail','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Email Address')?>'}]}
	,'email_provider':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Provider','ar':false,'validation':false,'invalid_msg':''}

}, 'email_credentials_other':{
	'email_other':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Address_other','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Email Address')?>'}]}
	,'email_provider':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Provider','ar':false,'validation':false,'invalid_msg':''}
	,'login':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Login_other','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Login')?>'}]}
	,'password_other':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Password_other','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Password')?>'}]}
	,'incoming_server':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Incoming_Server_other','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Incoming Server')?>'}]}
	,'outgoing_server':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Outgoing_Server_other','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Outgoing Server')?>'}]}

}, 'email_credentials_inikoo_mail':{
	'email_inikoo_mail':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Address_inikoo_mail','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Email Address')?>'}]}
	,'email_provider':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Provider','ar':false,'validation':false,'invalid_msg':''}
}
, 'email_credentials_MadMimi':{
	'api_email_MadMimi':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'API_Email_Address_MadMimi','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Email Address')?>'}]}
	,'api_key_MadMimi':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'API_Key_MadMimi','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid API Key')?>'}]}	
	,'email_MadMimi':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Address_MadMimi','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Email Address')?>'}]}
	,'email_provider':{'changed':true,'validated':true,'required':true,'group':1,'type':'item','name':'Email_Provider','ar':false,'validation':false,'invalid_msg':''}
}
		
};

var validate_scope_metadata={
'site_properties':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'site_ftp':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'site_client_area':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'site_checkout':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'site_menu':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'site_search':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'email_forgot':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'email_welcome':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'welcome_message':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'email_credentials':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'email_credentials_direct_mail':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'email_credentials_other':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'email_credentials_inikoo_mail':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
,'email_credentials_MadMimi':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'site_key','key':<?php echo$_SESSION['state']['site']['id']?>}
};






function change_block(e){
    var ids = ["general","layout","style","sections","pages","components", "email"]; 
	var block_ids = ["d_general","d_layout","d_style","d_sections","d_pages","d_components", "d_email"]; 
	Dom.setStyle(block_ids,'display','none');
	Dom.setStyle('d_'+this.id,'display','');
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=site-editing&value='+this.id ,{});
}


function change_components_block(e){
    var ids = ["headers","footers","website_search","menu", "email", "favicon"]; 
	var block_ids = ["d_general","d_layout","d_style","d_sections","d_pages","d_headers","d_footers","d_website_search","d_menu", "d_email", "d_favicon"]; 
	Dom.setStyle(block_ids,'display','none');
	Dom.setStyle('d_'+this.id,'display','');
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=site-editing&value='+this.id ,{});
}


function change_style_block(e){
    var ids = ["general","layout","style","sections","pages","headers","footers","website_search","menu", "email", "favicon"]; 
	var block_ids = ["d_general","d_layout","d_style","d_sections","d_pages","d_headers","d_footers","d_website_search","d_menu", "d_email", "d_favicon"]; 
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
								 rowsPerPage    : <?php echo$_SESSION['state']['site']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
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
				     	  //,{key:"url",label:"<?php echo _('URL')?>", <?php echo($_SESSION['state']['site']['edit_pages']['view']=='page_properties'?'':'hidden:true,')?>width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
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
			 ,"go","code","store_title","delete","link_title","page_title","page_keywords"

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
				   				       ,{key:"pages",label:"<?php echo _('Pages')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   				       ,{key:"image",label:"<?php echo _('Preview')?>", width:300,sortable:false,className:"aright"}
				     				     ,{key:"default", label:"",width:90,sortable:false,className:"acenter"}		         
				     ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'page_header'}		         
				       ];
				       
	 
				       
				       

	    var request="ar_edit_sites.php?tipo=page_headers&parent=site&parent_key="+Dom.get('site_key').value+"&tableid=2";
	    //alert(request)
	        this.dataSource2 = new YAHOO.util.DataSource(request);

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
			 ,"go","name","delete","pages","image","default"

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



  var tableid=3; 
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				         ,{key:"go", label:"", width:20,action:"none"}
				       ,{key:"name",label:"<?php echo _('Name')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				   				       ,{key:"pages",label:"<?php echo _('Pages')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   				       ,{key:"image",label:"<?php echo _('Preview')?>", width:300,sortable:false,className:"aright"}
				     				     ,{key:"default", label:"",width:90,sortable:false,className:"acenter"}		         
				     ,{key:"delete", label:"",width:13,sortable:false,action:'delete',object:'page_footer'}		         
				       ];
				       
	 
				       
				       

	    var request="ar_edit_sites.php?tipo=page_footers&parent=site&parent_key="+Dom.get('site_key').value+"&tableid=3";
	    //alert(request)
	        this.dataSource3 = new YAHOO.util.DataSource(request);

	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
			 ,"go","name","delete","pages","image","default"

			 ]};

        this.table3 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource3
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['site']['edit_footers']['nr']?> ,containers : 'paginator3', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['site']['edit_footers']['order']?>",
							     dir: "<?php echo $_SESSION['state']['site']['edit_footers']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );
	    
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table3.table_id=tableid;
     this.table3.subscribe("renderEvent", myrenderEvent);


	    this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table3.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table3.subscribe("cellClickEvent", onCellClick);
		    
	    this.table3.filter={key:'<?php echo $_SESSION['state']['site']['edit_footers']['f_field']?>',value:'<?php echo $_SESSION['state']['site']['edit_footers']['f_value']?>'};



};
    });


function show_dialog_upload_header(){

  region1 = Dom.getRegion('show_upload_header'); 
    region2 = Dom.getRegion('dialog_upload_header'); 
 var pos =[region1.right-region2.width,region1.bottom+2]
    Dom.setXY('dialog_upload_header', pos);
dialog_upload_header.show()
}
function close_upload_header(){
Dom.get('upload_header_use_file').value='';

dialog_upload_header.hide();
}

function cancel_upload_header_files(){
Dom.get('upload_header_use_file').value='';

dialog_upload_header_files.hide();
}
function upload_header_file(file){
Dom.get('upload_header_use_file').value=file;
upload_header();
}


function upload_header(e){
    YAHOO.util.Connect.setForm('upload_header_form', true,true);
    var request='ar_upload_page_content.php?tipo=upload_header';
    
    Dom.setStyle('processing_upload_header','display','');
        Dom.setStyle(['upload_header','cancel_upload_header'],'display','none');

    
   var uploadHandler = {
      upload: function(o) {
	 //  alert(o.responseText)
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){
	  

	     
        window.location.reload()
                
	    }else if(r.state==201){
	        dialog_upload_header.hide();
	        region1 = Dom.getRegion('show_upload_header'); 
            region2 = Dom.getRegion('dialog_upload_header_files'); 
            var pos =[region1.right-region2.width,region1.bottom+2]
            Dom.setXY('dialog_upload_header_files', pos);
	        dialog_upload_header_files.show();
	        buttons='';
	        for(var i=0; i<r.list.length; i++) {
                buttons=buttons+"<button onClick='upload_header_file(\""+r.list[i]+"\")' style='margin-top:0px;margin-bottom:10px' >"+r.list[i]+"</button> ";
            }
	        Dom.get('upload_header_files').innerHTML=buttons
        }
	    else{
	       
	      Dom.setStyle('processing_upload_headerr','display','none');
        Dom.setStyle(['upload_headerr','cancel_upload_headerr'],'display','');
		//alert(r.msg);
	    	}
    
    }
}    
    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);
}


function show_dialog_upload_footer(){

  region1 = Dom.getRegion('show_upload_footer'); 
    region2 = Dom.getRegion('dialog_upload_footer'); 
 var pos =[region1.right-region2.width,region1.bottom+2]
    Dom.setXY('dialog_upload_footer', pos);
dialog_upload_footer.show()
}
function close_upload_footer(){
Dom.get('upload_footer_use_file').value='';

dialog_upload_footer.hide();
}
function cancel_upload_footer_files(){
Dom.get('upload_footer_use_file').value='';

dialog_upload_footer_files.hide();
}
function upload_footer_file(file){
Dom.get('upload_footer_use_file').value=file;
upload_footer();
}


function upload_footer(e){
    YAHOO.util.Connect.setForm('upload_footer_form', true,true);
    var request='ar_upload_page_content.php?tipo=upload_footer';
    Dom.setStyle('processing_upload_footer','display','');
    Dom.setStyle(['upload_footer','cancel_upload_footer'],'display','none');
   var uploadHandler = {
      upload: function(o) {
	  // alert(o.responseText)
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){
	     
         window.location.reload()
                
	    }else if(r.state==201){
	        dialog_upload_footer.hide();
	        region1 = Dom.getRegion('show_upload_footer'); 
            region2 = Dom.getRegion('dialog_upload_footer_files'); 
            var pos =[region1.right-region2.width,region1.bottom+2]
            Dom.setXY('dialog_upload_footer_files', pos);
	        dialog_upload_footer_files.show();
	        buttons='';
	        for(var i=0; i<r.list.length; i++) {
                buttons=buttons+"<button onClick='upload_footer_file(\""+r.list[i]+"\")' style='margin-top:0px;margin-bottom:10px' >"+r.list[i]+"</button> ";
            }
	        Dom.get('upload_footer_files').innerHTML=buttons
        }
	    else{
	      Dom.setStyle('processing_upload_footer','display','none');
        Dom.setStyle(['upload_footer','cancel_upload_footer'],'display','');
	    
		alert(r.msg);
		}
}
}
    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);
};



function show_dialog_upload_search(){

  region1 = Dom.getRegion('show_upload_search'); 
    region2 = Dom.getRegion('dialog_upload_search'); 
 var pos =[region1.right-region2.width,region1.bottom+2]
    Dom.setXY('dialog_upload_search', pos);
dialog_upload_search.show()
}
function close_upload_search(){
Dom.get('upload_search_use_file').value='';

dialog_upload_search.hide();
}

function cancel_upload_search_files(){
Dom.get('upload_search_use_file').value='';

dialog_upload_search_files.hide();
}
function upload_search_file(file){
Dom.get('upload_search_use_file').value=file;
upload_search();
}

function upload_search(e){
    YAHOO.util.Connect.setForm('upload_search_form', true,true);
    var request='ar_upload_page_content.php?tipo=upload_search';
    Dom.setStyle('processing_upload_search','display','');
    Dom.setStyle(['upload_search','cancel_upload_search'],'display','none');
   var uploadHandler = {
      upload: function(o) {
	   //alert(o.responseText)
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){
	     
         window.location.reload()
                
	    }else if(r.state==201){
	        dialog_upload_search.hide();
	        region1 = Dom.getRegion('show_upload_search'); 
            region2 = Dom.getRegion('dialog_upload_search_files'); 
            var pos =[region1.right-region2.width,region1.bottom+2]
            Dom.setXY('dialog_upload_search_files', pos);
	        dialog_upload_search_files.show();
	        buttons='';
	        for(var i=0; i<r.list.length; i++) {
                buttons=buttons+"<button onClick='upload_search_file(\""+r.list[i]+"\")' style='margin-top:0px;margin-bottom:10px' >"+r.list[i]+"</button> ";
            }
	        Dom.get('upload_search_files').innerHTML=buttons
        }
	    else{
	      Dom.setStyle('processing_upload_search','display','none');
        Dom.setStyle(['upload_search','cancel_upload_search'],'display','');
	    
		alert(r.msg);
		}
}
}
    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);
};

function show_dialog_upload_menu(){

  region1 = Dom.getRegion('show_upload_menu'); 
    region2 = Dom.getRegion('dialog_upload_menu'); 
 var pos =[region1.right-region2.width,region1.bottom+2]
    Dom.setXY('dialog_upload_menu', pos);
dialog_upload_menu.show()
}
function close_upload_menu(){
Dom.get('upload_menu_use_file').value='';

dialog_upload_menu.hide();
}

function cancel_upload_menu_files(){
Dom.get('upload_menu_use_file').value='';

dialog_upload_menu_files.hide();
}
function upload_menu_file(file){
Dom.get('upload_menu_use_file').value=file;
upload_menu();
}

function upload_menu(e){
    YAHOO.util.Connect.setForm('upload_menu_form', true,true);
    var request='ar_upload_page_content.php?tipo=upload_menu';
    Dom.setStyle('processing_upload_menu','display','');
  Dom.setStyle(['upload_menu','cancel_upload_menu'],'display','none');
   var uploadHandler = {
      upload: function(o) {
	  // alert(o.responseText)
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){ 
         window.location.reload()                
	    }else if(r.state==201){
	        dialog_upload_menu.hide();
	        region1 = Dom.getRegion('show_upload_menu'); 
            region2 = Dom.getRegion('dialog_upload_menu_files'); 
            var pos =[region1.right-region2.width,region1.bottom+2]
            Dom.setXY('dialog_upload_menu_files', pos);
	        dialog_upload_menu_files.show();
	        buttons='';
	        for(var i=0; i<r.list.length; i++) {
                buttons=buttons+"<button onClick='upload_menu_file(\""+r.list[i]+"\")' style='margin-top:0px;margin-bottom:10px' >"+r.list[i]+"</button> ";
            }
	        Dom.get('upload_menu_files').innerHTML=buttons
        }
	    else{
	      Dom.setStyle('processing_upload_menu','display','none');
        Dom.setStyle(['upload_menu','cancel_upload_menu'],'display','');
	    
		alert(r.msg);
		}
}
}
    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);
};

function init(){
init_search('site');




  Event.addListener('cancel_upload_header_files', "click", cancel_upload_header_files);
 dialog_upload_header_files = new YAHOO.widget.Dialog("dialog_upload_header_files", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_header_files.render();
    
     Event.addListener('cancel_upload_footer_files', "click", cancel_upload_footer_files);
 dialog_upload_footer_files = new YAHOO.widget.Dialog("dialog_upload_footer_files", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_footer_files.render();
 Event.addListener('cancel_upload_menu_files', "click", cancel_upload_menu_files);
 dialog_upload_menu_files = new YAHOO.widget.Dialog("dialog_upload_menu_files", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_menu_files.render();
    
     Event.addListener('cancel_upload_search_files', "click", cancel_upload_search_files);
 dialog_upload_search_files = new YAHOO.widget.Dialog("dialog_upload_search_files", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_search_files.render();
    
   
  
 Event.addListener('save_edit_email_forgot', "click", save_edit_email_forgot);
 Event.addListener('reset_edit_email_forgot', "click", reset_edit_email_forgot);

 Event.addListener('save_edit_email_welcome', "click", save_edit_email_welcome);
 Event.addListener('reset_edit_email_welcome', "click", reset_edit_email_welcome);

 Event.addListener('save_edit_welcome_message', "click", save_edit_welcome_message);
 Event.addListener('reset_edit_welcome_message', "click", reset_edit_welcome_message);


 Event.addListener('save_edit_site_menu', "click", save_edit_site_menu);
 Event.addListener('reset_edit_site_menu', "click", reset_edit_site_menu);
 Event.addListener('save_edit_site_search', "click", save_edit_site_search);
 Event.addListener('reset_edit_site_search', "click", reset_edit_site_search);


 Event.addListener('show_upload_header', "click", show_dialog_upload_header);
Event.addListener("cancel_upload_header", "click", close_upload_header);
  Event.addListener('upload_header', "click", upload_header);
 dialog_upload_header = new YAHOO.widget.Dialog("dialog_upload_header", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_header.render();



 Event.addListener('show_upload_footer', "click", show_dialog_upload_footer);
Event.addListener("cancel_upload_footer", "click", close_upload_footer);
  Event.addListener('upload_footer', "click", upload_footer);
 dialog_upload_footer = new YAHOO.widget.Dialog("dialog_upload_footer", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_footer.render();



 Event.addListener('show_upload_menu', "click", show_dialog_upload_menu);
Event.addListener("cancel_upload_menu", "click", close_upload_menu);
  Event.addListener('upload_menu', "click", upload_menu);
 dialog_upload_menu = new YAHOO.widget.Dialog("dialog_upload_menu", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_menu.render();
    
    
 Event.addListener('show_upload_search', "click", show_dialog_upload_search);
Event.addListener("cancel_upload_search", "click", close_upload_search);
  Event.addListener('upload_search', "click", upload_search);
 dialog_upload_search = new YAHOO.widget.Dialog("dialog_upload_search", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_search.render();


YAHOO.util.Event.on('uploadButton', 'click', upload_image);

 ids=['page_properties','page_html_head','page_header'];
 YAHOO.util.Event.addListener(ids, "click",change_edit_pages_view,{'table_id':6,'parent':'page'})


    var ids = ["general","layout","style","sections","pages","headers","footers","website_search","menu", "email", "favicon"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
   

	   
	Event.addListener(["Mals","Inikoo"], "click", change_checkout_method);
	Event.addListener(["registration_simple","registration_wholesale","registration_none"], "click", change_registration_method);

	//Event.addListener(["locale_en_GB","locale_de_DE","locale_fr_FR","locale_es_ES","locale_pl_PL","locale_it_IT"], "click", change_locale_method);
	Event.addListener(["ftp_protocol_FTPS","ftp_protocol_FTP","ftp_protocol_SFTP"], "click", change_ftp_method);
	Event.addListener(["ftp_passive_Yes","ftp_passive_No"], "click", change_ftp_passive);
	Event.addListener(["show_badges_Yes","show_badges_No"], "click", change_show_badges);




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
	
	
	var site_search_html_oACDS = new YAHOO.util.FunctionDataSource(validate_site_search_html);
    site_search_html_oACDS.queryMatchContains = true;
    var site_search_html_oAutoComp = new YAHOO.widget.AutoComplete("site_search_html","site_search_html_Container", site_search_html_oACDS);
    site_search_html_oAutoComp.minQueryLength = 0; 
    site_search_html_oAutoComp.queryDelay = 0.1;  
	
	var site_search_css_oACDS = new YAHOO.util.FunctionDataSource(validate_site_search_css);
    site_search_css_oACDS.queryMatchContains = true;
    var site_search_css_oAutoComp = new YAHOO.widget.AutoComplete("site_search_css","site_search_css_Container", site_search_css_oACDS);
    site_search_css_oAutoComp.minQueryLength = 0; 
    site_search_css_oAutoComp.queryDelay = 0.1;  

	var site_search_javascript_oACDS = new YAHOO.util.FunctionDataSource(validate_site_search_javascript);
    site_search_javascript_oACDS.queryMatchContains = true;
    var site_search_javascript_oAutoComp = new YAHOO.widget.AutoComplete("site_search_javascript","site_search_javascript_Container", site_search_javascript_oACDS);
    site_search_javascript_oAutoComp.minQueryLength = 0; 
    site_search_javascript_oAutoComp.queryDelay = 0.1;  
	
	var site_menu_html_oACDS = new YAHOO.util.FunctionDataSource(validate_site_menu_html);
    site_menu_html_oACDS.queryMatchContains = true;
    var site_menu_html_oAutoComp = new YAHOO.widget.AutoComplete("site_menu_html","site_menu_html_Container", site_menu_html_oACDS);
    site_menu_html_oAutoComp.minQueryLength = 0; 
    site_menu_html_oAutoComp.queryDelay = 0.1;  
	
	var site_menu_css_oACDS = new YAHOO.util.FunctionDataSource(validate_site_menu_css);
    site_menu_css_oACDS.queryMatchContains = true;
    var site_menu_css_oAutoComp = new YAHOO.widget.AutoComplete("site_menu_css","site_menu_css_Container", site_menu_css_oACDS);
    site_menu_css_oAutoComp.minQueryLength = 0; 
    site_menu_css_oAutoComp.queryDelay = 0.1;  

	var site_menu_javascript_oACDS = new YAHOO.util.FunctionDataSource(validate_site_menu_javascript);
    site_menu_javascript_oACDS.queryMatchContains = true;
    var site_menu_javascript_oAutoComp = new YAHOO.widget.AutoComplete("site_menu_javascript","site_menu_javascript_Container", site_menu_javascript_oACDS);
    site_menu_javascript_oAutoComp.minQueryLength = 0; 
    site_menu_javascript_oAutoComp.queryDelay = 0.1;  	
	
	
    var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_client_newsletter_label);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Newsletter_Custom_Label","Site_Newsletter_Custom_Label_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;  
	

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_client_email_marketing_label);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Email_Marketing_Custom_Label","Site_Email_Marketing_Custom_Label_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_client_postal_marketing_label);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Postal_Marketing_Custom_Label","Site_Postal_Marketing_Custom_Label_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;



	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_twitter);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Twitter_URL","Site_Twitter_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_skype);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Skype_URL","Site_Skype_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_linkedin);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_LinkedIn_URL","Site_LinkedIn_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_flickr);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Flickr_URL","Site_Flickr_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_blog);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Blog_URL","Site_Blog_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_digg);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Digg_URL","Site_Digg_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_google);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Google_URL","Site_Google_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_rss);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_RSS_URL","Site_RSS_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;

	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_youtube);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Youtube_URL","Site_Youtube_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;



	var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_url_facebook);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_Facebook_URL","Site_Facebook_URL_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;








    var site_ftp_server_oACDS = new YAHOO.util.FunctionDataSource(validate_site_ftp_server);
    site_ftp_server_oACDS.queryMatchContains = true;
    var site_ftp_server_oAutoComp = new YAHOO.widget.AutoComplete("Site_FTP_Server","Site_FTP_Server_Container", site_ftp_server_oACDS);
    site_ftp_server_oAutoComp.minQueryLength = 0; 
    site_ftp_server_oAutoComp.queryDelay = 0.1;  
    
    
    var site_ftp_user_oACDS = new YAHOO.util.FunctionDataSource(validate_site_ftp_user);
    site_ftp_user_oACDS.queryMatchContains = true;
    var site_ftp_user_oAutoComp = new YAHOO.widget.AutoComplete("Site_FTP_User","Site_FTP_User_Container", site_ftp_user_oACDS);
    site_ftp_user_oAutoComp.minQueryLength = 0; 
    site_ftp_user_oAutoComp.queryDelay = 0.1;  
    
    var site_ftp_password_oACDS = new YAHOO.util.FunctionDataSource(validate_site_ftp_password);
    site_ftp_password_oACDS.queryMatchContains = true;
    var site_ftp_password_oAutoComp = new YAHOO.widget.AutoComplete("Site_FTP_Password","Site_FTP_Password_Container", site_ftp_password_oACDS);
    site_ftp_password_oAutoComp.minQueryLength = 0; 
    site_ftp_password_oAutoComp.queryDelay = 0.1;  
    
    var site_ftp_directory_oACDS = new YAHOO.util.FunctionDataSource(validate_site_ftp_directory);
    site_ftp_directory_oACDS.queryMatchContains = true;
    var site_ftp_directory_oAutoComp = new YAHOO.widget.AutoComplete("Site_FTP_Directory","Site_FTP_Directory_Container", site_ftp_directory_oACDS);
    site_ftp_directory_oAutoComp.minQueryLength = 0; 
    site_ftp_directory_oAutoComp.queryDelay = 0.1;  
    
    var site_ftp_directory_oACDS = new YAHOO.util.FunctionDataSource(validate_site_ftp_port);
    site_ftp_directory_oACDS.queryMatchContains = true;
    var site_ftp_directory_oAutoComp = new YAHOO.widget.AutoComplete("ftp_port","ftp_port_Container", site_ftp_directory_oACDS);
    site_ftp_directory_oAutoComp.minQueryLength = 0; 
    site_ftp_directory_oAutoComp.queryDelay = 0.1;     
	
	
		var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_forgot_body_plain);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("forgot_password_body_plain","forgot_password_body_plain_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_forgot_body_html);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("forgot_password_body_html","forgot_password_body_html_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_forgot_subject);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("forgot_password_subject","forgot_password_subject_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 


	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_welcome_body_plain);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("welcome_body_plain","welcome_body_plain_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;   

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_registration_disclaimer);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("registration_disclaimer","registration_disclaimer_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1;


	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_welcome_body_html);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("welcome_body_html","welcome_body_html_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 

	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_welcome_subject);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("welcome_subject","welcome_subject_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 


	var site_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_welcome_source);
	site_slogan_oACDS.queryMatchContains = true;
	var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("welcome_source","welcome_source_Container", site_slogan_oACDS);
	customer_Registration_Number_oAutoComp.minQueryLength = 0; 
	customer_Registration_Number_oAutoComp.queryDelay = 0.1; 

	
	
	YAHOO.util.Event.addListener('save_edit_site_checkout', "click", save_edit_site_checkout);
    YAHOO.util.Event.addListener('reset_edit_site_checkout', "click", reset_edit_site_checkout);
    YAHOO.util.Event.addListener('save_edit_site_properties', "click", save_edit_site_properties);
    YAHOO.util.Event.addListener('reset_edit_site_properties', "click", reset_edit_site_properties);
    YAHOO.util.Event.addListener('save_edit_site_ftp', "click", save_edit_site_ftp);
    YAHOO.util.Event.addListener('reset_edit_site_ftp', "click", reset_edit_site_ftp);
    
    YAHOO.util.Event.addListener('save_edit_site_client_area', "click", save_edit_site_client_area);
    YAHOO.util.Event.addListener('reset_edit_site_client_area', "click", reset_edit_site_client_area);
    
     YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_show6', "click",show_filter,6);
 YAHOO.util.Event.addListener('clean_table_filter_hide6', "click",hide_filter,6);
  YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);
 
 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 
 
  var oACDS6 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS6.queryMatchContains = true;
  oACDS6.table_id=6;
 var oAutoComp6 = new YAHOO.widget.AutoComplete("f_input6","f_container6", oACDS6);
 oAutoComp6.minQueryLength = 0; 

 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
  oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 
 
 var oACDS3 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS3.queryMatchContains = true;
  oACDS3.table_id=3;
 var oAutoComp3 = new YAHOO.widget.AutoComplete("f_input3","f_container3", oACDS3);
 oAutoComp3.minQueryLength = 0; 
    


}

function save_edit_email_forgot(){
    save_edit_general_bulk('email_forgot');
}

function reset_edit_email_forgot(){
    reset_edit_general('email_forgot')
}

function save_edit_email_welcome(){
    save_edit_general_bulk('email_welcome');
}

function reset_edit_email_welcome(){
    reset_edit_general('email_welcome')
}

function save_edit_welcome_message(){
    save_edit_general_bulk('welcome_message');
}

function reset_edit_welcome_message(){
    reset_edit_general('welcome_message')
}


function save_edit_site_properties(){
    save_edit_general_bulk('site_properties');
}
function reset_edit_site_properties(){
    reset_edit_general('site_properties')
}

function save_edit_site_ftp(){
    save_edit_general_bulk('site_ftp');
}

function save_edit_site_client_area(){
    save_edit_general_bulk('site_client_area');
}
function reset_edit_site_ftp(){
    reset_edit_general('site_ftp')
}
function reset_edit_site_client_area(){
    reset_edit_general('site_client_area')
}
function save_edit_site_checkout(){
    save_edit_general_bulk('site_checkout');
}
function reset_edit_site_checkout(){
    reset_edit_general('site_checkout')
}


function save_edit_site_menu(){
    save_edit_general_bulk('site_menu');
}

function reset_edit_site_menu(){
    reset_edit_general('site_menu')
     Dom.setStyle('show_upload_menu','display','')
}

function save_edit_site_search(){
    save_edit_general_bulk('site_search');
}

function reset_edit_site_search(){
    reset_edit_general('site_search');
     Dom.setStyle('show_upload_search','display','')

    
}



function validate_client_newsletter_label(query){

 validate_general('site_client_area','newsletter_label',unescape(query));
}


function validate_client_email_marketing_label(query){
 validate_general('site_client_area','email_marketing_label',unescape(query));
}

function validate_client_postal_marketing_label(query){
 validate_general('site_client_area','postal_marketing_label',unescape(query));
}


function validate_url_facebook(query){
 validate_general('site_client_area','facebook_url',unescape(query));
}

function validate_url_twitter(query){
 validate_general('site_client_area','twitter_url',unescape(query));
}

function validate_url_skype(query){
 validate_general('site_client_area','skype_url',unescape(query));
}
function validate_url_flickr(query){
 validate_general('site_client_area','flickr_url',unescape(query));
}
function validate_url_blog(query){
 validate_general('site_client_area','blog_url',unescape(query));
}
function validate_url_digg(query){
 validate_general('site_client_area','digg_url',unescape(query));
}
function validate_url_linkedin(query){
 validate_general('site_client_area','linkedin_url',unescape(query));
}
function validate_url_google(query){
 validate_general('site_client_area','google_url',unescape(query));
}
function validate_url_youtube(query){
 validate_general('site_client_area','youtube_url',unescape(query));
}
function validate_url_rss(query){
 validate_general('site_client_area','rss_url',unescape(query));
}

function validate_registration_disclaimer(query){
 validate_general('site_client_area','registration_disclaimer',unescape(query));
}


function validate_site_ftp_server(query){

 validate_general('site_ftp','ftp_server',unescape(query));
}
function validate_site_ftp_user(query){
 validate_general('site_ftp','ftp_user',unescape(query));
}
function validate_site_ftp_password(query){
 validate_general('site_ftp','ftp_password',unescape(query));
}
function validate_site_ftp_directory(query){
 validate_general('site_ftp','ftp_directory',unescape(query));
}
function validate_site_ftp_port(query){
 validate_general('site_ftp','ftp_port',unescape(query));
}


function validate_forgot_body_plain(query){
 validate_general('email_forgot','forgot_body_plain',unescape(query));
}
function validate_forgot_body_html(query){
 validate_general('email_forgot','forgot_body_html',unescape(query));
}
function validate_forgot_subject(query){
 validate_general('email_forgot','forgot_subject',unescape(query));
}
function validate_welcome_body_plain(query){
 validate_general('email_welcome','welcome_body_plain',unescape(query));
}



function validate_welcome_body_html(query){
 validate_general('email_welcome','welcome_body_html',unescape(query));
}
function validate_welcome_subject(query){
 validate_general('email_welcome','welcome_subject',unescape(query));
}
function validate_welcome_source(query){
 validate_general('welcome_message','welcome_source',unescape(query));
}





function validate_site_url(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('site_properties','url',unescape(query));

 if(original_query==''){
    
     validate_scope_data.site_properties.url.validated=true;
     validate_scope('site_properties'); 
 }

}
function validate_site_name(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('site_properties','name',unescape(query));

 if(original_query==''){
    
     validate_scope_data.site_properties.name.validated=true;
     validate_scope('site_properties'); 
 }

}
function validate_site_address(query){
 validate_general('site_properties','address',unescape(query));
}
function validate_site_telephone(query){
 validate_general('site_properties','telephone',unescape(query));
}
function validate_site_slogan(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('site_properties','slogan',unescape(query));

 if(original_query==''){
    
     validate_scope_data.site_properties.slogan.validated=true;
     validate_scope('site_properties'); 
 }

}

function validate_site_mals_id(query){
 validate_general('site_checkout','mals_id',unescape(query));
}
function validate_site_mals_url(query){
 validate_general('site_checkout','mals_url',unescape(query));
}
function validate_site_mals_url_multi(query){
 validate_general('site_checkout','mals_url_multi',unescape(query));
}
function change_checkout_method(){

types=Dom.getElementsByClassName('site_checkout_method', 'button', 'site_checkout_method_buttons')
Dom.removeClass(types,'selected');

Dom.get('site_checkout_method').value=this.id;
//alert(Dom.get('site_checkout_method').value);


site_id=Dom.get('site_key').value;
store_key=Dom.get('store_key').value;
var request='ar_edit_sites.php?tipo=edit_checkout_method&site_key=' + site_id +'&store_key='+store_key + '&site_checkout_method='+Dom.get('site_checkout_method').value
	          //  alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	        
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				Dom.addClass(r.new_value,'selected');
				
				if(r.new_value=='Mals'){
				Dom.setStyle(['mals_id_tr','mals_url1_tr','mals_url2_tr'],'display','');
				
				}else{
				
				Dom.setStyle(['mals_id_tr','mals_url1_tr','mals_url2_tr'],'display','none');
				
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


function validate_site_search_html(query){
 validate_general('site_search','html',unescape(query));
 
 if(Dom.getStyle('save_edit_site_search', 'visibility')=='hidden')
 Dom.setStyle('show_upload_search','display','')
 else{
  Dom.setStyle('show_upload_search','display','none')
 }
}
function validate_site_search_css(query){
 validate_general('site_search','css',unescape(query));
 if(Dom.getStyle('save_edit_site_search', 'visibility')=='hidden')
 Dom.setStyle('show_upload_search','display','')
 else
  Dom.setStyle('show_upload_search','display','none')
}
function validate_site_search_javascript(query){
 validate_general('site_search','javascript',unescape(query));
 if(Dom.getStyle('save_edit_site_search', 'visibility')=='hidden')
 Dom.setStyle('show_upload_search','display','')
 else
  Dom.setStyle('show_upload_search','display','none')
}

function validate_site_menu_html(query){
 validate_general('site_menu','html',unescape(query));
  if(Dom.getStyle('save_edit_site_menu', 'visibility')=='hidden')
 Dom.setStyle('show_upload_menu','display','')
 else
  Dom.setStyle('show_upload_menu','display','none')
}
function validate_site_menu_css(query){
 validate_general('site_menu','css',unescape(query));
   if(Dom.getStyle('save_edit_site_menu', 'visibility')=='hidden')
 Dom.setStyle('show_upload_menu','display','')
 else
  Dom.setStyle('show_upload_menu','display','none')
}
function validate_site_menu_javascript(query){
 validate_general('site_menu','javascript',unescape(query));
   if(Dom.getStyle('save_edit_site_menu', 'visibility')=='hidden')
 Dom.setStyle('show_upload_menu','display','')
 else
  Dom.setStyle('show_upload_menu','display','none')
}





function change_registration_method(){
types=Dom.getElementsByClassName('site_registration_method', 'button', 'site_registration_method_buttons')
Dom.removeClass(types,'selected');

Dom.get('site_registration_method').value=this.getAttribute('dbvalue');
//alert(Dom.get('site_checkout_method').value);


site_id=Dom.get('site_key').value;
store_key=Dom.get('store_key').value;
var request='ar_edit_sites.php?tipo=edit_registration_method&site_key=' + site_id +'&store_key='+store_key + '&site_registration_method='+Dom.get('site_registration_method').value
	            //alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	           // alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				Dom.addClass('registration_'+r.new_value,'selected');

            }
			else{
				Dom.addClass(Dom.get('site_registration_method').value,'selected');
			}
   			}
    });
}

function change_locale_method(o){
types=Dom.getElementsByClassName('site_locale_method', 'button', 'site_locale_method_buttons')
Dom.removeClass(types,'selected');

Dom.get('site_locale_method').value=o.options[o.selectedIndex].value;
//alert(Dom.get('site_checkout_method').value);


site_id=Dom.get('site_key').value;
store_key=Dom.get('store_key').value;
var request='ar_edit_sites.php?tipo=edit_locale_method&site_key=' + site_id +'&store_key='+store_key + '&site_locale='+Dom.get('site_locale_method').value
	            //alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	           // alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				Dom.addClass('locale_'+r.new_value,'selected');

            }
			else{
				Dom.addClass(Dom.get('site_locale_method').value,'selected');
			}
   			}
    });
}

function auto_fill_port(){
	validate_scope_data['site']['ftp_port'].validated=true;
	validate_scope_data['site']['ftp_port'].changed=true;

	//Dom.setStyle('save_edit_site','visibility','visible');
        //Dom.setStyle('reset_edit_site','visibility','visible');
}

function change_ftp_method(){
types=Dom.getElementsByClassName('ftp_protocol_method', 'button', 'ftp_protocol_method_buttons')
Dom.removeClass(types,'selected');

Dom.get('ftp_protocol_method').value=this.getAttribute('dbvalue');

if(Dom.get('ftp_protocol_method').value=='FTP' || Dom.get('ftp_protocol_method').value=='FTPS'){
	if(Dom.get('ftp_port').value==''){
		Dom.get('ftp_port').value=21;
		auto_fill_port();
		
	}
Dom.setStyle('tbody_ftp_passive','display','');
}
else{
	if(Dom.get('ftp_port').value==''){
		Dom.get('ftp_port').value=22;
		auto_fill_port();
		
	}
Dom.setStyle('tbody_ftp_passive','display','none');
}
//alert(Dom.get('site_checkout_method').value);


site_id=Dom.get('site_key').value;
store_key=Dom.get('store_key').value;
var request='ar_edit_sites.php?tipo=edit_ftp_method&site_key=' + site_id +'&store_key='+store_key + '&site_ftp='+Dom.get('ftp_protocol_method').value
	           // alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	           // alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				Dom.addClass('ftp_protocol_'+r.new_value,'selected');

            }
			else{
				Dom.addClass(Dom.get('ftp_protocol_method').value,'selected');
			}
   			}
    });
}

function change_ftp_passive(){
types=Dom.getElementsByClassName('ftp_passive_method', 'button', 'ftp_passive_method_buttons')
Dom.removeClass(types,'selected');

Dom.get('ftp_passive_method').value=this.getAttribute('dbvalue');

site_id=Dom.get('site_key').value;
store_key=Dom.get('store_key').value;
var request='ar_edit_sites.php?tipo=edit_ftp_passive&site_key=' + site_id +'&store_key='+store_key + '&site_passive='+Dom.get('ftp_passive_method').value
	           // alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	           // alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				Dom.addClass('ftp_passive_'+r.new_value,'selected');

			}
			else{
				Dom.addClass(Dom.get('ftp_passive_method').value,'selected');
			}
   			}
    });
}


function change_show_badges(){
types=Dom.getElementsByClassName('show_badges_method', 'button', 'show_badges_method_buttons')
Dom.removeClass(types,'selected');

Dom.get('show_badges_method').value=this.getAttribute('dbvalue');

site_id=Dom.get('site_key').value;
store_key=Dom.get('store_key').value;
var request='ar_edit_sites.php?tipo=edit_show_badges&site_key=' + site_id +'&store_key='+store_key + '&site_badges='+Dom.get('show_badges_method').value
	           // alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	           // alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				Dom.addClass('show_badges_'+r.new_value,'selected');

			}
			else{
				Dom.addClass(Dom.get('show_badges_method').value,'selected');
			}
   			}
    });
}

function save_social_media(key,value){

 var data_to_update=new Object;
 data_to_update[key]={'okey':key,'value':value}

 jsonificated_values=YAHOO.lang.JSON.stringify(data_to_update);


var request='ar_edit_sites.php?tipo=edit_site_client_area&values='+ jsonificated_values+"&site_key="+id


//var request='ar_edit_contacts.php?tipo=edit_customer&key=' + key+ '&newvalue=' + value +'&customer_key=' + customer_id
	//alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
//alert(o.responseText)
				var ra =  YAHOO.lang.JSON.parse(o.responseText);
				  for (x in ra){
               r=ra[x]
				if(r.state==200){
			
  
 
            if (r.newvalue=='no' || r.newvalue=='yes') {

		if(r.newvalue=='no')
			r.newvalue='No'
		if(r.newvalue=='yes')
			r.newvalue='Yes';
                           Dom.removeClass([r.key+'_No',r.key+'_Yes'],'selected');

               Dom.addClass(r.key+'_'+r.newvalue,'selected');

            }else{
                alert(r.msg)
            }
            }
        }
    }
    });

}



function set_default_header(header_key){

site_id=Dom.get('site_key').value;
var request='ar_edit_sites.php?tipo=set_default_header&site_key=' + site_id +'&header_key='+header_key
	           // alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	            //alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				
table_id=2
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);  
                table_id=1
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);  
            }
			else{
			}
   			}
    });

}

function set_default_footer(footer_key){

site_id=Dom.get('site_key').value;
var request='ar_edit_sites.php?tipo=set_default_footer&site_key=' + site_id +'&footer_key='+footer_key
	           // alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	         //   alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				
table_id=3
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);  
                table_id=1
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);  
            }
			else{
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
    
YAHOO.util.Event.onContentReady("rppmenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {trigger:"filter_name2"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });    
    
    
    YAHOO.util.Event.onContentReady("rppmenu3", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu3", {trigger:"rtext_rpp3" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu3", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {trigger:"filter_name3"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });    
    
    
    function post_item_updated_actions(branch,r){



}
