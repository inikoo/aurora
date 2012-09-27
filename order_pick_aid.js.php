<?php
include_once('common.php');
$order_key=0;
if(isset($_REQUEST['dn_key']) )
    $dn_key=$_REQUEST['dn_key'];
print "var dn_key=$dn_key;";
?>

YAHOO.namespace ("invoice"); 
 var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;
var updating_record;
var no_dispatchable_editor_dialog;
var assign_packer_dialog;
var dialog_locations;


var myonCellClick = function(oArgs) {


    var target = oArgs.target,
    column = this.getColumn(target),
    record = this.getRecord(target);


    
    datatable = this;
    var records=this.getRecordSet();
    //alert(records.getLength())
   

    //return;

    //alert(datatable)
    var recordIndex = this.getRecordIndex(record);

		
    switch (column.action) {
    case('edit_object'):
    
    updating_record=record;
    
    var data = record.getData();
    Dom.get('formated_todo_units').innerHTML=data['formated_todo'];
    Dom.get('todo_units').value=data['todo'];
    Dom.get('todo_itf_key').value=data['itf_key'];
    Dom.get('out_of_stock_units').value=(data['out_of_stock']==0)?'':data['out_of_stock'];
        Dom.get('required_units').value=data['required'];
    Dom.get('picked_units').value=data['picked'];

    Dom.get('not_found_units').value=(data['not_found']==0)?'':data['not_found']
    Dom.get('no_picked_other_units').value=(data['no_picked_other']==0)?'':data['no_picked_other']

    Dom.get('to_assign_todo_units').innerHTML=data['todo']-data['out_of_stock']-data['not_found']-data['no_picked_other'];


 var y=(Dom.getY(target))
   var x=(Dom.getX(target))


 if(Dom.get('todo_units').value<0){
 alert("caca")
 
 }else{
 
  x=x-120;
    y=y+18;
    Dom.setX('no_dispatchable_editor_dialog', x)
    Dom.setY('no_dispatchable_editor_dialog', y)
 
    no_dispatchable_editor_dialog.show();
}

    break;
    case('add_object'):
    case('remove_object'):
    case('check_all_object'):


	var data = record.getData();
	
	
	
	
 if(data['picked']==''){
	        data['picked']=0;
	        }
	
	if(column.action=='check_all_object'){
	
	  
	        
	  //  var new_qty=parseFloat(data['picked'])+1;
	  
	  
	  	  pending=data['required']-data['out_of_stock']-data['not_found']-data['no_picked_other']-data['packed']


if(pending==0 )
return;



	
	        new_qty=pending;
	    
	    
	}
	else if(column.action=='add_object'){
	
	  
	        
	    var new_qty=parseFloat(data['picked'])+1;
	  
	  
	  	  pending=data['required']-data['out_of_stock']-data['not_found']-data['no_picked_other']-data['packed']

if(pending==0 || new_qty>pending)
return;

	// alert('('+new_qty+'>'+pending+')  '+data['required']+' o:'+data['out_of_stock']+' '+data['not_found']+' '+data['no_picked_other'])
	//  return;
	 // alert(new_qty)
	
	    
	    
	    
	}
	else{
	    qty=parseFloat(data['picked']-data['packed'])
	    if(qty==0){
	        return;
	    }
	    var new_qty=qty-1;

        }


        if(new_qty==data['picked'])
            return;

var picker_key=Dom.get('assigned_picker').getAttribute('key');

 var ar_file='ar_edit_orders.php';
	request='tipo=pick_order&dn_key='+dn_key+'&key=quantity&new_value='+new_qty+'&itf_key='+ data['itf_key']+'&picker_key='+picker_key;
//	alert(request);
	//return;
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					 //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					    if(r.result=='updated'){
					    	datatable.updateCell(record,'picked',r.picked);
					    	if(r.formated_todo==0)
					    	    r.formated_todo='';
					    	datatable.updateCell(record,'formated_todo',r.formated_todo);
                            datatable.updateCell(record,'todo',r.todo);
					        
					        
					        if(r.todo){
					         	datatable.updateCell(record,'add','+');
					         	datatable.updateCell(record,'check_mark','<span style="color:#ccc">&#x2713;</span>');
					        }else{
					        	datatable.updateCell(record,'add','<span style="color:#ccc">+</span>');
					        	datatable.updateCell(record,'check_mark','&#x2713;');

					        }
					        
					        if((r.picked-r.packed)>0){
					        datatable.updateCell(record,'remove','-');

					        }else{
					        datatable.updateCell(record,'remove','<span style="color:#ccc">-</span>');
					        }
					        
					        
					   
					        
					        
					        
					        Dom.get('number_picked_transactions').innerHTML=r.number_picked_transactions;
					        Dom.get('number_transactions').innerHTML=r.number_transactions;
					        Dom.get('percentage_picked').innerHTML=r.percentage_picked;


				
					        if(r.number_picked_transactions>=r.number_transactions){
					            Dom.setStyle(['pick_all','update_locations'],'display','none');
					        }else{
					            Dom.setStyle(['pick_all','update_locations'],'display','');
					        }
                     
                                   
                        
                        }
					  
					
					    } else {
						alert(r.msg);
						//	callback();
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					    // callback();
					},
					    scope:this
					    },
				    request
				    
				    );  
	
	break;
   
		    
    default:
		    
	this.onEventShowCellEditor(oArgs);
	break;
    }
};   
var CellEdit = function (callback, newValue) {
      
    var record = this.getRecord(),
    column = this.getColumn(),
    oldValue = this.value,
    datatable = this.getDataTable();
    var records=datatable.getRecordSet();
    var ar_file='ar_edit_orders.php';
    
    var data = record.getData();
    
    var picker_key=Dom.get('assigned_picker').getAttribute('key');
    	  pending=data['required']-data['out_of_stock']-data['not_found']-data['no_picked_other']-data['packed']

    if(newValue>pending)
        new_qty=pending
    else
        new_qty=newValue

 var ar_file='ar_edit_orders.php';
	request='tipo=pick_order&dn_key='+dn_key+'&key=quantity&new_value='+new_qty+'&itf_key='+ data['itf_key']+'&picker_key='+picker_key;
    
    
    //request='tipo=edit_new_post_order&order_key='+data['order_key']+'&key='+column.object+'&new_value='+encodeURIComponent(newValue)+'&otf_key='+ data['otf_key'];
   // var request='tipo=edit_'+column.object+'&id='+order_key+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
  //  alert('R:'+request);
//return;
    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					    
					     if(r.result=='updated'){
					    
					    					    	datatable.updateCell(record,'picked',r.picked);
					    	if(r.formated_todo==0)
					    	    r.formated_todo='';
					    	datatable.updateCell(record,'formated_todo',r.formated_todo);
                            datatable.updateCell(record,'todo',r.todo);
					        
					        Dom.get('number_picked_transactions').innerHTML=r.number_picked_transactions;
					        Dom.get('number_transactions').innerHTML=r.number_transactions;
					        Dom.get('percentage_picked').innerHTML=r.percentage_picked;
					    
					    
					    
					    	if(r.todo){
					         	datatable.updateCell(record,'add','+');
					         	datatable.updateCell(record,'check_mark','<span style="color:#ccc">&#x2713;</span>');
					        }else{
					        	datatable.updateCell(record,'add','<span style="color:#ccc">+</span>');
					        	datatable.updateCell(record,'check_mark','&#x2713;');

					        }
					        
					        if((r.picked-r.packed)>0){
					        datatable.updateCell(record,'remove','-');

					        }else{
					        datatable.updateCell(record,'remove','<span style="color:#ccc">-</span>');
					        }
				
					    
					     //datatable.updateCell(record,'picked',r.picked);
					     //datatable.updateCell(record,'todo',r.todo);
					     //Dom.get('number_picked_transactions').innerHTML=r.number_picked_transactions;
					       // Dom.get('number_transactions').innerHTML=r.number_transactions;
					        //Dom.get('percentage_picked').innerHTML=r.percentage_picked;
					        
					        
					     	        if(r.number_picked_transactions>=r.number_transactions){
					            Dom.setStyle(['pick_all','update_locations'],'display','none');
					        }else{
					            Dom.setStyle(['pick_all','update_locations'],'display','');
					        }
                     
						
                      }
                        
					    callback(true,r.new_value);
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
					    },
				    request
				    
				    );  
  };

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


		
	    //START OF THE TABLE=========================================================================================================================
		
		var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
	    				     	{key:"itf_key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

				     ,{key:"sku", label:"<?php echo _('Part')?>",hidden:true,width:75,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						     ,{key:"picking_notes",label:"<?php echo _('Reference')?>", width:90,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
												  ,{key:"location",label:"<?php echo _('Location')?>", hidden:(Dom.get('method').value!='Inikoo'?false:true),width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

								,{key:"quantity",label:"<?php echo _('Qty')?>", hidden:(Dom.get('method').value!='Inikoo'?false:true),width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

		,{key:"description",label:"<?php echo _('Description')?>", width:(Dom.get('method').value=='Inikoo'?260:390),sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				//	,{key:"picking_notes",label:"<?php echo _('Notes')?>", width:150,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				
				// 	,{key:"used_in", label:"<?php echo _('Sold as')?>",width:230,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"location",label:"<?php echo _('Location')?>", hidden:(Dom.get('method').value=='Inikoo'?false:true),width:180,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

                
                
                ,{key:"picked",label:"<?php echo _('Picked')?>",hidden:(Dom.get('method').value=='Inikoo'?false:true), width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'pick_aid'}
										,{key:"check_mark",label:"", hidden:(Dom.get('method').value=='Inikoo'?false:true),width:3,sortable:false,action:'check_all_object',object:'pick_aid'}

					,{key:"add",label:"", hidden:(Dom.get('method').value=='Inikoo'?false:true),width:3,sortable:false,action:'add_object',object:'pick_aid'}
					,{key:"remove",label:"",hidden:(Dom.get('method').value=='Inikoo'?false:true), width:3,sortable:false,action:'remove_object',object:'pick_aid'}
					,{key:"formated_todo",label:"<?php echo _('Pending')?>",hidden:(Dom.get('method').value=='Inikoo'?false:true), width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},action:'edit_object',object:'pending_transactions'}
					,{key:"notes",label:"<?php echo _('Notes')?>",hidden:(Dom.get('method').value=='Inikoo'?false:true), width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},action:'edit_object',object:'pending_transactions'}
					,{key:"out_of_stock",label:"", width:1,hidden:true}
					,{key:"not_found",label:"", width:1,hidden:true}
					,{key:"no_picked_other",label:"", width:1,hidden:true}
					//,{key:"no_authorized",label:"", width:1,hidden:true}
					,{key:"packed",label:"", width:1,hidden:true}
								//	   ,{key:"quantity",label:"<?php echo _('Qty')?>", hidden:(Dom.get('method').value!='Inikoo'?false:true),width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				   ];

		//alert("ar_edit_orders.php?tipo=picking_aid_sheet&tid=0&dn_key="+Dom.get('dn_key').value);
	    this.pick_aidDataSource = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=picking_aid_sheet&tid=0&dn_key="+Dom.get('dn_key').value);
	    //alert("ar_edit_orders.php?tipo=picking_aid_sheet&tid=0&dn_key="+Dom.get('dn_key').value);
	   
	    this.pick_aidDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.pick_aidDataSource.connXhrMode = "queueRequests";
	    this.pick_aidDataSource.responseSchema = {
		resultsList: "resultset.data", 
		fields: [
			 "sku"
			 ,"used_in"
			 ,"description"
			 ,"location","picking_notes","packed","picking_notes"
			 ,"quantity","picked","add","remove","itf_key","todo","notes","required",'out_of_stock','not_found','formated_todo',"no_picked_other","check_mark"
			
			 ]};
	    this.pick_aidDataTable = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.pick_aidDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	

 this.pick_aidDataTable.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.pick_aidDataTable.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.pick_aidDataTable.subscribe("cellClickEvent", myonCellClick);
	


var tableid=2; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Alias')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=active_staff_list&active=Yes&tableid="+tableid+"&nr=20&sf=0");
//alert("ar_quick_tables.php?tipo=active_staff_list&active=Yes&tableid="+tableid+"&nr=20&sf=0");
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
      this.table2.subscribe("rowClickEvent", select_staff_from_list);
        this.table2.table_id=tableid;
           this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};	    

    
    };
  });


function add_no_dispatchable(tipo){

to_assign=  parseFloat((Dom.get('formated_todo_units').innerHTML=='')?0:Dom.get('formated_todo_units').innerHTML);
no_dipatchable_units=parseFloat((Dom.get(tipo).value=='')?0:Dom.get(tipo).value);
if(to_assign>0){
    
    if(to_assign<1){
        transfer=to_assign;
    }else{
        transfer=1;
    }
    
    Dom.get('formated_todo_units').innerHTML=to_assign-transfer;
    Dom.get(tipo).value=no_dipatchable_units+transfer;

}

}
function remove_no_dispatchable(tipo){
to_assign=  parseFloat((Dom.get('formated_todo_units').innerHTML=='')?0:Dom.get('formated_todo_units').innerHTML);
no_dipatchable_units=parseFloat((Dom.get(tipo).value=='')?0:Dom.get(tipo).value);
if(no_dipatchable_units>0){
    
    if(no_dipatchable_units<1){
        transfer=no_dipatchable_units;
    }else{
        transfer=1;
    }
    
    Dom.get('formated_todo_units').innerHTML=to_assign+transfer;
    Dom.get(tipo).value=no_dipatchable_units-transfer;

}

}

function save_no_dispatchable(){

todo=Dom.get('todo_units').value;
picked=Dom.get('picked_units').value;

required=Dom.get('required_units').value;

out_of_stock=(Dom.get('out_of_stock_units').value==''?0:Dom.get('out_of_stock_units').value);
not_found=(Dom.get('not_found_units').value==''?0:Dom.get('not_found_units').value);
no_picked_other=(Dom.get('no_picked_other_units').value==''?0:Dom.get('no_picked_other_units').value);
//no_authorized=(Dom.get('no_authorized_other_units').value==''?0:Dom.get('no_authorized_other_units').value);
//alert(todo+' '+out_of_stock+' '+not_found+' '+no_picked_other)
if(required-picked-out_of_stock-not_found-no_picked_other<0){
Dom.setStyle('todo_error_msg','display','block')
return;
}


var ar_file='ar_edit_orders.php'; 
var request='tipo=update_no_dispatched&dn_key='+dn_key+'&itf_key='+Dom.get('todo_itf_key').value+'&out_of_stock='+out_of_stock+'&not_found='+not_found+'&no_picked_other='+no_picked_other;


//alert(request)

YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    alert(o.responseText);
			
					   var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					    
					     if(r.result=='updated'){
			                
						    Dom.get('number_picked_transactions').innerHTML=r.number_picked_transactions;
					        Dom.get('number_transactions').innerHTML=r.number_transactions;
					        Dom.get('percentage_picked').innerHTML=r.percentage_picked;
					        if(r.number_picked_transactions>=r.number_transactions){
					            Dom.setStyle('finish','display','');
					            Dom.setStyle('continue_later','display','none');
					        }else{
					            Dom.setStyle('finish','display','none');
					            Dom.setStyle('continue_later','display','');
					        }
					        
					        datatable=tables['pick_aidDataTable'];
					        datatable.updateCell(updating_record,'formated_todo',r.formated_todo);
                            datatable.updateCell(updating_record,'notes',r.notes);
                           // datatable.updateCell(updating_record,'todo',r.todo);
                            datatable.updateCell(updating_record,'out_of_stock',r.out_of_stock);
                      //                              datatable.updateCell(updating_record,'no_authorized',r.no_authorized);

                            datatable.updateCell(updating_record,'not_found',r.not_found);
                            datatable.updateCell(updating_record,'no_picked_other',no_picked_other);

  no_dispatchable_editor_dialog.hide();


					        
                      }
                        
					   
                        

					    
					
					    
					
						

					    } else {
						alert(r.msg);
						
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					   
					},
					    scope:this
					    },
				    request
				    
				    );  

}



function set_pending_as_picked(){


Dom.get('set_all_as_picked').src="art/loading.gif";

	ar_file='ar_edit_orders.php';
   	request=ar_file+'?tipo=set_picking_aid_sheet_pending_as_picked&dn_key='+Dom.get('dn_key').value;
  	//alert(ar_file+request);return
    YAHOO.util.Connect.asyncRequest(
        'GET',
    request, {
		success: function (o) {
//alert(o.responseText)
var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
            
            
       
      window.location='order_pick_aid.php?id='+Dom.get('dn_key').value;
            
            }
           
        },
failure: function (o) {
            alert(o.statusText);
        },
scope:this
    }
    );


}

function pack_it_save(){

var staff_key=Dom.get('pack_it_staff_key').value;
var sup_pwd=   Dom.get('pack_it_password').value;
var dn_key=Dom.get('pack_it_dn_key').value;
    var request='ar_edit_orders.php?tipo=pack_it&dn_key='+escape(dn_key)+'&staff_key='+escape(staff_key)+'&pin='+escape(sup_pwd);
     
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		    
		    if(r.action='updated'){
		    location.href='order_pack_aid.php?id='+dn_key;
		    }
		    close_dialog('assign_packer_dialog');

		}else{
		  alert(r.msg);
	    }
	    }
	});    

}



function start_packing(){

  
     region1 = Dom.getRegion('start_packing'); 
    region2 = Dom.getRegion('assign_packer_dialog'); 

 var pos =[region1.right-region2.width-20,region1.bottom]

    Dom.setXY('assign_packer_dialog', pos);

assign_packer_dialog.show()
Dom.get('Assign_Packer_Staff_Name').focus();
    
}


function fill_edit_deal_form(deal_key){

    var request='ar_assets.php?tipo=get_deal_data&deal_key='+deal_key;
   //alert(request);  
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		   

		}else{
		  alert(r.msg);
	    }
	    }
	});    


	region1 = Dom.getRegion(e); 
	region2 = Dom.getRegion('dialog_locations'); 

	var pos =[region1.right,region1.top]

	Dom.setXY('dialog_locations', pos);

	dialog_locations.show();


}





function get_locations(e, part_sku){
//alert(sku);

    var request='ar_edit_orders.php?tipo=get_locations&part_sku='+part_sku;
   //alert(request);  
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		    Dom.get('location_content').innerHTML=r.result;

		}else{
		  alert(r.msg);
	    }
	    }
	});    


	region1 = Dom.getRegion(e); 
	region2 = Dom.getRegion('dialog_locations'); 

	var pos =[region1.right,region1.top]

	Dom.setXY('dialog_locations', pos);

	dialog_locations.show();


}


function init(){

  init_search('parts');

 no_dispatchable_editor_dialog = new YAHOO.widget.Dialog("no_dispatchable_editor_dialog", {visible : false,close:true,underlay: "none",draggable:false});
 no_dispatchable_editor_dialog.render();



Event.addListener('pick_all', "click",set_pending_as_picked);
 Event.addListener('start_packing', "click",start_packing);


dialog_locations = new YAHOO.widget.Dialog("dialog_locations", {visible : false,close:true,underlay: "none",draggable:false});
dialog_locations.render();



assign_picker_dialog = new YAHOO.widget.Dialog("assign_picker_dialog", {visible : false,close:false,underlay: "none",draggable:false});
	assign_picker_dialog.render();

	dialog_pick_it = new YAHOO.widget.Dialog("dialog_pick_it", {context:["pick_it_","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
	//dialog_pick_it.render();

	dialog_pack_it = new YAHOO.widget.Dialog("dialog_pack_it", {context:["pack_it","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
	dialog_pack_it.render();

	pick_assigned_dialog = new YAHOO.widget.Dialog("pick_assigned_dialog", {context:["pack_it","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
	//pick_assigned_dialog.render();
	pick_it_dialog = new YAHOO.widget.Dialog("pick_it_dialog", {context:["pack_it","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
	//pick_it_dialog.render();

	assign_packer_dialog = new YAHOO.widget.Dialog("assign_packer_dialog", {context:["pack_it","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
	assign_packer_dialog.render();
	pack_assigned_dialog = new YAHOO.widget.Dialog("pack_assigned_dialog", {context:["pack_it","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
	pack_assigned_dialog.render();
	pack_it_dialog = new YAHOO.widget.Dialog("pack_it_dialog", {context:["pack_it","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
	pack_it_dialog.render();    
	dialog_other_staff = new YAHOO.widget.Dialog("dialog_other_staff", {context:["other_staff","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
	dialog_other_staff.render();


}

YAHOO.util.Event.onDOMReady(init);
