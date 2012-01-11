{include file='header.tpl'}
<div id="bd" >
 {include file='locations_navigation.tpl'}

<div class="branch"> 
  <span >{if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; <a href="inventory.php?id={$warehouse->id}">{$location->get('Warehouse Name')} {t}Inventory{/t}</a> {/if}<a href="warehouse.php?id={$warehouse->id}">{t}Locations{/t}</a>  &rarr; {t}New Warehouse Area{/t}</span>
</div>


  <h1  style="padding:10px 20px">{t}New Warehouse Area{/t}</h1>
  
  

  <div id="block_individual"  style="margin:0px 20px;clear:both;">
    <div style="float:left;padding:20px;border:1px solid #ddd;width:400px">
      <table class="edit">
	<tr><td class="label">{t}Warehouse{/t}:</td><td><span style="font-weight:800">{$warehouse->get('Warehouse Name')}</span><input type="hidden" id="warehouse_key" ovalue="{$warehouse->id}" value="{$warehouse->id}"></td></tr>
	<tr><td class="label">{t}Area Code{/t}:</td><td><input  id="area_code" ovalue=""  type="text"/></td></tr>
	<tr><td class="label">{t}Area Name{/t}:</td><td><input  id="area_name" ovalue=""  type="text"/></td></tr>
	<tr><td class="label">{t}Area Description{/t}:</td><td><textarea ovalue="" id="area_description"></textarea></td></tr>




       </table>
      
    </div>
    
    <div id="location_save_block" style="margin:0px 20px;padding:20px 20px;float:left;border:1px solid #ddd;width:300px">
      <span id="add_area" class="button">{t}Save{/t}</span>
      
      <span style="margin-right:10px" id="reset_add_area" class="button">{t}Cancel{/t}</span>

    </div>
    
    
  </div>

  <div style="clear:both"></div>
  
  <div id="the_table" class="data_table" style="margin:20px 20px 20px 20px;clear:both">
    <span class="clean_table_title">{t}Warehouse Areas{/t}</span>
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable "> </div>

  </div>
   
    



</div>
{include file='footer.tpl'}

