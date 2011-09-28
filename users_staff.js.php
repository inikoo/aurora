<?php
include_once('common.php')?>
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

var  store_name=new Object;
var  warehouse_name=new Object;

<?php
  // todo: only list active stores
    $s='';
$sql="select `Warehouse Key`,`Warehouse Code` from `Warehouse Dimension`  ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
    $s.="warehouse_name[".$row['Warehouse Key']."]='".$row['Warehouse Code']."';";
}
mysql_free_result($res);
print $s;

    $s='';
$sql="select `Store Key`,`Store Code` from `Store Dimension`  ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
    $s.="store_name[".$row['Store Key']."]='".$row['Store Code']."';";
}
mysql_free_result($res);
print $s;



?>

   var group=function(el, oRecord, oColumn, oData){
		//  var tmp = oData.split(',');
		if(oData==''){
		      el.innerHTML ='';
		      return;
		}
		var tmp=oData;
		var sgroups='';
		  for(x in tmp){
		 
		      if(sgroups=='')
			  sgroups=group_name[tmp[x]];
		      else
			  sgroups=sgroups+', '+group_name[tmp[x]]
			      }
		el.innerHTML =sgroups;
		
	    };
	    
	    

     var warehouses=function(el, oRecord, oColumn, oData){
		//  var tmp = oData.split(',');
		if(oData==''){
		      el.innerHTML ='';
		      return;
		}
		var tmp=oData;
		var swarehouses='';
		  for(x in tmp){
		      if(swarehouses=='')
			  swarehouses=warehouse_name[tmp[x]];
		      else
			  swarehouses=swarehouses+', '+warehouse_name[tmp[x]]
			      }
		el.innerHTML =swarehouses;
		
	       };



var active=function(el, oRecord, oColumn, oData){                                                                                                                                                  
	
	if(oData=='No')                                                                                                                                                                                 
	    el.innerHTML ='<img src="art/icons/status_offline.png" />';                                                                                                                                
	else                                                                                                                                                                                           
	    el.innerHTML = '<img src="art/icons/status_online.png" />';                                                                                                                                
            };   
            
          var stores=function(el, oRecord, oColumn, oData){
		//  var tmp = oData.split(',');
		if(oData==''){
		      el.innerHTML ='';
		      return;
		}
		var tmp=oData;
		
		var sstores='';
		  for(x in tmp){
		      if(sstores=='')
			  sstores=store_name[tmp[x]];
		      else
			  sstores=sstores+', '+store_name[tmp[x]]
			      }
		el.innerHTML =sstores;
		
	       };  
            
            
            

   
    
    
    YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    
	    //START OF THE TABLE=========================================================================================================================
	    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	 
	   
	    var ColumnDefs = [
			
			
			      {key:"isactive",label:"<?php echo _('Active')?>" ,className:'aright',width:45  }
			      , {key:"alias", label:"<?php echo _('Login')?>",width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"name", label:"<?php echo _('Staff Name')?>",width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			  	,{key:"logins", label:"<?php echo _('Logins')?>",width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			  	,{key:"last_login", label:"<?php echo _('Last Login')?>",width:160,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"fail_logins", label:"<?php echo _('Fail Logins')?>",width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			  	,{key:"fail_last_login", label:"<?php echo _('Last Fail Login')?>",width:160,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			  //  ,{key:"groups",formatter:group,label:"<?php echo _('Groups')?>",className:"aleft"}
			    //   ,{key:"stores",formatter:stores, label:"<?php echo _('Stores')?>",sortable:true,className:"aleft"}
			    //   ,{key:"warehouses",formatter:warehouses, label:"<?php echo _('Warehouses')?>",sortable:true,className:"aleft"}


			
			
			];
			       
	    this.dataSource0 = new YAHOO.util.DataSource("ar_users.php?tipo=staff_users&tableid=0");
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
		
		
		fields: [
			 "id","isactive","handle","name","email","lang","groups","tipo","active","alias","stores","warehouses","logins","last_login","fail_logins","fail_last_login"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['users']['staff']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['users']['staff']['order']?>",
									 dir: "<?php echo$_SESSION['state']['users']['staff']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);


	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['users']['staff']['f_field']?>',value:'<?php echo$_SESSION['state']['users']['staff']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
	

	    

	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			      {key:"id", label:"<?php echo _('Id')?>", width:40,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
			      {key:"name", label:"<?php echo _('Group')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
			      {key:"users", label:"<?php echo _('Users')?>", sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_users.php?tipo=groups&tableid=1");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
		       "id","name","users"
			 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								    //    ,paginator : new YAHOO.widget.Paginator({
// 									      rowsPerPage    : <?php //echo$_SESSION['state']['users']['groups']['nr']?>,containers : 'paginator', 
//  									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
// 									      previousPageLinkLabel : "<",
//  									      nextPageLinkLabel : ">",
//  									      firstPageLinkLabel :"<<",
//  									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
// 									      ,template : "{FirstPageLink}{PreviousPageLink}<strong>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
// 									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['users']['groups']['order']?>",
									 dir: "<?php echo$_SESSION['state']['users']['groups']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    // this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    // this.table1.filter={key:'<?php echo$_SESSION['state']['users']['staff']['f_field']?>',value:'<?php echo$_SESSION['state']['users']['staff']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)


/* --------------------------------------------------------------------------------------------------------------------------------- */
	    var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;


	   
	    var ColumnDefs = [
			
			
		
			       {key:"user", label:"<?php echo _('User')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"ip", label:"<?php echo _('IP Address')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    ,{key:"login_date", label:"<?php echo _('Login Date')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			,{key:"logout_date", label:"<?php echo _('Logout Date')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}	
				 
			      
	     


			
			
			];
			       
	    this.dataSource2 = new YAHOO.util.DataSource("ar_users.php?tipo=staff_login_history&tableid=2");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
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


	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['users']['login_history']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['users']['login_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['users']['login_history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['users']['login_history']['f_field']?>',value:'<?php echo$_SESSION['state']['users']['login_history']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
	

/* --------------------------------------------------------------------------------------------------------------------------------- */
	};
    });




 function init(){
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
