

 function change_view_customers(e){
    
     var tipo=this.id;
     
      var table=tables['table0'];
      old_view=table.view;
      
      Dom.get('general').className='';
      Dom.get('contact').className='';
      Dom.get('address').className='';
      Dom.get('balance').className='';
      Dom.get('rank').className='';
      
      Dom.get(tipo).className='selected';
      table.hideColumn('location');
      table.hideColumn('last_order');
      table.hideColumn('orders');
      
      table.hideColumn('email');
      table.hideColumn('telephone');
      table.hideColumn('contact_name');
      
      table.hideColumn('address');
            table.hideColumn('billing_address');
      table.hideColumn('delivery_address');
            table.hideColumn('contact_since');


    //  table.hideColumn('town');
    //  table.hideColumn('postcode');
    //  table.hideColumn('region');
     // table.hideColumn('country');
      //      table.hideColumn('ship_address');
      //table.hideColumn('ship_town');
      //table.hideColumn('ship_postcode');
      //table.hideColumn('ship_region');
      //table.hideColumn('ship_country');
      
      
      table.hideColumn('total_payments');
      table.hideColumn('net_balance');
      table.hideColumn('total_refunds');
      table.hideColumn('total_profit');

      table.hideColumn('balance');
      table.hideColumn('top_orders');
      table.hideColumn('top_invoices');
      table.hideColumn('top_balance');
      table.hideColumn('top_profits');
      table.hideColumn('activity');



      if(tipo=='general'){
	  table.showColumn('name');
	  table.showColumn('location');
	  table.showColumn('last_order');
	  table.showColumn('orders');
	  table.showColumn('activity');
	table.showColumn('contact_since');

	  Dom.get('general').className='selected';
      }else if(tipo=='contact'){
	  table.showColumn('name');
	  table.showColumn('contact_name');
	  table.showColumn('email');
	  table.showColumn('telephone');

      }else if(tipo=='address'){
	  table.showColumn('address');
	  table.showColumn('billing_address');
	  table.showColumn('delivery_address');
	 // table.showColumn('region');
	 // table.showColumn('country');
	  Dom.get('address').className='selected';
   //   }else if(tipo=='ship_to_address'){
	//	  table.showColumn('ship_address');
	 // table.showColumn('ship_town');
	 // table.showColumn('ship_postcode');
	 // table.showColumn('ship_region');
	 // table.showColumn('ship_country');

      }else if(tipo=='balance'){
	     table.showColumn('name');
	  table.showColumn('net_balance');
	  table.showColumn('total_refunds');
	  table.showColumn('total_payments');
	  table.showColumn('total_profit');

	  table.showColumn('balance');

      }else if(tipo=='rank'){
	     table.showColumn('name');
	  table.showColumn('top_orders');
	  table.showColumn('top_invoices');
	  table.showColumn('top_balance');
	  table.showColumn('top_profits');

      }


      YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customers-table-view&value='+escape(tipo),{});
  }
  
  
   function common_customer_init(){
 
  var ids=['general','contact','address','ship_to_address','balance','rank'];
YAHOO.util.Event.addListener(ids, "click",change_view_customers);

}

YAHOO.util.Event.onDOMReady(common_customer_init);
