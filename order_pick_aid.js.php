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
					 //  alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					    if(r.result=='updated'){
					    	datatable.updateCell(record,'picked',r.picked);
					    	if(r.todo==0)
					    	    r.todo='';
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
					,{key:"todo",label:"<?php echo _('Pending')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
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
			 ,"quantity","picked","add","remove","itf_key","todo","notes"
			
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




function init(){



}

YAHOO.util.Event.onDOMReady(init);
