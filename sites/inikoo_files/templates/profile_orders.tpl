<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />

<div class="top_page_menu" style="padding:0px 20px 5px 20px">
<div class="buttons" style="float:left">
<button  onclick="window.location='profile.php?view=change_password'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Change Password{/t}</button>
<button  onclick="window.location='profile.php?view=address_book'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  class="selected" onclick="window.location='profile.php?view=orders'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button  onclick="window.location='profile.php?view=contact'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>
</div>
<div style="clear:both">
</div>
</div>


       
<div id="orders_block" >

<input type="hidden" id="customer_key"  value="{$page->customer->id}"/>



     {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable "> </div>


</div>     




