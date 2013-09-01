{include file='header.tpl'}
<div id="bd" >

<input type="hidden" id="company_department_key" value="$company_department->id"/>

<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="hr.php">{$account_label}</a> &rarr; {t}Department{/t}: {$company_department->get('Company Department Code')}</span> 
		</div>
		<div class="top_page_menu" style="margin-top:10px">
			<div class="buttons" style="float:right">
				{if $modify} <button onclick="window.location='edit_hr.php'"><img src="art/icons/cog.png" alt=""> {t}Edit Company Department{/t}</button>
			 {/if} 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Company Department{/t}: {$company_department->get('Company Department Name')} [{$company_department->get('Company Department Code')}]</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>


	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='staff'}selected{/if}" id="staff"> <span> {t}Employees{/t}</span></span></li>
		<li> <span class="item {if $block_view=='positions'}selected{/if}" id="positions"> <span> {t}Positions{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
 
<div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}Departments List{/t}</span>
  
  
 <div class="table_top_bar space">
						</div>
						{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
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
