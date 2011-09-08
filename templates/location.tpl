{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" style="padding:0">
<div style="padding:0 20px">
{include file='locations_navigation.tpl'}
 <div style="clear:left;"> 
 <span class="branch" ><a  href="warehouse.php?id={$location->get('Location Warehouse Key')}">{$location->get('Warehouse Name')}({$location->get('Warehouse Code')})</a> &rarr; <a  href="warehouse_area.php?id={$location->get('Location Warehouse Area Key')}">{$location->get('Warehouse Area Name')}({$location->get('Warehouse Area Code')})</a> {if $location->get('Location Shelf Key')} &rarr; <a  href="shelf.php?id={$location->get('Location Shelf Key')}">{t}Shelf{/t} {$location->get('Shelf Code')}</a>{/if}</span>
 </div>
 <div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>{t}Location{/t}: {$location->get('Location Code')} </h1>
  </div>
</div>



<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
    <li> <span class="item {if $view=='parts'}selected{/if}"  id="parts">  <span> {t}Parts{/t}</span></span></li>
    <li> <span class="item {if $view=='history'}selected{/if}"  id="history">  <span> {t}Stock History{/t}</span></span></li>
</ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>


<div id="block_details" style="{if $view!='details'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
 <table class="show_info_product">
      <tr><td>{t}Location{/t}:</td><td style="font-weight:800">{$location->get('Location Code')}</td></tr>
      <tr><td>{t}Used for{/t}:</td><td>{$location->get('Location Mainly Used For')}</td></tr>
      <tr><td>{t}Max Capacity{/t}:</td><td>{$location->get('Location Max Volume')}</td></tr>
      <tr><td>{t}Max Weight{/t}:</td><td>{$location->get('Location Max Weight')}</td></tr>
      <tr><td>{t}Max Slots{/t}:</td><td>{$location->get('Location Max Slots')}</td></tr>
    </table>

<div id="plot" style="{if !$show_details}display:none;{/if}"></div>
</div>     

<div id="block_parts" style="{if $view!='parts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


 
    <div style="float:right;padding:0;margin:0">
      <table class="options" style="float:right;padding:0;margin:0">
	<tr>
	  <td  id="add_product">Add Part</td>
	  
	</tr>
      </table>
      
      
      <div id="manage_stock" style="display:none;clear:both;margin:0 0 20px 5px">
	<div id="manage_stock_messages" ></div>
	<div id="manage_stock_locations" style="width:100px;display:none;margin-bottom:30px;margin-left:2px">
	  <input id="new_location_input" type="text">
	  <div id="new_location_container"></div>
	</div>
	<div id="manage_stock_products" style="width:400px;xdisplay:none;margin-bottom:30px;margin-left:2px;">
	  <input id="new_product_input" type="text">
	  <div id="new_product_container">
	    
	  </div>
	</div>
	
	<div id="manage_stock_engine"></div>
      </div>
    </div>
    
      <div id="product_messages" style="clear:both"></div>


  
  
    <div id="the_table" class="data_table" style="clear:both;margin-top:0px">
      <span class="clean_table_title">{t}Parts{/t}</span>
      
 
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
  
  {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name0 filter_value=$filter_value1  }
  <div  id="table1" style="font-size:90%"  class="data_table_container dtable btable "> </div>
</div>

</div>

<div id="block_history" style="{if $view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

  
  <span class="clean_table_title">{t}History{/t}</span>
  <div  id="clean_table_caption0" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div id="clean_table_filter0" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0" style="font-size:90%"  class="data_table_container dtable btable "> </div>

</div>




      


   
    
</div>



{include file='footer.tpl'}

{include file='stock_splinter.tpl'}
