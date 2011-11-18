{include file='header.tpl'}

<div id="bd" style="padding:0px">
<div style="padding:0px 20px;">



<div class="top_page_menu">
<div class="buttons" style="float:left">
<button   onclick="window.location='client.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}Edit Profile{/t}</button>
<button  onclick="window.location='address_book.php'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  class="selected" onclick="window.location='orders.php'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button   onclick="window.location='profile.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>

</div>


<div style="clear:both"></div>
</div>


    <h2 class="client" style="text-align:left">{$customer->get('Customer Name')} <span style="color:SteelBlue">{$id}</span></h2> 


sdfsdfsdf

</div>


  

</div> 

<div>
{include file='footer.tpl'}

