{include file='header.tpl'}
<div id="bd" >



<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="hr.php">{t}Staff{/t}</a> &rarr; {t}Area{/t} ({$position->get('Company Position Code')}) &rarr; {t}Position{/t} ({$position->get('Company Position Code')})</span> 
		</div>
		<div class="top_page_menu" style="margin-top:10px">
			<div class="buttons" style="float:right">
				{if $modify} <button onclick="window.location='edit_hr.php'"><img src="art/icons/cog.png" alt=""> {t}Edit Company Position{/t}</button>
			 {/if} 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Company Position{/t}: {$position->get('Company Position Title')} [{$position->get('Company Position Code')}]</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>



 
<div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}Employees{/t}</span>
  
  
  <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
      <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable"> </div>
  </div>

</div>


  <div id="filtermenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
	{foreach from=$filter_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
	{/foreach}
      </ul>
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
