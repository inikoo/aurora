{include file='header.tpl'}
<div id="bd" >
	<div id="no_details_title" style="clear:right;{if $show_details}display:none;{/if}">
    <h1>{t}Users Handing{/t}</h1>
</div>

<div class="top_row">
<h2>{t}Administration Account{/t}</h2>
<table>
<tr>
    <th style="width:100px">{t}Handle{/t}</th>
    <th style="width:200px">{t}Login Count{/t}</th>
    <th style="width:200px">{t}Last Login{/t}</th>
    <th style="width:220px">{t}Failed Login Count{/t}</th>
    <th style="width:200px">{t}Last Failed Login{/t}</th>
</tr>
<tr>
    <td style="text-align:center"><a href='user.php?id={$root->id}'>{$root->get('User Handle')}</a></td>
    <td style="text-align:center">{$root->get('Login Count')}</td>
    <td>{$root->get('Last Login')}</td>
    <td style="text-align:center">{$root->get('Failed Login Count')}</td>
    <td >{$root->get('Last Failed Login')}</td>
 </tr>

</table>
</div>
<div id="staff_column" class="col">
<h2>{t}Staff{/t}</h2>
<table>
<tr><td>{t}Employees{/t}:</td><td>{$number_staff}</td><tr>
<tr><td><a href="users_staff.php">{t}Users{/t}</a>:</td><td><a href="users_staff.php">{$number_users.Staff}</a></td><tr>
</table>

</div>	
<div id="suppliers_column" class="col">
<h2>{t}Suppliers{/t}</h2>
<table>
<tr><td><a href="suppliers.php">{t}Suppliers{/t}:</a></td><td><a href="suppliers.php">{$number_suppliers}</a></td><tr>
<tr><td><a href="users_supplier.php">{t}Users{/t}</a>:</td><td><a href="users_supplier.php">{$number_users.Supplier}</a></td><tr>
</table>
</div>
<div id="customers_column" class="col" style="margin-right:0px">
<h2>{t}Customers{/t}</h2>
<table>
<tr><td>{t}Online Stores{/t}:</td><td><a href="stores.php">{$number_stores}</a></td><tr>
<tr><td><a href="users_customer.php">{t}Users{/t}</a>:</td><td><a href="users_customer.php">{$number_users.Customer}</a></td><tr>
</table>
{if $number_stores>1}
<table>
<tr><td style="width:90px">{t}Store{/t}</td><td class="aright" style="width:70px">{t}Customers{/t}</td><td class="aright" style="width:70px">{t}Users{/t}</td><tr>
{foreach from=$stores item=store}
<tr><td>{$store->get('Store Code')}</td><td class="aright">{$store->get('Total Customer Contacts')}</td><td class="aright">{$store->get('Total Users')}</td><tr>
{/foreach}

</table>
{/if}
</div>
</div>
{include file='footer.tpl'}
