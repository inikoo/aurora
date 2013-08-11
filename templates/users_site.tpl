{include file='header.tpl'} 
<div id="bd">
	{include file='users_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; <a href="users.php">{t}Users{/t}</a> &rarr; {t}Staff Users{/t} </span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:right">
			{if $modify} <button onclick="window.location='edit_users_staff.php'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Users{/t}</button> {/if} 
		</div>
		<div class="buttons" style="float:left">
			<button onclick="window.location='users.php'"><img src="art/icons/house.png" alt=""> {t}Users Home{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<h1>
		{t}Site Users{/t} <span class="id">{$site->get('Site Name')}</span>
	</h1>
	<input type="hidden" id="site_key" value="{$site->id}"> 
	<div id="yui-main">
		<div class="data_table" style="margin-top:15px">
			<span class="clean_table_title">{t}Users{/t}</span> 
			  <div class="table_top_bar space"></div>

         {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 