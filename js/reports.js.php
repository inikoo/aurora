<?
include_once('../common.php');
?>
   var Event = YAHOO.util.Event;
    var Dom   = YAHOO.util.Dom;
function init(){


YAHOO.supplier.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?=_('Choose a date')?>:", close:true } );

 YAHOO.supplier.cal1.update=updateCal;

 YAHOO.supplier.cal1.container_id='v_calpop1';
 YAHOO.supplier.cal1.render();
 YAHOO.supplier.cal1.update();
 YAHOO.supplier.cal1.selectEvent.subscribe(CalhandleSelect, YAHOO.supplier.cal1, true); 
 YAHOO.util.Event.addListener("fr_from", "click", YAHOO.supplier.cal1.show, YAHOO.supplier.cal1, true);



  var change_plot= function(e){
   Dom.get('the_plot').src ='plot.php?tipo='+this.name;
   YAHOO.util.Connect.asyncRequest('POST','ar_orders.php?tipo=changesalesplot&value=' + escape(this.name) ); 
  }

  var ids = ["net_sales_gmonth"]; 
  Event.addListener(ids, "change", change_plot);
}

Event.onDOMReady(init);