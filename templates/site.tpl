{include file='header.tpl'}
<div id="bd" >
{include file='assets_navigation.tpl'}
 

<div class="todo" style="clear:both">
<h1>TO DO (KAKTUS-320)</h1>
<h2>Site details Info</h2>
<h3>Objective</h3>
<p>

Show relevant information & stats about this site Examples:Hits, visitors, url & ftp address, number of pages, orders placed in the website etc.

DB `Site Dimension`  add the new fields


</p>
</div>

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Site: {$site->get('Site Name')} ({$site->get('Site URL')})</h1>
  </div>
<div id="info" style="clear:left;margin:20px 0 10px 0;padding:0;{if !$show_details}display:none;{/if}">

<h2 style="margin:0;padding:0">{t}Site Information{/t}</h2>
<div style="width:350px;float:left">
  <table    class="show_info_product">

    <tr >
      <td>{t}Code{/t}:</td><td class="price">{$site->get('Site Code')}</td>
    </tr>
    <tr >
      <td>{t}Name{/t}:</td><td>{$site->get('Site Name')}</td>
    </tr>
 <tr >
      <td>{t}Web Page{/t}:</td><td>{$site->get('Site URL')}</td>
    </tr>
</table>
  <table    class="show_info_product">

   <tr>
	    <td>{t}Total Hits{/t}:</td><td class="number"><div >{$site->get('Visits')}</div></td>
	  </tr>
    <tr>
	    <td>{t}Unique Visitors{/t}:</td><td class="number"><div >{$site->get('Unique Visitors')}</div></td>
	  </tr>
	 
  </table>
</div>
<div style="width:15em;float:left;margin-left:20px">

</div>



<div class="todo" style="clear:both">
<h1>TO DO (KAKTUS-321)</h1>
<h2>Site charts</h2>
<h3>Objective</h3>
<p>
1) Total site Hits / day
2) Unique visitors / day
</p>
</div>


<div  style="color:red;clear:both">
<ul class="tabs" id="chooser_ul" style="margin-top:25px">
    <li>
	  <span class="item {if $plot_tipo=='store'}selected{/if}" onClick="change_plot(this)" id="plot_store" tipo="store"    >
	    <span>{$site->get('Site Code')} {t}Hits{/t}</span>
	  </span>
	</li>

	<li>
	  <span class="item {if $plot_tipo=='top_departments'}selected{/if}"  id="plot_top_departments" onClick="change_plot(this)" tipo="top_departments"  >
	    <span>{t}Unique Visitors{/t}</span>
	  </span>
	</li>
	

  </ul>
  
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script>

<div id="plot" style="clear:both;border:1px solid #ccc" >
	<div id="single_data_set"  >
		<strong>You need to upgrade your Flash Player</strong>
	</div>
</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_timeseries.xml.php?tipo=site_hits&site_key={$site->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("plot");
		// ]]>
	</script>
  
  
  <div style="clear:both"></div>
</div>
</div>


<div class="data_table" style="clear:both;margin-top:25px">
<div class="todo">
<h1>TO DO (KAKTUS-322)</h1>
<h2>List/thumbnails of site's pages</h2>
<h3>Objective</h3>
<p>
show code (link to:page.php?id=) ,url, current visitors, last 24h visitors , etc
</p>


</div>
    <span   class="clean_table_title" style="">{t}Pages{/t}</span>
 <div id="table_type">
     <span id="table_type_list" style="float:right" class="table_type state_details {if $table_type=='list'}selected{/if}">{t}List{/t}</span>
     <span id="table_type_thumbnail" style="float:right;margin-right:10px" class="table_type state_details {if $table_type=='thumbnails'}selected{/if}">{t}Thumbnails{/t}</span>
     </div>
   
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    
   
 {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 no_filter=1  }
<div  id="table1"   class="data_table_container dtable btable"> </div>
</div>




 

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




{include file='footer.tpl'}
