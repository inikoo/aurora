{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">
{include file='marketing_navigation.tpl'}

<div style="clear:left;margin:0 0px">
    <h1>{t}Marketing{/t}</h1>
</div>

</div>
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='metrics'}selected{/if}" id="metrics"  ><span>  {t}Emarketing{/t}</span></span></li>
    <li> <span class="item {if $view=='newsletter'}selected{/if}"  id="newsletter">  <span> {t}Google Adwords{/t}</span></span></li>
    <li> <span class="item {if $view=='email'}selected{/if}"  id="email">  <span> {t}YTC{/t}</span></span></li>
    <li> <span class="item {if $view=='web_internal'}selected{/if}"  id="web_internal">  <span> {t}YTC{/t}</span></span></li>
    <li> <span class="item {if $view=='web'}selected{/if}"  id="web">  <span> {t}YTC{/t}</span></span></li>
    <li> <span class="item {if $view=='other'}selected{/if}"  id="other">  <span> {t}Other Media Campaigns{/t}</span></span></li>
</ul>
 <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<!--Dashboard done here-->

<div style="clear:left;margin:2px 0px">

  <div style="background-color:#f8d285;height:60px;">
  <div class="campaign_head"></div>
  <table  style="margin-top:25px;" cellspacing="10" width="445">
  	<tr>
	<td><div class="topmenu current"><a href="">Dashboard</a></div></td>
	<td><div class="topmenu"><a href="">Campaigns</a</div></td>
       <td><div class="topmenu"><a href="">Lists</a</div></td>
	<td><div class="topmenu"><a href="">Reports</a</div></td>
	<td><div class="topmenu"><a href="">Autoresponders</a</div></td>
	</tr>
 </table>

</div> 	
<div style="padding:30px 0px 0px 4px;">
<h1 style="color: #CC6600; margin: 0px 0 0px 15px;text-transform: capitalize;">Dashboard</h3>
<table height="520" >
<tr>
 <td >
<div class="campaign_create"><a id="create_camp" href="">Create Campaign<span class="dwn">▼</span></a><div>


</td> 

<td style="background-color:#f1ede0;width:720px;">
<div style=" color: #CC6600;
    font-size: 20px;
    line-height: 1;
    margin: 1em 0 0 1em;">
  Getting started with Emarketing is easy …

</div><br><div style="height:40px;">
<div style="float:left;"><img style="height:40px; width:40px;" src="art/1.png"> </div><span style="float:left;line-height:40px;font-size:18px;">Create a list</span> 
 
<div class="go_next">
<a class="button-small" title="create a mailing list" href="marketing_list.php">go »</a>
</div>









</div></div>
<div style="height:40px;">
<div style="float:left;"><img style="height:40px; width:40px;" src="art/2.png"> </div><span style="float:left;line-height:40px;font-size:18px;">Create a campaign</span> <div class="go_next">
<a class="button-small" title="create a mailing list" href="marketing_create_campaign.php">go »</a>
</div></div>

<div style="height:40px;">
<div style="float:left;"><img style="height:40px; width:40px;" src="art/3.png"> </div><span style="float:left;line-height:40px;font-size:18px;">View campaign reports </span> <div class="go_next">
<a class="button-small" title="View campaign reports" href="#">go »</a>
</div></div>

</td>


</tr>


</table>

</div>


		</div>





<!--/Dashboard done here-->
<div id="block_metrics" style="{if $view!='metrics'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_newsletter" style="{if $view!='newsletter'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_email" style="{if $view!='email'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


  <span   class="clean_table_title" style="">{t}Email Campaigns{/t}</span>


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
