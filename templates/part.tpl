{include file='header.tpl'}
<div id="bd" >
  

<div>
  <h1 style="padding:10px 0 0 0 ;font-size:140%"><span style="font-weight:800">{t}Part SKU{/t} {$part->get('Part SKU')}</span> {$part->get('Part XHTML Description')}</h1>
  <h2 style="padding:0">{t}Sold as{/t}: {$part->get('Part XHTML Currently Used In')}</h2>
</div>

<div class="" id="block_info"  style="margin-top:10px;width:790px">

  <div id="photo_container" style="float:left;margin-top:0px">
    <div style="border:1px solid #ddd;padding-top:10;width:220px;text-align:center;margin:0 10px 0 0px">
      <div id="imagediv"   style="border:1px solid #ddd;width:200px;height:140px;padding:0px 0;xborder:none;cursor:pointer;;margin: 10px 0 10px 9px">
	<img src="{ if $num_images>0}{$images[$data.principal_image].med}{else}art/nopic.png{/if}"     id="image"   alt="{t}Image{/t}"/>
      </div>
    </div>
    <div style="width:160px;margin:auto;padding-top:5px"  >
      {foreach from=$images item=image  name=foo}
      {if $image.principal==0} <img  style="float:left;border:1px solid#ccc;padding:2px;margin:2px" src="{$image.tb}"  />{/if}
      {/foreach}
    </div>
  </div>

  <div style="width:250px;float:left;margin-left:10px">
  
    <table    class="show_info_product" >
      <td class="aright">
	
	<tr><td>{t}Status{/t}:</td><td>{$part->get('Part Status')}</td></tr>
      <tr><td>{t}Keeping since{/t}:</td><td>{$part->get('Valid From')}</td></tr>
	<tr><td>{t}Supplied by{/t}:</td><td>{$part->get('Part XHTML Currently Supplied By')}</td></tr>
	<tr><td>{t}Cost{/t}:</td><td>{$part->get('Cost')}</td></tr>
    </table>
    <table    class="show_info_product">
      <tr >
      <td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$parts_period_title}</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td>
    </tr>
      <tbody id="info_all" style="{if $parts_period!='all'}display:none{/if}">
	<tr><td>{t}Sales{/t}:</td><td class="aright">{$part->get('Total Sold Amount')}</td></tr>
	<tr><td>{t}Profit{/t}:</td><td class="aright">{$part->get('Total Absolute Profit')}</td></tr>
	<tr><td>{t}Margin{/t}:</td><td class="aright">{$part->get('Total Margin')}</td></tr>
	<tr><td>{t}GMROI{/t}:</td><td class="aright">{$part->get('Total GMROI')}</td></tr>

      </tbody>
      <tbody id="info_year"  style="{if $parts_period!='year'}display:none{/if}">
	<tr><td>{t}Sales{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
	<tr><td>{t}Profit{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
	<tr><td>{t}GMROI{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>


      </tbody>
        <tbody id="info_quarter" style="{if $parts_period!='quarter'}display:none{/if}"  >
       <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
      </tbody>
        <tbody id="info_month" style="{if $parts_period!='month'}display:none{/if}"  >
         <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
	  
      </tbody>
       <tbody id="info_week" style="{if $parts_period!='week'}display:none{/if}"  >
         <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
	  
      </tbody>
 </table>
</div>

 <div style="width:250px;float:left;margin-left:20px">
	<table   class="show_info_product" >
		  <tr>
		    <td>{t}Stock{/t}:<br>{$stock_units}</td><td class="stock aright" id="stock">{$part->get('Part Current Stock')}</td>
		  </tr>
<tr>
		    <td>{t}Available for{/t}:</td><td class="stock aright">{$part->get('Part XHTML Available For Forecast')}</td>
		  </tr>
		    {if $locations}
		    <tr><td>{t}Location{/t}:</td><td class="aright">
			<table class="locations " style="float:right"  >
			{foreach from=$locations item=location name=foo }
			<tr><td>{$location.icon} </td><td> {$location.name}</td><td style="padding-left:10px"> ({$location.stock})</td></tr>
			{/foreach}
			</table>
		      <td>
		    {/if}
		    {if $nextbuy>0   }<tr><td rowspan="2">{t}Next shipment{/t}:</td><td>{$data.next_buy}</td></tr><tr><td class="noborder">{$data.nextbuy_when}</td>{/if}
		    </tr>
		  </table>
		   <table    class="show_info_product">
      <tr >
      <td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$parts_period_title}</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td>
    </tr>
       <tbody id="info_all" style="{if $parts_period!='all'}display:none{/if}">
	<tr><td>{t}Required{/t}:</td><td class="aright">{$part->get('Total Required')}</td></tr>
      <tr><td>{t}Provided{/t}:</td><td class="aright">{$part->get('Total Provided')}</td></tr>
      <tr><td>{t}Adquired{/t}:</td><td class="aright">{$part->get('Total Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('Total Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('Total Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('Total Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('Total Lost')}</td></tr>
     </tbody>
      <tbody id="info_year"  style="{if $parts_period!='year'}display:none{/if}">
      <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>


      </tbody>
        <tbody id="info_quarter" style="{if $parts_period!='quarter'}display:none{/if}"  >
       <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
      </tbody>
        <tbody id="info_month" style="{if $parts_period!='month'}display:none{/if}"  >
         <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
	  
      </tbody>
       <tbody id="info_week" style="{if $parts_period!='week'}display:none{/if}"  >
         <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
	  
      </tbody>
</table>
</div>


</div>

	

  
 <div style="clear:both"></div>
 <div   id="block_plot" style="clear:both;{if $display.plot==0}display:none{/if};margin-top:20px;min-height:420px"  >
   {include file='plot_splinter.tpl'}
 </div>
 
<div  id="block_stock_transactons" class="data_table" style="clear:both;margin:25px 0px">
   <span id="table_title" class="clean_table_title">{t}Part Stock History{/t}</span>
   <div  class="clean_table_caption"  style="clear:both;">
     <div style="float:left;"><div id="table_info0" class="clean_table_info"> <span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span>  </div></div>
     <div class="clean_table_filter"  id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
     <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	</div>
   <div  id="table0"   class="data_table_container dtable btable "> </div>
 </div>

      



 <div  id="block_stock_transactons" class="data_table" style="clear:both;margin:25px 0px">
   <span id="table_title" class="clean_table_title">{t}Part Stock Transactions{/t}</span>
   <div  class="clean_table_caption"  style="clear:both;">
     <div style="float:left;"><div id="table_info1" class="clean_table_info"> <span id="rtext1"></span> <span class="rtext_rpp" id="rtext_rpp1"></span> <span class="filter_msg"  id="filter_msg1"></span>  </div></div>
     <div class="clean_table_filter"  id="clean_table_filter1"><div class="clean_table_info"><span id="filter_name1">{$filter_name1}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value1}" size=10/><div id='f_container1'></div></div></div>
     <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
	</div>
   <div  id="table1"   class="data_table_container dtable btable "> </div>
 </div>




</div>
</div>




</div>{include file='footer.tpl'}

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

