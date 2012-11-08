{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd"  style="padding:0">

<div style="clear:left;"> 
World>Reg
</div>



<div  id="block_info"  style="width:100%;padding:0">
      

      
      <div   style="clear:left;padding:0;width:100%">

	 
	  
<div id="map_countries" zstyle="float:left;border:0px solid #777;width:310px;height:320px" style="margin-right:40px;float:left;width:400px;height:480px;border:0px solid black">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	
<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("{$ammap_path}/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#FFFFFF");
        so.addVariable("path", "{$ammap_path}/ammap/");
		so.addVariable("data_file", escape("map_data_country.xml.php?code={$country->get('Country Code')}"));
        so.addVariable("settings_file", escape("{$settings_file}"));		
		so.addVariable("preloader_color", "#999999");
		so.write("map_countries");
		
	
		// ]]>
	</script>


	<div style="float:left;">

	  <h2>{$country->get('Country Name')} [{$country->get('Country Code')}]</h2>
	  <div   style="width:100%;">
	    <div  style="width:100%;font-size:90%"   >
              <div  style="width:200px;float:left;margin-right:20px">
	
		<table    class="show_info_product">
		    <tr>
		      <td>{t}Population{/t}:</td><td  class="price aright">{$country->get('Population')}</td>
		    </tr>
		   <tr>
		      <td>{t}GNP{/t}:</td><td  class="price aright">{$country->get('GNP')}</td>
		    </tr>
		    
		    <tr><td>{t}Sold Since{/t}:</td><td class="aright">{$country->get('For Sale Since Date')} </td>
		      {if $edit} <td   class="aright" ><input style="text-align:right" class="date_input" size="8" type="text"  id="v_invoice_date"  value="{$v_po_date_invoice}" name="invoice_date" /></td>{/if}
		    </tr>
		  
		</table>

	 



	      </div>
              <div  style="width:220px;float:left">

	

	
		
		
		

	 <table   class="show_info_product">
		    <tr ><td>{t}Currency{/t}:</td><td class="aright">{$country->get('Country Currency Name')} ({$country->get('Country Currency Code')})</td></tr>
		    <tr ><td>{t}Exchange{/t}:</td><td class="aright">
		   
		    <table style="float:right">
		    {$country->get_formated_exchange_reverse('GBP',false,'tr')}
		    {$country->get_formated_exchange('GBP',false,'tr')}
		    </table>
		    </td></tr>


		
		  </table>	  
		  <table  class="show_info_product">
		    <tr ><td>{t}Official Name{/t}:</td><td class="aright">{$country->get('Country Native Name')}</td></tr>
		    <tr ><td>{t}Languages{/t}:</td><td class="aright">{$country->get('Country Languages')}</td></tr>
		    <tr ><td>{t}Capital{/t}:</td><td class="aright">{$country->get('Country Capital Name')}</td></tr>
		    <tr ><td>{t}Government{/t}:</td><td class="aright">{$country->get('Country Goverment Form')}<br>{$country->get('Country Head of State')}</td></tr>

		
		  </table>
	
		
              </div>
	    </div>
	  </div>
	</div>
   
   </div>
      
      
     

    </div> 

  


  
 




<div  id="block_timeline" class="data_table" style="{if $display.orders==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Product Code Timeline{/t}</span>
    {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3}
    <div  id="table3"   class="data_table_container dtable btable"> </div>
  </div>
<div>


  <div   id="block_plot" style="clear:both;{if $display.plot==0}display:none{/if};margin-top:20px"  >
{include file='plot_splinter.tpl'}
    
     
</div>





 
      

  {if $view_orders} 
  <div  id="block_orders" class="data_table" style="{if $display.orders==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Orders with this Product{/t}</span>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable"> </div>
  </div>
  {/if}
  
  {if $view_customers} 
  <div  id="block_customers" class="data_table" style="{if $display.customers==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Customer who order this Product{/t}</span>
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
  <div  id="table1"   class="data_table_container dtable btable"> </div>
  </div>
  {/if}


  <div  id="block_history" class="data_table" style="{if $display.history==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Product History{/t}</span>
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
    <div  id="table2"   class="data_table_container dtable btable"> </div>
  </div>


</div>

</div>
<div id="web_status_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">

      {foreach from=$web_status_menu key=status_id item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_web_status('{$status_id}')"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

</div>{include file='footer.tpl'}

