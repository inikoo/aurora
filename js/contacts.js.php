<?
include_once('../common.php');
?>


YAHOO.namespace ("contacts"); 


YAHOO.util.Event.addListener(window, "load", function() {
	YAHOO.contacts.XHR_JSON = new function() {
		this.contactLink=  function(el, oRecord, oColumn, oData) {
		    var url="contact.php?id="+oRecord.getData("id");
		    el.innerHTML = oData.link(url);
	    }
		    
		    this.companyLink=  function(el, oRecord, oColumn, oData) {
			

			if(oData!=null){
			    var url="contact.php?id="+oRecord.getData("company_id");
		el.innerHTML = oData.link(url);
			}else
			    el.innerHTML = '';
	    }
			
			this.contactTipo=  function(el, oRecord, oColumn, oData) {
		 if(oData==2)
		     el.innerHTML = '<img src="art/icons/user.png" alt="<?_('Person')?>"/>';
		 if(oData==3)
		     el.innerHTML = '<img src="art/icons/user_female.png" alt="<?_('Person')?>"/>';
		 else if(oData==1)
		     el.innerHTML = '<img src="art/icons/building.png" alt="<?_('Bussiness')?>"/>';
			}
	    
			    
			//START OF THE TABLE=========================================================================================================================
			    
		var tableid=0; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		

		
		var ContactsColumnDefs = [
				      {key:"tipo", label:"", width:16,sortable:true,formatter:this.contactTipo,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      {key:"name", label:"<?=_('Name')?>", width:200,sortable:true,formatter:this.contactLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      //   {key:"company", label:"<?=_('Company')?>",width:80, sortable:true,formatter:this.companyLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      //{key:"email", label:"<?=_('Email')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      //{key:"tel", label:"<?=_('Telephone')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				      
				      //					 {key:"families", label:"<?=_('Contacts')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      //{key:"active", label:"<?=_('Contacts')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      

					 ];

	    this.ContactsDataSource = new YAHOO.util.DataSource("ar_contacts.php?tipo=contacts&tid=0");
	    this.ContactsDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.ContactsDataSource.connXhrMode = "queueRequests";
	    this.ContactsDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","name","email","tipo","company","company_id","tel"
			 //,{key:"families",parser:YAHOO.util.DataSource.parseNumber},
			 //	    {key:"active",parser:YAHOO.util.DataSource.parseNumber}
			 ]};
	    //__You shouls not change anything from here

	    this.ContactsDataSource.doBeforeCallback = mydoBeforeCallback;



	    this.ContactsDataTable = new YAHOO.widget.DataTable(tableDivEL, ContactsColumnDefs,
								   this.ContactsDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	 
	    this.ContactsDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
	    this.ContactsDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.ContactsDataTable}  } ]);
	    this.ContactsDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.ContactsDataTable}  } ]);
	    this.ContactsDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.ContactsDataTable}  } ]);
	    this.ContactsDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.ContactsDataTable}  } ]);
	    this.ContactsDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.ContactsDataTable}  } ]);
	    this.ContactsDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.ContactsDataTable}  } ]);
	    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.ContactsDataTable.paginatorMenu.show, null, this.ContactsDataTable.paginatorMenu);
	    this.ContactsDataTable.paginatorMenu.render(document.body);

	    



	    this.ContactsDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu0',  {context:['filterselector0',"tr", "br"]  });
	    this.ContactsDataTable.filterMenu.addItems([{ text: "<?=_('Name')?>", onclick:{fn:changeFilter,obj:{col:'name',text:"<?=_('Customer name')?>"},scope:this.ContactsDataTable}  } ]);
	    this.ContactsDataTable.filterMenu.addItems([{ text: "<?=_('Post Code')?>", onclick:{fn:changeFilter,obj:{col:'postcode',text:"<?=_('Post Code')?>"},scope:this.ContactsDataTable}  } ]);
	    YAHOO.util.Event.addListener('filterselector0', "click", this.ContactsDataTable.filterMenu.show, null, this.ContactsDataTable.filterMenu);
	    this.ContactsDataTable.filterMenu.render(document.body);
	    
	    this.ContactsDataTable.myreload=reload;
	    this.ContactsDataTable.sortColumn = mysort;
	    
	    this.ContactsDataTable.id=tableid;
	    this.ContactsDataTable.editmode=false;

	    this.ContactsDataTable.subscribe("initEvent", dataReturn); 
	    YAHOO.util.Event.addListener('paginator_next0', "click", nextpage, this.ContactsDataTable); 
	    YAHOO.util.Event.addListener('paginator_prev0', "click", prevpage, this.ContactsDataTable); 
	    YAHOO.util.Event.addListener('hidder0', "click", showtable, this.ContactsDataTable); 
	    YAHOO.util.Event.addListener('resetfilter0', "click", resetfilter, this.ContactsDataTable); 


	    
	    }
	    })
    

    
    function init(){

    var Dom = YAHOO.util.Dom;
    var Event = YAHOO.util.Event; 
    function mygetTerms(query) {
	var Dom = YAHOO.util.Dom
	var table=YAHOO.contacts.XHR_JSON.ContactsDataTable;
	var data=table.getDataSource();
	var newrequest="&sf=0&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

	//	alert(newrequest);
	data.sendRequest(newrequest,{success:table.onDataReturnInitializeTable, scope:table});
    };
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    






    YAHOO.contacts.dialog1 = new YAHOO.widget.Panel("add_contact_dialog", 
						  { 
							  width:"300px",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							  close:true
							  //							  buttons : [ { text:"Submit", handler:handleSubmit, isDefault:true },{ text:"Cancel", handler:handleCancel } ]
						  } );

    YAHOO.contacts.dialog1.render();

    
    var showdialog =function(){
	YAHOO.contacts.dialog1.show();
    };
    

    

 var newperson =function(){
   document.location="new_contact.php?tipo=person&from=contacts";
 }
 var newcompany =function(){
   document.location="new_contact.php?tipo=company&from=contacts";
 }




 Event.addListener( "add_contact" ,"click",showdialog);

 Event.addListener( "newperson" ,"click",newperson);
 Event.addListener( "newcompany" ,"click",newcompany);



    //var CancelNewContactButton= new YAHOO.widget.Button("cancel_newcontact");
    //CancelNewContactButton.addListener( "click",closedialog);
    
}




YAHOO.util.Event.onDOMReady(init);

