<div id="bd" style="padding:20px 15px;clear:both">
	<div id="families" class="content">
		{$page->get_primary_content()}
	
	    {foreach from=$_families item=family} 
	    {*}<a href="family.php?code={$family.code}&parent={$page->get('Page Code')}">{*}
        <a href="page.php?id={$family.page_id}">

   		<div class="block four family_showcase {if $family.col==1}first{/if}" style="margin-bottom:20px;position:relative">
			<div class="wraptocenter">
			<img src="{$family.img}"/> 
			</div>
			<h2>{$family.code}</h2>
			<h3>{$family.name}</h3>
		</div>
		</a>
		{/foreach} 
	</div>
	<div style="clear:both">
	</div>
</div>
