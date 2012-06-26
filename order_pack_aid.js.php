<?php
include_once 'common.php';
$order_key=0;
if (isset($_REQUEST['dn_key']) )
	$dn_key=$_REQUEST['dn_key'];
print "var dn_key=$dn_key;";
?>

YAHOO.namespace ("invoice");
 var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;
var updating_record;
var no_dispatchable_editor_dialog;
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
    Dom.get('packed_units').value=data['packed'];

    Dom.get('not_found_units').value=(data['not_found']==0)?'':data['not_found']
    Dom.get('no_packed_other_units').value=(data['no_packed_other']==0)?'':data['no_packed_other']
    Dom.get('to_assign_todo_units').innerHTML=data['todo']-data['out_of_stock']-data['not_found']-data['no_packed_other'];


 	var y=(Dom.getY(target))
   	var x=(Dom.getX(target))


  	x=x-120;
    y=y+18;
    Dom.setX('no_dispatchable_editor_dialog', x)
    Dom.setY('no_dispatchable_editor_dialog', y)
   //Dom.get('Assign_Packer_Staff_Name').focus();
   //Dom.get('assign_packer_dn_key').value=dn_key;
    no_dispatchable_editor_dialog.show();

    break;

    case('add_object'):
    case('remove_object'):
    case('check_all_object'):

	var data = record.getData();


	if(data['packed']==''){
	        data['packed']=0;
	        }

	if(column.action=='check_all_object'){



	  //  var new_qty=parseFloat(data['packed'])+1;


	  	  pending=data['picked']


	//  if(new_qty>(pending))
	        new_qty=pending;


	}
	else if(column.action=='add_object'){



	    var new_qty=parseFloat(data['packed'])+1;


	  //	  pending=data['required']-data['out_of_stock']-data['not_found']-data['no_packed_other']

	// alert('('+new_qty+'>'+pending+')  '+data['required']+' o:'+data['out_of_stock']+' '+data['not_found']+' '+data['no_packed_other'])
	//  return;

	  //alert(pending);
	//  if(new_qty>(pending))
	  //      new_qty=pending;


	}
	else{
	    qty=parseFloat(data['packed'])
	    if(qty==0){
	        return;
	    }
	    var new_qty=qty-1;

        }


        if(new_qty==data['packed'])
            return;

var packer_key=Dom.get('assigned_packer').getAttribute('key');

 var ar_file='ar_edit_orders.php';
	request='tipo=pack_order&dn_key='+dn_key+'&key=quantity&new_value='+new_qty+'&itf_key='+ data['itf_key']+'&packer_key='+packer_key;
	//alert(request);
	//return;
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					// alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					    if(r.result=='updated'){
					    	datatable.updateCell(record,'packed',r.packed);
					    	//if(r.formated_todo==0)
					    	//    r.formated_todo='';
					    	//datatable.updateCell(record,'formated_todo',r.formated_todo);



                            if(r.number_packed_transactions<r.number_picked_transactions){
                            datatable.updateCell(record,'add','+');
                            datatable.updateCell(record,'remove','<span style="color:#ccc">+</span>');
                            datatable.updateCell(record,'done','');
                            datatable.updateCell(record,'check_mark','&#8704;');

							}else{
							datatable.updateCell(record,'add','<span style="color:#ccc">+</span>');
                            datatable.updateCell(record,'remove','-');
                            datatable.updateCell(record,'done','&#x2713;');
                            datatable.updateCell(record,'check_mark','<span style="color:#ccc">&#8704;</span>');
							}


					        Dom.get('number_packed_transactions').innerHTML=r.number_packed_transactions;
					        Dom.get('number_transactions').innerHTML=r.number_transactions;
					        Dom.get('percentage_packed').innerHTML=r.percentage_packed;



					        if(r.number_packed_transactions>=r.number_transactions){
					            Dom.setStyle(['pack_all'],'display','none');
					        }else{
					            Dom.setStyle(['pack_all'],'display','');
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

    var packer_key=Dom.get('assigned_packer').getAttribute('key');
    	  pending=data['required']-data['out_of_stock']-data['not_found']-data['no_packed_other']

    if(newValue>pending)
        new_qty=pending
    else
        new_qty=newValue

 var ar_file='ar_edit_orders.php';
	request='tipo=pack_order&dn_key='+dn_key+'&key=quantity&new_value='+new_qty+'&itf_key='+ data['itf_key']+'&packer_key='+packer_key;


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

					    					    	datatable.updateCell(record,'packed',r.packed);
					    	if(r.formated_todo==0)
					    	    r.formated_todo='';
					    	datatable.updateCell(record,'formated_todo',r.formated_todo);
                            datatable.updateCell(record,'todo',r.todo);

					        Dom.get('number_packed_transactions').innerHTML=r.number_packed_transactions;
					        Dom.get('number_transactions').innerHTML=r.number_transactions;
					        Dom.get('percentage_packed').innerHTML=r.percentage_packed;





					     //datatable.updateCell(record,'packed',r.packed);
					     //datatable.updateCell(record,'todo',r.todo);
					     //Dom.get('number_packed_transactions').innerHTML=r.number_packed_transactions;
					       // Dom.get('number_transactions').innerHTML=r.number_transactions;
					        //Dom.get('percentage_packed').innerHTML=r.percentage_packed;


					     	        if(r.number_packed_transactions>=r.number_transactions){
					            Dom.setStyle(['pack_all','update_locations'],'display','none');
					        }else{
					            Dom.setStyle(['pack_all','update_locations'],'display','');
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

				     ,{key:"sku", label:"<?php echo _('Part')?>",width:45,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description",label:"<?php echo _('Description')?>", width:488,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				//	,{key:"packing_notes",label:"<?php echo _('Notes')?>", width:150,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				// 	,{key:"used_in", label:"<?php echo _('Sold as')?>",width:230,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			//	  ,{key:"location",label:"<?php echo _('Location')?>", width:150,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				  // ,{key:"quantity",label:"<?php echo _('Qty')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

                    ,{key:"packed",label:"<?php echo _('Packed')?>", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'pack_aid'}
										,{key:"done",label:"", width:3,sortable:false,action:'check_all_object',object:'pack_aid'}

					,{key:"check_mark",label:"", width:3,sortable:false,action:'check_all_object',object:'pack_aid'}
					,{key:"add",label:"", width:3,sortable:false,action:'add_object',object:'pack_aid'}
					,{key:"remove",label:"", width:3,sortable:false,action:'remove_object',object:'pack_aid'}
					,{key:"picked",label:"<?php echo _('Picked')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"notes",label:"<?php echo _('Notes')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"out_of_stock",label:"", width:1,hidden:true}
					,{key:"not_found",label:"", width:1,hidden:true}
					,{key:"no_packed_other",label:"", width:1,hidden:true}

				   ];


	    this.pack_aidDataSource = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=packing_aid_sheet&tid=0&dn_key="+Dom.get('dn_key').value);
	    //alert("ar_edit_orders.php?tipo=packing_aid_sheet&tid=0&dn_key="+Dom.get('dn_key').value);

	    this.pack_aidDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.pack_aidDataSource.connXhrMode = "queueRequests";
	    this.pack_aidDataSource.responseSchema = {
		resultsList: "resultset.data",
		fields: [
			 "sku"
			 ,"used_in"
			 ,"description","picked"
			 ,"location","packing_notes","done"
			 ,"quantity","packed","add","remove","itf_key","todo","notes","required",'out_of_stock','not_found','formated_todo',"no_packed_other","check_mark"

			 ]};
	    this.pack_aidDataTable = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.pack_aidDataSource, {
								       renderLoopSize: 50
								   }

								   );


 this.pack_aidDataTable.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.pack_aidDataTable.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.pack_aidDataTable.subscribe("cellClickEvent", myonCellClick);




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





function set_pending_as_packed(){

	ar_file='ar_edit_orders.php';
   	request=ar_file+'?tipo=set_packing_aid_sheet_pending_as_packed&dn_key='+Dom.get('dn_key').value;
    YAHOO.util.Connect.asyncRequest(
        'GET',
    request, {
		success: function (o) {
var r =  YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
     window.location='order_pack_aid.php?id='+Dom.get('dn_key').value;

            }

        },
failure: function (o) {
            alert(o.statusText);
        },
scope:this
    }
    );


}

function set_as_done(){
ar_file='ar_edit_orders.php';
   	request=ar_file+'?tipo=set_delivery_note_as_packed&dn_key='+Dom.get('dn_key').value;
   //alert(request)
   YAHOO.util.Connect.asyncRequest(
        'GET',
    request, {
		success: function (o) {
		//alert(o.responseText)
var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
     				window.location='dn.php?id='+Dom.get('dn_key').value;

            }

        },
failure: function (o) {
            alert(o.statusText);
        },
scope:this
    }
    );
}


function init(){

  init_search('parts');

 no_dispatchable_editor_dialog = new YAHOO.widget.Dialog("no_dispatchable_editor_dialog", {visible : false,close:true,underlay: "none",draggable:false});
 no_dispatchable_editor_dialog.render();



Event.addListener('pack_all', "click",set_pending_as_packed);
Event.addListener('set_as_done', "click",set_as_done);


}

YAHOO.util.Event.onDOMReady(init);
