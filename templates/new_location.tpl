{include file='header.tpl'} 
<div id="bd">
{include file='locations_navigation.tpl'} 
	<div class="branch">
  <span ><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a>  &rarr; {/if} <a href="edit_warehouse.php?id={$warehouse->id}">{t}Locations{/t} ({t}Editing Warehouse{/t})</a> &rarr; {t}New Location{/t}</span>

	</div>
	
	<div class="top_page_menu">
    <div class="buttons" style="float:right">
       
        <button  onclick="window.location='edit_warehouse.php?id={$warehouse->id}'" class="negative" ><img src="art/icons/door_out.png" alt=""> {t}Cancel{/t}</button>

        
       
    </div>
    <div class="buttons" style="float:left">
      
    <span class="main_title">{t}New Warehouse Area{/t}  <span id="title_code" class="id" >({$warehouse->get('Warehouse Code')})</span></span>


 </div>
    <div style="clear:both"></div>
</div>



	<div id="the_chooser" class="chooser" style="display:none;margin:0px 20px">
		<ul id="chooser_ul">
			<li id="individual" class="show"> {t}Individual{/t}</li>
			<li id="shelf" class="show" style="display:none"> {t}Shelf{/t}</li>
			<li id="rack" class="show" style="display:none"> {t}Pallet Rack{/t}</li>
			<li id="floor" class="show" style="display:none"> {t}Floor Space{/t}</li>
		</ul>
	</div>

	<div id="block_individual" style="display:'';margin:0px 0px;clear:both;">
		{include file='new_individual_location_splinter.tpl'} 
	</div>
</div>
{include file='footer.tpl'} 