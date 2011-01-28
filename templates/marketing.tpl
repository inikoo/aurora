{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 0px">

<div style="clear:left;margin:0 0px">

  <div style="background-color:#7080b1;height:60px;">
  <div class="campaign_head">Campaigns</div>
  <table  style="margin-top:24px;" cellspacing="10" width="445">
  	<tr>
	<td><div class="topmenu current"><a href="">Emarketing</a></div></td>
	<td><div class="topmenu"><a href="">Campaigns</a</div></td>
       <td><div class="topmenu"><a href="">Lists</a</div></td>
	<td><div class="topmenu"><a href="">Reports</a</div></td>
	<td><div class="topmenu"><a href="">Autoresponders</a</div></td>
	</tr>
 </table>

</div> 	
<div style="padding:30px 0px 0px 4px;">
<table height="520" >
<tr>
 <td style="background-color:#d3dbe8">
<div class="campaign_create"><a id="create_camp" href="">Create Campaign<span class="dwn">▼</span></a><div>











</td> 

<td style="background-color:#f1edeo;width:700px;">
<div style=" color: #CC6600;
    font-size: 20px;
    line-height: 1;
    margin: 1em 0 0 1em;">
  Getting started with MailChimp is easy …

</div><br><div style="height:75px;">
<div style="float:left;"><img src="art/1.png"> </div><span style="float:left;line-height:50px;font-size:18px;">Create a list</span> <div style="float:right;width:51px;height:26px;background-color:#c1b798;line-height;10px;-moz-border-radius: 5px 5px 5px 5px;"><a class="button-small" title="create a mailing list" href="#">go »</a></div></div></div>
<div style="height:75px;">
<div style="float:left;"><img src="art/2.png"> </div><span style="float:left;line-height:50px;font-size:18px;">Create a campaign</span> <div style="float:right;width:51px;height:26px;background-color:#c1b798;line-height;10px;-moz-border-radius: 5px 5px 5px 5px;"><a class="button-small" title="create a mailing list" href="marketing_create_campaign.php">go »</a></div></div></div>

</td>


</tr>


</table>

</div>


















    


	
		</div>

	</div>


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
