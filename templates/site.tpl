{include file='header.tpl'}
<div id="bd" style="padding:0px">
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script>
<input type="hidden" id="site_key" value="{$site->id}"/>
<input type="hidden" id="site_id" value="{$site->id}"/>

<div style="padding:0 20px">
{include file='assets_navigation.tpl'}
<div class="branch"> 
  <span>{if $user->get_number_stores()>1}<a  href="stores.php">{t}Stores{/t}</a> &rarr; <a href="store.php?id={$store->id}">{/if}{$store->get('Store Name')}</a>  &rarr; {t}Website{/t}: {$site->get('Site URL')}</span>
</div>
<div class="top_page_menu">
    <div class="buttons" style="float:right">
        {if $modify}
        <button  onclick="window.location='edit_site.php?id={$site->id}'" ><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Site{/t}</button>
        {/if}
    </div>
    <div class="buttons" style="float:left">
        <button  onclick="window.location='store.php?store={$store->id}'" ><img src="art/icons/house.png" alt=""> {t}Store{/t}</button>
        {if $store->get('Store Websites')>1}
        <button  onclick="window.location='sites.php?store={$store->id}'" ><img src="art/icons/world.png" alt=""> {t}Websites{/t}</button>
        {/if}
    </div>
    <div style="clear:both"></div>
</div>



    <h1>{$site->get('Site Name')} ({$site->get('Site URL')})</h1>


</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Overview{/t}</span></span></li>
    <li> <span class="item {if $block_view=='pages'}selected{/if}"  id="pages">  <span> {t}Pages{/t}</span></span></li>
    <li> <span class="item {if $block_view=='hits'}selected{/if}"   id="hits">  <span> {t}Hits{/t}</span></span></li>
    <li> <span class="item {if $block_view=='visitors'}selected{/if}"  id="visitors">  <span> {t}Visitors{/t}</span></span></li>

  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">


<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:20px 0 40px 0">



<div style="width:350px;float:left">
  <table    class="show_info_product">

   
    <tr >
      <td>{t}Name{/t}:</td><td>{$site->get('Site Name')}</td>
    </tr>
 <tr >
      <td>{t}Home Page{/t}:</td><td>{$site->get('Site URL')}</td>
    </tr>
</table>
<table    class="show_info_product">

   <tr>
	    <td>{t}Number Pages{/t}:</td><td class="number"><div >{$site->get('Number Pages')}</div></td>
	  </tr>
  
	 
  </table>

 
  </div>
  <div style="margin-left:20px;width:350px;float:left">
   <table    class="show_info_product">

   <tr>
	    <td>{t}Total Hits{/t}:</td><td class="number"><div >{$site->get('Visits')}</div></td>
	  </tr>
    <tr>
	    <td>{t}Unique Visitors{/t}:</td><td class="number"><div >{$site->get('Unique Visitors')}</div></td>
	  </tr>
	 
  </table>
   <table    class="show_info_product">

   <tr>
	    <td>{t}Last 24h Hits{/t}:</td><td class="number"><div >{$site->get('1 Day Visits')}</div></td>
	  </tr>
    <tr>
	    <td>{t}Last 24h Visitors{/t}:</td><td class="number"><div >{$site->get('1 Day Unique Visitors')}</div></td>
	  </tr>
	  
	    <tr>
	    <td>{t}Current Visitors{/t}:</td><td class="number"><div >{$site->get('Current Visitors')}</div></td>
	  </tr>
	 
  </table>
  
  
</div>
<div style="width:15em;float:left;margin-left:20px">

</div>



</div>
<div id="block_pages" style="{if $block_view!='pages'}display:none;{/if}clear:both;margin:20px 0 40px 0">
   <span   class="clean_table_title" >{t}Pages{/t}</span>
 
          <div  style="font-size:90%"   id="transaction_chooser" >
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Other}selected{/if} label_page_type"  id="elements_other"   >{t}Other{/t} (<span id="elements_other_number">{$elements_number.Other}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.DepartmentCatalogue}selected{/if} label_page_type"  id="elements_department_catalogue"   >{t}Department Catalogues{/t} (<span id="elements_department_catalogue_number">{$elements_number.DepartmentCatalogue}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.FamilyCatalogue}selected{/if} label_page_type"  id="elements_family_catalogue"    >{t}Family Catalogues{/t} (<span id="elements_family_catalogue_number">{$elements_number.FamilyCatalogue}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.ProductDescription}selected{/if} label_page_type"  id="elements_product_description"  >{t}Product Description{/t} (<span id="elements_product_description_number">{$elements_number.ProductDescription}</span>)</span>
         </div>
    
<div class="table_top_bar"></div>
  
    <div class="buttons small" style="float:right;margin-bottom:5px;margin-top:3px">
     <button id="table_type_list" class="table_type  {if $table_type=='list'}selected{/if}">{t}List{/t}</button>
     <button id="table_type_thumbnail"  class="{if $table_type=='thumbnails'}selected{/if}"      >{t}Thumbnails{/t}</button>
<div style="clear:both"></div>
     </div>

 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0  }
<div  id="table0"   class="data_table_container dtable btable" style="font-size:85%"> </div>


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

