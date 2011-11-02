{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}
<input type="hidden" id="store_id" value="{$store->id}">
<input type="hidden" id="auto" value="{if $auto==1}1{else}0{/if}">
{foreach from=$v_calpop key=key item=item}
<input type="hidden" id="v_calpop" cat={$key} value={$item}>
{/foreach}
<span id="error_no_name" style="display:none">{t}Please specify a name{/t}.</span>

<div> 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a href="customers_lists.php?store={$store->id}">{t}Lists{/t}</a> &rarr; {t}New List{/t}</span>
</div>
<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
    <div class="buttons" style="float:left">
        <button  onclick="window.location='customers.php?store={$store->id}'" ><img src="art/icons/house.png" alt=""> {t}Customers{/t}</button>
    </div>
  <div class="buttons">
      <button class="negative" onclick="window.location='customers_lists.php?store={$store->id}'" ><img src="art/icons/door_out.png" alt=""/> {t}Close{/t}</button>
     
      </div>
    <div style="clear:both"></div>
</div>


<h1 >{t}New Customers List{/t} <span class="id">{$store->get('Store Code')}</span></h1>
<table >

	<tr>
	<td colspan="2"><b>{t}Contacts who...{/t}</b></td>
	</tr>
      <tr>
        <td>{t}Register between{/t}:</td>
        <td>
            <input id="v_calpop3" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="customer_first_contacted_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop4" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="customer_first_contacted_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="customer_first_contacted_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="customer_first_contacted_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>        
      </tr>
     <tr>
        <td>{t}based in (location){/t}:</td>
        <td>
        <input id="geo_constraints" style="width:500px"/> 
        <div class="general_options" >
                <span id="postal_code" class="state_details">{t}Postal Code{/t}</span>
                <span id="city" class="state_details">{t}City{/t}</span>
                <span id="country" class="state_details">{t}Country{/t}</span>
                <span id="wregion" class="state_details">{t}World Region{/t}</span>

        </div>
        </td>
        
    </tr>  
      
      
    <tr>
        <td>{t}have{/t}: <span style="cursor:pointer" id="show_dont_have">&#8623;</span></td>
        <td>
   <div id="have_options" default_cat=""   class="options" style="margin:5px 0">
     {foreach from=$have_options item=cat3 key=cat_key name=foo3}
     <span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_have(this)" id="have_{$cat_key}"  parent="have_" cat="{$cat_key}"  >{$cat3.name}</span>
     {/foreach}
    </div>
        </td>
        
    </tr>
   
     <tr style="display:none" id='tr_dont_have'>
        <td>{t}don't have{/t}:</td>
        <td>
         <div id="dont_have_options" default_cat=""   class="options" style="margin:5px 0">
     {foreach from=$have_options item=cat3 key=cat_key name=foo3}
     <span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_have(this)" id="dont_have_{$cat_key}" parent="dont_have_"  cat="{$cat_key}" >{$cat3.name}</span>
     {/foreach}
    </div>    
        </td>
        
    </tr>
    
    
     <tr>
        <td>{t}wish to receive{/t}: <span style="cursor:pointer" id="show_dont_wish_to_receive">&#8623;</span></td>
        <td>
         <div id="allow_options" default_cat=""   class="options" style="margin:5px 0">
     {foreach from=$allow_options item=cat3 key=cat_key name=foo3}
     <span  class="catbox {if $cat3.selected}selected{/if}"  style="{if $cat_key=='all'}margin-left:20px{/if}"onclick="checkbox_changed_allow(this)" id="allow_{$cat_key}" parent="allow_"  cat="{$cat_key}" >{$cat3.name}</span>
     {/foreach}
    </div>    
        </td>
    </tr>
    
        <tr style="display:none"  id="tr_dont_wish_to_receive">
        <td>{t}don't wish to receive{/t}:</td>
        <td>
         <div id="dont_allow_options" default_cat=""   class="options" style="margin:5px 0">
     {foreach from=$allow_options item=cat3 key=cat_key name=foo3}
     <span  class="catbox {if $cat3.selected}selected{/if}"  style="{if $cat_key=='all'}display:none{/if}" onclick="checkbox_changed_allow(this)" id="dont_allow_{$cat_key}" parent="dont_allow_"  cat="{$cat_key}" >{$cat3.name}</span>
     {/foreach}
    </div>    
        </td>
    </tr>
    

     <tr><td>{t}Categories{/t}</td><td><input id="customer_categories" value="" style="width:500px" />
      <div class="general_options" >
                <span id="customer_category" class="state_details">{t}Other Categories{/t}</span>
                <span id="category_business_type" class="state_details" style="{if !$business_type}display:none{/if}">{t}Type of Business{/t}</span>
               

        </div>
      </td></tr>
    
	<tr><td colspan="2"><b>{t}Customers who ordered...{/t}</b></td></tr>
      <tr><td>{t}any of this product(s){/t}</td>
	  <td><input id="product_ordered_or" value="" style="width:500px" />
      <div class="general_options" >
                <span id="product_category" class="state_details">{t}Product Categories{/t}</span>
                <span id="product" class="state_details">{t}Product{/t}</span>
                <span id="family" class="state_details">{t}Family{/t}</span>
                <span id="department" class="state_details">{t}Department{/t}</span>

        </div>
      </td></tr>

      <tr style="display:none"><td>{t}but didn't order this product(s){/t}</td><td><input id="product_not_ordered1" value="" style="width:400px" /></td></tr>
      <tr style="display:none"><td>{t}and did't receive this product(s){/t}</td><td><input id="product_not_received1" value="" size="40" /></td></tr>
      <tr>
        <td>{t}during this period{/t}:</td>
        <td>
            <input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="product_ordered_or_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop2" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="product_ordered_or_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="product_ordered_or_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="product_ordered_or_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>
      </tr>
     
	
	 <tr><td colspan="2"><b>{t}Customer Stats{/t}</b></td></tr>
	 
	<tr>
		<td>{t}customers which are{/t}:
		<td>
			<div id="customers_which_options" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$customer_stat item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_customers_which(this)" id="customers_which_{$cat_key}"  parent="customers_which_" cat="{$cat_key}"  >{$cat3.name}</span>
			{/foreach}
			</div>
		</td>
	</tr>
{*
	<tr style="display:none" id='tr_not_customers_which'>
		<td>{t}not customers which are{/t}:</td>
		<td>
			<div id="not_customers_which_options" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$customer_stat item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_customers_which(this)" id="not_customers_which_{$cat_key}" parent="not_customers_which_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>    
		</td>
	</tr>
  *}  
  	  <tr id="lost_customer_title" style="display:none"><td colspan="2"><b>{t}Lost Customers{/t}</b></td></tr>
      <tr id="lost_customer"style="display:none">
        <td>{t}Register between{/t}:</td>
        <td>
            <input id="v_calpop5" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="lost_customer_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /><span class="calpop">&rarr;</span> 
			<input id="v_calpop6" type="text" class="calpop" size="11" maxlength="10" name="to" value=""/><img   id="lost_customer_to" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="lost_customer_from_Container" style="position:absolute;display:none; z-index:2"></div>
			<div id="lost_customer_to_Container" style="position:absolute;display:none; z-index:2"></div>
        </td>        
      </tr>
	  
	  <tr><td colspan="2"><b>{t}Customers with Order{/t}</b></td></tr>
      <tr>
        <td>{t}Number of Orders{/t}:</td>
		<td>
            <input id="number_of_orders_lower" type="text" class="text" size="5" maxlength="10" name="after" value=""/><span id="c" style="display:none">&rarr;</span> 
			<input style="display:none" id="number_of_orders_upper" type="text" class="text" size="5" maxlength="10" name="after" value=""/>
			<div id="order_condition_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$condition item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_order_condition(this)" id="order_condition_{$cat_key}" parent="order_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
     
      </tr>
	  
	  <tr><td colspan="2"><b>{t}Customers with Invoice{/t}</b></td></tr>
      <tr>
        <td>{t}Number of Invoices{/t}:</td>
		<td>
            <input id="number_of_invoices_lower" type="text" class="text" size="5" maxlength="10" name="after" value=""/><span id="a" style="display:none">&rarr;</span> 
			<input style="display:none" id="number_of_invoices_upper" type="text" class="text" size="5" maxlength="10" name="after" value=""/>
			<div id="invoice_condition_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$condition item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_invoice_condition(this)" id="invoice_condition_{$cat_key}" parent="invoice_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
     
      </tr>
	  
	  
	  <tr><td colspan="2"><b>{t}Customer Sales{/t}</b></td></tr>
      <tr>
        <td>{t}Sales{/t}:</td>
		<td>
            <input id="sales_lower" type="text" class="text" size="5" maxlength="10" name="after" value=""/><span id="b" style="display:none">&rarr;</span> 
			<input style="display:none" id="sales_upper" type="text" class="text" size="5" maxlength="10" name="after" value=""/>
			<div id="sales_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$condition item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_sales_condition(this)" id="sales_condition_{$cat_key}" parent="sales_condition_"  cat="sales_{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
     
      </tr>
	  
      </table>
<div style="border-top:1px solid #ccc">
<div id="save_dialog" style="width:600px;float:left;visibility:hidden">
 <div id="the_div" style="xdisplay:none;">    
	{t}Enter list name{/t} : <input type="text" name="list_name" id="list_name"> &nbsp;&nbsp;{t}Select List Type{/t} : <input type="radio" name="type" checked="checked" id="static" value="Static">&nbsp;{t}Static{/t} &nbsp;&nbsp;<input type="radio" name="type"  id="dynamic" value="Dynamic">&nbsp;{t}Dynamic{/t}
      </div>
<div id="save_list_msg"></div>
</div>
<div class="buttons">
      <button  style="display:none;" id="save_list"  >{t}Save List{/t}</button>
      <button  style="display:none;" id="modify_search" >{t}Redo List{/t}</button>
      <button  id="submit_search">{t}Create List{/t}</button>
</div>
</div>
<div style="padding:30px 40px;display:none" id="searching">
	{t}Search in progress{/t} <img style="margin-left:20px;position:relative;top:5px "src="art/progressbar.gif"/>
    </div>
<div id="the_table" class="data_table" style="margin-top:20px;clear:both;{if $auto==0}display:none{/if}" >
    <span class="clean_table_title">Customers List</span>
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
 
 <div id="dialog_department_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Department List{/t}</span>
            {include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5}
            <div  id="table5"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 

 
 
 <div id="dialog_family_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Family List{/t}</span>
            {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6}
            <div  id="table6"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 
 <div id="dialog_product_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Product List{/t}</span>
            {include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7}
            <div  id="table7"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 
 <div id="dialog_category_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Category List{/t}</span>
            {include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8}
            <div  id="table8"   class="data_table_container dtable btable "> </div>
        </div>
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
