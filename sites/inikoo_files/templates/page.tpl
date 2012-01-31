<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$title}</title>
	<link href="art/inikoo-icon.png" rel="shortcut icon" type="image/x-icon" />
	{foreach from=$css_files item=i } 
	<link rel="stylesheet" href="{$i}" type="text/css" />
	{/foreach} {foreach from=$js_files item=i } <script type="text/javascript" src="{$i}"></script> {/foreach} <style type="text/css">{$page->get_css()}</style> <script type="text/javascript">{$page->get_javascript()}</script> 
	<link rel="stylesheet" href="public_search.css.php?id={$site->id}" type="text/css" />
	<link rel="stylesheet" href="public_menu.css.php?id={$site->id}" type="text/css" />
<script type="text/javascript" src="public_search.js.php?id={$site->id}"></script> <script type="text/javascript" src="public_menu.js.php?id={$site->id}"></script> 
</head>
<body class="yui-skin-sam inikoo">
<div id="doc4">
	<div id="hd" style="padding:0;margin:0;z-index:3">
		{include file="string:{$page->get_header_template()}" } 
	</div>
	<div id="bd" style="z-index:1;">
		<div {if $type_content=='string'}id=&quot;content&quot;" class="content" style="position:relative;height:{$page->get('Page Content Height')}px;overflow-x:hidden;overflow-y:auto;clear:both;width:100%;" {else}style="min-height:475px" {/if}>
			{include file="$type_content:$template_string"} 
		</div>
	</div>
	<div id="ft" style="z-index:2">
		{include file="string:{$page->get_footer_template()}" } 
	</div>
</div>
</body>
</html>
