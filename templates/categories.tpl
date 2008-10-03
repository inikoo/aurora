<link rel="stylesheet" href="cat.css" type="text/css" />


<ul>
{foreach from=$use key=myId item=i}
  <li class="cat{$i.deep}">{$i.name}</li>
{/foreach}
</ul>


