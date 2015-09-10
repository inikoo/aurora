<?php
include_once('common.php');
?>

    
    YAHOO.namespace ("supplier"); 
YAHOO.supplier.views = new Array();
YAHOO.supplier.views[0]=<?php echo$_SESSION['tables']['po_item'][4][2]?>;
    YAHOO.supplier.po_id=<?php echo$_REQUEST['po_id']?>;
YAHOO.util.Event.addListener(window, "load", function() {
	YAHOO.supplier.XHR_JSON = new function() {

		
		this.description=  function(el, oRecord, oColumn, oData) {
		    el.innerHTML = '(1 '+oRecord.getData("units_tipo")+') '+oData+' @'+oRecord.getData("price");
		};
		this.familyLink=  function(el, oRecord, oColumn, oData) {
		    var url="asset_family.php?id="+oRecord.getData("group_id");
		    el.innerHTML = oData.link(url);
		};

		var tableid=1; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		

		this.deleteuser=function(oEditor, oSelf){
		    
		    var elContainer = oEditor.container;
		    elContainer.innerHTML='<?php echo _('Are you sure you want to desassociate this product?')?>';
		    oSelf._oCellEditor.value=1;
		    
		}
		


		var SuppliersColumnDefs1 = [
					   
					    {key:"stock", label:"<?php echo _('Stock')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}<?php echo($_SESSION['tables']['po_item'][4][3]==0?'':',hidden:true')?>}
					    ,{key:"code", label:"<?php echo _('Our Code')?>", width:<?php echo($_SESSION['tables']['po_item'][4][3]==1?90:90)?>,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					    ,{key:"sup_code", label:"<?php echo _('S Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"description", label:"<?php echo _('Description')?>",width:320, formatter:this.description,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"ordered", label:"<?php echo _('Ordered')?>",width:95, sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor:'textbox'
					     <?php echo($_SESSION['tables']['po_item'][4][3]==2?',hidden:true':'')?>}
					    ,{key:"received", label:"<?php echo _('Received')?>",width:70, sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor:'textbox'
					     <?php echo($_SESSION['tables']['po_item'][4][3]!=2?',hidden:true':'')?>}
					    ,{key:"damage", label:"<?php echo _('Damaged')?>",width:60, sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor:'textbox'
					      <?php echo(($_SESSION['tables']['po_item'][4][3]!=2 or $_SESSION['tables']['po_item'][4][3]!=1)?',hidden:true':'')?>}
					    ,{key:"eprice", label:"<?php echo _('Cost')?>",width:95, sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}


					   ];
		
		this.SuppliersDataSource1 = new YAHOO.util.DataSource("ar_suppliers.php?tipo=po&tid="+tableid);
		this.SuppliersDataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.SuppliersDataSource1.connXhrMode = "queueRequests";
		this.SuppliersDataSource1.responseSchema = {
		    resultsList: "resultset.data", 
		    totalRecords: 'resultset.total_records',
		fields: [
			 "id","family_id","fam","code","description","stock","price","price_outer","delete","p2s_id","sup_code","group_id","units_tipo","units_tipo_id","ordered","eprice","damage","received"
			 ]};
		
		this.SuppliersDataSource1.doBeforeCallback = mydoBeforeCallback;
		
		
		
    
//this.SuppliersDataTable.saveCellEditor =this.SuppliersDataTable.mySaveEditor;
		//	    this.SuppliersDataTable.subscribe("cellMouseoverEvent", this.highlightEditableCell);
		//this.SuppliersDataTable.subscribe("cellMouseoutEvent", this.SuppliersDataTable.onEventUnhighlightCell);
		//this.SuppliersDataTable.subscribe("cellClickEvent", this.SuppliersDataTable.onEventShowCellEditor);
	    
		
		
	 
		this.SuppliersDataTable1 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs1,this.SuppliersDataSource1, 
								     {renderLoopSize: 50
								      , sortedBy: {key:"<?php echo$_SESSION['tables']['product_withsupplier'][0]?>", dir:"<?php echo$_SESSION['tables']['product_withsupplier'][1]?>"}
								     });
	





this.SuppliersDataTable1.mySaveEditor = function (){
var Dom   = YAHOO.util.Dom;

    if(this._oCellEditor.isActive) {



	var newData = this._oCellEditor.value;

	// Copy the data to pass to the event

	if(this._oCellEditor.record.getData(this._oCellEditor.column.key)==null)
	    var oldData='';
	else
	    var oldData = YAHOO.widget.DataTable._cloneObject(this._oCellEditor.record.getData(this._oCellEditor.column.key));



	var request='ar_suppliers.php?tipo=update_poitem&key='+this._oCellEditor.column.key+'&qty=' + escape(newData) 
	+'&p2s_id=' + escape(this._oCellEditor.record.getData("p2s_id"))
	+'&units_tipo=' + escape(this._oCellEditor.record.getData("units_tipo_id"));
	//	alert(request);
	YAHOO.util.Connect.asyncRequest(
					'POST',
					request,{
					    success: function (o) {
						//alert(o.responseText)
						var r =  YAHOO.lang.JSON.parse(o.responseText);

						if (r.state == 200) {

						    // Update the Record


						    this._oRecordSet.updateRecordValue(this._oCellEditor.record, this._oCellEditor.column.key, this._oCellEditor.value);

						    
						    if(this._oCellEditor.column.key=='ordered' || this._oCellEditor.column.key=='received'){
							Dom.get('goods').innerHTML=r.gprice;
							Dom.get('total').innerHTML=r.tprice;
							Dom.get('distinct_products').innerHTML=r.items;
							this._oRecordSet.updateRecordValue(this._oCellEditor.record, 'eprice', r.eprice);
						    }

						    var tipo=<?php echo$_SESSION['tables']['po_item'][4][3]?>;
						    if(this._oCellEditor.column.key=='received' && tipo==1){

							Dom.get('v_total').value=r.total_int;
							Dom.get('v_total_c').value=r.total_decimal;

						    }


						    this.formatCell(this._oCellEditor.cell.firstChild);
						    this.render();

						    

						    
						    
						    
							    }else{
						    //alert(o.responseText)
						}
							    // Clear out the Cell Editor
							    this.resetCellEditor();
							    
							},
							failure: function(o) {alert("error")},
							scope: this
						    }
						    ); 


	}
}

	    // Set up editing flow
    this.highlightEditableCell = function(oArgs) {
	
	var elCell = oArgs.target;
	
	if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
	    this.highlightCell(elCell);
	}
    };






		this.SuppliersDataTable1.saveCellEditor =this.SuppliersDataTable1.mySaveEditor;
		this.SuppliersDataTable1.subscribe("cellMouseoverEvent", this.highlightEditableCell);
		this.SuppliersDataTable1.subscribe("cellMouseoutEvent", this.SuppliersDataTable1.onEventUnhighlightCell);
		this.SuppliersDataTable1.subscribe("cellClickEvent", this.SuppliersDataTable1.onEventShowCellEditor);



	
		this.SuppliersDataTable1.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
		this.SuppliersDataTable1.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.SuppliersDataTable1}  } ]);
		this.SuppliersDataTable1.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.SuppliersDataTable1}  } ]);
		this.SuppliersDataTable1.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.SuppliersDataTable1}  } ]);
		this.SuppliersDataTable1.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.SuppliersDataTable1}  } ]);
		this.SuppliersDataTable1.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.SuppliersDataTable1}  } ]);
		this.SuppliersDataTable1.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.SuppliersDataTable1}  } ]);
		YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.SuppliersDataTable1.paginatorMenu.show, null, this.SuppliersDataTable1.paginatorMenu);
		this.SuppliersDataTable1.paginatorMenu.render(document.body);
		this.SuppliersDataTable1.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
		this.SuppliersDataTable1.filterMenu.addItems([{ text: "<?php echo _('Supplier Code')?>", onclick:{fn:changeFilter,obj:{col:'code',text:"<?php echo _('Family Code')?>"},scope:this.SuppliersDataTable1}  } ]);
		this.SuppliersDataTable1.filterMenu.addItems([{ text: "<?php echo _('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?php echo _('Description')?>"},scope:this.SuppliersDataTable1}  } ]);
		YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.SuppliersDataTable1.filterMenu.show, null, this.SuppliersDataTable1.filterMenu);
		this.SuppliersDataTable1.filterMenu.render(document.body);
		
		this.SuppliersDataTable1.myreload=reload;
		this.SuppliersDataTable1.sortColumn = mysort;
		this.SuppliersDataTable1.id=tableid;
		this.SuppliersDataTable1.editmode=false;
		this.SuppliersDataTable1.subscribe("initEvent", dataReturn); 
		YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.SuppliersDataTable1); 
		YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.SuppliersDataTable1); 
		YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.SuppliersDataTable1); 
		YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.SuppliersDataTable1); 
		











	    
	};
    });




function init(){
 var Event = YAHOO.util.Event;
 var Dom   = YAHOO.util.Dom;


 
 function mygetTerms2(query) {YAHOO.supplier.XHR_JSON.SuppliersDataTable1.myreload();};
 var oACDS2 = new YAHOO.widget.DS_JSFunction(mygetTerms2);
 oACDS2.queryMatchContains = true;

 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input1","filtercontainer1", oACDS2);
 oAutoComp2.minQueryLength = 0; 
 
 YAHOO.supplier.changeview = function(e,view) {

     var Dom   = YAHOO.util.Dom;
        if(YAHOO.supplier.views[view]==0){
		  Dom.get('but_view'+view).className='selected';

		  YAHOO.supplier.views[view]=1;
		  YAHOO.supplier.XHR_JSON.SuppliersDataTable1.recordsperPage=25;

	      }else{

		  Dom.get('but_view'+view).className='';
		  YAHOO.supplier.XHR_JSON.SuppliersDataTable1.recordsperPage=500;

		YAHOO.supplier.views[view]=0;	

		
	      }
     YAHOO.supplier.XHR_JSON.SuppliersDataTable1.offset=0;
     YAHOO.supplier.XHR_JSON.SuppliersDataTable1.myreload('&view_all='+YAHOO.supplier.views[view]);

     
 }
 
Event.addListener(Dom.get('but_view0'),"click",YAHOO.supplier.changeview,0);




var receiving= function(){
    
    Dom.get('row_public_id').style.display='';
    Dom.get('public_id').style.display='none';
    Dom.get('edit_public_id').style.display='';
    Dom.get('row_date_received').style.display='';
    Dom.get('date_received').style.display='none';
    Dom.get('edit_date_received').style.display='';

    Dom.get('received_by').style.display='none';
    Dom.get('edit_received_by').style.display='';
    Dom.get('row_received_by').style.display='';

 Dom.get('checked_by').style.display='none';
    Dom.get('edit_checked_by').style.display='';
    Dom.get('row_checked_by').style.display='';

    Dom.get('row_invoice_date').style.display='';
    Dom.get('invoice_date').style.display='none';
    Dom.get('edit_invoice_date').style.display='';

    Dom.get('total').style.display='none';
    Dom.get('edit_total').style.display='';


    Dom.get('shipping').style.display='none';
    Dom.get('edit_shipping').style.display='';
    Dom.get('vat').style.display='none';
    Dom.get('edit_vat').style.display='';
    Dom.get('other_charge').style.display='';
    Dom.get('other').style.display='none';
    Dom.get('edit_other').style.display='';
    

    YAHOO.supplier.XHR_JSON.SuppliersDataTable1.showColumn('received');
    YAHOO.supplier.XHR_JSON.SuppliersDataTable1.showColumn('damage');
    YAHOO.supplier.XHR_JSON.SuppliersDataTable1.getColumn('ordered').editor="";
    
}


YAHOO.util.Event.addListener("receiving", "click", receiving);






 var handleSubmit = function() {this.submit();};
 var handleCancel = function() {this.cancel();};
 var handleSuccess = function(o) {};
 var handleFailure = function(o) {alert("Submission failed: " + o.status);};
 
 
 YAHOO.supplier.dialog1  = new YAHOO.widget.Dialog("submiting_form",
						   { width : "20em",
						     fixedcenter : true,
						     visible : false, 
						     constraintoviewport : true,
						     postmethod:"form",
						     buttons : [ { text:"<?php echo _('Submit')?>", handler:handleSubmit, isDefault:true },{ text:"<?php echo _('Cancel')?>", handler:handleCancel } ]

						   });
 
 YAHOO.supplier.dialog1.callback = { success: handleSuccess,failure: handleFailure };
 YAHOO.supplier.dialog1.render();
YAHOO.supplier.dialog2  = new YAHOO.widget.Dialog("deleting_form",
						   { width : "20em",
						     fixedcenter : true,
						     visible : false, 
						     constraintoviewport : true,
						     postmethod:"form"
						   });
 
 YAHOO.supplier.dialog2.callback = { success: handleSuccess,failure: handleFailure };
 YAHOO.supplier.dialog2.render();
YAHOO.supplier.dialog3  = new YAHOO.widget.Dialog("returning_form",
						   { width : "20em",
						     fixedcenter : true,
						     visible : false, 
						     constraintoviewport : true,
						     postmethod:"form"
						   });
 
 YAHOO.supplier.dialog3.callback = { success: handleSuccess,failure: handleFailure };
 YAHOO.supplier.dialog3.render();
YAHOO.supplier.dialog4  = new YAHOO.widget.Dialog("canceling_form",
						   { width : "20em",
						     fixedcenter : true,
						     visible : false, 
						     constraintoviewport : true,
						     postmethod:"form"
						   });
 
 YAHOO.supplier.dialog4.callback = { success: handleSuccess,failure: handleFailure };
 YAHOO.supplier.dialog4.render();

YAHOO.supplier.dialog5  = new YAHOO.widget.Dialog("receiving_form",
						   { width : "20em",
						     fixedcenter : true,
						     visible : false, 
						     constraintoviewport : true,
						     postmethod:"form"
						   });
 
 YAHOO.supplier.dialog5.callback = { success: handleSuccess,failure: handleFailure };
 YAHOO.supplier.dialog5.render();



 YAHOO.util.Event.addListener("deleting", "click", YAHOO.supplier.dialog2.show, YAHOO.supplier.dialog2, true);
 YAHOO.supplier.deletepo = function(e) {YAHOO.supplier.dialog2.submit()}
 Event.addListener(Dom.get('delete_po'),"click",YAHOO.supplier.deletepo);
 
YAHOO.util.Event.addListener("canceling", "click", YAHOO.supplier.dialog4.show, YAHOO.supplier.dialog4, true);
YAHOO.supplier.cancelpo = function(e) {YAHOO.supplier.dialog4.submit()}
Event.addListener(Dom.get('cancel_po'),"click",YAHOO.supplier.cancelpo);

YAHOO.util.Event.addListener("processing", "click", YAHOO.supplier.dialog5.show, YAHOO.supplier.dialog5, true);
YAHOO.supplier.processingpo = function(e) {YAHOO.supplier.dialog5.submit()}
Event.addListener(Dom.get('process_po'),"click",YAHOO.supplier.processingpo);








 YAHOO.util.Event.addListener("submiting", "click", YAHOO.supplier.dialog1.show, YAHOO.supplier.dialog1, true);
 
//YAHOO.supplier.submitpo = function(e) {YAHOO.supplier.dialog1.submit()}
 // Event.addListener(Dom.get('delete_po'),"click",YAHOO.supplier.submitpo);


YAHOO.supplier.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );

 YAHOO.supplier.cal1.update=updateCal;

 YAHOO.supplier.cal1.container_id='v_calpop1';
 YAHOO.supplier.cal1.render();
 YAHOO.supplier.cal1.update();
 YAHOO.supplier.cal1.selectEvent.subscribe(CalhandleSelect, YAHOO.supplier.cal1, true); 
 YAHOO.util.Event.addListener("calpop1", "click", YAHOO.supplier.cal1.show, YAHOO.supplier.cal1, true);




 
 


 



// YAHOO.supplier.cal3 = new YAHOO.widget.Calendar("cal3","cal3Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
//  YAHOO.supplier.cal3.update=updateCal;
//  YAHOO.supplier.cal3.id='v_calpop3';

//  YAHOO.supplier.cal3.render();
//  YAHOO.supplier.cal3.update();

//  YAHOO.supplier.cal3.selectEvent.subscribe(CalhandleSelect, YAHOO.supplier.cal3, true); 
//  YAHOO.util.Event.addListener("calpop3", "click", YAHOO.supplier.cal3.show, YAHOO.supplier.cal3, true);




     
     
     
     var onchangepo = function(e,o){
     if(o){
	 key=o.key;
	 value=o.value;
     }else{
     var key=this.name;
     if(key=='shipping'){
	 var value=Dom.get('v_shipping').value+'.'+Dom.get('v_shipping_c').value;
     }else if(key=='vat'){
	 var value=Dom.get('v_vat').value+'.'+Dom.get('v_vat_c').value;
     }else if(key=='other'){
	 var value=Dom.get('v_other').value+'.'+Dom.get('v_other_c').value;
     }else{
	 var value=this.value;
     }
     }


     var request='ar_suppliers.php?tipo=update_po&key='+key+'&qty=' + escape(value) 
     +'&po_id=' + escape(YAHOO.supplier.po_id)


     
     //      alert(request);
     YAHOO.util.Connect.asyncRequest(
				     'POST',
				     request,{
					 success: function (o) {

					     var r =  YAHOO.lang.JSON.parse(o.responseText);
					     
					     if (r.state == 200) {
						 
						 if(key=='shipping' || key=='other' || key=='vat'){
						     Dom.get('v_total').value=r.total_int;
						     Dom.get('v_total_c').value=r.total_decimal;
						 }
						 
					     }


					     
					 },
					 failure: function(o) {alert("error")},
					 scope: this
				     }
				     ); 
 }


 var ids = ["v_shipping", "v_shipping_c", "v_vat","v_vat_c","v_other","v_other_c","v_invoice_number"]; 
 YAHOO.util.Event.addListener(ids, "keyup", onchangepo,false);
var ids = ["v_checked_by", "v_received_by"]; 
 YAHOO.util.Event.addListener(ids, "change", onchangepo,false);

function myCalhandleSelect(type,args,obj) {
		var dates = args[0];
		var date = dates[0];
		var year = date[0], month = date[1], day = date[2];
		if(month<10)
		    month='0'+month;
		if(day<10)
		    day='0'+day;

		var txtDate1 = document.getElementById(this.container_id);
		txtDate1.value = day + "-" + month + "-" + year;
		

		onchangepo('',{key:this.container_name,value:txtDate1.value});

		this.hide();
    }





 YAHOO.supplier.cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?php echo _('Choose a date')?>:", close:true } );

 YAHOO.supplier.cal2.update=updateCal;
 YAHOO.supplier.cal2.container_id='v_invoice_date';
 YAHOO.supplier.cal2.container_name='invoice_date';

 YAHOO.supplier.cal2.render();
 YAHOO.supplier.cal2.update();
 YAHOO.supplier.cal2.selectEvent.subscribe(myCalhandleSelect, YAHOO.supplier.cal2, true); 
 YAHOO.util.Event.addListener("v_invoice_date", "click", YAHOO.supplier.cal2.show, YAHOO.supplier.cal2, true);






 var updatetime = function(){
     var date=Dom.get('v_date_received').value;
     var time=Dom.get('v_time_received').value;
     if(time!='' && date!=''){
	 onchangepo('',{key:'time_received',value:date+' '+time+':00'});
     }

 }

 var tp = new TimePicker('time2_picker', 'v_time_received', 'timepop3', {format24:true,onClose:updatetime})
     

function myCalhandleSelectDateTime(type,args,obj) {
		var dates = args[0];
		var date = dates[0];
		var year = date[0], month = date[1], day = date[2];
		if(month<10)
		    month='0'+month;
		if(day<10)
		    day='0'+day;


		var date = day + "-" + month + "-" + year;
		
	 


	 var time=Dom.get('v_time_received').value;
	 if(time=='')
	     time='13:00';


	 onchangepo('',{key:'time_received',value:date+' '+time+':00'});
	 

	 Dom.get('v_date_received').value=date;
	 Dom.get('v_time_received').value=time;


		this.hide();
    }





 YAHOO.supplier.cal3 = new YAHOO.widget.Calendar("cal3","cal3Container", { title:"<?php echo _('Choose a date')?>:", close:true } );

 YAHOO.supplier.cal3.update=updateCal;
 YAHOO.supplier.cal3.container_id='v_date_received';
 YAHOO.supplier.cal3.container_name='date_received';

 YAHOO.supplier.cal3.render();
 YAHOO.supplier.cal3.update();

 YAHOO.supplier.cal3.selectEvent.subscribe(myCalhandleSelectDateTime, YAHOO.supplier.cal3, true); 
 YAHOO.util.Event.addListener("v_date_received", "click", YAHOO.supplier.cal3.show, YAHOO.supplier.cal3, true);





}

YAHOO.util.Event.onDOMReady(init);

