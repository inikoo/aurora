<?
    include_once('../common.php');


?>

	YAHOO.namespace ("product"); 
YAHOO.product.edit=false;
function img_click(e,pos){
    var Event = YAHOO.util.Event;
    var Dom   = YAHOO.util.Dom;

	

    if(pos==(-1))
	var element=Dom.get('imagediv')
	else
	    var element=Dom.get('oim_'+pos);
    var pic_id=element.getAttribute('pic_id');
	
    if(pic_id>0){
	if(YAHOO.product.edit)
	    change_pic(pos);
	else
	    show_pic(pic_id);
	    
    }
	
}


function show_pic(pic_id){
    img='image.php?id='+pic_id;
    YAHOO.product.popimage = new YAHOO.widget.Panel("popimage", {x:100,y:100,visible:false, draggable:true, close:true } );
    YAHOO.product.popimage.setBody('<img src="'+img+'" height="500" alt="<?=_('Image not found')?>"/>');
    YAHOO.product.popimage.render("bd");
    YAHOO.product.popimage.show();
}


function change_pic(pos){
    var Event = YAHOO.util.Event;
    var Dom   = YAHOO.util.Dom;


    var element=Dom.get('oim_'+pos);

    var pic_id=element.getAttribute('pic_id');
	
    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    'ar_assets.php?tipo=changepic&new_id='+ escape(pic_id),{
					success: function (o) {
					    //alert(o.responseText)
					    var r =  YAHOO.lang.JSON.parse(o.responseText);
						
						
					    if (r.state == 200) {
						//alert(r.new_src);
						Dom.get('image').src=r.new_src;
						Dom.get('imagediv').setAttribute('pic_id',r.new_id);
						    
						//alert(r.others)
						if(r.others==0){
						    Dom.get('caption').style.display="";
						    Dom.get('caption').innerHTML=r.caption;
						    Dom.get('otherimages').style.display="none";
							
						}else{
						    Dom.get('caption').style.display="none";
						    Dom.get('caption').innerHTML=r.caption;
						    Dom.get('otherimages').style.display="";
							
						    for (i=0;i<5;i++) 
							{
							    Dom.get('oim_'+i).setAttribute('pic_id',r.other_img_id[i]);
							    child=Dom.getChildren('oim_'+i);
								
							    for (x in child){
								Dom.get('oim_'+i).removeChild(child[x]);
							    }
							    if(r.other_img_id[i]>0){
								    
								var im=document.createElement('img');
								    
								im.src=r.other_img[i];


								Dom.get('oim_'+i).appendChild(im);
								    
							    }
							}
						}
						    
					    }else{
						  
						//alert(o.responseText)
					    }
					    // Clear out the Cell Editor

							    
					},
					    failure: function(o) {alert("error")},
					    scope: this
					    }
				    ); 
	

	

}








YAHOO.util.Event.addListener(window, "load", function() {
	YAHOO.product.XHR_JSON = new function() {
		
		this.orderLink=  function(el, oRecord, oColumn, oData) {
		    var url="order.php?id="+oRecord.getData("id");
		    el.innerHTML = oData.link(url);
		};
		

		this.customerLink=  function(el, oRecord, oColumn, oData) {
		    var url="customer.php?id="+oRecord.getData("customer_id");
		    el.innerHTML = oData.link(url);
		};
		this.date=  function(el, oRecord, oColumn, oData) {
		    el.innerHTML = oRecord.getData("date");
		} ;  

		<?if($LU->checkRight(ORDER_VIEW)){?>

		    var tableid=0; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;
		
		    var ProductsColumnDefs = [
					      {key:"public_id", label:"<?=_('Number')?>", width:100,sortable:true,formatter:this.orderLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					      ,{key:"tipo", label:"<?=_('Type')?>",width:120, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					      ,{key:"customer_name", label:"<?=_('Customer')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					      ,{key:"date_index", label:"<?=_('Date')?>", width:200,formatter:this.date,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					      ,{key:"dispached", label:"<?=_('Dispached')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					      ,{key:"undispached", label:"<?='&Delta;'._('Ordered')?>", width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      
					      ];

		    this.ProductsDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=withproduct&tid="+tableid);
		    this.ProductsDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.ProductsDataSource.connXhrMode = "queueRequests";
		    this.ProductsDataSource.responseSchema = {
			resultsList: "resultset.data", 
			totalRecords: 'resultset.total_records',
			fields: [
				 "id","public_id","customer_name","tipo","date_index","date","dispached","undispached"
				 ]};
		
		    this.ProductsDataSource.doBeforeCallback = mydoBeforeCallback;
		    this.ProductsDataTable = new YAHOO.widget.DataTable
			(tableDivEL, ProductsColumnDefs,this.ProductsDataSource, {renderLoopSize: 50
										  ,sortedBy: {key:"<?=$_SESSION['tables']['order_withprod'][0]?>", dir:"<?=$_SESSION['tables']['order_withprod'][1]?>"} 
			});
		
		    this.ProductsDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
		    this.ProductsDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.ProductsDataTable}  } ]);
		    this.ProductsDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.ProductsDataTable}  } ]);
		    this.ProductsDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.ProductsDataTable}  } ]);
		    this.ProductsDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.ProductsDataTable}  } ]);
		    this.ProductsDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.ProductsDataTable}  } ]);
		    this.ProductsDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.ProductsDataTable}  } ]);
		    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.ProductsDataTable.paginatorMenu.show, null, this.ProductsDataTable.paginatorMenu);
		    this.ProductsDataTable.paginatorMenu.render(document.body);
		    this.ProductsDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
		    this.ProductsDataTable.filterMenu.addItems([{ text: "<?=_('Product Code')?>", onclick:{fn:changeFilter,obj:{col:'code',text:"<?=_('Family Code')?>"},scope:this.ProductsDataTable}  } ]);
		    this.ProductsDataTable.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?=_('Description')?>"},scope:this.ProductsDataTable}  } ]);
		    YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.ProductsDataTable.filterMenu.show, null, this.ProductsDataTable.filterMenu);
		    this.ProductsDataTable.filterMenu.render(document.body);
		
		    this.ProductsDataTable.myreload=reload;
		    this.ProductsDataTable.sortColumn = mysort;
		    this.ProductsDataTable.id=tableid;
		    this.ProductsDataTable.editmode=false;
		    this.ProductsDataTable.subscribe("initEvent", dataReturn); 
		    YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.ProductsDataTable); 
		    YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.ProductsDataTable); 
		    YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.ProductsDataTable); 
		    YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.ProductsDataTable); 
		    <?}?>
		    <?if($LU->checkRight(CUST_VIEW)){?>

		    var tableid=1; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;
		
		    var CustomersColumnDefs = [
					       //					  {key:"public_id", label:"<?=_('Number')?>", width:80,sortable:true,formatter:this.orderLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					       // ,{key:"tipo", label:"<?=_('Type')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					       {key:"customer_name", label:"<?=_('Customer')?>",width:250, sortable:true,formatter:this.customerLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					       // ,{key:"date_index", label:"<?=_('Date')?>", formatter:this.date,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					       ,{key:"orders", label:"<?=_('Orders')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					       ,{key:"dispached", label:"<?=_('Dispached')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					       ,{key:"todispach", label:"<?=_('To Dispach')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


					       ,{key:"nodispached", label:"<?=_('Undispached')?>", width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					       ,{key:"charged", label:"<?=_('Charged')?>", width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					       ];

		    this.CustomersDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=withcustomerproduct&tid="+tableid);
		    this.CustomersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.CustomersDataSource.connXhrMode = "queueRequests";
		    this.CustomersDataSource.responseSchema = {
			resultsList: "resultset.data", 
			totalRecords: 'resultset.total_records',
			fields: [
				 "customer_id","customer_name","dispached","nodispached","charged","todispach","orders"
				 ]};
		
		    this.CustomersDataSource.doBeforeCallback = mydoBeforeCallback;
		    this.CustomersDataTable = new YAHOO.widget.DataTable
			(tableDivEL, CustomersColumnDefs,this.CustomersDataSource, {renderLoopSize: 50
										    ,sortedBy: {key:"<?=$_SESSION['tables']['order_withcustprod'][0]?>", dir:"<?=$_SESSION['tables']['order_withcustprod'][1]?>"} 
			});
		
		    this.CustomersDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.CustomersDataTable}  } ]);
		    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.CustomersDataTable.paginatorMenu.show, null, this.CustomersDataTable.paginatorMenu);
		    this.CustomersDataTable.paginatorMenu.render(document.body);
		    this.CustomersDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
		    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=_('Product Code')?>", onclick:{fn:changeFilter,obj:{col:'code',text:"<?=_('Family Code')?>"},scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?=_('Description')?>"},scope:this.CustomersDataTable}  } ]);
		    YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.CustomersDataTable.filterMenu.show, null, this.CustomersDataTable.filterMenu);
		    this.CustomersDataTable.filterMenu.render(document.body);
		
		    this.CustomersDataTable.myreload=reload;
		    this.CustomersDataTable.sortColumn = mysort;
		    this.CustomersDataTable.id=tableid;
		    this.CustomersDataTable.editmode=false;
		    this.CustomersDataTable.subscribe("initEvent", dataReturn); 
		    YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.CustomersDataTable); 
		    YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.CustomersDataTable); 
		    YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.CustomersDataTable); 
		    YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.CustomersDataTable); 


		    <?}?>
			<?if($LU->checkRight(PROD_STK_VIEW)){?>


		    var tableid=2; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;
		
		    var CustomersColumnDefs = [
					   
					       {key:"stock", label:"<?=_('Stock')?>", width:60,sortable:false,className:"aright"}
					       ,{key:"available", label:"<?=_('Available')?>", width:60,sortable:false,className:"aright"}
					       ,{key:"operation", label:"<?=_('Operation')?>",width:400,sortable:false,className:"aleft"}
					       ,{key:"op_date", label:"<?=_('Date')?>",width:300, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   
					       ];
		
		    this.CustomersDataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=stock_history&tid="+tableid);
		    this.CustomersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.CustomersDataSource.connXhrMode = "queueRequests";
		    this.CustomersDataSource.responseSchema = {
			resultsList: "resultset.data", 
			totalRecords: 'resultset.total_records',
			fields: [
				 "id","stock","operation","op_date","available"
				 ]};
		
		    this.CustomersDataSource.doBeforeCallback = mydoBeforeCallback;
		    this.CustomersDataTable =
			new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,this.CustomersDataSource, {renderLoopSize: 50,
													      sortedBy: {key:"<?=$_SESSION['tables']['stock_history'][0]?>", dir:"<?=$_SESSION['tables']['stock_history'][1]?>"}				
			    });
		
		    this.CustomersDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.CustomersDataTable}  } ]);
		    this.CustomersDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.CustomersDataTable}  } ]);
		    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.CustomersDataTable.paginatorMenu.show, null, this.CustomersDataTable.paginatorMenu);
		    this.CustomersDataTable.paginatorMenu.render(document.body);
		
		
		
		    this.CustomersDataTable.myreload=reload;
		    this.CustomersDataTable.sortColumn = mysort;
		    this.CustomersDataTable.id=tableid;
		    this.CustomersDataTable.editmode=false;
		    this.CustomersDataTable.subscribe("initEvent", dataReturn); 
		    YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.CustomersDataTable); 
		    YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.CustomersDataTable); 
		    YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.CustomersDataTable); 
		
		
		    YAHOO.util.Event.addListener('option'+tableid+'_0', "click", toption, {scope:this.CustomersDataTable,id:0}); 
		    YAHOO.util.Event.addListener('option'+tableid+'_1', "click", toption, {scope:this.CustomersDataTable,id:1}); 
		    YAHOO.util.Event.addListener('option'+tableid+'_2', "click", toption, {scope:this.CustomersDataTable,id:2}); 
		    YAHOO.util.Event.addListener('option'+tableid+'_3', "click", toption, {scope:this.CustomersDataTable,id:3}); 
		    YAHOO.util.Event.addListener('option'+tableid+'_4', "click", toption, {scope:this.CustomersDataTable,id:4}); 

		    YAHOO.util.Event.addListener('dates'+tableid, "click", showdates, this.CustomersDataTable); 

		    <?}?>


	    
	    };
    });




function init(){


    var Event = YAHOO.util.Event;
    var Dom   = YAHOO.util.Dom;
    

    function mygetTerms(query) {
	var Dom = YAHOO.util.Dom
	    var table=YAHOO.product.XHR_JSON.ProductsDataTable;
	var data=table.getDataSource();
	var newrequest="&sf=0&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

	//	alert(newrequest);
	data.sendRequest(newrequest,{success:table.onDataReturnInitializeTable, scope:table});
    };
    <?if($LU->checkRight(ORDER_VIEW)){?>

	var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
	oACDS.queryMatchContains = true;
	var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
	oAutoComp.minQueryLength = 0; 
	<?}?>

    



    


	  var handleSubmit = function() {
	      this.submit();
	  };




	  var handleCancel = function() {
	      this.cancel();
	  };
	  var handleSuccess = function(o) {
	      //	    alert(o.responseText);
	      var response = YAHOO.lang.JSON.parse(o.responseText);

	      document.location.reload();

	      // 	     if(response.state==200){
	      // 		 YAHOO.product.XHR_JSON.ProductsDataTable.addRow(response.data,0);
	      // 		 YAHOO.product.dialog1.hide();
	      // 	     }else{
	      // 		 alert(response.resp);
	      // 	     }
	    
	  };
	  var handleSuccess_stock = function(o) {
	      var Dom = YAHOO.util.Dom;
	      var response = YAHOO.lang.JSON.parse(o.responseText);
	    

	      if(response.state==200){
		  Dom.get('stock').innerHTML=response.stock;

	      }else{
		  alert(response.resp);
	      }
	    
	  };
	  var handleSubmit_details = function() {
	      YAHOO.product.Editor.saveHTML();
	      this.submit();
	  };
	  var handleSuccess_details = function(o) {
	      var Dom = YAHOO.util.Dom;
	      var response = YAHOO.lang.JSON.parse(o.responseText);
     
     
	      if(response.state==200){
		 
		  YAHOO.product.Editor.saveHTML();
		 
		 
		  var html = YAHOO.product.Editor.get('element').value;

		    
		  Dom.get('extended_description').innerHTML=html;


	      }else{
		  alert(response.resp);
	      }
	    
	  };


	  var upload_pic= function(o) {

	      var Dom   = YAHOO.util.Dom;
	      //  alert(o.responseText)
	      var r = YAHOO.lang.JSON.parse(o.responseText);
	      if(r.state==200){
		  //alert(r.new_src);
		  Dom.get('image').src=r.new_src;
		  Dom.get('image').setAttribute('pic_id',r.new_id);
	
		  //alert(r.others)
		  if(r.others==0){
		      Dom.get('caption').style.display="";
		      Dom.get('caption').innerHTML=r.caption;
		      Dom.get('otherimages').style.display="none";

		  }else{
		      Dom.get('caption').style.display="none";
		      Dom.get('caption').innerHTML=r.caption;
		      Dom.get('otherimages').style.display="";

		      for (i=0;i<5;i++) 
			  {
			      //			    alert("contador "+i);
			      // alert(Dom.get('oim_'+i)+' '+r.other_img[0]);
			      //			    if(Dom.get('oim_'+i).child!='undefined')
			      //	Dom.get('oim_'+i).removeChild(Dom.get('oim_'+i).child);


			      child=Dom.getChildren('oim_'+i);
			      Dom.get('imagediv').setAttribute('pic_id',r.new_id);
			      //alert(child)
			      for (x in child){
				  //				alert(x+' '+child[x]);
				  Dom.get('oim_'+i).removeChild(child[x]);
			      }

			      //Dom.get('oim_'+i).removeChild(child);
			      //alert('caca')
			      if(r.other_img_id[i]>0){

				  var im=document.createElement('img');

				  im.src=r.other_img[i];

				  Dom.get('oim_'+i).appendChild(im);

			      }

			  }		    

		  }

	      }else{
		  alert(r.resp);
	      }
	    
	  };


	  var handleFailure = function(o) {
	      alert("Submission failed" );
	  };
	
	  <?if($LU->checkRight(PROD_MODIFY)){?>
	      YAHOO.product.dialog1  = new YAHOO.widget.Dialog("edit_product_form",
							       { width : "30em",
								 fixedcenter : true,
								 zIndex:100,
								 visible : false, 
								 constraintoviewport : true,
								 buttons : [ { text:"<?=_('Submit')?>", handler:handleSubmit, isDefault:true },
	      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							       });

	      YAHOO.product.dialog1.callback = { success: handleSuccess,failure: handleFailure };
	      YAHOO.product.dialog1.render();



	      YAHOO.product.dialog2  = new YAHOO.widget.Dialog("addtosupplier_form",
							       { width : "30em",
								 fixedcenter : true,
								 visible : false, 
								 constraintoviewport : true,
								 buttons : [ { text:"<?=_('Upload')?>", handler:handleSubmit, isDefault:true },
	      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							       });

	      YAHOO.product.dialog2.callback = { success: handleSuccess,failure: handleFailure };
	      YAHOO.product.dialog2.render();





	      YAHOO.product.dialog4  = new YAHOO.widget.Dialog("upload_pic_form",
							       { width : "30em",
								 fixedcenter : true,
								 visible : false, 
								 constraintoviewport : true,
								 //							       postmethod:"form",

								 buttons : [ { text:"<?=_('Upload')?>", handler:handleSubmit, isDefault:true },{ text:"<?=_('Cancel')?>", handler:handleCancel } ]
							       });
	
	      YAHOO.product.dialog4.callback = { upload: upload_pic};

	      YAHOO.product.dialog4.render();
	

	      YAHOO.product.dialog5  = new YAHOO.widget.Dialog("edit_details_form",
							       { 
								   fixedcenter : true,
								   visible : false, 
								   constraintoviewport : true,
								   //							       postmethod:"form",
								   buttons : [ { text:"<?=_('Save')?>", handler:handleSubmit_details, isDefault:true },{ text:"<?=_('Cancel')?>", handler:handleCancel } ]
							       });
	

	      YAHOO.product.dialog5.callback = { success: handleSuccess_details,failure: handleFailure };
	      YAHOO.product.dialog5.render();
	



	      YAHOO.util.Event.addListener("edit_product", "click",  YAHOO.product.dialog1.show, YAHOO.product.dialog1, true );
	      YAHOO.util.Event.addListener("add_supplier", "click",  YAHOO.product.dialog2.show, YAHOO.product.dialog2, true );


	      YAHOO.util.Event.addListener("add_pic", "click", YAHOO.product.dialog4.show, YAHOO.product.dialog4, true);
	      YAHOO.util.Event.addListener("edit_details", "click",  YAHOO.product.dialog5.show, YAHOO.product.dialog5, true );


	      YAHOO.product.changeedit = function(e) {
		  var Dom   = YAHOO.util.Dom;
		  var Event = YAHOO.util.Event;
	    
		  if(!YAHOO.product.edit){
		      YAHOO.product.edit=true;
		      Dom.get('but_view5').className='edit';
		      Dom.get('otherimages').className='editborder other_images';


		      Dom.get('but_view5').innerHTML='<?=_('Editing')?>';

		      Dom.get('but_view0').className='disabled';
		      Dom.get('but_view1').className='disabled';
		      Dom.get('but_view2').className='disabled';
		      Dom.get('but_view3').className='disabled';
		      Dom.get('but_view4').className='disabled';
		
		      Event.removeListener("but_view0", "click");
		      Event.removeListener("but_view1", "click");
		      Event.removeListener("but_view2", "click");
		      Event.removeListener("but_view3", "click");
		      Event.removeListener("but_view4", "click");




		      YAHOO.product.views[5]=1;

		      Dom.get('block0').style.display='';

		      Dom.get('block1').style.display='none';
		      Dom.get('block2').style.display='none';
		      Dom.get('block3').style.display='none';
		      Dom.get('block4').style.display='none';
		      Dom.get('buts').style.display='none';
		      Dom.get('edit_buts').style.display='';


		  }else{
		      YAHOO.product.edit=false;
		      Dom.get('otherimages').className='other_images';

		      Dom.get('edit_buts').style.display='none';

		      Dom.get('but_view5').className='';
		      YAHOO.product.views[5]=0;	
		      Dom.get('buts').style.display='';
		      Dom.get('but_view5').innerHTML='<?=_('Edit')?>';
		      if(YAHOO.product.views[0]==0){
			  Dom.get('but_view0').className='';
		      }else{
			  Dom.get('but_view0').className='selected';
			  Dom.get('block0').style.display='none'
		      }
		      if(YAHOO.product.views[1]==0){
			  Dom.get('but_view1').className='';
		      }else{
			  Dom.get('but_view1').className='selected';
			  Dom.get('block1').style.display=''
		      }
		      if(YAHOO.product.views[2]==0){
			  Dom.get('but_view2').className='';
		      }else{
			  Dom.get('but_view2').className='selected';
			  Dom.get('block0').style.display=''
		      }
		      if(YAHOO.product.views[3]==0){
			  Dom.get('but_view3').className='';
		      }else{
			  Dom.get('but_view3').className='selected';
			  Dom.get('block3').style.display=''
		      }
		      if(YAHOO.product.views[4]==0){
			  Dom.get('but_view4').className='';
		      }else{
			  Dom.get('but_view4').className='selected';
			  Dom.get('block4').style.display=''
		      }
		

		      Event.addListener("but_view0","click",YAHOO.product.changeview,0);
		      Event.addListener("but_view1","click",YAHOO.product.changeview,1);
		      Event.addListener("but_view2","click",YAHOO.product.changeview,2);
		      Event.addListener("but_view3","click",YAHOO.product.changeview,3);
		      Event.addListener("but_view4","click",YAHOO.product.changeview,4);
		
		    
		  }
		

	      }











	      <?}?>    		   


		<?if($LU->checkRight(PROD_STK_MODIFY)){?>

	      YAHOO.product.dialog3  = new YAHOO.widget.Dialog("setstock_form",
							       { width : "30em",
								 fixedcenter : true,
								 visible : false, 
								 constraintoviewport : true,
								 buttons : [ { text:"<?=_('Submit')?>", handler:handleSubmit, isDefault:true },
	      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							       });

	      YAHOO.product.dialog3.callback = { success: handleSuccess_stock,failure: handleFailure };
	      YAHOO.product.dialog3.render();
	      YAHOO.util.Event.addListener("update_stock", "click", YAHOO.product.dialog3.show, YAHOO.product.dialog3, true);
	      <?}?>

		    










		      YAHOO.product.plot='<?=$_SESSION['views']['product_plot']?>';
		      document.getElementById('plot_'+YAHOO.product.plot).className='selected';
		      YAHOO.product.views = new Array();

		      YAHOO.product.views[0]=<?=$_SESSION['views']['product_blocks'][0]?>;
		      YAHOO.product.views[1]=<?=$_SESSION['views']['product_blocks'][1]?>;
		      YAHOO.product.views[2]=<?=$_SESSION['views']['product_blocks'][2]?>;
		      YAHOO.product.views[3]=<?=$_SESSION['views']['product_blocks'][3]?>;
		      YAHOO.product.views[4]=<?=$_SESSION['views']['product_blocks'][4]?>;
		      YAHOO.product.views[5]=<?=$_SESSION['views']['product_blocks'][5]?>;


		      <?if($LU->checkRight(ORDER_VIEW)){?>



// 			  if(YAHOO.product.plot==0)
// 			      YAHOO.product.show_plot_weeksales();
// 			  else if(YAHOO.product.plot==1)
// 			      YAHOO.product.show_plot_weekorders();
// 			  else if(YAHOO.product.plot==2)
// 			      YAHOO.product.show_plot_weeksalesperorder();
// 			  else if(YAHOO.product.plot==3)
// 			      YAHOO.product.show_plot_monthsales();
	
			  <?}?>


	
			    YAHOO.product.changeview = function(e,view) {

				var Dom   = YAHOO.util.Dom;
				if(YAHOO.product.views[view]==0){
				    Dom.get('but_logo'+view).style.display='';
				    Dom.get('block'+view).style.display='';
				    YAHOO.product.views[view]=1;
				    YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changeproductblock&value=1&block=' + escape(view) ); 

				}else{
				    Dom.get('but_logo'+view).style.display='none';
				    Dom.get('block'+view).style.display='none';
				    YAHOO.product.views[view]=0;	
				    YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changeproductblock&value=0&block=' + escape(view) ); 

				}
		

			    }

 			    YAHOO.product.changeplot = function(e,plot_name) {
				
				document.getElementById('plot_'+YAHOO.product.plot).className='opaque';
				YAHOO.product.plot=plot_name;
				document.getElementById('the_plot').src = 'plot.php?tipo='+plot_name;
				document.getElementById('plot_'+plot_name).className='selected';
				
				YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changeproductplot&value=' + escape(plot_name) ); 


			    }
// 				old_plot=YAHOO.product.plot;
// 				if(old_plot==new_plot)
// 				    return;
// 				var Dom   = YAHOO.util.Dom;
// 				YAHOO.product.plot=new_plot;
	    
// 				Dom.get('plot'+old_plot).style.display='none'

// 				Dom.get('plot'+new_plot).style.display=''

// 				Dom.get("plot_view"+old_plot).className='';
// 				Dom.get("plot_view"+new_plot).className='selected';

// 				if(new_plot==0){
// 				    YAHOO.product.show_plot_weeksales();
// 				    Dom.get('plot_title').innerHTML='<?=_('Product sales value per week')?>';
// 				}else if(new_plot==1){
// 				    YAHOO.product.show_plot_weekorders();
// 				    Dom.get('plot_title').innerHTML='<?=_('Orders with this product per week')?>';
// 				}else if(new_plot==2){
// 				    YAHOO.product.show_plot_weeksalesperorder();
// 				    Dom.get('plot_title').innerHTML='<?=_('Sales value per order per week')?>';
// 				}else if(new_plot==3){
// 				    YAHOO.product.show_plot_monthsales();
// 				    Dom.get('plot_title').innerHTML='<?=_('Product sales value per month')?>';
// 				}else if(new_plot==4){
// 				    YAHOO.product.show_plot_monthorders();
// 				    Dom.get('plot_title').innerHTML='<?=_('Orders with this product per month')?>';
// 				}else if(new_plot==5){
// 				    YAHOO.product.show_plot_monthsalesperorder();
// 				    Dom.get('plot_title').innerHTML='<?=_('Sales value per order per month')?>';
// 				}

	    
// 				YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changeproductplot&value=' + escape(new_plot) ); 
	    
	    
	    
// 			    }
	
	
	
	

	
	
			    Event.addListener("plot_sales_week","click",YAHOO.product.changeplot,'sales_week');
			    Event.addListener("plot_sales_month","click",YAHOO.product.changeplot,'sales_month');
			    Event.addListener("plot_sales_quarter","click",YAHOO.product.changeplot,'sales_quarter');
			    Event.addListener("plot_sales_year","click",YAHOO.product.changeplot,'sales_year');
			    Event.addListener("plot_out_week","click",YAHOO.product.changeplot,'out_week');
			    Event.addListener("plot_out_month","click",YAHOO.product.changeplot,'out_month');
			    Event.addListener("plot_out_quarter","click",YAHOO.product.changeplot,'out_quarter');
			    Event.addListener("plot_out_year","click",YAHOO.product.changeplot,'out_year');
			    Event.addListener("plot_stock_day","click",YAHOO.product.changeplot,'stock_day');


// 			    Event.addListener("plot_view0","click",YAHOO.product.changeplot,0);
// 			    Event.addListener("plot_view1","click",YAHOO.product.changeplot,1);
// 			    Event.addListener("plot_view2","click",YAHOO.product.changeplot,2);
// 			    Event.addListener("plot_view3","click",YAHOO.product.changeplot,3);
// 			    Event.addListener("plot_view4","click",YAHOO.product.changeplot,4);
// 			    Event.addListener("plot_view5","click",YAHOO.product.changeplot,5);


			    Event.addListener("but_view0","click",YAHOO.product.changeview,0);
			    Event.addListener("but_view1","click",YAHOO.product.changeview,1);
			    Event.addListener("but_view2","click",YAHOO.product.changeview,2);
			    Event.addListener("but_view3","click",YAHOO.product.changeview,3);
			    Event.addListener("but_view4","click",YAHOO.product.changeview,4);
			    Event.addListener("but_view5","click",YAHOO.product.changeedit,5);


			    Event.addListener("oim_0","click",img_click,0);
			    Event.addListener("oim_1","click",img_click,1);
			    Event.addListener("oim_2","click",img_click,2);
			    Event.addListener("oim_3","click",img_click,3);
			    Event.addListener("oim_4","click",img_click,4);
			    Event.addListener("imagediv","click",img_click,-1);






    
			    var texteditorConfig = {
				height: '300px',
				width: '530px',
				dompath: true,
				focusAtStart: true
			    };


			    YAHOO.product.Editor = new YAHOO.widget.Editor('editor', texteditorConfig);
			    YAHOO.product.Editor._defaultToolbar.buttonType = 'basic';
			    YAHOO.product.Editor.render();


			    YAHOO.product.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?=_('Choose a date')?>:", close:true } );

			    YAHOO.product.cal1.update=updateCal;
			    YAHOO.product.cal1.id=1;

			    YAHOO.product.cal1.render();
			    YAHOO.product.cal1.update();

			    YAHOO.product.cal1.selectEvent.subscribe(CalhandleSelect, YAHOO.product.cal1, true); 
 




			    // YAHOO.util.Event.addListener("calpop1", "click", YAHOO.product.cal1.show, YAHOO.product.cal1, true);



		       
			    var myTooltip = new YAHOO.widget.Tooltip("myTooltip", { context:"upo_label,outall_label,awoutall_label,awoutq_label"} ); 
}

YAHOO.util.Event.onDOMReady(init);
