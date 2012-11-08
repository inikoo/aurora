{include file='header.tpl'}

<div id="bd" style="padding:0" >

<div style="padding:0 20px">
{include file='reports_navigation.tpl'}
{include file='calendar_splinter.tpl'}


<div class="branch" style="width:300px;padding-top:5px"> 
   <span ><a  href="report_geo_sales.php?world=1">{t}World{/t}</a> &rarr; <a  href="report_geo_sales.php?continent={$continent_code}">{$continent_code}</a></span>

</div>
<h1 style="clear:left">{$title}</h1>

</div>


<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='overview'}selected{/if}"  id="overview">  <span> {t}Sales Overview{/t}</span></span></li>
    <li> <span class="item {if $view=='map'}selected{/if}"  id="map">  <span> {t}Map{/t}</span></span></li>
    <li> <span class="item {if $view=='wregions'}selected{/if}"  id="wregions">  <span> {t}Word Regions{/t}</span></span></li>
    <li> <span class="item {if $view=='countries'}selected{/if}"  id="countries">  <span> {t}Countries{/t}</span></span></li>
   
</ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>


<div id="block_overview" style="{if $view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
 <div id="close1">
    {$continent_code}
    {if !$top_countries_in_continent}
    {$no_sales_message}
    {$from}
    {t}to{/t}
    {$to}
    {else}
    <h2>{t}Top Countries{/t}</h2>
    <div style="float:right;width:300px">
      <table>
	<tr><td>{t}Country{/t}</td><td>{t}Sales{/t}</td></tr>
	{foreach from = $top_countries_in_continent item=data_country}
	<tr>
	  <td>{$data_country.country}</td>
	  <td>{$data_country.sales}</td>
	</tr>
	{/foreach}
      </table>
    </div>
    <div id="plot1" style="float:left;width:500px" >
      <strong>You need to upgrade your Flash Player</strong>
    </div>
    
    <script type="text/javascript">
      // <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "465", "380", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_countries_sales_by_continent&from={$from}&to={$to}&continent_id={$continent_code}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot1");
		// ]]>
    </script>
    {/if}
  </div>

  
  <div id="close2" style="clear:both">
    {if !$top_regions}
    
    {else}
  <h2>{t}Top Regions{/t}</h2>
  <div style="float:right;width:300px">
    <table>
      <tr><td>{t}Region{/t}</td><td>{t}Sales{/t}</td></tr>
      {foreach from = $top_regions_in_continent item=data_region}
      <tr>
	<td>{$data_region.region}</td>
	<td>{$data_region.sales}</td>
      </tr>
      {/foreach}
    </table>
  </div>
  <div id="plot2" style="float:left;width:500px" >
    <strong>You need to upgrade your Flash Player</strong>
  </div>
  
  <script type="text/javascript">
    // <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "465", "380", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_regions_sales_by_continent&from={$from}&to={$to}&continent_id={$continent_code}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot2");
		// ]]>
  </script>

  
  
  {/if}
  </div>

</div>
<div id="block_map" style="{if $view!='map'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

<div class="branch" style="margin:0px;float:right;width:100px;text-align:right">


<span id="map_links_wregions" class="{if $map_links=='wregions'}selected{/if}" style="display:block;margin-top:10px;" >{t}World Regions{/t} &crarr;</span>
<span id="map_links_countries" class="{if $map_links=='countries'}selected{/if}" style="display:block;margin-top:10px;" >{t}Countries{/t} &crarr;</span>


</div>

<div style="border:1px solid #ccc;padding:10px;margin-top:5px;width:800px">

	<div id="map_countries" style="{if $map_links!='countries'}display:none;{/if}width:700px;height:480px;">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

<div id="map_wregions" style="{if $map_links!='wregions'}display:none;{/if}width:700px;height:480px;">
		<strong>You need to upgrade your Flash Player</strong>
	</div>


	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("{$ammap_path}/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#FFFFFF");
        so.addVariable("path", "{$ammap_path}/ammap/");
		so.addVariable("data_file", escape("map_data_world_countries.xml.php?report=sales&from={$from}&to={$to}"));
        so.addVariable("settings_file", escape("{$settings_file}"));		
		so.addVariable("preloader_color", "#999999");
		so.write("map_countries");
		
		var so = new SWFObject("{$ammap_path}/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#FFFFFF");
        so.addVariable("path", "{$ammap_path}/ammap/");
		so.addVariable("data_file", escape("map_data_world_wregions.xml.php?report=sales&from={$from}&to={$to}"));
        so.addVariable("settings_file", escape("{$settings_file}"));		
		so.addVariable("preloader_color", "#999999");
		so.write("map_wregions");
		
			
		
		// ]]>
	</script>
	
	
</div>

</div>  
<div id="block_countries" style="{if $view!='countries'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
    <span id="table_title" class="clean_table_title">{t}Counties{/t}</span>
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable"> </div>
  
</div>
<div id="block_wregions" style="{if $view!='wregions'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

    <span id="table_title" class="clean_table_title">{t}World Regions{/t}</span>
         <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>

    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
    <div  id="table1"   class="data_table_container dtable btable"> </div>
 

</div>

     
<div id="photo_container" style="display:none;float:left;border:0px solid #777;width:510px;height:320px">

	    <iframe id="the_map" src ="map.php?country=" frameborder="0" scrolling="no" width="550"  height="420"></iframe>
	   
	    
	    
	  </div>

<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu2" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},2)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu2" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


     
</div>





 
      


 
</div>




</div>{include file='footer.tpl'}

