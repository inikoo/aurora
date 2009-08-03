<?phpinclude_once('common.php')?>
var Dom   = YAHOO.util.Dom;
var add_user_dialog_others;
var add_user_dialog;



var  group_name=new Object;

<?php
    $g='';
foreach($_group as $key=>$value){
    $g.="group_name[$key]='$value';";
}
print $g;
?>
    
    
    
    YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    this.formatLang=  function(el, oRecord, oColumn, oData) {
		el.innerHTML = '<img src="art/flags/'+oRecord.getData("countrycode")+'.gif" alt="'+oRecord.getData("country")+'"> '+oData;
	    }
		this.userLink=  function(el, oRecord, oColumn, oData) {
		var url="user.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }	

	     //START OF THE TABLE=========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

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

	    var active=function(el, oRecord, oColumn, oData){

		if(oData=='0')
		    el.innerHTML ='<img src="art/icons/status_offline.png" />';
		else
		    el.innerHTML = '<img src="art/icons/status_online.png" />';
	    };

	    var edit_active=function (callback, newValue) {
		
		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable();
		//		for( x in record)
		user_id=record.getData('id');
		var request='ar_users.php?tipo=edit_user&user_id='+escape(user_id)+'&key=' + column.key + '&newValue=' + escape(newValue) + '&oldValue=' + escape(oldValue)
		//alert(request);
		YAHOO.util.Connect.asyncRequest(
						'POST',
						request, {
						    success:function(o) {
							//	alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {
							    callback(true, r.data);
							} else {
							    alert(r.msg);
							    callback();
							}
						    },
						    failure:function(o) {
							alert(o.statusText);
							callback();
						    },
						    scope:this
						}
					        
						);                                              
	    }
		
	  var edit_group=function (callback, newValue) {
		
		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable();
		//		for( x in record)
		user_id=record.getData('id');
		var request='ar_users.php?tipo=edit_user&user_id='+escape(user_id)+'&key=' + column.key + '&newValue=' + escape(newValue) + '&oldValue=' + escape(oldValue)
		//	alert(request);
		YAHOO.util.Connect.asyncRequest(
						'POST',
						request, {
						    success:function(o) {
							//alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {
							    callback(true, r.data);
							    var table=tables['table1'];
							    var datasource=tables['dataSource1'];
							    var request='';
							    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
							    
							} else {
							    alert(r.msg);
							    callback();
							}
						    },
						    failure:function(o) {
							alert(o.statusText);
							callback();
						    },
						    scope:this
						}
					        
						);                                              
	    }	
	    var ColumnDefs = [
			         {key:"delete",label:"" ,width:16 ,hidden:true},
				 {key:"password",label:"" ,width:16 },
				 //	 {key:"passwordmail",label:"" ,width:16 },
				 {key:"isactive",formatter:active,label:"" ,width:16 ,editor: new YAHOO.widget.RadioCellEditor({radioOptions:[{label:"yes", value:"1"}, {label:"no", value:"0"}],defaultValue:"0",asyncSubmitter:edit_active })  },
				 

				 {key:"tipo", label:"<?php echo _('Type')?>",width:35,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				 {key:"handle", label:"<?php echo _('Handle')?>",width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				 {key:"name", label:"<?php echo _('Name')?>",sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				 {key:"email", label:"<?php echo _('Email')?>",sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				 {key:"lang", label:"<?php echo _('Language')?>",sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},},
				 {key:"groups",formatter:group,label:"<?php echo _('Groups')?>",className:"aleft" , editor: new YAHOO.widget.CheckboxCellEditor({
					     asyncSubmitter:edit_group,checkboxOptions:[
							      <?php
							      $g='';
							      foreach($_group as $key=>$value){
								  $g.="{label:'$value<br>', value:$key},";
							      }
							      preg_replace('/,$/','',$g);
							      print $g;
							      ?>
							      ]
					 })}
			      ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_users.php?tipo=users&tableid=0");
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
			 "id","isactive","handle","name","email","lang","groups","password","delete","tipo","passwordmail"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['users']['user_list']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['users']['user_list']['order']?>",
									 dir: "<?php echo$_SESSION['state']['users']['user_list']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);


	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['users']['user_list']['f_field']?>',value:'<?php echo$_SESSION['state']['users']['user_list']['f_value']?>'};
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
								     // sortedBy: {key:"<?php echo$_SESSION['tables']['customers_list'][0]?>", dir:"<?php echo$_SESSION['tables']['customers_list'][1]?>"},
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								    //    ,paginator : new YAHOO.widget.Paginator({
// 									      rowsPerPage    : <?php echo$_SESSION['state']['users']['groups']['nr']?>,containers : 'paginator', 
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
	    // this.table1.filter={key:'<?php echo$_SESSION['state']['users']['user_list']['f_field']?>',value:'<?php echo$_SESSION['state']['users']['user_list']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)



	};
    });




 function init(){
 var Dom   = YAHOO.util.Dom;
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
	 var oMenu = new YAHOO.widget.Menu("rppmenu0", { context:["rtext_rpp0","tl", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp0", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu0", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });