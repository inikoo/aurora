{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {t}Account{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
					<button style="{if !$user->can_edit('account')}display:none{/if}" onclick="window.location='edit_account.php'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit{/t}</button>  

			</div>
			<div class="buttons" style="float:left">
				<span class="main_title" ><img src="art/icons/xhq.png" style="height:18px;position:relative;bottom:2px"/> <span style="position:relative;bottom:-1px">{t}Account{/t}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div style="padding:0px">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Overview{/t}</span></span></li>
			<li> <span class="item {if $block_view=='changelog'}selected{/if}" id="changelog"> <span> {t}Changelog{/t}</span></span></li>
		</ul>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
		</div>
	</div>
	<div style="padding:0 20px">
		<div id="block_details" style="clear:both;margin:10px 0 40px 0;{if $block_view!='details'}display:none{/if}"></div>
		<div id="block_changelog" style="clear:both;margin:20px 0 40px 0;{if $block_view!='changelog'}display:none{/if}">
		
				<span class="clean_table_title">{t}Changelog{/t}</span> 
				
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable">
				</div>
			
		</div>
	</div>
</div>
<div id="filtermenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'} 