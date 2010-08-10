{include file='header.tpl'}
<div id="bd" >
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import Data</h1>
  </div>

<div class="instructions">
<h2>Importing customers from a data file</h2>

<table>
<tr><td>Customer Name</td><td>Name of the shop or business. Can be blank in the case of personal contacts</td><tr>
<tr><td>Customer Fiscal Name</td><td>Used for invoicing</td><tr>
<tr><td>Customer Tax Number</td><td></td><tr>
<tr><td>Contact Name</td><td></td><tr>
<tr><td>Email</td><td>Email associated with the contact name, distinct customers con not share same emails</td><tr>
<tr><td>Telephone</td><td></td><tr>
<tr><td>Fax</td><td></td><tr>
<tr><td>Contact Address Line 1</td><td></td><tr>
<tr><td>Contact Address Line 2</td><td></td><tr>
<tr><td>Contact Address Line 3</td><td></td><tr>
<tr><td>Contact City</td><td></td><tr>
<tr><td>Contact Region</td><td></td><tr>
<tr><td>Contact Postal Code</td><td></td><tr>
<tr><td>Contact Country</td><td></td><tr>


</table>
</div>

<div>
<table class="edit">
<tr class="top"><td class="label">{t}Data file{/t}:</td><td></td></tr>
</table>
</div>



</div>

{include file='footer.tpl'}
