<?php
include_once('common.php');
$order_key=0;
if(isset($_REQUEST['dn_key']) )
    $dn_key=$_REQUEST['dn_key'];
print "var dn_key=$dn_key;";
?>

YAHOO.namespace ("invoice"); 

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
    var data = record.getData();
    Dom.get('formated_todo_units').innerHTML=data['formated_todo'];

    Dom.get('todo_units').value=data['todo'];
        Dom.get('todo_itf_key').value=data['itf_key'];
       
    Dom.get('out_of_stock_units').value=(data['out_of_stock']==0)?'':data['out_of_stock'];
        Dom.get('not_found_units').value=(data['not_found']==0)?'':data['not_found']
           Dom.get('no_picked_other_units').value=(data['no_picked_other']==0)?'':data['no_picked_other']

   Dom.get('to_assign_todo_units').innerHTML=data['todo']-data['out_of_stock']-data['not_found']-data['no_picked_other'];


    break;
    case('add_object'):
    case('remove_object'):
	var data = record.getData();
	
	
	
	
 if(data['picked']==''){
	        data['picked']=0;
	        }
	if(column.action=='add_object'){
	
	  
	        
	    var new_qty=parseFloat(data['picked'])+1;
	  
	    if(new_qty>data['quantity'])
	        new_qty=data['quantity'];
	    
	    
	}else{
	    qty=parseFloat(data['picked'])
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
					  alert(o.responseText);
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
if(newValue>data['quantity'])
 new_qty=data['quantity']
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
					   // alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					    
					     if(r.result=='updated'){
					    
					     datatable.updateCell(record,'picked',r.picked);
					     					     datatable.updateCell(record,'todo',r.todo);

					     Dom.get('number_picked_transactions').innerHTML=r.number_picked_transactions;
					        Dom.get('number_transactions').innerHTML=r.number_transactions;
					        Dom.get('percentage_picked').innerHTML=r.percentage_picked;
						
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
    YAHOO.invoice.XHR_JSON = new function() {


		
	    //START OF THE TABLE=========================================================================================================================
		
		var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
	    				     	{key:"itf_key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

				     ,{key:"sku", label:"<?php echo _('Part')?>",width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  //   ,{key:"used_in", label:"<?php echo _('Sold as')?>",width:120,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description",label:"<?php echo _('Description')?>", width:300,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"location",label:"<?php echo _('Location')?>", width:70,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"quantity",label:"<?php echo _('Qty')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
                       ,{key:"picked",label:"<?php echo _('Picked')?>", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'pick_aid'}
					,{key:"add",label:"", width:3,sortable:false,action:'add_object',object:'pick_aid'}
					,{key:"remove",label:"", width:3,sortable:false,action:'remove_object',object:'pick_aid'}
					

					,{key:"formated_todo",label:"<?php echo _('Pending')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},action:'edit_object',object:'pending_transactions'}
					,{key:"notes",label:"", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				   ];


	    this.InvoiceDataSource = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=picking_aid_sheet&tid=0");
	    this.InvoiceDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.InvoiceDataSource.connXhrMode = "queueRequests";
	    this.InvoiceDataSource.responseSchema = {
		resultsList: "resultset.data", 
		fields: [
			 "sku"
			 ,"used_in"
			 ,"description"
			 ,"location"
			 ,"quantity","picked","add","remove","itf_key","todo","notes","required",'out_of_stock','not_found','formated_todo',"no_picked_other"
			
			 ]};
	    this.InvoiceDataTable = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.InvoiceDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	

 this.InvoiceDataTable.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.InvoiceDataTable.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.InvoiceDataTable.subscribe("cellClickEvent", myonCellClick);
	


    
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
out_of_stock=(Dom.get('out_of_stock_units').value==''?0:Dom.get('out_of_stock_units').value);
not_found=(Dom.get('not_found_units').value==''?0:Dom.get('not_found_units').value);
no_picked_other=(Dom.get('no_picked_other_units').value==''?0:Dom.get('no_picked_other_units').value);


if(todo-out_of_stock-not_found-no_picked_other<0){
Dom.setStyle('todo_error_msg','display','block')
return;
}


var ar_file='ar_edit_orders.php'; 
var request='tipo=update_no_dispatched&dn_key='+dn_key+'&itf_key='+Dom.get('todo_itf_key').value+'&out_of_stock='+out_of_stock+'&not_found='+not_found+'&no_picked_other='+no_picked_other;




YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    alert(o.responseText);
			return;
					   var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					    
					     if(r.result=='updated'){
					    
						
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


function init(){



}

YAHOO.util.Event.onDOMReady(init);
