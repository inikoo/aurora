{include file='header.tpl'}

<div id="bd">
{if $scope=='customers_store'}

{include file='contacts_navigation.tpl'}

<div  class="branch"> 

  <span  >{if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {$id}</span>
</div>



<div id="top_page_menu" class="top_page_menu">

    <div class="buttons" style="float:left">
        <button  onclick="window.location='customers.php?store={$store->id}'" ><img src="art/icons/house.png" alt=""> {t}Customers{/t}</button>
    </div>
    <div class="buttons" style="float:right">
                 <button  id="new_map"><img src="art/icons/x.png" alt=""> {t}Add Field Map{/t}</button>

             <button  id="browse_maps"><img src="art/icons/add.png" alt=""> {t}Pick a Map{/t}</button>
    </div>
    <div style="clear:both"></div>
</div>
{/if}

<input type="hidden" id="scope" value="{$scope}">
<input type="hidden" id="scope_key" value="{$scope_key}">
<div id="no_details_title" style="clear:left;">
    <h1>{t}Import Contacts From CSV File{/t}</h1>
</div>
<div class="left3Quarters" style="text-align:right">
    <input type="hidden" name="form" value="form" />
    <div class="framedsection">
        <div id="call_table"></div>
    </div>
    <div class="buttons">
    <button class="button" id="insert_data" >{t}Insert data{/t}</button>	
</div>
</div>
</div>



<div id="dialog_map" style="width:180px">
  <div id="map_msg" style="width:100%;text-align:center;margin:0;padding:5px 20px;;display:none;"></div>
  <table id="map_form_table" style="padding:10px;margin:20px" >
   <tr id="map_error_used_map_name"  style="display:none"><td colspan=2  id="map_form_text"  >{t}Map name already taken, please use another name{/t}</td></tr>
  <tr id="map_form_text_tr"><td colspan=2  id="map_form_text"  >{t}Please write the map name{/t}</td></tr>
    <tr><td colspan=2>
	<input  id="map_name" />
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%;display:block;margin-top:10px">
  <td style="text-align:center;width:50%">
   
  <td style="text-align:center;width:50%">
    <span   id="save_map"  class="unselectable_text button"   >{t}Save{/t}</span></td></tr>
</table>
</div>


 <div id="dialog_map_select">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Map List{/t}</span>
            {include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5}
            <div  id="table5"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 
 
{include file='footer.tpl'}
