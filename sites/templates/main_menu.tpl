<ul class="menu" id="menu">
<li><a href="index.php" class="menulink">{$traslated_labels.home}</a></li>
	<li><a href="#" class="menulink">{$traslated_labels.info}</a>
	<ul>
		  {foreach from=$info_pages item=page}
		  <li><a href="{$page.url}"  class="underline" >{$page.short_title}</a></li>
		  {/foreach}
		</ul>
	
	</li>
	<li>
		<a href="#" class="menulink">{$traslated_labels.catalogues}</a>
	
	<ul>
		  {foreach from=$departments item=department}
		  <li><a href="department.php?code={$department.code}"  class="underline" >{$department.name}</a></li>
		  {/foreach}
		</ul>
	
	
	</li>
	<li>
		<a href="#" class="menulink">{$traslated_labels.incentives}</a>
		<ul>
		  {foreach from=$incentive_pages item=page}
		  <li><a href="{$page.url}"  class="underline" >{$page.short_title}</a></li>
		  {/foreach}
		</ul>
		
		
	</li>
	<li>
		<a href="#" class="menulink">{$traslated_labels.inspiration}</a>
		<ul>
		  {foreach from=$inspiration_pages item=page}
		  <li><a href="{$page.url}"  class="underline" >{$page.short_title}</a></li>
		  {/foreach}
		  
		</ul>
	</li>
</ul>












	
<script type="text/javascript">
	var menu=new menu.dd("menu");
	menu.init("menu","menuhover");
</script>