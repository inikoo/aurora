<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="customer_key"  value="{$page->customer->id}"/>
<input type="hidden" id="prefix"  value="{$address_identifier}"/>

<div class="top_page_menu" style="padding:0px 20px 5px 20px">
<div class="buttons" style="float:left">
<button  onclick="window.location='profile.php?view=change_password'" ><img src="art/icons/cog_edit.png" alt=""> {t}Change Password{/t}</button>
<button  class="selected"  onclick="window.location='profile.php?view=address_book'" ><img src="art/icons/book_addresses.png" alt=""> {t}Address Book{/t}</button>
<button  onclick="window.location='profile.php?view=orders'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button  onclick="window.location='profile.php?view=contact'" ><img src="art/icons/user.png" alt=""> {t}My Account{/t}</button>
</div>


<div style="clear:both">
</div>
</div>


       
<div id="add_address_block" >
    <div class="buttons" style="float:right">
            <button onClick="window.location='profile.php?view=address_book'" ><img src="art/icons/door_out.png" alt=""> {t}Exit{/t}</button>
    </div>
    <div style="clear:both"></div>

{include file='edit_address_splinter.tpl' close_if_reset=true address_identifier=$address_identifier address_type='Shop' show_tel=true show_contact=true  address_function='Shipping'  hide_type=true hide_description=true show_form=false  show_components=false }

</div>     



