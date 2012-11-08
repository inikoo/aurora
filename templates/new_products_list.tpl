{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}


   
      <h2 style="clear:both">{t}New Product List{/t} ({$store->get('Store Name')})</h2>
<div style="border:1px solid #ccc;padding:20px;width:870px">
<input type="hidden" id="store_id" value="{$store_id}">

<span id="error_no_name" style="display:none">{t}Please specify a name{/t}.</span>
      <table >
	<form>
		<tr><td colspan="2"><b>{t}Product Validity...{/t}</b></td></tr>
      <tr>
        <td>{t}Product on record between{/t}:</td>
        <td>
            <input id="v_calpop3" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="product_first_validated_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop4" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="product_first_validated_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="product_first_validated_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="product_first_validated_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>        
      </tr>
<tr><td colspan="2"><b>{t}Some features...{/t}</b></td></tr> 
     <tr>
        <td>{t}contained in{/t}:</td>
        <td>
        <input id="geo_constraints" style="width:500px"/> 
        <div class="general_options" >
                <span id="family1" class="state_details">{t}Family{/t}</span>
                <span id="department1" class="state_details">{t}Department{/t}</span>
               

        </div>
        </td>
        
    </tr>  
      
        <tr><td>{t}any of this product(s){/t}</td><td><input id="product_ordered_or" value="" style="width:500px" />
      <div class="general_options" >
                <span id="brand" class="state_details">{t}Brand{/t}</span>
                <span id="tarrif" class="state_details">{t}Tarrif{/t}</span>
                <span id="special_characteristics" class="state_details">{t}Special Characteristics{/t}</span>
        </div>
      </td><tr>

      <tr style="display:none"><td>{t}but didn't order this product(s){/t}</td><td><input id="product_not_ordered1" value="" style="width:400px" /></td><tr>
      <tr style="display:none"><td>{t}and did't receive this product(s){/t}</td><td><input id="product_not_received1" value="" size="40" /></td><tr>

	 <tr><td colspan="2"><b>{t}Pricing...{/t}</b></td></tr> 
     <tr>
        <td>{t}price{/t}:</td>
		<td>
            <input id="price_lower" type="text" class="text" size="5" maxlength="10" name="after" value=""/><span id="a" style="display:none">&rarr;</span> 
			<input style="display:none" id="price_upper" type="text" class="text" size="5" maxlength="10" name="after" value=""/>
			<div id="price_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$condition item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_price_condition(this)" id="price_condition_{$cat_key}" parent="price_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
        
    </tr>  
	
     <tr>
        <td>{t}Total invoiced amount...{/t}:</td>
		<td>
            <input id="invoice_lower" type="text" class="text" size="5" maxlength="10" name="after" value=""/><span id="b" style="display:none">&rarr;</span> 
			<input style="display:none" id="invoice_upper" type="text" class="text" size="5" maxlength="10" name="after" value=""/>
			<div id="invoice_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$condition item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_invoice_condition(this)" id="invoice_condition_{$cat_key}" parent="invoice_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
        
    </tr> 
	
	 <tr><td colspan="2"><b>{t}Product state...{/t}</b></td></tr> 
     <tr>
        <td>{t}web state{/t}:</td>
		<td>
			<div id="web_state_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$web_state item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_web_state_condition(this)" id="web_state_condition_{$cat_key}" parent="web_state_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
        
    </tr>  	

     <tr>
        <td>{t}availability state{/t}:</td>
		<td>
			<div id="availability_state_option" default_cat=""   class="options" style="margin:5px 0">
			{foreach from=$availability_state item=cat3 key=cat_key name=foo3}
			<span  class="catbox {if $cat3.selected}selected{/if}"  onclick="checkbox_changed_availability_state_condition(this)" id="availability_state_condition_{$cat_key}" parent="availability_condition_"  cat="{$cat_key}" >{$cat3.name}</span>
			{/foreach}
			</div>  
		</td>
        
    </tr>  		
	
  
  
{*	<tr><td colspan="2"><b>{t}Customers who ordered...{/t}</b></td></tr>     *}

 {*     <tr>
        <td>{t}during this period{/t}:</td>
        <td>
            <input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value=""/><img   id="product_ordered_or_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> 
            <input id="v_calpop2" class="calpop"  size="11" maxlength="10"   type="text" class="text" size="8" name="to" value=""/><img   id="product_ordered_or_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
            <div id="product_ordered_or_from_Container" style="position:absolute;display:none; z-index:2"></div>
            <div id="product_ordered_or_to_Container" style="display:none; z-index:2;position:absolute"></div>
        </td>
      </tr>

 *}    
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

    
    


<div id="the_table" class="data_table" style="margin-top:20px;clear:both;display:none">
    <span class="clean_table_title">{t}Products{/t}</span>
<span  id="export_csv0" style="float:right;margin-left:20px"  class="table_type state_details" tipo="products" >{t}Export (CSV){/t}</span>
<a style="float:right;margin-left:20px"  class="table_type state_details"  href="export_xml.php" >{t}Export (XML){/t}</a>

     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    
    <span   style="float:right;margin-left:80px" class="state_details" state="{$show_percentages}"  id="show_percentages"  atitle="{if $show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>
    
    
    
    <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
      <tr><td  {if $product_view=='general'}class="selected"{/if} id="product_general" >{t}General{/t}</td>
	{if $view_stock}<td {if $product_view=='stock'}class="selected"{/if}  id="product_stock"  >{t}Stock{/t}</td>{/if}
	{if $view_sales}<td  {if $product_view=='sales'}class="selected"{/if}  id="product_sales"  >{t}Sales{/t}</td>{/if}
	<td  {if $product_view=='parts'}class="selected"{/if}  id="product_parts"  >{t}Parts{/t}</td>
	<td  {if $product_view=='cats'}class="selected"{/if}  id="product_cats"  >{t}Groups{/t}</td>
      </tr>
    </table>
	
    <table id="product_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	  <tr>
	    
	    <td class="option {if $product_period=='all'}selected{/if}" period="all"  id="product_period_all" >{t}All{/t}</td>
	    <td class="option {if $product_period=='year'}selected{/if}"  period="year"  id="product_period_year"  >{t}1Yr{/t}</td>
	    <td class="option {if $product_period=='quarter'}selected{/if}"  period="quarter"  id="product_period_quarter"  >{t}1Qtr{/t}</td>
	    <td class="option {if $product_period=='month'}selected{/if}"  period="month"  id="product_period_month"  >{t}1M{/t}</td>
	    <td class="option {if $product_period=='week'}selected{/if}" period="week"  id="product_period_week"  >{t}1W{/t}</td>
	  </tr>
      </table>

       <table  id="product_avg_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $product_avg=='totals'}selected{/if}" avg="totals"  id="product_avg_totals" >{t}Totals{/t}</td>
	  <td class="option {if $product_avg=='month'}selected{/if}"  avg="month"  id="product_avg_month"  >{t}M AVG{/t}</td>
	  <td class="option {if $product_avg=='week'}selected{/if}"  avg="week"  id="product_avg_week"  >{t}W AVG{/t}</td>
	  <td class="option {if $product_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff"  id="product_avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td class="option {if $product_avg=='week_eff'}selected{/if}" style="display:none"  avg="week_eff"  id="product_avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
      </table>



        {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name2 filter_value=$filter_value0  }

    <div  id="table0"   class="data_table_container dtable btable"> </div>
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
<div class="search_box" style="margin-top:30px;font-size:90%;display:none" id="the_search_box" >

   <table>
     <tr><td colspan="" style="text-align:right;border-bottom:1px solid #ccc" >Search over:</td></tr>
     <tr><td style="text-align:right">{t}All Customers{/t}</td><td><input checked="checked" name="geo_group" id="geo_group_all" value="all" type="radio"></td></tr>
     <tr><td style="text-align:right">{$home} {t}Customers{/t}</td><td><input  name="geo_group"  id="geo_group_home" value="home" type="radio"></td></tr>
     <tr><td style="text-align:right">{t}Foreign Customers{/t}</td><td><input  name="geo_group"  id="geo_group_nohome" value="nohome" type="radio"></td></tr>
     <tr><td colspan="" style="text-align:right;border-bottom:1px solid #ccc;height:30px;vertical-align:bottom" >Only Customers:</td></tr>
     <tr><td style="text-align:right">{t}with Email{/t}</td><td><input   id="with_email"  type="checkbox"></td></tr>
     <tr><td style="text-align:right">{t}with Telephone{/t}</td><td><input    id="with_tel"  type="checkbox"></td></tr>

   </table>
 </div>
{include file='footer.tpl'}
<div id="dialog_wregion_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}World Regions{/t}</span>
            {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
            <div  id="table1"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>



<div id="dialog_country_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Country List{/t}</span>
            {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
            <div  id="table2"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>


<div id="dialog_postal_code_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Postal Code List{/t}</span>
            {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3}
            <div  id="table3"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>
 
 
<div id="dialog_city_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Cities{/t}</span>
            {include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4}
            <div  id="table4"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div> 
 
 <div id="dialog_department_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Department List{/t}</span>
            {include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5}
            <div  id="table5"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>
 
 <div id="dialog_family_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Family List{/t}</span>
            {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6}
            <div  id="table6"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>
 
 <div id="dialog_product_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Product List{/t}</span>
            {include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7}
            <div  id="table7"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>
 
 <div id="dialog_category_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Category List{/t}</span>
            {include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8}
            <div  id="table8"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>
 

 
