{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<<<<<<< HEAD:templates/marketing.tpl
<div style="padding:0 0px">
=======
<div style="padding:0 20px">

>>>>>>> 57f7fa59ca854a0a279b20794e67020ca4916f7f:templates/marketing.tpl

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
<<<<<<< HEAD:templates/marketing.tpl


=======
		<table width="974" height="670" border="0">
		<tr>
		<td width="274">
			<div id="middlebar">
			<ul class="menu">
			<li><a href="#" onclick="javascript:showlayer('sm_1')"> Create Campaign</a>
			
			</li>
			<ul class="submenu" id="sm_1">
			<li><a href="regular_campaign.php">Regular Campaign</a></li>
			<li><a href="plain_campaign.php">Plain Campaign</a></li>
			<li><a href="split_campaign.php">Split Campaign</a></li>
			<li><a href="rss_campaign.php">RSS Campaign</a></li>
			</ul>
			</ul>
			</div>
		</td> 
		<td width="700">
>>>>>>> 57f7fa59ca854a0a279b20794e67020ca4916f7f:templates/marketing.tpl

			<table border="0" width="700" height="670" cellspacing=0>
			<form action="" method="">				
			<tr height="270">
		<td style="line-height:40px;"> Getting started with e - marketing in Kaktus is not so difficult anymore .... <br>
					<img src="art/1.png" width="40" height="40"> Create List 
						<span style="padding-left:500px;"><input type="submit" name="list" value="Go"></span><br>
					<img src="art/2.png" width="40" height="40"> Create Campaign 
						<span style="padding-left:458px;"><input type="submit" name="campaign" value="Go"></span><br>
					<img src="art/3.png" width="40" height="40"> View Campaign Reports 
						<span style="padding-left:415px;"><input type="submit" name="report" value="Go"></span><br>
					</td>
				</tr>
			</form>
				<tr height="400">
					<td>Kaktus e - marketing Blog
    					<br>
					<div>Kaktus Redesign Coming</div>
					<div>Kaktus Redesign Coming</div>
					<div>Kaktus Redesign Coming</div>
					<div>Kaktus Redesign Coming</div>
					<div>Kaktus Redesign Coming</div>
					<div>Kaktus Redesign Coming</div>
					<div>Kaktus Redesign Coming</div>						
					</td>
				</tr>
			</table>		
		</td>
		</tr>
		</table>

<<<<<<< HEAD:templates/marketing.tpl














    
=======
>>>>>>> 57f7fa59ca854a0a279b20794e67020ca4916f7f:templates/marketing.tpl

 <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<<<<<<< HEAD:templates/marketing.tpl
	
		</div>

	</div>


</div>
</div>
=======
		
>>>>>>> 57f7fa59ca854a0a279b20794e67020ca4916f7f:templates/marketing.tpl

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
