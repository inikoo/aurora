{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">





 
<div style="clear:left;margin:0 0px">
    <h1>{t}Marketing{/t}</h1>
</div>

</div>
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


 <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

		

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
