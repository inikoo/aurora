
 <div class="dialog_inikoo" style="padding:20px">
<input type="hidden" id="query" value="{$query}" />
	<div class="search_results">
		{t}Searching for{/t} <span class="code">{$query}</span>. {$formated_number_results} 
		{if $did_you_mean!=''}
		<p style="margin-top:20px">{t}Did you mean{/t}: <i><a href="search.php?q={$did_you_mean}" class="code">{$did_you_mean}</a></i>?</p>
		{/if}
		
		
		{foreach from=$results item=result} 
		<div class="result" style="margin-bottom:20px;clear:both;margin-top:30px">
			
			<div style="height:125px;width:145px;float:left;text-align:center;;margin:0px 15px 0px 5px">
			<div style="height:125px;width:145px;border:1px solid #ccc;padding:0px;vertical-align:middle;text-align:center;display: table-cell;">
			{if $result.image!=''}<a href="{$result.url}"><img src="{$result.image}" style="max-height:110px;max-width: 130px;overflow:hidden;"></a>{/if}
			</div>
			</div>
			<div style="margin-left:140px">
				<h3 style="margin-bottom:2.5px">
					<a href="{$result.url}" class="result_title">{$result.title}</a>
				</h3>
				<p style="margin:0px">
					{$result.description}
				</p>
				<p style="margin:5px 0px">
					{$result.asset_description}
				</p>
			</div>
			<div style="clear:both">
			</div>
		</div>
		{/foreach} 
	</div>
</div>
