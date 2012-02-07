<?php
include_once('common.php');

?>
var Dom   = YAHOO.util.Dom;
var add_user_dialog_others;
var add_user_dialog;


var  group_name=new Object;

<?php

$s='';
$sql="select * from `User Group Dimension`  ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
    $s.="group_name[".$row['User Group Key']."]='".$row['User Group Name']."';";
}
mysql_free_result($res);
print $s;

   
?>
    
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


function forgot_password()
{

     var request='ar_users.php?tipo=forgot_password';
  alert(request)
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		        if(r.result=='send'){
					alert(r.result);
    		    }else if(r.result=='handle_not_found'){
					alert(r.result);	
		        }else{
					alert(r.result);
		        }
		    }else{
				alert(r.result);
		    }
			

		},failure:function(o){
		    alert(o)
		}
	    
	    });
}
function forget_password(o, email){

//   var pos = Dom.getXY(o);
//   
//   pos[0]=pos[0]+500
// 
// 
//     Dom.setXY('password_msg', pos);
    var store_key=Dom.get('store_key').value;
    var site_key=1;//Dom.get('site_key').value;
// email=this.getAttribute('email')
var customer_id=Dom.get('customer_id').value;
var url ='http://'+ window.location.host + window.location.pathname;
var request='ar_register.php?tipo=forgot_password&customer_key=' + customer_id +'&store_key='+store_key + '&url='+url + '&email='+email + '&site_key='+site_key
// Dom.get('password_msg').innerHTML='Sending';
// Dom.get('password_msg').style.display='';
	            alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	            //alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				alert('sent');
				//Dom.get('password_msg').innerHTML="Email Sent"
				//Dom.get('password_msg').style.display='';
				//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;

            }
			else{
				alert('error');
				//Dom.get('password_msg').innerHTML=r.msg;
				//Dom.get('password_msg').style.display='';
			}
   			}
    });
} 

 function init(){

Event.addListener('forgot_password', "click",forgot_password);
 ids=['login_history','access','email'];
 YAHOO.util.Event.addListener(ids, "click",change_block)

 init_search('users');
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 


//       add_user_dialog = new YAHOO.widget.Menu("add_user_dialog", {context:["add_user","tr", "br","beforeShow"]  });
//       add_user_dialog.render();
//       add_user_dialog.subscribe("show", add_user_dialog.focus);
//       YAHOO.util.Event.addListener("add_user", "click", add_user_dialog.show, null, add_user_dialog); 

//       add_user_dialog_others = new YAHOO.widget.Menu("add_user_other", {context:["add_user","tr", "br","beforeShow"]  });
//       add_user_dialog_others.render();
//      add_user_dialog_others.subscribe("show", add_user_dialog_others.focus);
//       add_user_dialog_staff = new YAHOO.widget.Menu("add_user_staff", {context:["add_user","tr", "br","beforeShow"]  });
//       add_user_dialog_staff.render();
//       add_user_dialog_staff.subscribe("show", add_user_dialog_staff.focus);
      
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
