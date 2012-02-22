{include file='header.tpl'}
<div id="bd" style="padding:0px">
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script>
<input type="hidden" id="site_key" value="{$site->id}"/>
<input type="hidden" id="site_id" value="{$site->id}"/>

<input type="hidden" id="page_key" value="{$page->id}"/>

<div style="padding:0 20px">
{include file='assets_navigation.tpl'}
<div  class="branch"> 
			 <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_websites()>1}<a href="sites.php">{t}Websites{/t}</a>  &rarr;{/if} <img style="vertical-align:0px;margin-right:1px" src="art/icons/hierarchy.gif" alt="" /> <a href="site.php?id={$site->id}">{$site->get('Site URL')}</a> (<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a>) &rarr; <img style="vertical-align:-1px;" src="art/icons/layout_bw.png" alt="" /> {$page->get('Page Code')} ({t}Deleted{/t})</span> 
</div>
<div class="top_page_menu">
    <div class="buttons" style="float:right">
    {if isset($next)}<img class="next" onMouseover="this.src='art/next_button.gif'"  onMouseout="this.src='art/next_button.png'"  title="{$next.title}"  onclick="window.location='{$next.link}'"   src="art/next_button.png" alt="{t}Next{/t}"  / >{/if}

     </div>
    
    
    <div class="buttons" style="float:left">
        {if isset($prev)}<img class="previous" onMouseover="this.src='art/previous_button.gif'"  onMouseout="this.src='art/previous_button.png'"   title="{$prev.title}" onclick="window.location='{$prev.link}'"  src="art/previous_button.png" alt="{t}Previous{/t}"   />{/if}

        <button  onclick="window.location='site.php?id={$site->id}'" ><img src="art/icons/house.png" alt=""> {t}Site{/t}</button>
    </div>
    <div style="clear:both"></div>
</div> 



    <h1>{t}Deleted Page{/t}: <span class="id"> {$page->get('Page Code')}</span> <span style="text-decoration:line-through;font-size:90%;color:#777">{$page->get('Page URL')}</span></h1>


</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Overview{/t}</span></span></li>

    <li> <span class="item {if $block_view=='hits'}selected{/if}"   id="hits">  <span> {t}Hits{/t}</span></span></li>
    <li> <span class="item {if $block_view=='visitors'}selected{/if}"  id="visitors">  <span> {t}Visitors{/t}</span></span></li>

  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>




<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:25px 0 40px 0;padding:0 20px">




<div style="width:450px;float:left;margin-top:0">
  <table  id="page_info"  class="show_info_product">

     <tr >
      <td style="width:140px">{t}Type{/t}:</td><td>{$page->get_formated_store_section()}</td>
    </tr>
    <tr >
      <td style="width:140px">{t}Header Title{/t}:</td><td>{$page->get('Page Title')}</td>
    </tr>
 <tr >
      <td style="width:140px">{t}URL{/t}:</td><td style="text-decoration:line-through;">{$page->get('Page URL')}</td>
    </tr>
      <tr >
      <td style="width:140px">{t}Link Label{/t}:</td><td>{$page->get('Page Short Title')}</td>
    </tr>
    
</table>
 <table  border=0 id="table_total_visitors"  class="show_info_product">

   <tr>
	    <td style="width:140px">{t}Total Hits{/t}:</td><td class="number"><div >{$page->get('Visits')}</div></td>
	  </tr>
    <tr>
	    <td style="width:140px">{t}Unique Visitors{/t}:</td><td class="number"><div>{$page->get('Unique Visitors')}</div></td>
	  </tr>
	 
  </table>



 
  </div>
  <div style="{if $page->get('Page Upload State')!='Upload'}display:none;{/if}margin-left:20px;width:450px;float:left;position:relative;top:-12px">
  
    <span style="font-size:11px;color:#777;">{t}Live snapshot{/t}, {$page->get_snapshot_date()}</span> <img id="recapture_page" style="position:relative;top:-1px;cursor:pointer" src="art/icons/camera_bw.png" alt="recapture"/>
      <img style="width:470px" src="image.php?id={$page->get('Page Snapshot Image Key')}" alt=""/>

</div>
  <div style="{if $page->get('Page Upload State')=='Upload'}display:none;{/if}margin-left:20px;width:450px;float:left;position:relative;top:-12px">


    <span style="font-size:11px;color:#777;">{t}Preview snapshot{/t}<span id="capture_preview_date">, {$page->get_preview_snapshot_date()}</span></span> <img id="recapture_preview" style="position:relative;top:-1px;cursor:pointer" src="art/icons/camera_bw.png" alt="recapture"/><img id="recapture_preview_processing" style="display:none;height:12.5px;position:relative;top:-1px;" src="art/loading.png"/>
  <img id="page_preview_snapshot" style="width:470px" src="image.php?id={$page->get('Page Preview Snapshot Image Key')}" alt=""/>
 
  
  


  
</div>


<div style="clear:both;margin-bottom:20px"></div>
</div>

<div id="block_hits" style="{if $block_view!='hits'}display:none;{/if}clear:both;margin:20px 0 40px 0">
<div id="plot1" style="clear:both;border:1px solid #ccc" >
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
		so.write("plot1");
		// ]]>
	</script>
  
</div>
<div id="block_visitors" style="{if $block_view!='visitors'}display:none;{/if}clear:both;margin:20px 0 40px 0">

<div id="plot2" style="clear:both;border:1px solid #ccc" >
	<div id="single_data_set"  >
		<strong>You need to upgrade your Flash Player</strong>
	</div>
</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_timeseries.xml.php?tipo=site_visitors&site_key={$site->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("plot2");
		// ]]>
	</script>
  
</div>





 




 




</div>
{include file='footer.tpl'}
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

