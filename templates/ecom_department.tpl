<div id="bd" style="padding:20px 15px;clear:both">
	<div id="families" class="content">
		{if $department->data['Product Department Description']!=''} 
		<div class="description_block">
			<img id="main_image" class="image" src="{$department->get('Product Department Main Image')}" /> 
			<div class="content">
				<h1>
					{$department->get('Product Department Code')}
				</h1>
				<h2>
					{$department->get('Product Department Name')}
				</h2>
				<div class="description">
					{$department->get('Product Department Description')} 
				</div>
			</div>
			<div style="clear:both">
			</div>
		</div>
		{/if} {foreach from=$_families item=family} 
		<a href="page.php?id={$family.page_id}"> 
		<div class="block four family_showcase {if $family.col==1}first{/if}" style="margin-bottom:20px;position:relative">
			<div class="wraptocenter">
				<img src="{$family.img}" /> 
			</div>
			<h2>
				{$family.code}
			</h2>
			<h3>
				{$family.name}
			</h3>
		</div>
		</a> {/foreach} 
	</div>
	<div style="clear:both">
	</div>
</div>
