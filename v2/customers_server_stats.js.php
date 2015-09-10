<?php include_once('common.php');?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;




function change_table_type(){

if(this.id=='all_contacts'){
Dom.removeClass('contacts_with_orders','selected')
Dom.addClass('all_contacts','selected')
tipo='all_contacts'
  tables.table0.hideColumn('contacts_with_orders');
 tables.table0.hideColumn('new_contacts_with_orders');
  tables.table0.hideColumn('active_contacts_with_orders');
tables.table0.hideColumn('losing_contacts_with_orders');
 tables.table0.hideColumn('lost_contacts_with_orders');
 
 tables.table0.showColumn('contacts');
 tables.table0.showColumn('new_contacts');
  tables.table0.showColumn('active_contacts');
tables.table0.showColumn('losing_contacts');
 tables.table0.showColumn('lost_contacts');
  

  
  
}else{
Dom.addClass('contacts_with_orders','selected')
Dom.removeClass('all_contacts','selected')
tipo='contacts_with_orders';
 
  tables.table0.hideColumn('contacts');
 tables.table0.hideColumn('new_contacts');
  tables.table0.hideColumn('active_contacts');
tables.table0.hideColumn('losing_contacts');
 tables.table0.hideColumn('lost_contacts');
  
  tables.table0.showColumn('contacts_with_orders');
 tables.table0.showColumn('new_contacts_with_orders');
  tables.table0.showColumn('active_contacts_with_orders');
tables.table0.showColumn('losing_contacts_with_orders');
 tables.table0.showColumn('lost_contacts_with_orders');
 
}
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-customers-type&value=' + escape(tipo) ,{success:function(o) {}});









}

 function init(){
 

 
  init_search('customers');
 
 
}

YAHOO.util.Event.onDOMReady(init);

