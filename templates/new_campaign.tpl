{include file='header.tpl'}
<div id="bd" >

  <div class="search_box" style="margin-top:15px">
  <div class="general_options">
    {foreach from=$general_options_list item=options }
        {if $options.tipo=="url"}
            <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
        {else}
            <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
        {/if}
    {/foreach}
    </div>
</div>
<h1>{t}New Campaign{/t}</h1>
<div>
<h2>{t}Define Goals{/t}</h2>

<table class="edit">
<tr><td class="label">{t}Campaign Name:{/t}</td><td><input type="text" value=""></td></tr>
<tr><td class="label">{t}Campaign Objetives:{/t}</td><td><textarea></textarea></td></tr>
</table>
</div>



<div >
<h2>{t}Create Email List{/t}</h2>

 <div class="search_box" style="margin-top:30px;font-size:90%;" id="the_search_box" >

   <table>
     <tr><td colspan="" style="text-align:right;border-bottom:1px solid #ccc" >Search over:</td></tr>
     <tr><td style="text-align:right">{t}All Customers{/t}</td><td><input checked="checked" name="geo_group" id="geo_group_all" value="all" type="radio"></td></tr>
     <tr><td style="text-align:right">{$home} {t}Customers{/t}</td><td><input  name="geo_group"  id="geo_group_home" value="home" type="radio"></td></tr>
     <tr><td style="text-align:right">{t}Foreign Customers{/t}</td><td><input  name="geo_group"  id="geo_group_nohome" value="nohome" type="radio"></td></tr>
   
   </table>
 </div>
   
   

      <table class="edit" border=0>
	
	<tr><td colspan="2"><b>Customers who..</b></td></tr>
      <tr><td>ordered this product(s)</td><td><input id="product_ordered1" value="" size="40" /></td><tr>
      <tr><td>but didn't order this product(s)</td><td><input id="product_not_ordered1" value="" size="40" /></td><tr>
      <tr><td>and did't receive this product(s)</td><td><input id="product_not_received1" value="" size="40" /></td><tr>
      <tr><td>during this period</td><td><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> </td></tr>
      <tr style="height:30px;"><td  style="vertical-align:bottom;"><span  style="margin-left:0px;border:1px solid #ccc;padding:4px 5px;cursor:pointer" id="submit_advanced_search">{t}Create List{/t}</span>
</td></tr>
      </table>
      
   
      
      <div style="padding:30px 40px;display:none" id="searching">
	{t}Search in progress{/t} <img src="art/progressbar.gif"/>
      </div>

    
    <div id="the_table" class="data_table" style="margin:20px 20px;clear:both;display:none" >
      
      <h2 >{t}{$table_title}{/t}</h2>
      <div id="short_menu" class="nodetails" style="clear:both;width:100%;margin-bottom:0px">
      <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	</tr>
      </table>
      <table style="float:right;margin:0 0 0 0px ;padding:0" >
	<tr>
	  <td>{t}Export the result list as{/t}:</td>
	  <td><img src="art/icons/page_excel.png" style="vertical-align:bottom"/><a  href="csv.php?tipo=cas" class="state_details" style="color:black;position:relative;bottom:1px">{t}CSV file{/t}</a></td>
	</tr>
      </table>
    </div>



      
      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	<div class="clean_table_filter" id="clean_table_filter0" style="display:none"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
	  <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>

    </div>



<div style="clear:left;margin:0 0px">
    <h2>{t}Choose Email Layout{/t}</h2>
    <div class="block_list" style="clear:both;">
    <div style="background-image:url('art/basic.gif');background-repeat:no-repeat;background-position:center 0px;height:20px;padding:140px 0 0 0;" onClick="mail_layout('basic')" ">{t}Basic{/t}</div>
    <div style="background-image:url('art/postcard.gif');background-repeat:no-repeat;background-position:center 0px;height:20px;padding:140px 0 0 0;" onClick="mail_layout('basic')" ">{t}Postcard{/t}</div>
    <div style="background-image:url('art/left_column.gif');background-repeat:no-repeat;background-position:center 0px;height:20px;padding:140px 0 0 0;" onClick="mail_layout('basic')" ">{t}Left Column{/t}</div>
    <div style="background-image:url('art/right_column.gif');background-repeat:no-repeat;background-position:center 0px;height:20px;padding:140px 0 0 0;" onClick="mail_layout('basic')" ">{t}Right Column{/t}</div>

 </div>
    
<div style="clear:left;margin:0 0px">
    <h2>{t}Compose Email{/t}</h2>
 <table class="edit">
<tr><td class="label">{t}Email Subject:{/t}</td><td><input type="text" value=""></td></tr>
<tr><td class="label">{t}Email:{/t}</td><td></td></tr>
<tr><td colspan="2"><textarea id="email_body" name="v_details" changed=0 olength=""  ovalue=""  ohash="" rows="20" cols="100"></textarea>

</table>   
    
 </div>  
    
    
</div>

</div>
{include file='footer.tpl'}
