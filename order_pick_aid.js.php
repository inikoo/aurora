<?php
include_once('common.php');?>

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
 if(data['quantity']==''){
	        data['quantity']=0;
	        }
	if(column.action=='add_object'){
	
	  
	        
	    var new_qty=parseFloat(data['quantity'])+1;
	    if(new_qty>data['max_resend'])
	        new_qty=data['max_resend'];
	    
	    
	}else{
	    qty=parseFloat(data['quantity'])
	    if(qty==0){
	        return;
	    }
	    var new_qty=qty-1;

        }


        if(new_qty==data['quantity'])
            return;

 var ar_file='ar_edit_orders.php';
	request='tipo=edit_new_post_order&order_key='+data['order_key']+'&key=quantity&new_value='+new_qty+'&otf_key='+ data['otf_key'];
	//alert(request);
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					   // alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					    if(r.result=='updated'){
					    	datatable.updateCell(record,'quantity',r.quantity);
					    datatable.updateCell(record,'operation',r.operation);
					        datatable.updateCell(record,'reason',r.reason);
					    datatable.updateCell(record,'to_be_returned',r.to_be_returned);
                      if(r.data != undefined){
                      for(x in r.data){
                            for (y in r.data[x]){
                                if(Dom.get(x+'_'+y)!=null)
                                Dom.get(x+'_'+y).innerHTML=r.data[x][y];
                            }
						   
						}
						
                      
                        
                        if(r.data['Refund']['Distinct_Products']==0){
                        Dom.setStyle('refund','display','none');
                        }else{
                         Dom.setStyle('refund','display','');
                        }
                        if(r.data['Credit']['Distinct_Products']==0){
                        Dom.setStyle('credit','display','none');
                        }else{
                         Dom.setStyle('credit','display','');
                        }                       
                    
                       if(r.data['Resend']['Distinct_Products']==0){
                       
                        Dom.setStyle('resend','display','none');
                        }else{
                         Dom.setStyle('resend','display','');
                        }                        
                        
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
    
    request='tipo=edit_new_post_order&order_key='+data['order_key']+'&key='+column.object+'&new_value='+encodeURIComponent(newValue)+'&otf_key='+ data['otf_key'];
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
					    	datatable.updateCell(record,'quantity',r.quantity);
					    datatable.updateCell(record,'operation',r.operation);
					        datatable.updateCell(record,'reason',r.reason);
					    datatable.updateCell(record,'to_be_returned',r.to_be_returned);
                      
                      for(x in r.data){
                            for (y in r.data[x]){
                                if(Dom.get(x+'_'+y)!=null)
                                Dom.get(x+'_'+y).innerHTML=r.data[x][y];
                            }
						    //Dom.get(x).innerHTML=r.data[x];
						}
						
                      }
                        
					    
					      if(r.data['Refund']['Distinct_Products']==0){
                        Dom.setStyle('refund','display','none');
                        }else{
                         Dom.setStyle('refund','display','');
                        }
                        if(r.data['Credit']['Distinct_Products']==0){
                        Dom.setStyle('credit','display','none');
                        }else{
                         Dom.setStyle('credit','display','');
                        }                       
                    
                       if(r.data['Resend']['Distinct_Products']==0){
                       
                        Dom.setStyle('resend','display','none');
                        }else{
                         Dom.setStyle('resend','display','');
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
				     {key:"sku", label:"<?php echo _('Part')?>",width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  //   ,{key:"used_in", label:"<?php echo _('Sold as')?>",width:120,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description",label:"<?php echo _('Description')?>", width:300,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"location",label:"<?php echo _('Location')?>", width:70,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"quantity",label:"<?php echo _('Qty')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
                       ,{key:"picked",label:"<?php echo _('Picked')?>", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'pick_aid'}
					,{key:"add",label:"", width:3,sortable:false,action:'add_object',object:'pick_aid'}
					,{key:"remove",label:"", width:3,sortable:false,action:'remove_object',object:'pick_aid'}
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
			 ,"quantity","picked","add","remove"
			
			 ]};
	    this.InvoiceDataTable = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.InvoiceDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	

 this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", myonCellClick);
	


    
    };
  });




function init(){



}

YAHOO.util.Event.onDOMReady(init);
