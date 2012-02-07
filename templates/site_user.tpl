{include file='header.tpl'} 
<div id="bd" style="padding:0">
	<div style="padding:0 20px">
		{include file='users_navigation.tpl'} 
		<input id="user_key" value="{$site_user->id}" type="hidden" />
		<input id="site_key" value="{$site->id}" type="hidden" />
		<input id="store_key" value="{$site->get('Site Store Key')}" type="hidden" />
		<input id="customer_id" value="{$site_user->get('User Parent Key')}" type="hidden" />

		<input id="forgot_password_handle" value="{$site_user->get('User Handle')}" type="hidden" />
		<div class="branch">
			<span><a href="users.php">{t}Users{/t}</a> &rarr; <a href="users_site.php?site_key={$site->id}">{t}Site Users{/t} ({$site->get('Site Code')})</a> &rarr; {$site_user->get('User Handle')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if $modify} <button style="display:none" onclick="window.location='edit_site_user.php'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit User{/t}</button> {/if} 
			</div>
			<div class="buttons" style="float:left">
				<button onclick="window.location='users.php'"><img src="art/icons/house.png" alt=""> {t}Users Home{/t}</button> 
			</div>

			<div class="buttons" style="float:right">
				<button onclick="forget_password(this, '{$site_user->get('User Handle')}')"><img src="art/icons/house.png" alt=""> {t}Forgot Password{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<h1>
			{t}Site User{/t}: {$site_user->get('User Handle')} 
		</h1>
		<div style="clear:both">
		</div>
		<div style="width:270px;margin-top:0px;float:left">
			<table class="show_info_product">
				<td class="aright"> 
				<tr>
					<td>{t}Site{/t}:</td>
					<td><a href="site.php?id={$site->id}">{$site->get('Site Code')}</a> (<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a>)</td>
				</tr>
				<tr>
					<td>{t}Customer{/t}:</td>
					<td><a href="customer.php?id={$customer->id}">{$customer->get('Customer Name')}</a></td>
				</tr>
			</table>
		</div>
		<div style="width:310px;margin-top:0px;float:left;margin-left:20px">
			<table class="show_info_product">
				<td class="aright"> 
				<tr>
					<td>{t}Login Count{/t}:</td>
					<td>{$site_user->get('Login Count')}</td>
				</tr>
				<tr>
					<td>{t}Last Login{/t}:</td>
					<td>{$site_user->get('Last Login')}</td>
				</tr>
			</table>
		</div>
		<div style="width:310px;margin-top:0px;float:left;margin-left:20px">
			<table class="show_info_product">
				<td class="aright"> 
				<tr>
					<td>{t}Failed Login Count{/t}:</td>
					<td>{$site_user->get('Failed Login Count')}</td>
				</tr>
				<tr>
					<td>{t}Failed Last Login{/t}:</td>
					<td>{$site_user->get('Last Failed Login')}</td>
				</tr>
			</table>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
		<li> <span class="item {if $block_view=='login_history'}selected{/if}" id="login_history"> <span> {t}Login History{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div style="padding:0 20px">
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
		<div id="block_login_history" style="{if $block_view!='login_history'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Login History{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" class="data_table_container dtable btable ">
			</div>
		</div>
		<div id="block_access" style="{if $block_view!='access'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
	</div>
</div>
{include file='footer.tpl'} 