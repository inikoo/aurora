{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">

{include file='marketing_navigation.tpl'}

 
<div style="clear:left;margin:0 0px">
    <h1>{t}Marketing{/t}</h1>
</div>

</div>
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item selected{if $view=='Emarketing'}selected{/if}" id="metrics"  ><span>  {t}Emarketing{/t}</span></span></li>
    <li> <span class="item {if $view=='newsletter'}selected{/if}"  id="newsletter">  <span> {t}eNewsletters{/t}</span></span></li>
    <li> <span class="item {if $view=='email'}selected{/if}"  id="email">  <span> {t}Email Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='web_internal'}selected{/if}"  id="web_internal">  <span> {t}Site Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='web'}selected{/if}"  id="web">  <span> {t}Internet Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='other'}selected{/if}"  id="other">  <span> {t}Other Media Campaigns{/t}</span></span></li>
</ul>
 <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>


<div id="block_metrics" style="{if $view!='Emarketing'}display:block;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


<h2>Emarketing</h2>
<div class="table_top_bar"></div>
<table class="options" style="float: left; margin: 0pt 0pt 0pt 0px; padding: 0pt;">
	<tbody><tr><td id="create_list"><a href="#">Create List</a></td>
        <td id="view_list"><a href="customers_lists.php">View List</a></td>
	  <td id="create_campaign"  class="campaign_tab"><a style="color:#ffffff;" href="#" onclick="checkListTable()">Create Campaign</a></td>	  
          <td id="view_campaign" ><a href="campaign_builder.php">View Campaign</a></td>	</tr>
      </tbody></table>



      <h2 style="clear:both">{t}Create Campaign{/t} <span style="padding-left:300px;">{$link}</span></h2>
<div style="border:1px solid #ccc;padding:50px;width:690px">
	<div id="campaign_div">{$msg}</div>
   
	<form action="create_campaign_data.php" method="post" name="campaign" id="campaign" onsubmit="return process();">  
 <table border="0" width="700">
	<tr>
	  <td width="300"> Select list </td><td><b>:</b></td><td align="right"> 

		
		<select name="customer_list_key" id="customer_list_key" style="width:233px;">
			{section name="record" loop="$customer"}
				<option value="{$customer[record].$k}">{$customer[record].$n}</option>
			{/section} 
		</select>
		
	 </td>
	</tr>	
	
	<tr>
	  <td width="300"> Campaign Name   </td><td><b>:</b></td><td align="right"> <input type="text" name="campaign_name" id="campaign_name" size="30" value="{$campaign_name}"> </td>
	</tr>
	<tr>
	  <td> Campaign Objective  </td><td><b>:</b></td><td> <input type="text" name="campaign_obj" id="campaign_obj" size="30" value="{$campaign_obj}"> </td>
	</tr>

	<tr>
	  <td> Campaign Maximum Email </td><td><b>:</b></td><td> <input type="text" name="campaign_mail" id="campaign_mail" size="30" value="{$campaign_mail}"> </td>
	</tr>

	<tr>
	  <td>  Campaign Content </td> <td><b>:</b></td><td></td>
	</tr>

	<tr>
		<td colspan="3"><textarea name="campaign_content" id="campaign_content" class="ckeditor" cols="28">{$campaign_content}</textarea></td>
	</tr>

	<tr>
	  <td colspan=3 align="right"> <input type="submit" class="Emarketing_button" name="createCampaign" value="Create"> </td>
	</tr>
		
		<input type="hidden" name="max_num_mail" id="max_num_mail" value="{$count}"> 		
	</table></form>
      

</div> 





</div>
<div id="block_newsletter" style="{if $view!='newsletter'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_email" style="{if $view!='email'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


  <span   class="clean_table_title" >{t}Email Campaigns{/t}</span>


  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    
   
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0  }
<div  id="table0"   class="data_table_container dtable btable"> </div>


</div>
<div id="block_web_internal" style="{if $view!='web_internal'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_web" style="{if $view!='web'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_other" style="{if $view!='other'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
</div>

{include file='footer.tpl'}

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
