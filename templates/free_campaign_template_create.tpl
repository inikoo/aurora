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
	  <td id="create_campaign"><a href="new_campaign.php">Create Campaign</a></td>	  
          <td id="view_campaign" style="background-color:#7296E1;"><a style="color:#ffffff;" href="campaign_builder.php">View Campaign</a></td>	</tr>
      </tbody></table>



<h2 style="clear:both">{t}Free Template{/t} </h2>
<div style="border:1px solid #ccc;padding:50px;width:690px">
	<div id="campaign_div">{$msg}</div>


      <table border="0" width="700">
	<form action="free_template_preview.php" method="post" name="free_Template" id="free_Template" onsubmit="return validateForm();">
	<tr>
	  <td> Subject </td><td><b>:</b></td><td> <input type="text" name="f_template_sub" id="f_template_sub" size="30" value=""> </td>
	</tr>

	<tr>
	  <td>  Body </td> <td><b>:</b></td><td></td>
	</tr>

	<tr>
		<td colspan="3"><textarea name="f_template_body" id="f_template_body" class="ckeditor" cols="28"></textarea></td>
	</tr>

	<tr>
	  <td colspan=3 align="right"> <input type="submit" name="createCampaign" class="Emarketing_button" value="Create & Preview"> </td>
	</tr>
		<input type="hidden" name="template" value="{$template}">
		<input type="hidden" name="email[]" value="{$email_list}">		
	</form>
      </table>

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
