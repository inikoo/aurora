<div id="bd" style="padding:20px 15px">
	<div id="departments" class="content">
		{$page->get_primary_content()}
	
	    {foreach from=$_departments item=department} 
	    <a href="page.php?id={$department.page_id}"> 
		<div class="block four department_showcase {if $department.col==1}first{/if}" style="margin-bottom:20px;position:relative">
			
			<div class="wraptocenter">
			<img src="{$department.img}"> 
			</div>
			<h3>{$department.name}</h3>
		</div>
		</a>
		{/foreach} 
	</div>
	<div style="clear:both">
	</div>
</div>
