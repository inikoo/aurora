{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; &#8704; {t}Stores{/t} ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons left" style="float:left">
			<span class="main_title">{t}Editing Stores{/t}</span> 
		</div>
		<div class="buttons" style="float:right">
			<button style="margin-left:0px" onclick="window.location='stores.php'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button>  
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul">
		<li> <span class="item {if $block_view=='stores'}selected{/if}" id="stores"><span> {t}Stores{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div id="edit_messages">
		</div>
		<div class="edit_block" style="margin:0;padding:0 0px;{if $block_view!='stores'}display:none{/if}" id="d_stores">
			<span class="clean_table_title" style="margin-right:5px">{t}Store List{/t}</span>
			<div class="buttons small left">
			<button style="{if !$user->can_create('account')}display:none{/if}" onclick="window.location='new_store.php'"><img src="art/icons/add.png" alt=""> {t}New{/t}</button>
			</div>
			
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_delete_store" style="padding:10px 10px 10px 10px;">
	<h2 style="padding-top:0px">
		{t}Delete Store{/t} 
	</h2>
	<h2 style="padding-top:0px" id="dialog_delete_store_data">
	</h2>
	<input type="hidden" id="dialog_delete_store_key" value=""> 
	<input type="hidden" id="dialog_delete_store_table_id" value=""> 
	<input type="hidden" id="dialog_delete_store_recordIndex" value=""> 
	<p>
		{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div style="display:none" id="deleting">
		<img src="art/loading.gif" alt=""> {t}Deleting store, wait please{/t} 
	</div>
	<div id="delete_store_buttons" class="buttons">
		<button onclick="save_delete('delete','store')" class="positive">{t}Yes, delete it!{/t}</button> <button onclick="cancel_delete('delete','store')" class="negative">{t}No i dont want to delete it{/t}</button> 
	</div>
</div>
{include file='footer.tpl'} 