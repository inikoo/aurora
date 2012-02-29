<?php  include_once('common.php');

 ?>
//alert(Dom.get('store_key').value);
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var location_id=<?php echo $_REQUEST['location_id'] ?>;
var scope='product';
var store_key=1;
var dialog_family_list;
var dialog_part_list;
var warehouse_key=<?php echo $_REQUEST['warehouse_key'] ?>;
var Editor_change_part;
var location_name;

function change_block(e){
   
     Dom.setStyle(['description_block','parts_block'],'display','none');
 	 Dom.get(this.id+'_block').style.display='';
	 Dom.removeClass(['description','parts'],'selected');
	 Dom.addClass(this, 'selected');
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=location-edit&value='+this.id ,{});
   
}



function validate_location_code(query){
validate_general('location_description','code',unescape(query));
}
function validate_location_radius(query){
validate_general('location_description','radius',unescape(query));
}
function validate_location_deep(query){
validate_general('location_description','deep',unescape(query));
}
function validate_location_height(query){
validate_general('location_description','height',unescape(query));
}
function validate_location_width(query){
validate_general('location_description','width',unescape(query));
}

function validate_location_max_weight(query){
validate_general('location_description','weight',unescape(query));
}

function validate_location_max_volume(query){
validate_general('location_description','volume',unescape(query));
}
function validate_location_max_slots(query){
validate_general('location_description','slots',unescape(query));
}
function validate_location_distinct_parts(query){
validate_general('location_description','parts',unescape(query));
}




function reset_location(){
reset_edit_general('location_description')
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

var tableid=2; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=area_list&warehouse_key="+warehouse_key+"&tableid="+tableid+"&nr=20&sf=0");
//alert("ar_quick_tables.php?tipo=area_list&warehouse_key="+warehouse_key+"&tableid="+tableid+"&nr=20&sf=0");
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
			 "code",'name','key'
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", select_area);
        this.table2.table_id=tableid;
           this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};



		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"sku", label:"<?php echo _('SKU')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location_key", label:"", hidden:true,isPrimaryKey:true} 
				       ,{key:"part_sku", label:"", hidden:true,isPrimaryKey:true} 
				       ,{key:"description", label:"<?php echo _('Description')?>", width:470,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"can_pick", label:"<?php echo _('Can Pick')?>", width:80,className:"aright" ,editor: new YAHOO.widget.RadioCellEditor({radioOptions:["<?php echo _('Yes')?>","<?php echo _('No')?>"],disableBtns:true,asyncSubmitter: CellEdit}),object:'part_location'}
				       ,{key:"min", label:"<?php echo _('Min')?>", width:50,className:"aright", editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'part_location'}
				       ,{key:"max", label:"<?php echo _('Max')?>", width:50,className:"aright", editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'part_location'}
				       ,{key:"qty", label:"<?php echo _('Qty')?>", hidden:true,width:50,className:"aright", editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'part_location'}
				       ,{key:"move",label:"<?php echo _('Move')?>", hidden:true,width:30,className:"aright",action:'move'}
				       ,{key:"lost", label:"<?php echo _('Lost')?>",hidden:true, width:30,className:"aright",action:'lost'}
				       ,{key:"delete", label:"", width:30,className:"aright",object:'part_location',action:'delete'}
				     
				       ];
	    //alert("ar_warehouse.php?tipo=parts_at_location&sf=0&tableid="+tableid);
	    this.dataSource0 = new YAHOO.util.DataSource("ar_warehouse.php?tipo=parts_at_location&sf=0&tableid="+tableid);
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
			 "sku"
			 ,"description"
			 ,'qty'
			 ,'can_pick','move','audit','lost','delete','number_locations','number_qty','part_sku','location_key','part_stock','location', 'min', 'max'
		
			 ]};
	    
this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							   ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['location']['parts']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['location']['parts']['order']?>",
							     dir: "<?php echo$_SESSION['state']['location']['parts']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.table_id=tableid;
     	this.table0.subscribe("renderEvent", myrenderEvent);
    
	  

		


		    
	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);



	    this.table0.filter={key:'<?php echo$_SESSION['state']['location']['parts']['f_field']?>',value:'<?php echo$_SESSION['state']['location']['parts']['f_value']?>'};








}});


function delete_location(){
	
	if (confirm('Are you sure, you want to delete location '+location_name+' now?')) {
		var request='ar_edit_warehouse.php?tipo=delete_location&location_key=' + Dom.get('location_key').value + '&area_key=' + Dom.get('area_key').value
		//alert(request);//return;
		YAHOO.util.Connect.asyncRequest('POST',request ,{
			success:function(o) {
			//alert(o.responseText)
			var r =  YAHOO.lang.JSON.parse(o.responseText);


			if(r.state==200){
				window.location.href='warehouse.php';
			}
			else{
				alert(r.msg);
			}

			}
		});
	}



}

function save_location_used_for(key,value){

 var data_to_update=new Object;

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));



var request='ar_edit_warehouse.php?tipo=edit_location_description&key=' + key+ '&newvalue=' + value +'&location_key=' + location_id+'&okey=' + key
	//alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
//alert(o.responseText)
				var r =  YAHOO.lang.JSON.parse(o.responseText);


				if(r.state==200){

				window.location.reload()
            }

    }
    });

}



function select_area(oArgs){
//alert('ss');return;

area_key=tables.table2.getRecord(oArgs.target).getData('key');

 dialog_area_list.hide();


        var request = 'ar_edit_warehouse.php?tipo=edit_location_area&key=' + 'Location Warehouse Area Key' + '&newvalue=' + area_key+ '&id=' + location_id
         //alert(request);

        YAHOO.util.Connect.asyncRequest('POST', request, {
                success: function(o) {
                        //alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200) {

                                //Dom.get('current_department_code').innerHTML=r.newdata['code'];
				window.location.reload();



                        } else {


                                }
                                


                }
                


        });



}

function init(){
 var ids = ['description','parts']; 
    YAHOO.util.Event.addListener(ids, "click", change_block);

location_name=Dom.get('location_name').value;

number_regex="\\d+";
validate_scope_data=
{

    'location_description':{
	'code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Code','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Location Code')?>'}]}
	//,'stock':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Stock_Value','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Stock Value')?>'}]}

	//,'radius':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Location_Radius','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Radius')?>'}]}
	//,'deep':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Location_Deep','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Depth')?>'}]}	
	//,'height':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Location_Height','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Height')?>'}]}	
	//,'width':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Location_Width','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Width')?>'}]}	

	,'volume':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Location_Max_Volume','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Volume')?>'}]}	
	,'weight':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Location_Max_Weight','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Weight')?>'}]}


	//,'slots':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Location_Max_Slots','ar':false,'validation':[{'regexp':"\\d",'invalid_msg':'<?php echo _('Invalid Number')?>'}]}
	//,'parts':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Distinct_Parts','ar':false,'validation':[{'regexp':"\\d",'invalid_msg':'<?php echo _('Invalid parts')?>'}]}
	//,'stock_type':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'location_has_stock_type','ar':false}
	
	}

    };
	
	

	
validate_scope_metadata={
    'location_description':{'type':'edit','ar_file':'ar_edit_warehouse.php','key_name':'location_key','key':Dom.get('location_key').value}
    

};


  init_search('locations');


 

 
//Editor_change_part = new YAHOO.widget.Dialog("Editor_change_part", {width:'450px',close:false,visible:false,underlay: "none",draggable:false});
 //   Editor_change_part.render();
    
    
    






  


    Event.addListener('save_edit_location_description', "click", save_location);
    Event.addListener('reset_edit_location_description', "click", reset_location);


    
    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_location_code);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Location_Code","Location_Code_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;
   
    var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_radius);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Radius","Location_Radius_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_deep);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Deep","Location_Deep_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_height);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Height","Location_Height_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_width);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Width","Location_Width_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;

	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_max_weight);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Max_Weight","Location_Max_Weight_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


   var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_max_volume);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Max_Volume","Location_Max_Volume_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_max_slots);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Max_Slots","Location_Max_Slots_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;
	


	dialog_area_list = new YAHOO.widget.Dialog("dialog_area_list", {context:["edit_location_area","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_area_list.render();
	
		   

	
    Event.addListener("edit_location_area", "click", dialog_area_list.show,dialog_area_list , true);

}



YAHOO.util.Event.onDOMReady(init);





function save_location(){
save_edit_general('location_description');

}


function save_location_old(key,value){

alert('xx');

 var data_to_update=new Object;
 data_to_update={'okey':key,'value':value}

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_assets.php?tipo=edit_location&values='+ jsonificated_values+"&location_key="+location_id


//var request='ar_edit_contacts.php?tipo=edit_customer&key=' + key+ '&newvalue=' + value +'&customer_key=' + customer_id
	//alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
//alert(o.responseText)
//return;
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				  //alert(r.newvalue);
				if(r.state==200){
			
  
 
            if (r.new_data['type']=='used_for') {
				Dom.removeClass('used_for_'+r.new_data['old_value'],'selected');
				Dom.addClass('used_for_'+r.newvalue,'selected');

            }else if(r.new_data['type']=='shape'){
				
				Dom.removeClass('shape_'+r.new_data['old_value'],'selected');
				Dom.addClass('shape_'+r.newvalue,'selected');
            }else if(r.new_data['type']=='has_stock'){
				Dom.removeClass('has_stock_'+r.new_data['old_value'],'selected');
				Dom.addClass('has_stock_'+r.newvalue,'selected');
            }
        }
        
    }
    });




}

	