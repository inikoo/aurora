<?php
include_once('common.php');
print "var plot = new Object;";
foreach($_SESSION['state']['reports'] as $key => $value){
    if(is_array($value) and array_key_exists('plot', $value))
	print "plot.$key='".$value['plot']."';";
}
?>

var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var current_view='<?php echo$_SESSION['state']['reports']['view']?>';

function init(){

	var attributes = {opacity: { to: 1 }};
	var myAnim = new YAHOO.util.Anim('plot_div', attributes);
	myAnim.duration = 3; 

	
    	cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
	cal2.update=updateCal;
	cal2.id=2;
	cal2.render();
	
	cal2.cfg.setProperty("iframe", true);
	
	cal2.cfg.setProperty("zIndex", 10);
	//cal2.stackIframe();
	//cal2..showIframe();
	
	cal2.update();
	cal2.selectEvent.subscribe(handleSelect, cal2, true);
	

 
	cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
	cal1.update=updateCal;cal1.id=1;cal1.render();

	cal1.cfg.setProperty("iframe", true);
	
	cal1.cfg.setProperty("zIndex", 10);
	cal1.update();cal1.selectEvent.subscribe(handleSelect, cal1, true); 
	YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
	YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);
	

	var change_map = function(e){
	    var region=this.id;
	    var request='ar_map.php?tipo='+ escape(region);
	    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			
			if (r.state == 200) {

			    Dom.get('map_image').src=r.url;
			    
		}
		
	    }
	});
	    
	}
	var change_plot = function(e){

	    
	    var callback =
	    {
		success: function(o) {
		    //alert(o.responseText)
		},
		failure: function(o) {alert("error")},
		cache:false 
	    }



	    if(this.id=='timeplot_sales'){
		Dom.get('plot_options_sales').style.display='none';
		Dom.get('plot_options_sales_bis').style.display='';
		Dom.get('plot_div').style.width='950px';
		Dom.get('the_plot').src = 'timeplot.php?f=d';
		
		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-sales-plot&value=timeplot_sales',callback );

	    }else if(this.id=='net_sales_month' || this.id=='net_sales_month_bis'){
		Dom.get('plot_div').style.width='810px';
		Dom.get('plot_options_sales').style.display='';
		Dom.get('plot_options_sales_bis').style.display='none';


		Dom.get('net_sales_month').className='but selected';
		Dom.get('net_diff1y_sales_month').className='but';
		Dom.get('timeplot_sales').className='but';
		Dom.get('tr_net_diff1y_sales_month').style.display='none';
		Dom.get('tr_net_diff1y_sales_month_per').style.display='none';
			Dom.get('tr_net_sales_gmonth').style.display='';

		if(Dom.get('net_sales_gmonth').checked){
		    Dom.get('the_plot').src = 'plot.php?tipo=total_sales_groupby_month';
		    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-sales-plot&value=total_sales_groupby_month',callback );
		    plot_sales='net_sales_gmonth';
		}else{

		    Dom.get('the_plot').src = 'plot.php?tipo=total_sales_month';
		    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-sales-plot&value=total_sales_month',callback );
		    plot_sales='net_sales_month';
		}
		
		
		
		
	    }else if(this.id=='net_sales_gmonth'){
		
		Dom.get('plot_div').style.width='810px';
		Dom.get('plot_options_sales').style.display='';
		Dom.get('plot_options_sales_bis').style.display='none';
		
		if(this.checked){
		    Dom.get('the_plot').src = 'plot.php?tipo=total_sales_groupby_month';

		    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-sales-plot&value=total_sales_groupby_month',callback );
		    plot_sales='net_sales_gmonth';
		}else{
		    Dom.get('the_plot').src = 'plot.php?tipo=total_sales_month';
		    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-sales-plot&value=total_sales_month' ,callback);
		    

		    plot_sales='net_sales_month';
		}
		
	    }else if(this.id=='net_diff1y_sales_month' || this.id=='net_diff1y_sales_month_net' || this.id=='net_diff1y_sales_month_bis'){
		Dom.get('plot_div').style.width='810px';
		Dom.get('plot_options_sales').style.display='';
		Dom.get('plot_options_sales_bis').style.display='none';
		
		Dom.get('net_sales_month').className='but';
		Dom.get('net_diff1y_sales_month').className='but selected';
		Dom.get('timeplot_sales').className='but';
		Dom.get('tr_net_diff1y_sales_month').style.display='';
		Dom.get('tr_net_diff1y_sales_month_per').style.display='';
		Dom.get('tr_net_sales_gmonth').style.display='none';

		
		Dom.get('net_diff1y_sales_month_net').checked=true;
		Dom.get('the_plot').src = 'plot.php?tipo=net_diff1y_sales_month';

		YAHOO.util.Connect.asyncRequest('GET','ar_sessions.php?tipo=update&keys=reports-sales-plot&value=net_diff1y_sales_month' ,callback);
		
		plot_sales='net_diff1y_sales_month';
		
	    }if(this.id=='net_diff1y_sales_month_per'){
		Dom.get('plot_div').style.width='810px';
		Dom.get('plot_options_sales').style.display='';
		Dom.get('plot_options_sales_bis').style.display='none';
		

		Dom.get('the_plot').src = 'plot.php?tipo=net_diff1y_sales_month_per';
		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-sales-plot&value=net_diff1y_sales_month_per' ,callback);
		plot_sales='net_diff1y_sales_month_per';
		

	    }


	}
	var ids = ["net_diff1y_sales_month","timeplot_sales","net_sales_month","net_diff1y_sales_month_bis","net_sales_month_bis"]; 
 	Event.addListener(ids, "click", change_plot);
	var ids = ["net_sales_gmonth","net_diff1y_sales_month_per","net_diff1y_sales_month_net"]; 
 	Event.addListener(ids, "change", change_plot);



	var go_free = function(e){
	    var from=Dom.get('v_calpop1').value;
	    var to=Dom.get('v_calpop2').value;
	    location.href='report_sales.php?tipo=f&from='+from+'&to='+to; 
	}
	var ids = ["go_free_report"]; 
	Event.addListener(ids, "change", go_free);

	var change_front_page = function(e){
	    tipo=this.id;
	    
	    



	    if(tipo!=current_view){
		
		Dom.get('header_'+current_view).style.display='none';
		Dom.get('header_'+tipo).style.display='';
		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-view&value=' + escape(tipo) );
		Dom.get(tipo).className='nav2 onleft link selected';
		Dom.get(current_view).className='nav2 onleft link';

		if(current_view=='sales'){
		    Dom.get('plot_options_'+current_view).style.display='none';
		    Dom.get('plot_options_'+current_view).style.display='none';
		}else if (current_view=='geosales'){
		 Dom.get('map').style.display='none';
		}
		

		if(tipo=='geosales'){
		    Dom.get('front_plot').style.display='none';
		    Dom.get('map').style.display='';
		}else if(tipo=='prod')
		    Dom.get('front_plot').style.display='none';
		else if(tipo=='sales'){
		    Dom.get('front_plot').style.display='';
		    Dom.get('plot_div').style.opacity=0;
		    Dom.get('the_plot').src = 'plot.php?tipo='+plot[tipo];
		    Dom.get('plot_options_'+tipo).style.display='';
		    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-'+tipo+'-plot&value=' + escape(plot[tipo]) );

		}

		
		current_view=tipo;
	


	

	    }
	}

	var ids = ["sales","geosales","customers","times","prod","stock","products"]; 
	Event.addListener(ids, "click", change_front_page);

	var ids = ["world","asia","africa","europe","middle_east","north_america","south_america"]; 
	Event.addListener(ids, "click", change_map);


}

Event.onDOMReady(init);