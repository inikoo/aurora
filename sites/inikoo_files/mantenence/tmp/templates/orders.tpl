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


<h2 class="client">{t}Orders{/t}</h2>

 {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
       <div  id="table2"   class="data_table_container dtable btable "> </div>





</div>


  

</div> 

<div>
{include file='footer.tpl'}

