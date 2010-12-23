
 <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li>
	  <span class="item {if $plot_tipo=='store'}selected{/if}" onClick="change_plot(this)" id="plot_store" tipo="store"    >
	    <span>{$store->get('Store Code')} {t}Store{/t}</span>
	  </span>
	</li>
{* --------------------------commented part------------------------
	<li>
	  <span class="item {if $plot_tipo=='top_departments'}selected{/if}"  id="plot_top_departments" onClick="change_plot(this)" tipo="top_departments"  >
	    <span>{t}Top Departments{/t}</span>
	  </span>
	</li>
	<li>
	  <span class="item {if $plot_tipo=='pie'}selected{/if}" onClick="change_plot(this)" id="plot_pie" tipo="pie"     forecast="{$plot_data.pie.forecast}" interval="{$plot_data.pie.interval}"  >
	    <span>{t}Department's Pie{/t}</span>
	  </span>
	</li>
---------------------------------------------------------------------- *}
  </ul>



<!-- amcolumn script-->
  <script type="text/javascript" src="external_libs/amcolumn_1.6.4.2/amcolumn/swfobject.js"></script>
	<div id="flashcontent">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/amcolumn_1.6.4.2/amcolumn/amcolumn.swf", "amcolumn", "100%", "500", "8", "#FFFFFF");
		so.addVariable("path", "external_libs/amcolumn_1.6.4.2/amcolumn/");
		so.addVariable("settings_file", encodeURIComponent("external_libs/amcolumn_1.6.4.2/amcolumn/amcolumn_settings.xml"));        // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("external_libs/amcolumn_1.6.4.2/amcolumn/amcolumn_data.xml"));		

//	so.addVariable("chart_data", encodeURIComponent("data in CSV or XML format"));                // you can pass chart data as a string directly from this file
//	so.addVariable("chart_settings", encodeURIComponent("<settings>...</settings>"));             // you can pass chart settings as a string directly from this file
//	so.addVariable("additional_chart_settings", encodeURIComponent("<settings>...</settings>"));  // you can append some chart settings to the loaded ones
//  so.addVariable("loading_settings", "LOADING SETTINGS");                                       // you can set custom "loading settings" text here
//  so.addVariable("loading_data", "LOADING DATA");                                               // you can set custom "loading data" text here
//  so.addVariable("preloader_color", "#000000");	
//  so.addVariable("error_loading_file", "ERROR LOADING FILE");                                   // you can set custom "error loading file" text here
		so.write("flashcontent");
		// ]]>
	</script>
<!-- end of amcolumn script -->
