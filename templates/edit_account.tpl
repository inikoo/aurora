{include file='header.tpl'} 
<div id="bd">
	<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; &#9733; {t}Account{/t} ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons left" style="float:left">
			<span class="main_title">{t}Editing Account{/t}</span> 
		</div>
		<div class="buttons" style="float:right">
			<button style="margin-left:0px" onclick="window.location='account.php'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul">
		<li> <span class="item {if $block_view=='description'}selected{/if}" id="description"> <span> {t}Custom Fields{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div id="edit_messages">
		</div>
		<div class="edit_block" style="margin:0;padding:0 0px;{if $block_view!='description'}display:none{/if}" id="d_description">
		<div class="buttons small" >
		<button>New Custom field</button>
		</div>
		<div style="clear:both">
			<span class="clean_table_title">{t}Custom fields list{/t}</span> 
				
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable">
				</div>
		</div>
		</div>

	</div>
</div>

{include file='footer.tpl'} 