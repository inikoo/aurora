{include file='header.tpl'}
<div id="bd" >
{include file='orders_navigation.tpl'}


   
      <h2 style="clear:left">{t}New Delivery Notes List{/t} ({$store->get('Store Code')})</h2>
      
      
<div style="clear:both;border:1px solid #ccc;padding:20px;width:870px">
<input type="hidden" id="store_id" value="{$store->id}">

<span id="error_no_name" style="display:none">{t}Please specify a name{/t}.</span>
      <table >
	<form>
		<tr><td colspan="2"><b>{t}Delivery Notes which...{/t}</b></td></tr>
      <tr>
        <td>{t}Created Date{/t}:</td>
        <td>
            <input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="created_date_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop2" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="created_date_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="created_date_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="created_date_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>        
      </tr>
     <tr>
        <td>{t}Start Picking Date{/t}:</td>
        <td>
            <input id="v_calpop3" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="start_picking_date_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop4" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="start_picking_date_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="start_picking_date_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="start_picking_date_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>    
        
    </tr>      
    <tr>
		<td>{t}Finish Picking Date{/t}:</td>
        <td>
            <input id="v_calpop5" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="finish_picking_date_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop6" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="finish_picking_date_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="finish_picking_date_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="finish_picking_date_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>    
        
    </tr> 
    <tr>
		<td>{t}Start Packing Date{/t}:</td>
        <td>
            <input id="v_calpop7" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="start_packing_date_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop8" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="start_packing_date_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="start_packing_date_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="start_packing_date_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>    
        
    </tr> 
	<tr>
		<td>{t}Finish Packing Date{/t}:</td>
        <td>
            <input id="v_calpop9" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="finish_packing_date_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop10" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="finish_packing_date_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="finish_packing_date_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="finish_packing_date_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>    
        
    </tr> 
	<tr>
		<td>{t}Dispatched Approved Date{/t}:</td>
        <td>
            <input id="v_calpop11" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="dispatched_approved_date_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop12" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="dispatched_approved_date_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="dispatched_approved_date_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="dispatched_approved_date_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>    
        
    </tr> 
	<tr>
		<td>{t}Delivery Note Date{/t}:</td>
        <td>
            <input id="v_calpop13" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="delivery_note_date_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop14" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="delivery_note_date_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="delivery_note_date_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="delivery_note_date_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>    
        
    </tr> 
	
	
	
	<tr>
		<td>{t}Delivery Note Country{/t}:</td>
		<td>
		<input id="billing_geo_constraints" style="width:400px"/> 
		<div class="general_options" >
			<span id="postal_code" class="state_details">{t}Postal Code{/t}</span>
			<span id="city" class="state_details">{t}City{/t}</span>
			<span id="country" class="state_details">{t}Country{/t}</span>
			<span id="wregion" class="state_details">{t}World Region{/t}</span>
		</div>
		</td>      
    </tr>  

	  
      <tr>
        <td>{t}Weight{/t}:</td>
		<td>
            <input id="weight_lower" type="text" class="text" size="5" maxlength="10" name="after" value=""/><span id="a" style="display:none">&rarr;</span> 
			<input style="display:none" id="weight_upper" type="text" class="text" size="5" maxlength="10" name="after" value=""/>
			<div id="weight_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$condition item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_weight_condition(this)" id="weight_condition_{$cat_key}" parent="weight_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
     
      </tr>
	  <tr><td colspan="2"><b>{t}Delivery Notes type...{/t}</b></td></tr>
	  <tr>
        <td>{t}State{/t}:</td>
		<td>
			<div id="state_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$state item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_state_condition(this)" id="state_condition_{$cat_key}" parent="state_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
     
      </tr>
	  
	  <tr>
        <td>{t}Note Type{/t}:</td>
		<td>
			<div id="note_type_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$note_type item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_note_type_condition(this)" id="note_type_condition_{$cat_key}" parent="note_type_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
     
      </tr>
	  
	  <tr>
        <td>{t}Dispatch Method{/t}:</td>
		<td>
			<div id="dispatch_method_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$dispatch_method item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_dispatch_method_condition(this)" id="dispatch_method_condition_{$cat_key}" parent="dispatch_method_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
     
      </tr>
	  
	  <tr>
        <td>{t}Parcel Type{/t}:</td>
		<td>
			<div id="parcel_type_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$parcel_type item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_parcel_type_condition(this)" id="parcel_type_condition_{$cat_key}" parent="parcel_type_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
     
      </tr>
	  
      </table>
      </form>
       </table>
</div> 
<div style="padding:20px;width:890px;xtext-align:right">
<div id="save_dialog" style="width:600px;float:left;visibility:hidden">
 <div id="the_div" style="xdisplay:none;">    
	{t}Enter list name{/t} : <input type="text" name="list_name" id="list_name"> &nbsp;&nbsp;{t}Select List Type{/t} : <input type="radio" name="type" checked="checked" id="static" value="Static">&nbsp;{t}Static{/t} &nbsp;&nbsp;<input type="radio" name="type"  id="dynamic" value="Dynamic">&nbsp;{t}Dynamic{/t}
      </div>
<div id="save_list_msg"></div>
</div>
<div style="float:left">
      <span  style="display:none;margin-left:20px;border:1px solid #ccc;padding:4px 5px;cursor:pointer" id="save_list"  >{t}Save List{/t}</span>
      <span  style="display:none;margin-left:20px;border:1px solid #ccc;padding:4px 5px;cursor:pointer" id="modify_search" >{t}Redo List{/t}</span>
      <span  style="margin-left:20px;border:1px solid #ccc;padding:4px 5px;cursor:pointer" id="submit_search">{t}Create List{/t}</span>
</div>
</div>




    <div style="padding:30px 40px;display:none" id="searching">
	{t}Search in progress{/t} <img style="margin-left:20px;position:relative;top:5px "src="art/progressbar.gif"/>
    </div>

    
    <div id="the_table" class="data_table" style="margin-top:20px;clear:both;display:none" >
    <span class="clean_table_title">Delivery Notes List</span>
 <div id="table_type">
         <a  style="float:right"  class="table_type state_details"  href="customers_lists_csv.php" >{t}Export (CSV){/t}</a>

     </div>


  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>

      <div id="short_menu" class="nodetails" style="clear:both;width:100%;margin-bottom:0px">
 
  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	  <td {if $view=='address'}class="selected"{/if}  id="address"  >{t}Address{/t}</td>
	  <td {if $view=='balance'}class="selected"{/if}  id="balance"  >{t}Balance{/t}</td>
	  <td {if $view=='rank'}class="selected"{/if}  id="rank"  >{t}Ranking{/t}</td>

	</tr>
      </table>
 
 
      
    </div>



 
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=true }
     	<div  id="table0"   style="font-size:90%" class="data_table_container dtable btable "> </div>


</div>		

    </div>

<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>



{include file='footer.tpl'}
<div id="dialog_wregion_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}World Regions{/t}</span>
            {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
            <div  id="table1"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>



<div id="dialog_country_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Country List{/t}</span>
            {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
            <div  id="table2"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>


<div id="dialog_postal_code_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Postal Code List{/t}</span>
            {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3}
            <div  id="table3"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 
 
<div id="dialog_city_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Cities{/t}</span>
            {include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4}
            <div  id="table4"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div> 
 

 
   <div id="filtermenu3" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu3 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',3)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
  </div>
 
   <div id="filtermenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
 </div>
  <div id="filtermenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
 </div>
 
   <div id="filtermenu4" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu4 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',4)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

  <div id="filtermenu5" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu5 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',5)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
 <div id="filtermenu6" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu6 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',6)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
 <div id="filtermenu7" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu7 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',7)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
