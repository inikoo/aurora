<?
include_once('../common.php');

?>

    
    YAHOO.namespace("supplier"); 
YAHOO.supplier.views = new Array();
YAHOO.supplier.views[0]=<?=$_SESSION['views']['supplier_blocks'][0]?>;
YAHOO.supplier.views[1]=<?=$_SESSION['views']['supplier_blocks'][1]?>;
YAHOO.supplier.contact_id=<?=$_REQUEST['contact_id']?>;
YAHOO.supplier.supplier_id=<?=$_REQUEST['supplier_id']?>;
<?=$_SESSION['tmp']?>;

var lock_ar = new Array();
var pid=0;



YAHOO.util.Event.addListener(window, "load", function() {
	YAHOO.supplier.XHR_JSON = new function() {
		

		this.productLink=  function(el, oRecord, oColumn, oData) {
		    var url="asset_product.php?id="+oRecord.getData("id");
		    el.innerHTML = oData.link(url);
		};
		this.familyLink=  function(el, oRecord, oColumn, oData) {
		    var url="asset_family.php?id="+oRecord.getData("group_id");
		    el.innerHTML = oData.link(url);
		};

		var tableid=2; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		

		this.deleteuser=function(oEditor, oSelf){
		    
		    var elContainer = oEditor.container;
		    elContainer.innerHTML='<?=_('Are you sure you want to desassociate this product?')?>';
		    oSelf._oCellEditor.value=1;
		    
		}
		
		    <?if($_REQUEST['prods']>0){?>
		    


		var SuppliersColumnDefs2 = [
					   
					   {key:"delete",label:"" ,width:16,hidden:true }
				   
					   ,{key:"code", label:"<?=_('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}


					   ,{key:"fam", label:"<?=_('Family')?>",width:100,formatter:this.familyLink, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   
					   
					   ,{key:"description", label:"<?=_('Description')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"stock", label:"<?=_('Stock')?>",width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"sup_code", label:"<?=_('S Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"price_unit", label:"<?=_('UPC')?>",width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					   

					   ];
		
		this.SuppliersDataSource2 = new YAHOO.util.DataSource("ar_assets.php?tipo=withsupplier&tid="+tableid);
		this.SuppliersDataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.SuppliersDataSource2.connXhrMode = "queueRequests";
		this.SuppliersDataSource2.responseSchema = {
		    resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","family_id","fam","code","description","stock","price_unit","price_outer","delete","p2s_id","sup_code","group_id"
			 ]};
		
		this.SuppliersDataSource2.doBeforeCallback = mydoBeforeCallback;
		
		
		
    
//this.SuppliersDataTable.saveCellEditor =this.SuppliersDataTable.mySaveEditor;
		//	    this.SuppliersDataTable.subscribe("cellMouseoverEvent", this.highlightEditableCell);
		//this.SuppliersDataTable.subscribe("cellMouseoutEvent", this.SuppliersDataTable.onEventUnhighlightCell);
		//this.SuppliersDataTable.subscribe("cellClickEvent", this.SuppliersDataTable.onEventShowCellEditor);
	    
		
		
	 
		this.SuppliersDataTable2 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs2,this.SuppliersDataSource2, 
								     {renderLoopSize: 50
								      , sortedBy: {key:"<?=$_SESSION['tables']['product_withsupplier'][0]?>", dir:"<?=$_SESSION['tables']['product_withsupplier'][1]?>"}
								     });
	





this.SuppliersDataTable2.mySaveEditor = function (){


    if(this._oCellEditor.isActive) {



	var newData = this._oCellEditor.value;

	// Copy the data to pass to the event

	if(this._oCellEditor.record.getData(this._oCellEditor.column.key)==null)
	    var oldData='';
	else
	    var oldData = YAHOO.widget.DataTable._cloneObject(this._oCellEditor.record.getData(this._oCellEditor.column.key));



	var request='ar_suppliers.php?tipo=updateone_p2s&key='+this._oCellEditor.column.getKey()+'&value=' + escape(newData) +'&id=' + escape(this._oCellEditor.record.getData("p2s_id"));
	//	alert(request);
	YAHOO.util.Connect.asyncRequest(
					'POST',
					request,{
					    success: function (o) {
						//	alert(o.responseText);
						var r =  YAHOO.lang.JSON.parse(o.responseText);
						
						if (r.state == 200) {
						    //alert("ok");
						    // Update the Record
						    this._oRecordSet.updateRecordValue(this._oCellEditor.record, this._oCellEditor.column.key, this._oCellEditor.value);
						    // Update the UI
						    this.formatCell(this._oCellEditor.cell.firstChild);
						    this._syncColWidths(false);
						    
						    if(this._oCellEditor.column.getKey()=='delete'){
							
							this.deleteRow(this._oCellEditor.record);
						    }	
						    
						    
						    
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






		this.SuppliersDataTable2.saveCellEditor =this.SuppliersDataTable2.mySaveEditor;
		this.SuppliersDataTable2.subscribe("cellMouseoverEvent", this.highlightEditableCell);
		this.SuppliersDataTable2.subscribe("cellMouseoutEvent", this.SuppliersDataTable2.onEventUnhighlightCell);
		this.SuppliersDataTable2.subscribe("cellClickEvent", this.SuppliersDataTable2.onEventShowCellEditor);



	
		this.SuppliersDataTable2.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
		this.SuppliersDataTable2.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.SuppliersDataTable2}  } ]);
		this.SuppliersDataTable2.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.SuppliersDataTable2}  } ]);
		this.SuppliersDataTable2.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.SuppliersDataTable2}  } ]);
		this.SuppliersDataTable2.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.SuppliersDataTable2}  } ]);
		this.SuppliersDataTable2.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.SuppliersDataTable2}  } ]);
		this.SuppliersDataTable2.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.SuppliersDataTable2}  } ]);
		YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.SuppliersDataTable2.paginatorMenu.show, null, this.SuppliersDataTable2.paginatorMenu);
		this.SuppliersDataTable2.paginatorMenu.render(document.body);
		this.SuppliersDataTable2.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
		this.SuppliersDataTable2.filterMenu.addItems([{ text: "<?=_('Supplier Code')?>", onclick:{fn:changeFilter,obj:{col:'code',text:"<?=_('Family Code')?>"},scope:this.SuppliersDataTable2}  } ]);
		this.SuppliersDataTable2.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?=_('Description')?>"},scope:this.SuppliersDataTable2}  } ]);
		YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.SuppliersDataTable2.filterMenu.show, null, this.SuppliersDataTable2.filterMenu);
		this.SuppliersDataTable2.filterMenu.render(document.body);
		
		this.SuppliersDataTable2.myreload=reload;
		this.SuppliersDataTable2.sortColumn = mysort;
		this.SuppliersDataTable2.id=tableid;
		this.SuppliersDataTable2.editmode=false;
		this.SuppliersDataTable2.subscribe("initEvent", dataReturn); 
		YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.SuppliersDataTable2); 
		YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.SuppliersDataTable2); 
		YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.SuppliersDataTable2); 
		YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.SuppliersDataTable2); 
		







		<?}if($_REQUEST['pos']>0){?>

		var tableid=1; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		

		this.dnLink=  function(el, oRecord, oColumn, oData) {
		    var url="porder.php?id="+oRecord.getData("id");
		    el.innerHTML = oData.link(url);
		};


		var SuppliersColumnDefs1 = [
					   

					   {key:"id", label:"<?=_('Id')?>", width:120,formatter:this.dnLink,width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   , {key:"tipo", label:"<?=_('Status')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					   , {key:"public_id", label:"<?=_('Inv Number')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"date_index", label:"<?=_('Date')?>", width:300,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					   // ,{key:"date_received", label:"<?=_('Date Received')?>", width:200, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   
					   

					   ,{key:"total", label:"<?=_('Total')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					   

					   ];
		
		this.SuppliersDataSource1 = new YAHOO.util.DataSource("ar_suppliers.php?tipo=pos&tid="+tableid);
		this.SuppliersDataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.SuppliersDataSource1.connXhrMode = "queueRequests";
		this.SuppliersDataSource1.responseSchema = {
		    resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","public_id","date_index","total","tipo"
			 ]};
		
		this.SuppliersDataSource1.doBeforeCallback = mydoBeforeCallback;
		
		
		
    
//this.SuppliersDataTable.saveCellEditor =this.SuppliersDataTable.mySaveEditor;
		//	    this.SuppliersDataTable.subscribe("cellMouseoverEvent", this.highlightEditableCell);
		//this.SuppliersDataTable.subscribe("cellMouseoutEvent", this.SuppliersDataTable.onEventUnhighlightCell);
		//this.SuppliersDataTable.subscribe("cellClickEvent", this.SuppliersDataTable.onEventShowCellEditor);
	    
		
		
	 
		this.SuppliersDataTable1 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs1,this.SuppliersDataSource1, {renderLoopSize: 50
																   , sortedBy: {key:"<?=$_SESSION['tables']['po_list'][0]?>", dir:"<?=$_SESSION['tables']['po_list'][1]?>"}
		    });
	








	
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
		this.SuppliersDataTable1.filterMenu.addItems([{ text: "<?=_('Supplier Code')?>", onclick:{fn:changeFilter,obj:{col:'code',text:"<?=_('Family Code')?>"},scope:this.SuppliersDataTable}  } ]);
		this.SuppliersDataTable1.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?=_('Description')?>"},scope:this.SuppliersDataTable}  } ]);
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


		YAHOO.util.Event.addListener('option'+tableid+'_0', "click", toption, {scope:this.SuppliersDataTable1,id:0}); 
		YAHOO.util.Event.addListener('option'+tableid+'_1', "click", toption, {scope:this.SuppliersDataTable1,id:1}); 
		YAHOO.util.Event.addListener('option'+tableid+'_2', "click", toption, {scope:this.SuppliersDataTable1,id:2}); 
		YAHOO.util.Event.addListener('option'+tableid+'_3', "click", toption, {scope:this.SuppliersDataTable1,id:3}); 




		     <?}?>







	    
	};
    });




function init(){
 var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;









    function mygetTerms2(query) {YAHOO.supplier.XHR_JSON.SuppliersDataTable2.myreload();};
    var oACDS2 = new YAHOO.widget.DS_JSFunction(mygetTerms2);
    oACDS2.queryMatchContains = true;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","filtercontainer2", oACDS2);
    oAutoComp2.minQueryLength = 0; 
    


    var handleSubmit = function() {
		this.submit();
	};
	var handleCancel = function() {
		this.cancel();
	};
	var handleSuccess = function(o) {
	    
	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};


	YAHOO.supplier.dialog1  = new YAHOO.widget.Dialog("add_delivernote",
							     { width : "20em",
							       fixedcenter : true,
							       visible : false, 
							       constraintoviewport : true,
							       postmethod:"form",
							       
							  buttons : [ { text:"<?=_('Create')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							});

	YAHOO.supplier.dialog1.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.supplier.dialog1.render();

	YAHOO.supplier.changeview = function(e,view) {
	      var Dom   = YAHOO.util.Dom;

	      if(YAHOO.supplier.views[view]==0){
		  Dom.get('but_view'+view).className='selected';
		  Dom.get('block'+view).style.display='';
		  YAHOO.supplier.views[view]=1;
		  YAHOO.util.Connect.asyncRequest('POST','ar_suppliers.php?tipo=changesupplierblock&value=1&block=' + escape(view) ); 
		  
	      }else{

		  Dom.get('but_view'+view).className='';
		Dom.get('block'+view).style.display='none';
		YAHOO.supplier.views[view]=0;	
		YAHOO.util.Connect.asyncRequest('POST','ar_suppliers.php?tipo=changesupplierblock&value=0&block=' + escape(view) ); 
		
	      }
	}
	YAHOO.supplier.changeeditmode = function() {
	    Dom.get('but_view0').className='disabled';
	    Dom.get('but_view1').className='disabled';
	    Dom.get('new_po').style.display='none';
	    Dom.get('dn').style.display='none';
	    Dom.get('edit_products').className='edit';
	    Dom.get('edit_supplier').className='edit';
	    Dom.get('block0').style.display='none';


	    YAHOO.supplier.XHR_JSON.SuppliersDataTable2.getColumn('sup_code').editor="textbox";
	    YAHOO.supplier.XHR_JSON.SuppliersDataTable2.getColumn('price_unit').editor="textbox";

	}
	
	Event.addListener(Dom.get('but_view0'),"click",YAHOO.supplier.changeview,0);
	Event.addListener(Dom.get('but_view1'),"click",YAHOO.supplier.changeview,1);
	Event.addListener(Dom.get('edit_products'),"click",YAHOO.supplier.changeeditmode);
	Event.addListener(Dom.get('but_view3'),"click",YAHOO.supplier.dialog1.show, YAHOO.supplier.dialog1, true);



    YAHOO.util.Event.addListener("new_delivernote", "click", YAHOO.supplier.dialog1.show, YAHOO.supplier.dialog1, true);
    
    var newpo= function(){location.href="porder.php?new=<?=$_SESSION['tables']['product_withsupplier'][4]?>"}
    YAHOO.util.Event.addListener("new_po", "click", newpo);


    var delete_element= function(){

	var edit_container_row=this.parentNode.parentNode;
	var telecom_id=edit_container_row.getAttribute('c_id');
	var key=edit_container_row.getAttribute('key');

	request='ar_contacts.php?tipo=update_contact&key=del_'+key+'&id='+telecom_id;
	
	
	//       	alert(request);
     YAHOO.util.Connect.asyncRequest(
				     'POST',
				     request,{
					 success: function (o) {
					     // alert(o.responseText)

					     var r =  YAHOO.lang.JSON.parse(o.responseText);
					     
					     if (r.state == 200) {

						 Dom.get(key+'_c'+r.element_id).style.display='none';

						 
					     }


					     
					 },
					 failure: function(o) {alert("error")},
					 scope: this
				     }
				     ); 


    }

    



   var change_element = function(e,o){


     if(o){

	var  key=o.key;
	var element_id=o.element_id;
	var edit_container_row=Dom.get(key+'_c'+element_id)
     }else{
	 var edit_container_row=this.parentNode.parentNode;
	 var key=edit_container_row.getAttribute('key');
	 var element_id=edit_container_row.getAttribute('c_id');
     }
     
     var busy=Number(edit_container_row.getAttribute('busy'));
     if(busy>0){
	 setTimeout(change_element,100+busy*100 ,'',{'key':key,'element_id':element_id});
	 //setTimeout(alert,100+busy*100,'caca');
	 return
     }

     if(key=='tel' || key=='fax'){
	 


	 var name=Dom.get(key+'_name'+element_id).value;
	 var code=Dom.get(key+'_code'+element_id).value;
	 var number=Dom.get(key+'_number'+element_id).value;

	 if(key=='tel'){

	     var ext=Dom.get(key+'_ext'+element_id).value;
	     var telecom_tipo=0;
	     //if(busy_tel && !retrying_tel){
	     // setTimeout(change_element('',{'key':key,'element_id':element_id}),200);
	     //	 retrying_tel=true;
	     //	 return
	     // }else
		 
	 }else{
	     var ext='';
	     var telecom_tipo=4;
	 }
	 if(number.length<6)
	     return;


	 
	 


	 var request='ar_contacts.php?tipo=update_contact&key=telecom' 
	 +'&name=' + escape(name)
	 +'&code=' + escape(code)
	 +'&number=' + escape(number)
	 +'&ext=' + escape(ext)
	 +'&telecom_id=' + escape(element_id)
	 +'&contact_id=' + YAHOO.supplier.contact_id
	 +'&telecom_tipo='+escape(telecom_tipo);
	 //	 alert(request)
		 
     }else if(key=='email' || key=='www'){
	 
	 var name=Dom.get(key+'_name'+element_id).value;
	 var address=Dom.get(key+'_address'+element_id).value;
	 

	 if(key=='email' && !isValidEmail(address)){
	     return
	 }else if(key=='www' && !isValidURL(address)){
	     return
	 }
	 

	 var request='ar_contacts.php?tipo=update_contact&key='+key 
	 +'&name=' + escape(name)
	 +'&address=' + escape(address)
	 +'&element_id=' + escape(element_id)
	 +'&contact_id=' + YAHOO.supplier.contact_id
	 // alert(request)



     }else if(key=='other'){
	 var value=Dom.get('v_other').value+'.'+Dom.get('v_other_c').value;
     }else{
	 var value=this.value;
     }
     


     //  if(busy){
     //	 setTimeout(change_element('',{'key':key,'element_id':element_id}),200);
	 
     //	 return;
     // }
     edit_container_row.setAttribute('busy',busy+1)

     
     YAHOO.util.Connect.asyncRequest(
				     'POST',
				     request,{
					 success: function (o) {
					     
					     var res_busy=Number(edit_container_row.getAttribute('busy'))
					     edit_container_row.setAttribute('busy',res_busy-1)
					     
					     //alert(o.responseText)
					     var r =  YAHOO.lang.JSON.parse(o.responseText);
					     
					     
					     if (r.state == 200) {
						 
						 if(key=='tel' || key=='fax' || key=='mob' ){
						     if(r.new){
							 var new_tel=Dom.get(key+'_c').cloneNode(true);

							 Dom.get(key+'_del').style.display='';
							 Dom.get(key+'_c').setAttribute('c_id',r.telecom_id);
							 Dom.get(key+'_c').setAttribute('id',key+'_c'+r.telecom_id);
							 Dom.get(key+'_name').setAttribute('id',key+'_name'+r.telecom_id);
							 Dom.get(key+'_code').setAttribute('id',key+'_code'+r.telecom_id);
							 Dom.get(key+'_number').setAttribute('id',key+'_number'+r.telecom_id);
							 if(key=='tel'){
							     Dom.get(key+'_ext').setAttribute('id',key+'_ext'+r.telecom_id);
							     Dom.get(key+'_l').innerHTML='<?=_('Tel').':'?>';
														 
							 }else if(key=='fax'){
							      Dom.get(key+'_l').innerHTML='<?=_('Fax').':'?>';
							 }
							 Dom.get(key+'_l').setAttribute('id',key+'_l'+r.telecom_id);
							 Dom.get(key+'_del').setAttribute('id',key+'_del'+r.telecom_id);
							 YAHOO.util.Event.addListener(key+'_del'+r.telecom_id, "click", delete_element,false);
							 Dom.get(key+'').setAttribute('id',key+''+r.telecom_id);
							 Dom.get(key+''+r.telecom_id).innerHTML=r.tel;
							 
							 Dom.get(key+'_b').appendChild(new_tel);



							 Dom.get(key+'_code').value='';
							 Dom.get(key+'_number').value='';
							 Dom.get(key+'_name').value='';
							 var ids = [key+'_code',key+'_number']; 
							 YAHOO.util.Event.addListener(ids, "keyup", change_element,false);
							 if(key=='tel')
							     YAHOO.util.Event.addListener(key+'_ext', "keyup", change_element,false);
							 
						     }else{
							 Dom.get(key+''+r.telecom_id).innerHTML=r.tel;
						     }
						 }else if(key=='email' || key=='www')
						     {
							 if(r.new){
							   var new_element=Dom.get(key+'_c').cloneNode(true);
							   Dom.get(key+'_l').innerHTML='';
							   Dom.get(key+'_del').style.display='';
							   Dom.get(key+'_c').setAttribute('c_id',r.address_id);
							   Dom.get(key+'_c').setAttribute('id',key+'_c'+r.address_id);
							   
							   Dom.get(key+'_name').setAttribute('id',key+'_name'+r.address_id);
							   Dom.get(key+'_address').setAttribute('id',key+'_address'+r.address_id);

							   Dom.get(key+'_l').setAttribute('id',key+'_l'+r.address_id);
							   Dom.get(key+'_del').setAttribute('id',key+'_del'+r.address_id);
							   Dom.get(key+'').setAttribute('id',key+''+r.address_id);
							   Dom.get(key+''+r.address_id).innerHTML=r.link_address;
							   
							   Dom.get(key+'_b').appendChild(new_element);	
							   YAHOO.util.Event.addListener(key+'_del', "click", delete_element,false);
							   Dom.get(key+'_address').value='';
							   Dom.get(key+'_name').value='';
							   var ids = [key+'_name',key+'_address']; 
							   YAHOO.util.Event.addListener(ids, "keyup", change_element,false);
						       }else{
							   Dom.get(key+''+r.address_id).innerHTML=r.link_address;
						       }
						     
						     
						 }
						 
						 
					     }


					     
					 },
					 failure: function(o) {alert("error")},
					 scope: this
				     }
						       ); 


}

   var clean_labels =function(){
       if(this.id=='tel_c'){
	   Dom.get('tel_code').value='';
	   Dom.get('tel_number').value='';
	   Dom.get('tel_name').value='';
	   Dom.get('tel_name').className='text normal_left';
	   Dom.get('tel_number').className='normal_right text';
	   Dom.get('tel_code').className='normal_right text';
	   var ids = ["tel_c"]; 
	   YAHOO.util.Event.removeListener(ids, "click"); 
       }else if(this.id=='fax_c'){
	    Dom.get('fax_code').value='';
	   Dom.get('fax_number').value='';
	   Dom.get('fax_name').value='';
	   Dom.get('fax_name').className='text normal_left';
	   Dom.get('fax_number').className='normal_right text';
	   Dom.get('fax_code').className='normal_right text';
	   var ids = ["fax_c"]; 
	   YAHOO.util.Event.removeListener(ids, "click"); 
       }else if(this.id=='email_c'){
	   Dom.get('email_name').value='';
	   Dom.get('email_address').value='';
	   Dom.get('email_name').className='text normal_left';
	   Dom.get('email_address').className='text normal_left';
	   var ids = ["email_c"]; 
	   YAHOO.util.Event.removeListener(ids, "click"); 
       }else if(this.id=='www_c'){
	   Dom.get('www_name').value='';
	   Dom.get('www_address').value='';
	   Dom.get('www_name').className='text normal_left';
	   Dom.get('www_address').className='text normal_left';
	   var ids = ["www_c"]; 
	   YAHOO.util.Event.removeListener(ids, "click"); 
       }
       

   }





   var ids = ["tel_c","fax_c","email_c","www_c"]; 
   YAHOO.util.Event.addListener(ids, "click", clean_labels,false);


   var ids = ["tel_code","tel_number","tel_ext","tel_name","fax_code","fax_number","fax_name","email_name","email_address","www_name","www_address"]; 
   YAHOO.util.Event.addListener(ids, "keyup", change_element,false);

var tel_name_ids = new Array();

var tel_code_ids = new Array();
var tel_number_ids = new Array();
var tel_ext_ids = new Array();
var tel_del_ids = new Array();
for (var i=0;i<tel_ids.length;i++)
    {
	tel_name_ids[i]='tel_name'+tel_ids[i];
	tel_code_ids[i]='tel_code'+tel_ids[i];
	tel_number_ids[i]='tel_number'+tel_ids[i];
	tel_ext_ids[i]='tel_ext'+tel_ids[i];
	tel_del_ids[i]='tel_del'+tel_ids[i];
    }
YAHOO.util.Event.addListener(tel_del_ids, "click", delete_element,false);
YAHOO.util.Event.addListener(tel_name_ids, "keyup", change_element,false);
YAHOO.util.Event.addListener(tel_code_ids, "keyup", change_element,false);
YAHOO.util.Event.addListener(tel_number_ids, "keyup", change_element,false);
YAHOO.util.Event.addListener(tel_ext_ids, "keyup", change_element,false);


var fax_code_ids = new Array();
var fax_number_ids = new Array();
var fax_name_ids = new Array();
var fax_del_ids = new Array();
for (var i=0;i<fax_ids.length;i++)
    {
	fax_code_ids[i]='fax_code'+fax_ids[i];
	fax_number_ids[i]='fax_number'+fax_ids[i];
	fax_name_ids[i]='fax_name'+fax_ids[i];
	fax_del_ids[i]='fax_del'+fax_ids[i];
    }
YAHOO.util.Event.addListener(fax_del_ids, "click", delete_element,false);
YAHOO.util.Event.addListener(fax_name_ids, "keyup", change_element,false);
YAHOO.util.Event.addListener(fax_code_ids, "keyup", change_element,false);
YAHOO.util.Event.addListener(fax_number_ids, "keyup", change_element,false);



var email_address_ids = new Array();
var email_name_ids = new Array();
var email_del_ids = new Array();
for (var i=0;i<email_ids.length;i++)
    {
	email_address_ids[i]='email_address'+email_ids[i];
	email_name_ids[i]='email_name'+email_ids[i];
	email_del_ids[i]='email_del'+email_ids[i];
    }
YAHOO.util.Event.addListener(email_del_ids, "click", delete_element,false);
YAHOO.util.Event.addListener(email_name_ids, "keyup", change_element,false);
YAHOO.util.Event.addListener(email_address_ids, "keyup", change_element,false);


var www_address_ids = new Array();
var www_name_ids = new Array();
var www_del_ids = new Array();
for (var i=0;i<www_ids.length;i++)
    {
	www_address_ids[i]='www_address'+www_ids[i];
	www_name_ids[i]='www_name'+www_ids[i];
	www_del_ids[i]='www_del'+www_ids[i];
    }
YAHOO.util.Event.addListener(www_del_ids, "click", delete_element,false);
YAHOO.util.Event.addListener(www_name_ids, "keyup", change_element,false);
YAHOO.util.Event.addListener(www_address_ids, "keyup", change_element,false);







}

YAHOO.util.Event.onDOMReady(init);
