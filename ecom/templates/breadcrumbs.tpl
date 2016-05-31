{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:31 May 2016 at 16:53:50 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<ul class="breadcrumb container">
{foreach from=$breadcrumbs name=breadcrumb item=breadcrumb }
	<li><a href="{$breadcrumb.reference}"><span>{if $breadcrumb.icon!=''}<i class="fa fa-{$breadcrumb.icon}"></i> {/if}{$breadcrumb.label}</a></li>
{/foreach}
</ul>
