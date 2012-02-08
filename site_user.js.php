<?php
include_once('common.php');

?>
var Dom   = YAHOO.util.Dom;

var dialog_change_password;
var dialog_set_password;
var  group_name=new Object;


    YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			      // {key:"user", label:"<?php echo _('User')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      {key:"ip", label:"<?php echo _('IP Address')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    ,{key:"login_date", label:"<?php echo _('Login Date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			,{key:"logout_date", label:"<?php echo _('Logout Date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}	
			];
			       
	    this.dataSource0= new YAHOO.util.DataSource("ar_users.php?tipo=customer_user_login_history&tableid=0&customer_user=1&user_key="+Dom.get('user_key').value);
		//alert("ar_users.php?tipo=customer_user_login_history&tableid=0&user_key="+Dom.get('user_key').value)
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
		
		
		fields: ["user","ip","login_date","logout_date"]};


	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['site_user']['login_history']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['site_user']['login_history']['order']?>",
									 dir: "<?php echo $_SESSION['state']['site_user']['login_history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);


	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['site_user']['login_history']['f_field']?>',value:'<?php echo$_SESSION['state']['site_user']['login_history']['f_value']?>'};
	   
	   
	   
	

	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			      // {key:"user", label:"<?php echo _('User')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      {key:"ip", label:"<?php echo _('IP Address')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    ,{key:"login_date", label:"<?php echo _('Login Date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			,{key:"logout_date", label:"<?php echo _('Logout Date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}	
			];
			       
	    this.dataSource1 = new YAHOO.util.DataSource("ar_users.php?tipo=staff_user_login_history&user_key="+Dom.get('user_key').value+"&tableid=1");
	 
	 this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
		
		
		fields: ["user","ip","login_date","logout_date"]};


	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['site_user']['login_history']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site_user']['login_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site_user']['login_history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);


	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['site_user']['login_history']['f_field']?>',value:'<?php echo$_SESSION['state']['site_user']['login_history']['f_value']?>'};



	};
    });


function change_block(){
ids=['login_history','access','email'];
block_ids=['block_login_history','block_access','block_email'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=user-block_view&value='+this.id ,{});
}



function send_reset_password(){
Dom.setStyle('dialog_change_password_buttons','display','none')

Dom.setStyle('dialog_change_password_wait','display','')


 var data_to_update=new Object;
data_to_update['site_key']=Dom.get('site_key').value;
data_to_update['user_key']=Dom.get('user_key').value;
data_to_update['url'] ='http://'+ Dom.get('site_url').value + '/register.php';
 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));

var request='ar_register.php?tipo=send_reset_password&values='+jsonificated_values

	       
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	        
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				
			}
			else{
				
			}
			Dom.get('dialog_change_password_response_msg').innerHTML=r.msg;
			Dom.setStyle('dialog_change_password_response','display','')

Dom.setStyle('dialog_change_password_wait','display','none')
			
   		}
    });
} 

function show_dialog_change_password(){
region1 = Dom.getRegion('show_dialog_change_password'); 
    region2 = Dom.getRegion('dialog_change_password'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('dialog_change_password', pos);
Dom.setStyle('dialog_change_password_buttons','display','')
Dom.setStyle(['dialog_change_password_wait','dialog_change_password_response'],'display','none')

	dialog_change_password.show()

}

function show_dialog_set_password(){

region1 = Dom.getRegion('show_dialog_change_password'); 
    region2 = Dom.getRegion('dialog_set_password'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('dialog_set_password', pos);

	Dom.get('change_password_password1').value='';
	Dom.get('change_password_password2').value='';
		
 dialog_change_password.hide();
dialog_set_password.show();
Dom.get('change_password_password1').focus();
}


function submit_change_password(){

Dom.setStyle('dialog_set_password_main','display','');
var error=false;


	if(  Dom.get('change_password_password1').value=='' &&  Dom.get('change_password_password1').value==Dom.get('change_password_password2').value ){
		Dom.addClass(['change_password_password1','change_password_password2'],'error');
		error=true;
		Dom.setStyle('change_password_error_no_password','display','')

	}else{
	Dom.removeClass(['change_password_password1','change_password_password2'],'error');
	Dom.setStyle('change_password_error_no_password','display','none')

	}



	if(!error){
		if( Dom.get('change_password_password1').value!=Dom.get('change_password_password2').value ){
			Dom.addClass(['change_password_password1','change_password_password2'],'error');
			if(!error)
				Dom.setStyle('change_password_error_password_not_march','display','')
				error=true;

		}else{
			Dom.removeClass(['change_password_password1','change_password_password2'],'error');
			Dom.setStyle('change_password_error_password_not_march','display','none')

		}
	}
	if(!error){
		if(!error &&   Dom.get('change_password_password1').value.length<6){
			Dom.addClass(['change_password_password1'],'error');

			if(!error)
				Dom.setStyle('change_password_error_password_too_short','display','')

			
			error=true;
		}else{
			Dom.removeClass(['change_password_password1'],'error');
			Dom.setStyle('change_password_error_password_too_short','display','none')

		}
	}



	if(!error)
	change_password()
}
	

function clear_change_password_messages(){
			Dom.setStyle(['change_password_error_password_too_short','change_password_error_password_not_march','change_password_error_no_password'],'display','none')
Dom.removeClass(['change_password_password1','change_password_password2'],'error');
Dom.setStyle('tr_change_password_error_message','display','none');
}


function change_password_changed(e){
clear_change_password_messages();
  var key;     
     if(window.event)
         Key = window.event.keyCode; //IE
     else
         Key = e.which; //firefox     

     if (Key == 13){
	 submit_change_password();
	 
	 }
}


function change_password(o){
//alert('change');//return;
    var user_key=Dom.get('user_key').value;
	

    //var store_key=Dom.get('store_key').value;
    //var site_key=Dom.get('site_key').value;
	
	//ep1=AESEncryptCtr(sha256_digest(Dom.get('change_password_password1').value),Dom.get('epwcp1').value,256);
	ep1=sha256_digest(Dom.get('change_password_password1').value);
  //   var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 
    ep2=Dom.get('epwcp2').value;
	
//alert(ep1)	
	
//	njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
    //njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
//var url ='http://'+ window.location.host + window.location.pathname;

var data={'user_key':user_key,'ep1':ep1, 'ep2':ep2}

  var json_value = encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request=' ar_edit_users.php?tipo=change_passwd&user_id='+user_key+'&ep1='+ep1+'&ep2='+ep2;
//alert(request);//return;
  Dom.setStyle('tr_email_in_db_buttons','display','none');
    Dom.setStyle('tr_forgot_password_wait2','display','');

    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){

             


		   
		        
		        dialog_set_password.hide();
		       
		      
		    }else{
		    Dom.setStyle('tr_change_password_error_message','display','')
                Dom.get('change_password_error_message').innerHTML=r.msg;
		      
		    }
			

		},failure:function(o){
		  //  alert(o)
		}
	    
	    });



}


 function init(){

 ids=['login_history'];
 YAHOO.util.Event.addListener(ids, "click",change_block)

 init_search('users');
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 

dialog_change_password = new YAHOO.widget.Dialog("dialog_change_password", { visible : false,close:true,underlay: "none",draggable:false});
dialog_change_password.render();
Event.addListener("show_dialog_change_password", "click", show_dialog_change_password);
Event.addListener("close_dialog_change_password", "click", dialog_change_password.hide,dialog_change_password , true);
Event.addListener('send_reset_password', "click",send_reset_password);

 
 dialog_set_password = new YAHOO.widget.Dialog("dialog_set_password", { visible : false,close:true,underlay: "none",draggable:false});
dialog_set_password.render();
 
 Event.addListener('change_password', "click",show_dialog_set_password);
Event.addListener("cancel_change_password", "click", dialog_set_password.hide,dialog_set_password , true);
 Event.addListener('submit_change_password', "click",submit_change_password);
Event.addListener(['change_password_password1','change_password_password2'], "keyup",change_password_changed);
Event.addListener(['change_password_password1','change_password_password2'], "keydown",change_password_changed);

 
 }

 YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
