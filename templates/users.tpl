{include file='header.tpl'}
<div id="bd" >
{include file='users_navigation.tpl'}
<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  {t}Users{/t}</span>
</div>

<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">

<div class="buttons" style="float:right">



<button  onclick="window.location='preferences.php'" ><img src="art/icons/cog.png" alt=""> {t}Preferences{/t}</button>




</div>


<div class="buttons" style="float:left">

<button  onclick="window.location='users_customer.php'" ><img src="art/icons/page_world.png" alt=""> {t}Website Users{/t}</button>
<button  onclick="window.location='users_supplier.php'" ><img src="art/icons/lorry.png" alt=""> {t}Supplier Users{/t}</button>
<button  onclick="window.location='users_staff.php'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Inikoo Users{/t}</button>



</div>


<div style="clear:both"></div>
</div>



	
    <h1>{t}Users Handing{/t}</h1>


<div class="top_row">

<table>
<tr>
<td colspan="5"><h2>{t}Administration Account{/t}</h2></td>
</tr>

<tr>
    <th style="width:100px">{t}Handle{/t}</th>
    <th style="width:200px">{t}Login Count{/t}</th>
    <th style="width:200px">{t}Last Login{/t}</th>
    <th style="width:220px">{t}Failed Login Count{/t}</th>
    <th style="width:200px">{t}Last Failed Login{/t}</th>
</tr>
<tr>
    <td style="text-align:center"><a href='admin_user.php'>{$root->get('User Handle')}</a></td>
    <td style="text-align:center">{$root->get('Login Count')}</td>
    <td>{$root->get('Last Login')}</td>
    <td style="text-align:center">{$root->get('Failed Login Count')}</td>
    <td >{$root->get('Last Failed Login')}</td>
 </tr>
{if isset($warehouse_user)}
<tr>
<td colspan="5"><h2>{t}Warehouse Account{/t}</h2></td>
</tr>

<tr>
    <th style="width:100px">{t}Handle{/t}</th>
    <th style="width:200px">{t}Login Count{/t}</th>
    <th style="width:200px">{t}Last Login{/t}</th>
    <th style="width:220px">{t}Failed Login Count{/t}</th>
    <th style="width:200px">{t}Last Failed Login{/t}</th>
</tr>
<tr>
    <td style="text-align:center"><a href='warehouse_user.php'>{$warehouse_user->get('User Handle')}</a></td>
    <td style="text-align:center">{$warehouse_user->get('Login Count')}</td>
    <td>{$warehouse_user->get('Last Login')}</td>
    <td style="text-align:center">{$warehouse_user->get('Failed Login Count')}</td>
    <td >{$warehouse_user->get('Last Failed Login')}</td>
 </tr>
{/if}

</table>
</div>
<div id="staff_column" class="col">
<h2>{t}Staff{/t}</h2>
<table>
<tr><td>{t}Employees{/t}:</td><td>{$number_staff}</td><tr>
<tr><td>{t}Users{/t}:</td><td><a style="text-decoration:none" href="users_staff.php">{$number_users.Staff} <img style="vertical-align:middle;" src="art/link.gif" alt=""/></a></td><tr>
</table>

</div>	
<div id="suppliers_column" class="col">
<h2>{t}Suppliers{/t}</h2>
<table>
<tr><td><a href="suppliers.php">{t}Suppliers{/t}</a>:</td><td>{$number_suppliers}</td><tr>
<tr><td>{t}Users{/t}:</td><td><a style="text-decoration:none" href="users_supplier.php">{$number_users.Supplier} <img style="vertical-align:middle;" src="art/link.gif" alt=""/></a></td><tr>
</table>
</div>
<div id="customers_column" class="col" style="margin-right:0px">
<h2>{t}Website users{/t}</h2>
<table>
<tr><td>{t}Online sites{/t}:</td><td><a href="sites.php">{$number_sites}</a></td><tr>
<tr><td>{t}Users{/t}:</td><td>{$number_users.Customer}</td><tr>
</table>
{if $number_sites>1}
<table>
<tr><td style="width:90px">{t}Site{/t}</td><td class="aright" style="width:70px">{t}Users{/t}</td><tr>
{foreach from=$sites item=site}
<tr><td>{$site->get('Site Code')}</td><td class="aright"><a href="users_site.php?site_key={$site->id}">{$site->get('Total Users')}</a></td><tr>
{/foreach}

</table>
{/if}
	
</div>




</div>

{include file='footer.tpl'}
