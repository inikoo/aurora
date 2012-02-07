<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="customer_key"  value="{$page->customer->id}"/>
<input type="hidden" id="prefix"  value="{$address_identifier}"/>

{include file='profile_header.tpl' select='address_book'} 


       
<div id="add_address_block" >
    <div class="buttons" style="float:right">
            <button onClick="window.location='profile.php?view=address_book'" ><img src="art/icons/door_out.png" alt=""> {t}Exit{/t}</button>
    </div>
    <div style="clear:both"></div>
       <div id="dialog_new_billing_address" style="width:540px;margin-top:10px;padding:10px 0 0 0 ;border:1px solid #ccc;display:''">
       <table id="new_billing_address_table" border=0 style="width:500px;margin:0 auto">
       {include file='edit_address_splinter.tpl' close_if_reset=true address_identifier=$address_identifier address_type='Shop' show_tel=true show_contact=true  address_function=$address_function  hide_type=true hide_description=true show_form=false  show_components=true }
     </table>
	</div>
</div>     



