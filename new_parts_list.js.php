<?php
include_once('common.php');
?>
    var Event   = YAHOO.util.Event;

    var Dom   = YAHOO.util.Dom;
    
    
    
    
var dialog_country_list;
var dialog_wregion_list;
var dialog_postal_code_list;
var dialog_city_list;
var dialog_department_list;
var dialog_family_list;
var dialog_product_list;
var dialog_category_list;

var searched=false;
function save_search_list(){

	var list_name = Dom.get('list_name').value;
	
	if(list_name==''){
	Dom.get('save_list_msg').innerHTML=Dom.get('error_no_name').innerHTML;
	return;
	}
	
	
	if(Dom.get('dynamic').checked == true)
	{
		var list_type = 'Dynamic'
	}else{
	var list_type = 'Static';	
	}
	
	var awhere=get_awhere();


  Dom.setStyle('the_table','display','none');
    Dom.setStyle('saving','display','');
    Dom.setStyle('saving_form','display','none');


	var request="ar_edit_assets.php?tipo=new_parts_list&list_name="+list_name+'&list_type='+list_type+'&parent_key='+Dom.get('warehouse_key').value+'&awhere='+awhere;
//	alert(request);//return;
	
		YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
//		alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			location.href='parts_list.php?id='+r.customer_list_key;

		    }else
			
			 Dom.setStyle('the_table','display','');
    Dom.setStyle('saving','display','none');
    Dom.setStyle('saving_form','display','');
			Dom.get('save_list_msg').innerHTML=r.msg;
			
		}
	    });  
	
	
	
	

}



var data_returned=function(){
	 if(searched){
	     Dom.get('searching').style.display='none';
	     Dom.get('the_table').style.display='';
	     Dom.get('save_list').style.display='';
	     Dom.get('modify_search').style.display='';
	     Dom.get('submit_search').style.display='none';


	 }	 
    }
    
    
    function checkbox_changed_have(o){

cat=Dom.get(o).getAttribute('cat');
this_parent=Dom.get(o).getAttribute('parent');
if(this_parent=='have_')
    other_parent='dont_have_';
else//
    other_parent='have_';
    
if(    Dom.hasClass(o,'selected')){
    Dom.removeClass(o,'selected');
        

}else{
    Dom.addClass(o,'selected');
    Dom.removeClass(other_parent+cat,'selected');

}

}
 
 
function select_country(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints=geo_constraints+tables.table2.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_country_list.hide();
    hide_filter(true,2)
}

function select_postal_code(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints=geo_constraints+tables.table3.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_postal_code_list.hide();
    hide_filter(true,3)
}

function select_wregion(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints='wr('+geo_constraints+tables.table1.getRecord(oArgs.target).getData('wregion_code').replace(/<.*?>/g, '')+')';
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_wregion_list.hide();
    hide_filter(true,1)
}

function select_city(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints=geo_constraints+tables.table4.getRecord(oArgs.target).getData('city').replace(/<.*?>/g, '');
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_wregion_list.hide();
    hide_filter(true,4)
}
function select_department(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table5.getRecord(oArgs.target).getData('department_code').replace(/<.*?>/g, '');
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_department_list.hide();
    hide_filter(true,5)
}

function select_family(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table6.getRecord(oArgs.target).getData('family_code').replace(/<.*?>/g, '');
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_family_list.hide();
    hide_filter(true,6)
}
function select_product(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table7.getRecord(oArgs.target).getData('product_code').replace(/<.*?>/g, '');
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_product_list.hide();
    hide_filter(true,7)
}
function select_category(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table8.getRecord(oArgs.target).getData('category_code').replace(/<.*?>/g, '');
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_category_list.hide();
    hide_filter(true,8)
}
    
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	   var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 

				    {key:"sku", label:"<?php echo _('SKU')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description", label:"<?php echo _('Description')?>",width:290,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description_small", label:"<?php echo _('Description')?>",width:200,<?php echo($_SESSION['state']['warehouse']['parts']['view']!='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"used_in", label:"<?php echo _('Used In')?>",width:200,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied_by", label:"<?php echo _('Supplied By')?>",width:200,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   
				   	,{key:"locations", label:"<?php echo _('Locations')?>", width:200,sortable:false,className:"aleft",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='locations' )  ?'':'hidden:true')?>}
				    ,{key:"stock", label:"<?php echo _('Stock')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='locations' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //  ,{key:"available_for", label:"<?php echo _('S Until')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_value", label:"<?php echo _('Stk Value')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='locations')?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"avg_stock", label:"<?php echo _('AS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"avg_stockvalue", label:"<?php echo _('ASV')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"keep_days", label:"<?php echo _('KD')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outstock_days", label:"<?php echo _('OofS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"unknown_days", label:"<?php echo _('?S')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'   ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"sold", label:"<?php echo _('Sold(Given) Qty')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //   ,{key:"given", label:"<?php echo _('Given Qty')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				    ,{key:"money_in", label:"<?php echo _('Sold')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    //    ,{key:"profit", label:"<?php echo _('Profit Out')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit_sold", label:"<?php echo _('Profit (Inc Given)')?>", width:160,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      

			       ];
request="ar_parts.php?tipo=parts&parent=warehouse&parent_key="+Dom.get('warehouse_key').value+"&tableid=0";

	    this.dataSource0 = new YAHOO.util.DataSource(request);
		//alert("ar_assets.php?tipo=parts&parent=store&tableid=0");
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
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'sku','description', 'used_in', 'supplied_by', 'stock', 'sold', 'available_for', 'stock_value'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['store']['products']['nr']+1?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse']['parts']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse']['parts']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
	    

 this.table0.subscribe("dataReturnEvent", data_returned);  
	    
	    this.table0.view='<?php echo $_SESSION['state']['warehouse']['parts']['view']?>';
	    this.table0.filter={key:'<?php echo $_SESSION['state']['warehouse']['parts']['f_field']?>',value:'<?php echo $_SESSION['state']['warehouse']['parts']['f_value']?>'};
		
	



   var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
			
			
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                   ,{key:"code",formatter:"remove_links", label:"<?php echo _('Code')?>",width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", formatter:"remove_links",label:"<?php echo _('Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			     // ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     //  ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      
			   //   ,{key:"wregion", label:"<?php echo _('Region')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
			      
	     


			
			
			];
			       
	    this.dataSource2 = new YAHOO.util.DataSource("ar_regions.php?tipo=country_list&tableid=2&nr=20&sf=0");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    	    this.dataSource2.table_id=tableid;

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
		
		
		fields: [
			 "name","flag",'code','population','gnp','wregion'
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['countries']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['countries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['countries']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.table_id=tableid;
     this.table2.subscribe("renderEvent", myrenderEvent);
	    
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", select_country);
     


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['world']['countries']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['countries']['f_value']?>'};
	    //

// --------------------------------------Postal code table starts here--------------------------------------------------------
//=============

   var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
			
			
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                   ,{key:"code",formatter:"remove_links", label:"<?php echo _('Postal Code')?>",width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", formatter:"remove_links",label:"<?php echo _('Country Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			     // ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     //  ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      
			   //   ,{key:"wregion", label:"<?php echo _('Region')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
			      
	     


			
			
			];
			       
	    this.dataSource3 = new YAHOO.util.DataSource("ar_regions.php?tipo=postal_code&tableid=3&nr=20&sf=0");
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    	    this.dataSource3.table_id=tableid;

	    this.dataSource3.responseSchema = {
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
			 "name","flag",'code','population','gnp','wregion'
			 ]};

	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['countries']['nr']?>,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['countries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['countries']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table3.subscribe("rowMouseoverEvent", this.table3.onEventHighlightRow);
       this.table3.subscribe("rowMouseoutEvent", this.table3.onEventUnhighlightRow);
      this.table3.subscribe("rowClickEvent", select_postal_code);
     
this.table3.table_id=tableid;
     this.table3.subscribe("renderEvent", myrenderEvent);

	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.filter={key:'<?php echo$_SESSION['state']['world']['countries']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['countries']['f_value']?>'};
	    //

	
	};

    });



function get_awhere(){

	price=Dom.getElementsByClassName('selected', 'span', 'price_option');
    price_array= new Array();
    for(x in price){
        price_array[x]=price[x].getAttribute('cat');
    }
	invoice=Dom.getElementsByClassName('selected', 'span', 'invoice_option');
    invoice_array= new Array();
    for(x in invoice){
        invoice_array[x]=invoice[x].getAttribute('cat');
    }	
	
	web_state=Dom.getElementsByClassName('selected', 'span', 'web_state_option');
    web_state_array= new Array();
    for(x in web_state){
        web_state_array[x]=web_state[x].getAttribute('cat');
    }
	
	availability_state=Dom.getElementsByClassName('selected', 'span', 'availability_state_option');
    availability_state_array= new Array();
    for(x in availability_state){
        availability_state_array[x]=availability_state[x].getAttribute('cat');
    }
	
	if(Dom.hasClass('tariff_code_invalid','selected')){
		invalid_tariff_code='No'
	}else if(Dom.hasClass('tariff_code_valid','selected')){
		invalid_tariff_code='Yes'
	}
	
	else{
		invalid_tariff_code=''
	}
	tariff_code=Dom.get('tariff_code').value
	
	
    var data={ 
    invalid_tariff_code:invalid_tariff_code,
    tariff_code:tariff_code,
	
	part_dispatched_from:Dom.get('v_calpop1').value,
	part_dispatched_to:Dom.get('v_calpop2').value,
	
	//price:price_array,
	//invoice:invoice_array,
	//web_state:web_state_array,
	//availability_state:availability_state_array,
	geo_constraints:Dom.get('geo_constraints').value,
	//product_ordered1:Dom.get('product_ordered_or').value,
	//product_not_ordered1: Dom.get('product_not_ordered1').value,
	//product_not_received1: Dom.get('product_not_received1').value,
	part_valid_from:Dom.get('v_calpop3').value,
	part_valid_to:Dom.get('v_calpop4').value,
	//price_lower:Dom.get('price_lower').value,
	//price_upper:Dom.get('price_upper').value,
	//invoice_lower:Dom.get('invoice_lower').value,
	//invoice_upper:Dom.get('invoice_upper').value,
    }



    return YAHOO.lang.JSON.stringify(data);

   

}


function submit_search(e){



searched=true;

  
   var awhere=get_awhere();

    var table=tables.table0;
    var datasource=tables.dataSource0;
	warehouse_id=Dom.get('warehouse_id').value;
    var request='&sf=0&parent=warehouse&parent_key='+Dom.get('warehouse_id').value+'&where=' +awhere;

    Dom.setStyle('the_table','display','none');
    Dom.setStyle('searching','display','');
    Dom.setStyle('save_dialog','visibility','visible');

	//alert(request);
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     

}
function submit_search_on_enter(e,tipo){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     
     if (key == 13)
	 submit_search(e,tipo);
};


function hide_price(){
		Dom.setStyle('price_upper','display','none')
		Dom.setStyle('a','display','none')
		Dom.get('price_upper').value=''
}
 
function checkbox_changed_price_condition(o){
	cat=Dom.get(o).getAttribute('cat');

	if(cat=='less'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			hide_price();
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'price_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='equal'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			hide_price();
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'price_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='more'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			hide_price();
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'price_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='between'){
		Dom.setStyle('price_upper','display','')
		Dom.setStyle('a','display','')
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'price_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}
	
} 


function hide_invoice(){
		Dom.setStyle('invoice_upper','display','none')
		Dom.setStyle('b','display','none')
		Dom.get('invoice_upper').value=''
}
 
function checkbox_changed_invoice_condition(o){
	cat=Dom.get(o).getAttribute('cat');

	if(cat=='less'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			hide_invoice();
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'invoice_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='equal'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			hide_invoice();
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'invoice_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='more'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			hide_invoice();
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'invoice_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='between'){
		Dom.setStyle('invoice_upper','display','')
		Dom.setStyle('b','display','')
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'invoice_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}
	
} 


function checkbox_changed_web_state_condition(o){
	cat=Dom.get(o).getAttribute('cat');

	if(cat=='online_force_out_of_stock'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'web_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='online_auto'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'web_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='offline'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'web_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='unknown'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'web_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='online_force_for_sale'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'web_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}
}

function checkbox_changed_availability_state_condition(o){
	cat=Dom.get(o).getAttribute('cat');

	if(cat=='optimal'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'availability_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='low'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'availability_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='critical'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'availability_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='surplus'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'availability_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='out_of_stock'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'availability_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='unknown'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'availability_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='no_applicable'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'availability_state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}
}


function tariff_code_invalid(){



if(Dom.hasClass('tariff_code_invalid','selected')){
Dom.setStyle('tariff_code','display','')
Dom.removeClass(['tariff_code_valid','tariff_code_invalid'],'selected')

}else{
Dom.removeClass('tariff_code_valid','selected')
Dom.addClass('tariff_code_invalid','selected')
Dom.setStyle('tariff_code','display','none')

}

}


function tariff_code_valid(){
if(Dom.hasClass('tariff_code_valid','selected')){
Dom.setStyle('tariff_code','display','')
Dom.removeClass(['tariff_code_valid','tariff_code_invalid'],'selected')

}else{

Dom.addClass('tariff_code_valid','selected')
Dom.removeClass('tariff_code_invalid','selected')

Dom.setStyle('tariff_code','display','none')

}

}
function select_european_union_countries(){
dialog_country_list.hide();

var request='ar_kbase.php?tipo=add_european_union_countries&current_countries='+Dom.get('geo_constraints').value
	           alert(request)
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	           alert(o.responseText);	
			    var r =  YAHOO.lang.JSON.parse(o.responseText);
			    if(r.state==200){
                    Dom.get('geo_constraints').value=r.geo_constraints;
                }
   			}
    });

}

function init(){

 YAHOO.util.Event.addListener('dialog_country_list_european_union', "click",select_european_union_countries);
 var ids=['parts_general','parts_stock','parts_sales','parts_forecast','parts_locations'];
YAHOO.util.Event.addListener(ids, "click",change_parts_view,0);
 ids=['parts_period_all','parts_period_three_year','parts_period_year','parts_period_yeartoday','parts_period_six_month','parts_period_quarter','parts_period_month','parts_period_ten_day','parts_period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_parts_period,0);
 ids=['parts_avg_totals','parts_avg_month','parts_avg_week',"parts_avg_month_eff","parts_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_parts_avg,0);


  init_search('parts');


YAHOO.util.Event.addListener('tariff_code_invalid', "click",tariff_code_invalid);
YAHOO.util.Event.addListener('tariff_code_valid', "click",tariff_code_valid);



YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);
 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 
 
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 oACDS1.table_id=1;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 

    dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", {context:["country","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_country_list.render();
    Event.addListener("country", "click", dialog_country_list.show,dialog_country_list , true);

    dialog_wregion_list = new YAHOO.widget.Dialog("dialog_wregion_list", {context:["wregion","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_wregion_list.render();
    Event.addListener("wregion", "click", dialog_wregion_list.show,dialog_wregion_list , true);

    dialog_city_list = new YAHOO.widget.Dialog("dialog_city_list", {context:["city","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_city_list.render();
    Event.addListener("city", "click", dialog_city_list.show,dialog_city_list , true);


    dialog_postal_code_list = new YAHOO.widget.Dialog("dialog_postal_code_list", {context:["postal_code","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_postal_code_list.render();
    Event.addListener("postal_code", "click", dialog_postal_code_list.show,dialog_postal_code_list , true);

    dialog_department_list = new YAHOO.widget.Dialog("dialog_department_list", {context:["department","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_department_list.render();
    Event.addListener("department", "click", dialog_department_list.show,dialog_department_list , true);

    dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {context:["family","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_family_list.render();
    Event.addListener("family", "click", dialog_family_list.show,dialog_family_list , true);

    dialog_product_list = new YAHOO.widget.Dialog("dialog_product_list", {context:["product","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_product_list.render();
    Event.addListener("product", "click", dialog_product_list.show,dialog_product_list , true);

    dialog_category_list = new YAHOO.widget.Dialog("dialog_category_list", {context:["category","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_category_list.render();
    Event.addListener("category", "click", dialog_category_list.show,dialog_category_list , true);
	


YAHOO.util.Event.addListener(['submit_search','modify_search'], "click",submit_search);
YAHOO.util.Event.addListener(['product_ordered1'], "keydown",submit_search_on_enter);
YAHOO.util.Event.addListener(['save_list'], "click",save_search_list);


//var ids=['general','contact'];
//YAHOO.util.Event.addListener(ids, "click",change_view);

cal1 = new YAHOO.widget.Calendar("part_dispatched_from","part_dispatched_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal1.update=updateCal;
 cal1.id='1';
 cal1.render();
 cal1.update();
 cal1.selectEvent.subscribe(handleSelect, cal1, true); 

 cal2 = new YAHOO.widget.Calendar("part_dispatched_to","part_dispatched_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal2.update=updateCal;
 cal2.id='2';
 cal2.render();
 cal2.update();
 cal2.selectEvent.subscribe(handleSelect, cal2, true); 

cal3 = new YAHOO.widget.Calendar("part_created_from","part_created_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal3.update=updateCal;
 cal3.id='3';
 cal3.render();
 cal3.update();
cal3.selectEvent.subscribe(handleSelect, cal3, true); 

cal4 = new YAHOO.widget.Calendar("part_created_to","part_created_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal4.update=updateCal;
 cal4.id='4';
 cal4.render();
 cal4.update();
cal4.selectEvent.subscribe(handleSelect, cal4, true); 





//cal2.cfg.setProperty("iframe", true);
//cal2.cfg.setProperty("zIndex", 10);



YAHOO.util.Event.addListener("part_dispatched_from", "click", cal1.show, cal1, true);
YAHOO.util.Event.addListener("part_dispatched_to", "click", cal2.show, cal2, true);
YAHOO.util.Event.addListener("part_created_from", "click", cal3.show, cal3, true);
YAHOO.util.Event.addListener("part_created_to", "click", cal4.show, cal4, true);

}

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });

