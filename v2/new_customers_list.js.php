<?php
include_once('common.php');
?>
    var Event = YAHOO.util.Event;

   var Dom = YAHOO.util.Dom;

   var customer_views_ids = ['general', 'contact', 'address', 'ship_to_address', 'balance', 'rank', 'weblog'];

   var dialog_country_list;
   var dialog_wregion_list;
   var dialog_postal_code_list;
   var dialog_city_list;
   var dialog_department_list;
   var dialog_family_list;
   var dialog_product_list;
   var dialog_category_list;

   var searched = false;



   function save_search_list() {



       var store_id = Dom.get('store_id').value;
       var list_name = Dom.get('list_name').value;

       if (list_name == '') {
           Dom.get('save_list_msg').innerHTML = Dom.get('error_no_name').innerHTML;
           return;
       }


       if (Dom.get('dynamic').checked == true) {
           var list_type = 'Dynamic';
       } else {
           var list_type = 'Static';
       }

       var awhere = get_awhere();

       var request = "ar_edit_contacts.php?tipo=new_list&list_name=" + list_name + '&list_type=' + list_type + '&store_id=' + store_id + '&awhere=' + awhere;

       Dom.setStyle(['save_buttons', 'save_dialog'], 'display', 'none')
       Dom.setStyle('saving_the_list', 'display', '')
       YAHOO.util.Connect.asyncRequest('POST', request, {
           success: function(o) {
               var r = YAHOO.lang.JSON.parse(o.responseText);
               if (r.state == 200) {
                   location.href = 'customers_list.php?id=' + r.customer_list_key;

               } else {
               Dom.get('save_list_msg').innerHTML = r.msg;
               Dom.setStyle(['save_buttons', 'save_dialog'], 'display', '')
       Dom.setStyle('saving_the_list', 'display', 'none')
               }
           }
       });





   }



   var data_returned = function() {
           if (searched) {
               Dom.get('searching').style.display = 'none';
               Dom.get('the_table').style.display = '';
               Dom.get('save_list').style.display = '';
               Dom.get('modify_search').style.display = '';
               Dom.get('submit_search').style.display = 'none';


           }
       }


   function show_dont_wish_to_receive() {
       Dom.setStyle('show_dont_wish_to_receive', 'display', 'none')
       Dom.setStyle('tr_dont_wish_to_receive', 'display', '')
   }

   function show_dont_have() {
       Dom.setStyle('show_dont_have', 'display', 'none')
       Dom.setStyle('tr_dont_have', 'display', '')
   }

   function show_lost_customer() {
       Dom.setStyle('lost_customer_title', 'display', '')
       Dom.setStyle('lost_customer', 'display', '')
   }

   function hide_lost_customer() {
       Dom.setStyle('lost_customer_title', 'display', 'none')
       Dom.setStyle('lost_customer', 'display', 'none')
       Dom.get('v_calpop5').value = ''
       Dom.get('v_calpop6').value = ''


   }


   function set_values() {
       v_calpop == Dom.getElementsByClassName('v_calpop');
       for (x in v_calpop) {
           //alert(v_calpop);
       }
   }

   function checkbox_changed_allow(o) {

       cat = Dom.get(o).getAttribute('cat');

       if (cat == 'all') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'allow_options'), 'selected');
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'dont_allow_options'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else {
           Dom.removeClass('allow_all', 'selected');
           cat = Dom.get(o).getAttribute('cat');
           this_parent = Dom.get(o).getAttribute('parent');
           if (this_parent == 'allow_') {
               other_parent = 'dont_allow_';
           } else {
               other_parent = 'allow_';
           }
           if (Dom.hasClass(o, 'selected')) {
               Dom.removeClass(o, 'selected');
           } else {
               Dom.addClass(o, 'selected');
               Dom.removeClass(other_parent + cat, 'selected');
           }
       }
   }
   
   function change_customer_with_pending_orders(value) {

       if (value) {
           Dom.setStyle(['basket_payment_method_tr', 'customer_without_pending_orders','basket_days_no_change_tr'], 'display', '')
           Dom.setStyle(['customer_with_pending_orders'], 'display', 'none')
           Dom.get('pending_orders').value = 'Yes';
           Dom.get('basket_days_no_change').focus()
       } else {
           Dom.setStyle(['basket_payment_method_tr', 'customer_without_pending_orders','basket_days_no_change_tr'], 'display', 'none')
           Dom.setStyle(['customer_with_pending_orders'], 'display', '')
           o = Dom.get('payment_method_all')
           Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', o.parentNode), 'selected');
           Dom.addClass(o, 'selected');
           Dom.get('pending_orders').value = 'No';
       }

   }


   
     function change_checkbox(o,tag) {

       cat = Dom.get(o).getAttribute('cat');

       if (cat == 'all') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
           
           
           
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', o.parentNode), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else {
           Dom.removeClass(tag+'_all', 'selected');

           if (Dom.hasClass(o, 'selected')) {
               Dom.removeClass(o, 'selected');
           } else {
               Dom.addClass(o, 'selected');
              
           }
       }
   }
   

   function checkbox_changed_have(o) {

       cat = Dom.get(o).getAttribute('cat');
       this_parent = Dom.get(o).getAttribute('parent');
       if (this_parent == 'have_') {
           other_parent = 'dont_have_';
       } else {
           other_parent = 'have_';
       }
       if (Dom.hasClass(o, 'selected')) {
           Dom.removeClass(o, 'selected');
       } else {
           Dom.addClass(o, 'selected');
           Dom.removeClass(other_parent + cat, 'selected');
       }



   }

   function checkbox_changed_customers_which(o) {
       cat = Dom.get(o).getAttribute('cat');

       if (Dom.hasClass(o, 'selected')) {
           Dom.removeClass(o, 'selected');
           if (cat == 'lost') {

               hide_lost_customer();
           }

       } else {
           Dom.addClass(o, 'selected');

           if (cat == 'lost') {

               show_lost_customer();
           }

           //			Dom.removeClass(other_parent+cat,'selected');
       }




/*
	if(cat=='lost'){
		show_lost_customer();
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'customers_which_options'),'selected');
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'dont_allow_options'),'selected');
			Dom.addClass(o,'selected');
		}
	}else{
		hide_lost_customer();
		Dom.removeClass('customers_which_lost','selected');
		cat=Dom.get(o).getAttribute('cat');
		this_parent=Dom.get(o).getAttribute('parent');
		if(this_parent=='customers_which_'){
			other_parent='not_customers_which_';
		}else{
			other_parent='customers_which_';
		}    
		if(Dom.hasClass(o,'selected')){
			Dom.removeClass(o,'selected');
		}else{
			Dom.addClass(o,'selected');
			Dom.removeClass(other_parent+cat,'selected');
		}
	}


*/




   }

   function hide_invoice() {
       Dom.setStyle('number_of_invoices_upper', 'display', 'none')
       Dom.setStyle('a', 'display', 'none')
       Dom.get('number_of_invoices_upper').value = ''
   }

   function hide_order() {
       Dom.setStyle('number_of_orders_upper', 'display', 'none')
       Dom.setStyle('c', 'display', 'none')
       Dom.get('number_of_orders_upper').value = ''
   }

   function hide_sales() {
       Dom.setStyle('sales_upper', 'display', 'none')
       Dom.setStyle('b_sales', 'display', 'none')
       Dom.get('sales_upper').value = ''
   }

   function hide_logins() {
       Dom.setStyle('logins_upper', 'display', 'none')
       Dom.setStyle('b_logins', 'display', 'none')
       Dom.get('logins_upper').value = ''
   }

   function hide_failed_logins() {
       Dom.setStyle('failed_logins_upper', 'display', 'none')
       Dom.setStyle('b_failed_logins', 'display', 'none')
       Dom.get('failed_logins_upper').value = ''
   }

   function hide_requests() {
       Dom.setStyle('requests_upper', 'display', 'none')
       Dom.setStyle('b_requests', 'display', 'none')
       Dom.get('requests_upper').value = ''
   }

   function checkbox_changed_invoice_condition(o) {
       cat = Dom.get(o).getAttribute('cat');

       if (cat == 'less') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_invoice();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'invoice_condition_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'equal') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_invoice();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'invoice_condition_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'more') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_invoice();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'invoice_condition_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'between') {
           Dom.setStyle('number_of_invoices_upper', 'display', '')
           Dom.setStyle('a', 'display', '')
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'invoice_condition_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       }

   }

   function checkbox_changed_order_condition(o) {
       cat = Dom.get(o).getAttribute('cat');

       if (cat == 'less') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_order();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'order_condition_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'equal') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {

               hide_order();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'order_condition_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'more') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_order();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'order_condition_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'between') {
           Dom.setStyle('number_of_orders_upper', 'display', '')
           Dom.setStyle('c', 'display', '')
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'order_condition_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       }

   }


   function checkbox_changed_sales_condition(o) {
       cat = Dom.get(o).getAttribute('cat');

       if (cat == 'sales_less') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_sales();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'sales_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'sales_equal') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_sales();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'sales_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'sales_more') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_sales();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'sales_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'sales_between') {
           Dom.setStyle('sales_upper', 'display', '')
           Dom.setStyle('b_sales', 'display', '')
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'sales_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       }

   }


   function checkbox_changed_logins_condition(o) {
       cat = Dom.get(o).getAttribute('cat');

       if (cat == 'logins_less') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_logins();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'logins_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'logins_equal') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_logins();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'logins_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'logins_more') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_logins();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'logins_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'logins_between') {
           Dom.setStyle('logins_upper', 'display', '')
           Dom.setStyle('b_logins', 'display', '')
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'logins_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       }

   }

   function checkbox_changed_failed_logins_condition(o) {
       cat = Dom.get(o).getAttribute('cat');

       if (cat == 'failed_logins_less') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_failed_logins();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'failed_logins_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'failed_logins_equal') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_failed_logins();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'failed_logins_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'failed_logins_more') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_failed_logins();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'failed_logins_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'failed_logins_between') {
           Dom.setStyle('failed_logins_upper', 'display', '')
           Dom.setStyle('b_failed_logins', 'display', '')
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'failed_logins_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       }

   }

   function checkbox_changed_requests_condition(o) {
       cat = Dom.get(o).getAttribute('cat');

       if (cat == 'requests_less') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_requests();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'requests_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'requests_equal') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_requests();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'requests_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'requests_more') {
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               hide_requests();
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'requests_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       } else if (cat == 'requests_between') {
           Dom.setStyle('requests_upper', 'display', '')
           Dom.setStyle('b_requests', 'display', '')
           if (Dom.hasClass(o, 'selected')) {
               return;
           } else {
               Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', 'requests_option'), 'selected');
               Dom.addClass(o, 'selected');
           }
       }

   }
  
function select_category(oArgs) {


       var customer_category = Dom.get('customer_categories').value;
       if (customer_category != '') {
           customer_category = customer_category + ','
       }
       customer_category = customer_category + tables.table8.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
       Dom.get('customer_categories').value = customer_category;
       dialog_category_list.hide();
       hide_filter(true, 2)
   }

   function select_country(oArgs) {
       var geo_constraints = Dom.get('geo_constraints').value;
       if (geo_constraints != '') {
           geo_constraints = geo_constraints + ','
       }
       geo_constraints = geo_constraints + tables.table2.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
       Dom.get('geo_constraints').value = geo_constraints;
       dialog_country_list.hide();
       hide_filter(true, 2)
   }

   function select_postal_code(oArgs) {
  
   
       var geo_constraints = Dom.get('geo_constraints').value;
       if (geo_constraints != '') {
           geo_constraints = geo_constraints + ','
       }
       geo_constraints = geo_constraints + 'pc(' + tables.table3.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '') + ')';
       Dom.get('geo_constraints').value = geo_constraints;
       dialog_postal_code_list.hide();
       hide_filter(true, 3)
   }

   function select_wregion(oArgs) {
       var geo_constraints = Dom.get('geo_constraints').value;
       if (geo_constraints != '') {
           geo_constraints = geo_constraints + ','
       }
       geo_constraints = geo_constraints + 'wr(' + tables.table1.getRecord(oArgs.target).getData('wregion_code').replace(/<.*?>/g, '') + ')';
       Dom.get('geo_constraints').value = geo_constraints;
       dialog_wregion_list.hide();
       hide_filter(true, 1)
   }

   function select_city(oArgs) {
       var geo_constraints = Dom.get('geo_constraints').value;
       if (geo_constraints != '') {
           geo_constraints = geo_constraints + ','
       }
       geo_constraints = geo_constraints + 't(' + tables.table4.getRecord(oArgs.target).getData('city').replace(/<.*?>/g, '') + ')';
       Dom.get('geo_constraints').value = geo_constraints;
       dialog_city_list.hide();
       hide_filter(true, 4)
   }

   function select_department(oArgs) {
       var product_ordered_or = Dom.get('product_ordered_or').value;
       if (product_ordered_or != '') {
           product_ordered_or = product_ordered_or + ','
       }
       product_ordered_or = product_ordered_or + 'd(' + tables.table5.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '') + ')';
       Dom.get('product_ordered_or').value = product_ordered_or;
       dialog_department_list.hide();
       hide_filter(true, 5)
   }

   function select_family(oArgs) {
       var product_ordered_or = Dom.get('product_ordered_or').value;
       if (product_ordered_or != '') {
           product_ordered_or = product_ordered_or + ','
       }
       product_ordered_or = product_ordered_or + 'f(' + tables.table6.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '') + ')';
       Dom.get('product_ordered_or').value = product_ordered_or;
       dialog_family_list.hide();
       hide_filter(true, 6)
   }

   function select_product(oArgs) {
   
   
   
       var product_ordered_or = Dom.get('product_ordered_or').value;
       if (product_ordered_or != '') {
           product_ordered_or = product_ordered_or + ','
       }
       product_ordered_or = product_ordered_or + tables.table7.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
       Dom.get('product_ordered_or').value = product_ordered_or;
       dialog_product_list.hide();
       hide_filter(true, 7)
   }

   function select_product_category(oArgs) {
       var product_ordered_or = Dom.get('product_ordered_or').value;
       if (product_ordered_or != '') {
           product_ordered_or = product_ordered_or + ','
       }
       product_ordered_or = product_ordered_or + 'cat(' + tables.table8.getRecord(oArgs.target).getData('category_code').replace(/<.*?>/g, '') + ')';
       Dom.get('product_ordered_or').value = product_ordered_or;
       dialog_category_list.hide();
       hide_filter(true, 8)
   }

    
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


		var store_key=Dom.get('store_id').value;

	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				        {key:"id", label:"<?php echo$customers_ids[0]?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?> width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"contact_since", label:"<?php echo _('Since')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"orders", label:"<?php echo _('Orders')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"activity", label:"<?php echo _('Status')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",width:160,<?php echo($_SESSION['state']['customers']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"email", label:"<?php echo _('Email')?>",width:210,<?php echo($_SESSION['state']['customers']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"telephone", label:"<?php echo _('Telephone')?>", width:137,<?php echo($_SESSION['state']['customers']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},className:"aleft"}
				       ,{key:"address", label:"<?php echo _('Contact Address')?>", width:176,<?php echo($_SESSION['state']['customers']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"billing_address", label:"<?php echo _('Billing Address')?>", width:170,<?php echo($_SESSION['state']['customers']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"delivery_address", label:"<?php echo _('Delivery Address')?>", width:170,<?php echo($_SESSION['state']['customers']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"total_payments", label:"<?php echo _('Sales')?>",width:99,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_refunds", label:"<?php echo _('Refunds')?>",width:90,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"net_balance", label:"<?php echo _('Balance')?>",width:90,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"balance", label:"<?php echo _('Outstanding')?>",width:90,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_profit", label:"<?php echo _('Profit')?>",width:90,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_orders", label:"<?php echo _('Rank Orders')?>",width:121,<?php echo($_SESSION['state']['customers']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_invoices", label:"<?php echo _('Rank Invoices')?>",width:121,<?php echo($_SESSION['state']['customers']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_balance", label:"<?php echo _('Rank Balance')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_profits", label:"<?php echo _('Rank Profits')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
								,{key:"logins", label:"<?php echo _('Logins')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='weblog'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"failed_logins", label:"<?php echo _('Failed Logis')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='weblog'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"requests", label:"<?php echo _('Viewed Pages')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='weblog'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				];


					    var awhere=get_awhere();
	
	store_id=Dom.get('store_id').value;
    //var request='&sf=0&store_id='+store_id+'&where=' +awhere;
					 
		request="ar_contacts.php?tipo=customers&sf=0&parent=store&parent_key=0&where=" +awhere
		//alert(request)
	    this.dataSource0 = new YAHOO.util.DataSource(request);
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.table_id=tableid;

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
			 'id',
			 'name',
			 'location',
			 'orders',
			 'email',
			 'telephone',
			 'last_order','activity',
			 'total_payments','contact_name'
			 ,"address"
			 ,"billing_address","delivery_address"
			 ,"total_paymants","total_refunds","net_balance","total_profit","balance","contact_since"
			 ,"top_orders","top_invoices","top_balance","top_profits","logins","failed_logins","requests"
			 ]};

		
		

	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;


	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['customers']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['customers']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['customers']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
//	    alert("<?php echo$_SESSION['state']['customers']['list']['order']?>")
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    
	    this.table0.subscribe("dataReturnEvent", data_returned);  


	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['customers']['f_field']?>',value:'<?php echo $_SESSION['state']['customers']['customers']['f_value']?>'};

	var tableid=1;
	
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"wregion_code", label:"<?php echo _('Code')?>",width:30, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"wregion_name", label:"<?php echo _('World Region')?>",width:120, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     	,{key:"flags", label:"<?php echo _('Countries')?>",width:240, sortable:false,className:"aleft"}

					];
		    
		      
		      this.dataSource1 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=world_regions_list&tableid=1&nr=20&sf=0");
		      this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource1.connXhrMode = "queueRequests";
		      	    this.dataSource1.table_id=tableid;

		      this.dataSource1.responseSchema = {
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
				  "wregion_name","wregion_code","flags"
				   ]};
		      
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								   
								   ,sortedBy : {
								      key: "wregion_code",
								       dir: ""
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
                   this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
                   
                   this.table1.subscribe("rowMouseoverEvent", this.table1.onEventHighlightRow);
       this.table1.subscribe("rowMouseoutEvent", this.table1.onEventUnhighlightRow);
      this.table1.subscribe("rowClickEvent", select_wregion);
     

                   
	    this.table1.filter={key:'wregion_code',value:''};

   var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"code", label:"<?php echo _('Code')?>",width:25,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", label:"<?php echo _('Name')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"wregion", label:"<?php echo _('Region')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

		];
			       
	    this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=country_list&tableid=2&nr=20&sf=0");
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
			 "name","flag",'code','wregion'
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
      this.table2.subscribe("rowClickEvent", select_country);
     


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};


   var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var ColumnDefs = [
                   {key:"code", label:"<?php echo _('Postal Code')?>",width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    ,{key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			       ,{key:"name",label:"<?php echo _('Country Name')?>",width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 ,{key:"times_used", label:"<?php echo _('Times Used')?>",width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			];
			       
	    this.dataSource3 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=postal_codes_list&store_key="+store_key+"&tableid=3&nr=20&sf=0");
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
			 "name","flag",'code','times_used'
			 ]};

	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
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
     


	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.filter={key:'code',value:''};

   var tableid=4; 
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
                   {key:"city", label:"<?php echo _('City Name')?>",width:125,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                    ,{key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	,{key:"name", label:"<?php echo _('Country Name')?>",width:115,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					 ,{key:"times_used", label:"<?php echo _('Times Used')?>",width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

	];
			       
	    this.dataSource4 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=towns_list&store_key="+store_key+"&tableid=4&nr=20&sf=0");
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
	    	    this.dataSource4.table_id=tableid;

	    this.dataSource4.responseSchema = {
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
			 "name","flag",'city','times_used'
			 ]};

	    this.table4 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource4
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator4', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "city",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table4.subscribe("rowMouseoverEvent", this.table4.onEventHighlightRow);
       this.table4.subscribe("rowMouseoutEvent", this.table4.onEventUnhighlightRow);
      this.table4.subscribe("rowClickEvent", select_city);
     


	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table4.filter={key:'city',value:''};

   var tableid=5; 
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			
                    {key:"code", label:"<?php echo _('Code')?>",width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				];
			       
	    this.dataSource5 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=department_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    	    this.dataSource5.table_id=tableid;

	    this.dataSource5.responseSchema = {
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
			 "code","name"
			 ]};

	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);





 this.table5.subscribe("rowMouseoverEvent", this.table5.onEventHighlightRow);
       this.table5.subscribe("rowMouseoutEvent", this.table5.onEventUnhighlightRow);
      this.table5.subscribe("rowClickEvent", select_department);
           
           this.table5.table_id=tableid;
           this.table5.subscribe("renderEvent", myrenderEvent);



	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table5.filter={key:'code',value:''};
	    
   var tableid=6; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			
                    {key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource6 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=family_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource6.connXhrMode = "queueRequests";
	    	    this.dataSource6.table_id=tableid;

	    this.dataSource6.responseSchema = {
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
			 "code",'name'
			 ]};

	    this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource6
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator6', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table6.subscribe("rowMouseoverEvent", this.table6.onEventHighlightRow);
       this.table6.subscribe("rowMouseoutEvent", this.table6.onEventUnhighlightRow);
      this.table6.subscribe("rowClickEvent", select_family);
        this.table6.table_id=tableid;
           this.table6.subscribe("renderEvent", myrenderEvent);


	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table6.filter={key:'code',value:''};


   var tableid=7; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
                    {key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			  			];
			       
		this.dataSource7 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=product_list&parent=store&parent_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource7.connXhrMode = "queueRequests";
	    	    this.dataSource7.table_id=tableid;

	    this.dataSource7.responseSchema = {
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
			 "code","name"
			 ]};

	    this.table7 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource7
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator7', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table7.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table7.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table7.subscribe("rowMouseoverEvent", this.table7.onEventHighlightRow);
       this.table7.subscribe("rowMouseoutEvent", this.table7.onEventUnhighlightRow);
      this.table7.subscribe("rowClickEvent", select_product);
     


	    this.table7.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table7.filter={key:'code',value:''};


 var tableid=8; 
	    var tableDivEL="table"+tableid;

	    var ColumnDefs = [
                   {key:"label", label:"<?php echo _('Code')?>",width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"code",label:'',width:250,hidden:true}

			       ,{key:"tree",label:"<?php echo _('Tree')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			];
			       
	    this.dataSource8 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=category_list&subject=Customers&store_key="+Dom.get('store_id').value+"&tableid=8&nr=20&sf=0");
	    this.dataSource8.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource8.connXhrMode = "queueRequests";
	    	    this.dataSource8.table_id=tableid;

	    this.dataSource8.responseSchema = {
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
			 "code","label","tree","key"
			 ]};

	    this.table8 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource8
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator8', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info8'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "label",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table8.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table8.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table8.subscribe("rowMouseoverEvent", this.table8.onEventHighlightRow);
       this.table8.subscribe("rowMouseoutEvent", this.table8.onEventUnhighlightRow);
      this.table8.subscribe("rowClickEvent", select_category);
     

	    this.table8.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table8.filter={key:'label',value:''};



	
	};

    });


function get_awhere() {
    dont_have = Dom.getElementsByClassName('selected', 'button', 'dont_have_options');
    dont_have_array = new Array();
    for (x in dont_have) {
        dont_have_array[x] = dont_have[x].getAttribute('cat');
    }
    have = Dom.getElementsByClassName('selected', 'button', 'have_options');
    have_array = new Array();
    for (x in have) {
        have_array[x] = have[x].getAttribute('cat');
    }

    allow = Dom.getElementsByClassName('selected', 'button', 'allow_options');
    allow_array = new Array();
    for (x in allow) {
        allow_array[x] = allow[x].getAttribute('cat');
    }

    dont_allow = Dom.getElementsByClassName('selected', 'button', 'dont_allow_options');
    dont_allow_array = new Array();
    for (x in dont_allow) {
        dont_allow_array[x] = dont_allow[x].getAttribute('cat');
    }

    customers_which = Dom.getElementsByClassName('selected', 'button', 'customers_which_options');
    customers_which_array = new Array();
    for (x in customers_which) {
        customers_which_array[x] = customers_which[x].getAttribute('cat');
    }

    invoice_option = Dom.getElementsByClassName('selected', 'button', 'invoice_condition_option');
    invoice_condition_option_array = new Array();
    for (x in invoice_option) {
        invoice_condition_option_array[x] = invoice_option[x].getAttribute('cat');
    }

    order_option = Dom.getElementsByClassName('selected', 'button', 'order_condition_option');
    order_condition_option_array = new Array();
    for (x in order_option) {
        order_condition_option_array[x] = order_option[x].getAttribute('cat');
    }

    sales_option = Dom.getElementsByClassName('selected', 'button', 'sales_option');
    sales_option_array = new Array();
    for (x in sales_option) {
        sales_option_array[x] = sales_option[x].getAttribute('cat');

    }
    logins_option = Dom.getElementsByClassName('selected', 'button', 'logins_option');
    logins_option_array = new Array();
    for (x in logins_option) {
        logins_option_array[x] = logins_option[x].getAttribute('cat');
    }
    failed_logins_option = Dom.getElementsByClassName('selected', 'button', 'failed_logins_option');
    failed_logins_option_array = new Array();
    for (x in failed_logins_option) {
        failed_logins_option_array[x] = failed_logins_option[x].getAttribute('cat');
    }
    requests_option = Dom.getElementsByClassName('selected', 'button', 'requests_option');
    requests_option_array = new Array();
    for (x in requests_option) {
        requests_option_array[x] = requests_option[x].getAttribute('cat');
    }

pending_order_payment_method= Dom.getElementsByClassName('selected', 'button', 'pending_order_payment_method');
pending_order_payment_method_array =new Array();
    for (x in pending_order_payment_method) {
        pending_order_payment_method_array[x] = pending_order_payment_method[x].getAttribute('field');
    }


    order_time_units_since_last_order_qty = parseFloat(Dom.get('order_time_units_since_last_order_qty').value);
    order_time_units_since_last_order_units = Dom.get('order_time_units_since_last_order_unit').value;
    if (!order_time_units_since_last_order_qty > 0) {
        order_time_units_since_last_order_qty = -1;

    }

    var store_key = Dom.get('store_id').value;

    var data = {
        store_key: store_key,
        dont_have: dont_have_array,
        have: have_array,
        allow: allow_array,
        dont_allow: dont_allow_array,
        customers_which: customers_which_array,
        invoice_option: invoice_condition_option_array,
        order_option: order_condition_option_array,


        //not_customers_which:not_customers_which_array,
        geo_constraints: Dom.get('geo_constraints').value,
        categories: Dom.get('customer_categories').value,

        product_ordered1: Dom.get('product_ordered_or').value,
        //	product_ordered2: Dom.get('product_ordered2').value,
        product_not_ordered1: Dom.get('product_not_ordered1').value,
        //	product_not_ordered2: Dom.get('product_not_ordered2').value,
        product_not_received1: Dom.get('product_not_received1').value,
        //	product_not_received2: Dom.get('product_not_received2').value,
        ordered_from: Dom.get('v_calpop1').value,
        ordered_to: Dom.get('v_calpop2').value,
        customer_created_from: Dom.get('v_calpop3').value,
        customer_created_to: Dom.get('v_calpop4').value,
        lost_customer_from: Dom.get('v_calpop5').value,
        lost_customer_to: Dom.get('v_calpop6').value,
        number_of_invoices_upper: Dom.get('number_of_invoices_upper').value,
        number_of_invoices_lower: Dom.get('number_of_invoices_lower').value,
        number_of_orders_upper: Dom.get('number_of_orders_upper').value,
        number_of_orders_lower: Dom.get('number_of_orders_lower').value,
        sales_lower: Dom.get('sales_lower').value,
        sales_upper: Dom.get('sales_upper').value,
        sales_option: sales_option_array,

        logins_lower: Dom.get('logins_lower').value,
        logins_upper: Dom.get('logins_upper').value,
        logins_option: logins_option_array,
        failed_logins_lower: Dom.get('failed_logins_lower').value,
        failed_logins_upper: Dom.get('failed_logins_upper').value,
        failed_logins_option: failed_logins_option_array,
        requests_lower: Dom.get('requests_lower').value,
        requests_upper: Dom.get('requests_upper').value,
        requests_option: requests_option_array,

        order_time_units_since_last_order_qty: order_time_units_since_last_order_qty,
        order_time_units_since_last_order_units: order_time_units_since_last_order_units,
        pending_orders:Dom.get('pending_orders').value,
        pending_orders_days_no_change:Dom.get('basket_days_no_change').value,
pending_orders_days_no_change_type:Dom.get('basket_days_no_change_type').value,
        pending_order_payment_method:pending_order_payment_method_array,
    }
    return YAHOO.lang.JSON.stringify(data);



}


function submit_search(e) {

    searched = true;
    var awhere = get_awhere();
    var table = tables.table0;
    var datasource = tables.dataSource0;
    store_id = Dom.get('store_id').value;
    var request = '&sf=0&parent_key=' + store_id + '&where=' + awhere;
    Dom.setStyle('the_table', 'display', 'none');
    Dom.setStyle('searching', 'display', '');
    Dom.setStyle('save_dialog', 'visibility', 'visible');
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

}


var submit_search_on_enter=function(e,tipo){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

     if (key == 13)
	 submit_search(e,tipo);
};

function show_dialog_department_list() {
    region1 = Dom.getRegion('department');
    region2 = Dom.getRegion('dialog_department_list');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_department_list', pos);
    dialog_department_list.show();
}
function show_dialog_family_list() {
    region1 = Dom.getRegion('family');
    region2 = Dom.getRegion('dialog_family_list');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_family_list', pos);
    dialog_family_list.show();
}
function show_dialog_product_list() {
    region1 = Dom.getRegion('product');
    region2 = Dom.getRegion('dialog_product_list');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_product_list', pos);
    dialog_product_list.show();
}



function init() {




    init_search('customers_store');
    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS1.queryMatchContains = true;
    oACDS1.table_id = 1;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
    oAutoComp1.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    YAHOO.util.Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);

    var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2.queryMatchContains = true;
    oACDS2.table_id = 2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
    oAutoComp2.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    YAHOO.util.Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);

    var oACDS3 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS3.queryMatchContains = true;
    oACDS3.table_id = 3;
    var oAutoComp3 = new YAHOO.widget.AutoComplete("f_input3", "f_container3", oACDS3);
    oAutoComp3.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show3', "click", show_filter, 3);
    YAHOO.util.Event.addListener('clean_table_filter_hide3', "click", hide_filter, 3);

    var oACDS4 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS4.queryMatchContains = true;
    oACDS4.table_id = 4;
    var oAutoComp4 = new YAHOO.widget.AutoComplete("f_input4", "f_container4", oACDS4);
    oAutoComp4.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show4', "click", show_filter, 4);
    YAHOO.util.Event.addListener('clean_table_filter_hide4', "click", hide_filter, 4);




    var oACDS5 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS5.queryMatchContains = true;
    oACDS5.table_id = 5;
    var oAutoComp5 = new YAHOO.widget.AutoComplete("f_input5", "f_container5", oACDS5);
    oAutoComp5.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show5', "click", show_filter, 5);
    YAHOO.util.Event.addListener('clean_table_filter_hide5', "click", hide_filter, 5);

    var oACDS6 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS6.queryMatchContains = true;
    oACDS6.table_id = 6;
    var oAutoComp6 = new YAHOO.widget.AutoComplete("f_input6", "f_container6", oACDS6);
    oAutoComp6.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show6', "click", show_filter, 6);
    YAHOO.util.Event.addListener('clean_table_filter_hide6', "click", hide_filter, 6);

    var oACDS7 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS7.queryMatchContains = true;
    oACDS7.table_id = 7;
    var oAutoComp7 = new YAHOO.widget.AutoComplete("f_input7", "f_container7", oACDS7);
    oAutoComp7.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show7', "click", show_filter, 7);
    YAHOO.util.Event.addListener('clean_table_filter_hide7', "click", hide_filter, 7);


    dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", {
        context: ["country", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_country_list.render();
    Event.addListener("country", "click", dialog_country_list.show, dialog_country_list, true);
    
    
    
    
    

    dialog_wregion_list = new YAHOO.widget.Dialog("dialog_wregion_list", {
        context: ["wregion", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_wregion_list.render();
    Event.addListener("wregion", "click", dialog_wregion_list.show, dialog_wregion_list, true);

    dialog_city_list = new YAHOO.widget.Dialog("dialog_city_list", {
        context: ["city", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_city_list.render();
    Event.addListener("city", "click", dialog_city_list.show, dialog_city_list, true);


    dialog_postal_code_list = new YAHOO.widget.Dialog("dialog_postal_code_list", {
        context: ["postal_code", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_postal_code_list.render();
    Event.addListener("postal_code", "click", dialog_postal_code_list.show, dialog_postal_code_list, true);

    dialog_department_list = new YAHOO.widget.Dialog("dialog_department_list", {
      
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_department_list.render();
    Event.addListener("department", "click", show_dialog_department_list) ;

    dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_family_list.render();
    Event.addListener("family", "click", show_dialog_family_list);

    dialog_product_list = new YAHOO.widget.Dialog("dialog_product_list", {
        
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_product_list.render();
    Event.addListener("product", "click", show_dialog_product_list);

    dialog_category_list = new YAHOO.widget.Dialog("dialog_category_list", {
        context: ["customer_category", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_category_list.render();
    Event.addListener("customer_category", "click", dialog_category_list.show, dialog_category_list, true);

    YAHOO.util.Event.addListener(['submit_search', 'modify_search'], "click", submit_search);
    YAHOO.util.Event.addListener(['product_ordered1'], "keydown", submit_search_on_enter);
    YAHOO.util.Event.addListener(['save_list'], "click", save_search_list);

    YAHOO.util.Event.addListener(['show_dont_wish_to_receive'], "click", show_dont_wish_to_receive);
    YAHOO.util.Event.addListener(['show_dont_have'], "click", show_dont_have);


    //var ids=['general','contact'];
    //YAHOO.util.Event.addListener(ids, "click",change_view);
    cal1 = new YAHOO.widget.Calendar("product_ordered_or_from", "product_ordered_or_from_Container", {
        title: "<?php echo _('From Date')?>:",
        close: true
    });
    cal1.update = updateCal;
    cal1.id = '1';
    cal1.render();
    cal1.update();
    cal1.selectEvent.subscribe(handleSelect, cal1, true);

    cal2 = new YAHOO.widget.Calendar("product_ordered_or_to", "product_ordered_or_to_Container", {
        title: "<?php echo _('To Date')?>:",
        close: true
    });
    cal2.update = updateCal;
    cal2.id = '2';
    cal2.render();
    cal2.update();
    cal2.selectEvent.subscribe(handleSelect, cal2, true);

    cal3 = new YAHOO.widget.Calendar("customer_first_contacted_from", "customer_first_contacted_from_Container", {
        title: "<?php echo _('From Date')?>:",
        close: true
    });
    cal3.update = updateCal;
    cal3.id = '3';
    cal3.render();
    cal3.update();
    cal3.selectEvent.subscribe(handleSelect, cal3, true);

    cal4 = new YAHOO.widget.Calendar("customer_first_contacted_to", "customer_first_contacted_to_Container", {
        title: "<?php echo _('To Date')?>:",
        close: true
    });
    cal4.update = updateCal;
    cal4.id = '4';
    cal4.render();
    cal4.update();
    cal4.selectEvent.subscribe(handleSelect, cal4, true);

    cal5 = new YAHOO.widget.Calendar("lost_customer_from", "lost_customer_from_Container", {
        title: "<?php echo _('From Date')?>:",
        close: true
    });
    cal5.update = updateCal;
    cal5.id = '5';
    cal5.render();
    cal5.update();
    cal5.selectEvent.subscribe(handleSelect, cal5, true);

    cal6 = new YAHOO.widget.Calendar("lost_customer_to", "lost_customer_to_Container", {
        title: "<?php echo _('To Date')?>:",
        close: true
    });
    cal6.update = updateCal;
    cal6.id = '6';
    cal6.render();
    cal6.update();
    cal6.selectEvent.subscribe(handleSelect, cal6, true);


    //cal2.cfg.setProperty("iframe", true);
    //cal2.cfg.setProperty("zIndex", 10);


    YAHOO.util.Event.addListener("product_ordered_or_from", "click", cal1.show, cal1, true);
    YAHOO.util.Event.addListener("product_ordered_or_to", "click", cal2.show, cal2, true);
    YAHOO.util.Event.addListener("customer_first_contacted_from", "click", cal3.show, cal3, true);
    YAHOO.util.Event.addListener("customer_first_contacted_to", "click", cal4.show, cal4, true);
    YAHOO.util.Event.addListener("lost_customer_from", "click", cal5.show, cal5, true);
    YAHOO.util.Event.addListener("lost_customer_to", "click", cal6.show, cal6, true);

    YAHOO.util.Event.addListener(["number_of_orders_lower","number_of_orders_upper","order_time_units_since_last_order_qty"
    ,"number_of_invoices_lower","number_of_invoices_upper","sales_upper","sales_lower","logins_lower","logins_upper",
    "failed_logins_lower","failed_logins_upper",
    "requests_lower","requests_upper"
    ], "keyup", show_boundary_options);



    YAHOO.util.Event.addListener("order_time_units_since_last_order_qty", "keyup", validate_order_time_units_since_last_order);
 YAHOO.util.Event.addListener(Dom.getElementsByClassName('fields','button','fields_management'),'click',show_fields);

    if (Dom.get('auto').value == '1') {
        Dom.setStyle('save_dialog', 'visibility', 'visible');
        Dom.setStyle('save_list', 'display', '');
        Dom.setStyle('modify_search', 'display', '');
        Dom.setStyle('submit_search', 'display', 'none');
    }

    set_values();
    
        YAHOO.util.Event.addListener(customer_views_ids, "click", change_view_customers, 0);


}

function show_fields(){
	Dom.addClass(this,'selected')
	Dom.setStyle(this.getAttribute('field'),'display','');
}

function show_boundary_options() {

    if (this.value != '') {
        Dom.setStyle(Dom.getElementsByClassName('catbox', 'button', this.parentNode.parentNode), 'display', '');

        if (is_numeric(this.value)) {
            Dom.removeClass(Dom.getElementsByClassName('catbox', 'button', this.parentNode.parentNode), 'disabled');
            Dom.removeClass(this,'error')
        } else {
            Dom.addClass(Dom.getElementsByClassName('catbox', 'button', this.parentNode.parentNode), 'disabled');
            Dom.addClass(this,'error')

        }

    } else {
        Dom.setStyle(Dom.getElementsByClassName('catbox', 'button', this.parentNode.parentNode), 'display', 'none');
    }

}



YAHOO.util.Event.onDOMReady(init);

function validate_order_time_units_since_last_order() {
    var qty = parseFloat(this.value)
    unit = Dom.get('order_time_units_since_last_order_unit').value;
    if (qty > 0) {

        Dom.addClass('order_time_units_since_last_order_' + unit, 'selected')
    } else {
        Dom.removeClass('order_time_units_since_last_order_' + unit, 'selected')
    }

}

YAHOO.util.Event.onContentReady("rppmenu0", function() {
    rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"
    });
    rppmenu.render();
    rppmenu.subscribe("show", rppmenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu1", function() {
    var oMenu1 = new YAHOO.widget.ContextMenu("filtermenu1", {
        trigger: "filter_name1"
    });
    oMenu1.render();
    oMenu1.subscribe("show", oMenu1.focus);

});
YAHOO.util.Event.onContentReady("filtermenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
        trigger: "filter_name2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});
YAHOO.util.Event.onContentReady("filtermenu3", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {
        trigger: "filter_name3"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});
YAHOO.util.Event.onContentReady("filtermenu4", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu4", {
        trigger: "filter_name4"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});
YAHOO.util.Event.onContentReady("filtermenu5", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu5", {
        trigger: "filter_name5"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu6", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu6", {
        trigger: "filter_name6"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});
YAHOO.util.Event.onContentReady("filtermenu7", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu7", {
        trigger: "filter_name7"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
    // oMenu.show()
});

