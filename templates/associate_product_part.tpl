{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<input type='hidden' id="family_key" value="{$family->id}"> 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; <a href="department.php?id={$department->id}">{$department->get('Product Department Name')}</a> &rarr; {$family->get('Product Family Code')} ({t}Adding Product{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons">
			{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button style="margin-left:0px" onclick="window.location='edit_family.php?id={$family->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Cancel{/t}</button> <button style="display:none;margin-left:0px" onclick="window.location='associate_product_part.php?id={$family->id}'"><img src="art/icons/brick_add.png" alt="" /> {t}Associate Product{/t}</button> 
		</div>
		<div class="buttons" style="float:left">
			{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} <span class="main_title">{t}Adding product to family{/t}: <span id="title_name">{$family->get('Product Family Name')}</span> <span class="id" id="title_code">({$family->get('Product Family Code')})</span></span> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	
	<div class="search_box">
	</div>
	<div id="contact_messages_div">
		<span id="contact_messages"></span> 
	</div>
	<div>
		<div id="results" style="margin-top:0px;float:right;width:600px;">
		</div>
		<div style="float:left;width:900px;margin-top:20px">
			<table class="edit" border=0 style="width:100%;margin-bottom:0px">
				<input type="hidden" value="{$family->get('Product Family Store Key')}" id="store_key" />
				<input type="hidden" value="{$family->id}" id="family_key" />
				<input type="hidden" value="0" id="part_key" />
				<tr class="title">
					<td colspan="3">{t}Product Info{/t}</td>
				</tr>
				<tr style="display:none">
					<td style="width:210px" class="label">{t}Product Store{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="store_code" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="store_code_Container">
						</div>
					</div>
					</td>
					<td id="store_list" onclick="view_store_list(this)">{t}List{/t}</td>
					<td id="store_code_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style="width:210px" class="label">{t}Part{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="part_code" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="part_code_Container">
						</div>
					</div>
					</td>
					<td id="family_list">
					<div class="buttons small left">
						<button onclick="view_family_list(this)">{t}List{/t}</buttons>
					</div>
					<span id="part_code_msg" class="edit_td_alert"></span> </td>
				</tr>
				<tr>
					<td style="width:210px" class="label">{t}Product Code{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="product_code" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="product_code_Container">
						</div>
					</div>
					</td>
					<td id="product_code_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style="width:210px" class="label">{t}Product Name{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="product_name" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="product_name_Container">
						</div>
					</div>
					</td>
					<td id="product_name_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style="width:210px" class="label">{t}Product Units{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="product_units" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="product_units_Container">
						</div>
					</div>
					</td>
					<td id="product_units_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style="width:210px" class="label">{t}Product Price{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="product_price" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="product_price_Container">
						</div>
					</div>
					</td>
					<td id="product_price_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style="width:210px" class="label">{t}Product RRP{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="product_rrp" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="product_rrp_Container">
						</div>
					</div>
					</td>
					<td id="product_rrp_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style="width:210px" class="label">{t}Product Weight{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="product_weight" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="product_weight_Container">
						</div>
					</div>
					</td>
					<td id="product_weight_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style="width:210px" class="label">{t}Product Special Characteristics{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="special_characteristics" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="special_characteristics_Container">
						</div>
					</div>
					</td>
					<td id="special_characteristics_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style="width:210px" class="label">{t}Product Description{/t}:</td>
					<td style="width:370px"> 
					<div>
						<input style="width:100%" id="product_description" changed="0" type='text' class='text' value="" />
						<div id="product_description_Container">
						</div>
					</div>
					</td>
					<td id="product_description_msg" class="edit_td_alert"></td>
				</tr>
				<tr style="height:10px">
					<td colspan="3"></td>
				</tr>
				<tr>
					<td colspan="2"> 
					<div class="buttons">
						<button style="margin-right:10px;visibility:" id="save_new_product" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px;visibility:" id="reset_new_product"  onclick="window.location='edit_family.php?id={$family->id}'" class="negative">{t}Cancel{/t}</button> 
					</div>
					</td>
					<td></td>
				</tr>
			</tr>
			<tr style="height:10px">
				<td colspan="3" id="new_product_dialog_msg"></td>
			</tr>
			<tr>
			</table>
		</div>
		<div style="clear:both;height:40px">
		</div>
	</div>
</div>
<div class="star_rating" id="star_rating_template" style="display:none">
	<img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" />
</div>
</div>
<div id="dialog_store_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Store List{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_family_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Part List{/t}</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table1" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'}