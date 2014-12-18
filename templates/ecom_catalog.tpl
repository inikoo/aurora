<div id="bd" style="padding:20px 15px">
	<div id="departments" class="content">
		{$page->get_primary_content()}
	    {foreach from=$_departments item=department} 
		<div class="block four department_showcase {if $department.col==1}first{/if}" style="margin-bottom:20px;position:relative">
			<a href="department.php?code={$department.code}&parent={$page->get('Page Code')}"><img class="more_info" src="art/moreinfo_corner{$department.col}.png"> </a>
			<div class="wraptocenter">
			<img src="{$department.img}"> 
			</div>
			{$department.name}
		</div>
		{/foreach} 
	</div>
	<div style="clear:both">
	</div>
</div>
