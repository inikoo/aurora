<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_note;
var dialog_new_sticky_note;
var dialog_sticky_note;
var dialog_export;
var dialog_link;
var customer_key=<?php echo $_SESSION['state']['customer']['id']?>;
var dialog_edit_note;

var onCellClick = function(oArgs) {
    var target = oArgs.target,
                 column = this.getColumn(target),
                          record = this.getRecord(target);

    var recordIndex = this.getRecordIndex(record);
    ar_file='ar_edit_contacts.php';

    switch (column.action) {
    case('edit'):
           Dom.get('edit_note_history_key').value=record.getData('key');

        Dom.get('edit_note_input').value=record.getData('note');
                
         Dom.get('record_index').value=recordIndex;

     
  var y=(Dom.getY(target))-0
    var x=(Dom.getX(target))-200
 Dom.setX('dialog_edit_note', x)
    Dom.setY('dialog_edit_note', y)
dialog_edit_note.show();
    
    break;
    case 'delete':
        if (record.getData('delete')!='') {

            if(record.getData('can_delete')){

            var delete_type=record.getData('delete_type');
            if (confirm('Are you sure, you want to '+delete_type+' this row?')) {
               



  
                YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record), {
                success: function (o) {
                   //  alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200 && r.action=='deleted') {

                            this.deleteRow(target);


                        } else if (r.state == 200 && r.action=='discontinued') {

                            var data = record.getData();
                            data['delete']=r.delete;
                            data['delete_type']=r.delete_type;
                            this.updateRow(recordIndex,data);



                        } else {
                            alert(r.msg);
                        }
                    },
failure: function (o) {
                        alert(o.statusText);
                    },
scope:this
                }
                );
            }
            }else{
            
            
      
               
            
            if(record.getData('strikethrough')=='Yes')
            var action='unstrikethrough_';
            else
             var action='strikethrough_';

        
        
            YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo='+action+column.object + myBuildUrl(this,record), {
                success: function (o) {
                  //   alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        
                          var data = record.getData();
                            data['strikethrough']=r.strikethrough;
                    data['delete']=r.delete;
                    
                    //data['delete_type']=r.delete_type;
                            this.updateRow(recordIndex,data);
                        
                    
                    },
failure: function (o) {
                        alert(o.statusText);
                    },
scope:this
                }
                );
            
            }
            
            
            
        }
        break;

    default:

        this.onEventShowCellEditor(oArgs);
        break;
    }
};

function make_order(){

    var customer_id=Dom.get('make_order_customer_id').value;
    
    var data={
	'courier':Dom.get('make_order_courier').value,
	'special_instructions':Dom.get('make_order_special_instructions').value,
	'payment_method':Dom.get('make_order_payment_method').value,
	'gold_reward':Dom.get('gold_reward').value,
	'offer':Dom.get('offer').value,
    };
    //alert('customer_csv.php?id='+customer_id+'&data='+encodeURIComponent(YAHOO.lang.JSON.stringify(data)))

// var value=new Object()
  //      for (i in data)
    //        value[i]=my_encodeURIComponent(data[i]);

        var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data));

//alert('customer_csv.php?id='+customer_id+'&data='+json_value);
  //return;
	  window.open('customer_csv.php?id='+customer_id+'&data='+json_value,'Download');
    close_dialog('make_order');

}

function showdetails(o){

 

    var history_id=o.getAttribute('hid');
    var details=o.getAttribute('d');
    tr=Dom.getAncestorByTagName(o,'tr');
    row_index=tr.rowIndex+1;
    var table=Dom.getAncestorByTagName(o,'table');
    //alert(o);
    if(details=='no'){
	row_class=tr.getAttribute('class');

	var request="ar_history.php?tipo=history_details&id="+history_id;
	//alert(request)	
YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			var x=table.insertRow(row_index);
			x.setAttribute('class',row_class);
			x.setAttribute('id','chd'+history_id);

			var c1=x.insertCell(0);
			var c2=x.insertCell(1);
			var c3=x.insertCell(2);
			x.setAttribute('style','padding:10px 0 ;border-top:none')
			c1.innerHTML="";
			c2.innerHTML="";
			c3.setAttribute('style','padding:10px 0 ;');


			c3.setAttribute('colspan',3);
			c3.innerHTML=r.details;
			Dom.get('ch'+history_id).src='art/icons/showed.png';
			Dom.get('ch'+history_id).setAttribute('d','yes');

			
		    }
		       
		}
	    });   
    }else{
	Dom.get('ch'+history_id).src='art/icons/closed.png';
	Dom.get('ch'+history_id).setAttribute('d','no');
	table.deleteRow(row_index);

    }
     
	
}

function save(tipo){
   
    switch(tipo){
    case('edit_note'):
    
        YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo=customer_edit_note&customer_key='+customer_key+'&note_key='+Dom.get('edit_note_history_key').value+'&note='+my_encodeURIComponent(Dom.get('edit_note_input').value)+'&date='+Dom.get('edit_note_date').getAttribute('value')+'&record_index='+Dom.get('record_index').value, {
                success: function (o) {
              //  alert(o.responseText)
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                          
                          if(r.state==200){
                          	var table=tables['table0'];
                       
                          	   record = table.getRecord(r.record_index);
                          	 
                          	  var data = record.getData();
                          data['note']=r.newvalue;
                          table.updateRow(r.record_index,data);
                          
                                                    close_dialog('edit_note');;

                          }else{
                          Dom.get('edit_note_msg').innerHTML=r.msg;
                          
                          }
                          
                     
                      
                    },
                    failure: function (o) {
                        alert(o.statusText);
                    },
                scope:this
                }
                );
    
    
    break;
    case('note'):
        var value=my_encodeURIComponent(Dom.get(tipo+"_input").value);
        var note_type=my_encodeURIComponent(Dom.get("note_type").getAttribute('value'));

	var request="ar_edit_contacts.php?tipo=customer_add_note&customer_key="+customer_key+"&note="+value+"&details=&note_type="+note_type;

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//	alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			close_dialog(tipo)
			var table=tables['table0'];
			var datasource=tables['dataSource0'];
			var request='';
			datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    

		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
	    });        
	

	break;
	 case('sticky_note'):
	  case('new_sticky_note'):

  //  var value=my_encodeURIComponent(Dom.get(tipo+"_input").value);


 var data_to_update=new Object;
 data_to_update[tipo]={'okey':tipo,'value':Dom.get(tipo+"_input").value}

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer&values='+ jsonificated_values+"&customer_key="+customer_key


	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//	alert(o.responseText)
		    var ra =  YAHOO.lang.JSON.parse(o.responseText);
		      for (x in ra){
               r=ra[x]
		    
		    if (r.state==200) {
		    
		    Dom.get('sticky_note_content').innerHTML=r.newvalue;
			
			close_dialog(r.key);

            if(r.newvalue==''){
                Dom.setStyle(['sticky_note_div','sticky_note_bis_tr'],'display','none');
                Dom.setStyle('new_sticky_note_tr','display','');

            }else{
                           

             Dom.setStyle(['sticky_note_div','sticky_note_bis_tr'],'display','');
                Dom.setStyle('new_sticky_note_tr','display','none');
            }

            var table=tables['table0'];
			var datasource=tables['dataSource0'];
			var request='';
			datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
			
		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
		}
	    });        
	

	

	break;	
	
   case('link'):
   var value='';
   if(Dom.get("link_note").value!='')
    value=Dom.get("link_note").value+'; ';
   alert(Dom.get("link_file").html);
    	value=value+Dom.get("link_file").value+' <a href="file://'+Dom.get("link_file").value+'">link</a>';

   var value=encodeURIComponent(value);
	var request="ar_edit_contacts.php?tipo=edit_customer&key=Note&customer_key="+customer_key+"&newvalue="+value;
	alert(request);
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			
		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
	    });        
  
    
    break;
    }
    
    
    
    
};

function change(e,o,tipo){
    switch(tipo){
    case('note'):
	if(o.value!=''){
	    enable_save(tipo);
/*
	    if(window.event)
		key = window.event.keyCode; //IE
	    else
		key = e.which; //firefox     
	    
	    if (key == 13)
		save(tipo);
*/

	}else
	    disable_save(tipo);
	break;
    case('new_sticky_note'):
        if(o.value!=''){
	    enable_save(tipo);
	    }else{
	    disable_save(tipo);
	    }
	    
    break;
    }
};


function enable_save(tipo){
    switch(tipo){
    case('note'):
	Dom.get(tipo+'_save').style.visibility='visible';
	break;
	 case('new_sticky_note'):
	Dom.get(tipo+'_save').style.visibility='visible';
	break;
    }
};

function disable_save(tipo){
    switch(tipo){
    case('note'):
	Dom.get(tipo+'_save').style.visibility='hidden';
	break;
	case('new_sticky_note'):
	Dom.get(tipo+'_save').style.visibility='hidden';
	break;
    }
};


function close_dialog(tipo){
    switch(tipo){
  //   case('long_note'):
// 	//Dom.get(tipo+"_input").value='';
// 	dialog_note.hide();

// 	break;
  case('attach'):

	Dom.get(tipo+"_note").value='';
	//	Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_attach.hide();

	break;
      case('edit_note'):

	Dom.get("edit_note_input").value='';
	
	dialog_edit_note.hide();
break;
    case('note'):

	Dom.get(tipo+"_input").value='';
	Dom.get(tipo+'_save').style.visibility='hidden';
	
	dialog_note.hide();
break;
 case('sticky_note'):
	dialog_sticky_note.hide();
	 Dom.get('sticky_note_input').value=Dom.get('sticky_note_content').innerHTML;
	break;
case('new_sticky_note'):
	 Dom.get('sticky_note_input').value=Dom.get('sticky_note_content').innerHTML;

	Dom.get(tipo+"_input").value='';
	Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_new_sticky_note.hide();

	break;
    
     case('link'):
    Dom.get("link_note").value='';
	Dom.get("link_file").value='';
	//Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_link.hide();

	break;
    
 case('export'):
 dialog_export.hide();
 break;
 case('make_order'):

     //	Dom.get(tipo+"_input").value='';
	//Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_make_order.hide();

	break;
    }


};

 
Event.addListener(window, "load", function() {
	    tables = new function() {
		    
		    var tableid=0; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		    
		    
		    var myRowFormatter = function(elTr, oRecord) {
		   
    if (oRecord.getData('type') =='Orders') {
        Dom.addClass(elTr, 'customer_history_orders');
    }else if (oRecord.getData('type') =='Notes') {
        Dom.addClass(elTr, 'customer_history_notes');
    }else if (oRecord.getData('type') =='Changes') {
        Dom.addClass(elTr, 'customer_history_changes');
    }
    return true;
}; 
		    
		    
  this.prepare_note = function(elLiner, oRecord, oColumn, oData) {
          
            if(oRecord.getData("strikethrough")=="Yes") { 
            Dom.setStyle(elLiner,'text-decoration','line-through');
            Dom.setStyle(elLiner,'color','#777');

            }
            elLiner.innerHTML=oData
        };
        		    
		    var ColumnDefs = [
				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:520}
                      ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'customer_history'}
                      ,{key:"edit", label:"",width:12,sortable:false,action:'edit',object:'customer_history'}

					   ];
		
		    this.dataSource0  = new YAHOO.util.DataSource("ar_history.php?tipo=customer_history&sf=0&tid="+tableid);
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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
                  fields: ["note","date","time","handle","delete","can_delete" ,"delete_type","key","edit","type","strikethrough"]};
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								 formatRow: myRowFormatter,
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customer']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customer']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customer']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table0.filter={key:'<?php echo$_SESSION['state']['customer']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customer']['table']['f_value']?>'};

	        this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table0.subscribe("cellClickEvent", onCellClick);            



	    //   Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)









    var tableid=1; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		    
		    var ColumnDefs = [
				      {key:"subject", label:"<?php echo _('Family')?>",className:"aleft",width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   				      ,{key:"description", label:"<?php echo _('Description')?>",className:"aleft",width:270,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				   ,{key:"orders", label:"<?php echo _('Orders')?>",className:"aright",width:60}

				      ,{key:"ordered", label:"<?php echo _('Ordered')?>",className:"aright",width:60}
				      ,{key:"dispatched", label:"<?php echo _('Dispatched')?>", className:"aright",width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					   ];
		
		    this.dataSource1  = new YAHOO.util.DataSource("ar_contacts.php?tipo=assets_dispatched_to_customer&tid="+tableid);
		    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.table_id=tableid;
	    this.dataSource1.responseSchema = {
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
		fields: ["subject","ordered","dispatched","orders","description" ]};
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customer']['assets']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customer']['assets']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customer']['assets']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table1.filter={key:'<?php echo$_SESSION['state']['customer']['assets']['f_field']?>',value:'<?php echo$_SESSION['state']['customer']['assets']['f_value']?>'};

	   



// ------------------------------------- orders start -------------------------------

 var tableid=2; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		   
		    var ColumnDefs =  [
				       {key:"public_id", label:"<?php echo _('Order ID')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"last_update", label:"<?php echo _('Last Updated')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"current_state",label:"<?php echo _('Current State')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
                                        {key:"order_date", label:"<?php echo _('Order Date')?>", width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      {key:"total_amount", label:"<?php echo _('Total')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 
					
					 ];
		
		    this.dataSource2  = new YAHOO.util.DataSource("ar_contacts.php?tipo=customer_orders&tid="+tableid);
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
		
		fields: ["public_id","last_update","current_state","order_date","total_amount"]};
			  
		    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['customer']['orders']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customer']['orders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customer']['orders']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table2.filter={key:'<?php echo$_SESSION['state']['customer']['orders']['f_field']?>',value:'<?php echo$_SESSION['state']['customer']['orders']['f_value']?>'};

	   

// -------------------------------------orders end ----------------------------------

	
	};
    });


function take_order(){
    location.href='order.php?new=1&customer_key=<?php echo $_SESSION['state']['customer']['id']?>'; 


}



function change_view(){
ids=['orders','history','products','details'];
block_ids=['block_orders','block_history','block_products','block_details'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');

Dom.removeClass(ids,'selected');

Dom.addClass(this,'selected');
//alert('ar_sessions.php?tipo=update&keys=customer-view&value='+this.id)
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer-view&value='+this.id ,{});
}

var oMenu;



function change_elements(){

ids=['elements_changes','elements_orders','elements_notes','elements_attachments'];


if(Dom.hasClass(this,'selected')){

var number_selected_elements=0;
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
number_selected_elements++;
}
}

if(number_selected_elements>1){
Dom.removeClass(this,'selected')

}

}else{
Dom.addClass(this,'selected')

}

table_id=0;
 var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
var request='';
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
request=request+'&'+ids[i]+'=1'
}else{
request=request+'&'+ids[i]+'=0'

}
}
  
 // alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}

function init(){

Event.addListener(['elements_changes','elements_orders','elements_notes','elements_attachments'], "click",change_elements);


  init_search('customers_store');
Event.addListener(['orders','history','products','details'], "click",change_view);


Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 


    var alt_shortcuts = function(type, args, obj) {
	if(args[0]==78){
	    window.location=Dom.get("next").href;
	}else if(args[0]==80){
	    window.location=Dom.get("next").href;
	}

    }

    kpl1 = new YAHOO.util.KeyListener(document, { alt:true ,keys:[78,80] }, { fn:alt_shortcuts } );
    kpl1.enable();



	//Details textarea editor ---------------------------------------------------------------------
	var texteditorConfig = {
	    height: '270px',
	    width: '750px',
	    dompath: true,
	    focusAtStart: true
	};     

 	editor = new YAHOO.widget.Editor('long_note_input', texteditorConfig);

	editor._defaultToolbar.buttonType = 'basic';
 	editor.render();

	//	editor.on('editorKeyUp',change_textarea,'details' );
	//-------------------------------------------------------------


dialog_note = new YAHOO.widget.Dialog("dialog_note", {context:["note","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_note.render();

dialog_edit_note = new YAHOO.widget.Dialog("dialog_edit_note", {visible : false,close:false,underlay: "none",draggable:false});
dialog_edit_note.render();


dialog_new_sticky_note = new YAHOO.widget.Dialog("dialog_new_sticky_note", {context:["new_sticky_note","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_new_sticky_note.render();

dialog_sticky_note = new YAHOO.widget.Dialog("dialog_sticky_note", {context:["sticky_note","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_sticky_note.render();

dialog_attach = new YAHOO.widget.Dialog("dialog_attach", {context:["attach","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_attach.render();

dialog_link = new YAHOO.widget.Dialog("dialog_link", {context:["link","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_link.render();

dialog_make_order = new YAHOO.widget.Dialog("dialog_make_order", {context:["make_order","tr","br"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_make_order.render();

dialog_export = new YAHOO.widget.Dialog("dialog_export", {context:["export_data","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
Event.addListener("new_sticky_note", "click", dialog_new_sticky_note.show,dialog_new_sticky_note , true);
Event.addListener(["sticky_note",'sticky_note_bis'], "click", dialog_sticky_note.show,dialog_sticky_note , true);

Event.addListener("note", "click", dialog_note.show,dialog_note , true);
Event.addListener("attach", "click", dialog_attach.show,dialog_attach , true);
Event.addListener("link", "click", dialog_link.show,dialog_link , true);
Event.addListener("export_data", "click", dialog_export.show,dialog_export , true);

Event.addListener("make_order", "click", dialog_make_order.show,dialog_make_order , true);


Event.addListener("take_order", "click", take_order , true);
dialog_export.render();

 if(Dom.get('sticky_note_content').innerHTML==''){
 

                Dom.setStyle(['sticky_note_div','sticky_note_bis_tr'],'display','none');
                Dom.setStyle('new_sticky_note_tr','display','');

            }else{

             Dom.setStyle('sticky_note_div','display','');
                Dom.setStyle('new_sticky_note_tr','display','none');
            }

/*
dialog_long_note = new YAHOO.widget.Dialog("dialog_long_note", {context:["customer_data","tl","tl"] ,visible : false,close:false,underlay: "none",draggable:false});
dialog_long_note.render();
Event.addListener("long_note", "click", dialog_long_note.show,dialog_long_note , true);

//Event.addListener("note", "click", dialog_note.hide,dialog_note , true);

*/

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
  var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 oACDS1.table_id=1;
var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 

var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 oACDS2.table_id=2;
var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 






}






YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp1", "click", rppmenu.show, null, rppmenu);


    });

YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name2", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("rppmenu2", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp2", "click", rppmenu.show, null, rppmenu);


    });
