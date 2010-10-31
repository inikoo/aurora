{include file='header.tpl'}
<div id="bd" >
	<div id="no_details_title" style="clear:right;{if $show_details}display:none;{/if}">
    <h1>{t}Users Handing{/t}</h1>
</div>

<div class="top_row">
<h2>{t}Administration Account{/t}</h2>
<table>
<tr><th>{t}Handle{/t}</th><th>{t}Login Count{/t}</th><th>{t}Last Login{/t}</th><th>{t}Failed Login Count{/t}</th><th>{t}Last Failed Login{/t}</th></tr>
<tr><th>{$root->data['User Handle']}</th><th>{t}Login Count{/t}</th><th>{t}Last Login{/t}</th><th>{t}Failed Login Count{/t}</th><th>{t}Last Failed Login{/t}</th></tr>

</table>
</div>
<div id="staff_column" class="col">
<h2>{t}Staff{/t}</h2>
<table>
<tr><td>{t}Employees{/t}:</td><td>{$number_staff}</td><tr>
<tr><td>{t}Users{/t}:</td><td>{$number_users_staff}</td><tr>
</table>

</div>	
<div id="suppliers_column" class="col">
<h2>{t}Suppliers{/t}</h2>
<table>
<tr><td>{t}Suppliers{/t}:</td><td><a href="suppliers.php">{$number_suppliers}</a></td><tr>
<tr><td>{t}Users{/t}:</td><td>{$number_users_suppliers}</td><tr>
</table>
</div>
<div id="customers_column" class="col" style="margin-right:0px">
<h2>{t}Customers{/t}</h2>
<table>
<tr><td>{t}Online Stores{/t}:</td><td><a href="stores.php">{$number_stores}</a></td><tr>
</table>
<table>
<tr><td style="width:90px">{t}Store{/t}</td><td class="aright" style="width:70px">{t}Customers{/t}</td><td class="aright" style="width:70px">{t}Users{/t}</td><tr>

{foreach from=$stores item=store}
<tr><td>{$store->get('Store Code')}</td><td class="aright">{$store->get('Total Customer Contacts')}</td><td class="aright">{$store->get('Total Users')}</td><tr>
{/foreach}
</table>
</div>
</div>
{include file='footer.tpl'}
