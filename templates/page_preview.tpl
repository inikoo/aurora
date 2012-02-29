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
<input type="hidden" id="take_snapshot" value="{$take_snapshot}" />
<input type="hidden" id="update_heights" value="{$update_heights}" />
<div id="doc4">
	<div id="preview_hd" style="background:#245e86 url('art/themes/cobalt.jpg') bottom left repeat-x;color:#fff;;padding:3px 10px;height:22px;{if !$show_header}display:none{/if}">
		<input type="hidden" id="page_key" value="{$page->id}" />
		{if isset($prev)}<img style="cursor:pointer;vertical-align:text-top;height:20px;margin-right:5px" class="previous" onmouseover="this.src='art/previous_button_yellow.png'" onmouseout="this.src='art/previous_button_white.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button_white.png" alt="{t}Previous{/t}" />{/if} 
		<a href="store.php?id={$page->get('Page Store Key')}"><img style="height:20px;vertical-align:text-top;margin-right:10px" src="art/inikoo_logo_small.png" ></a> <span style="margin-right:10px;font-weight:800">{$page->get('Page Code')}</span>
		{$page->get('Page Short Title')} 
		<a href="http://{$page->get('Page URL')}">
			<img src="art/external_link.png" alt=""/>
		</a> {if isset($next)}<img style="cursor:pointer;vertical-align:text-top;height:20px;margin-left:10px;float:right" class="next" onmouseover="this.src='art/next_button_yellow.png'" onmouseout="this.src='art/next_button_white.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button_white.png" alt="{t}Next{/t}" />{/if} 
		<div style="width:400px;float:right" class="buttons small">
			<button id="show_logout_view" onclick="window.location='page_preview.php?id={$page->id}&logged=0'">{t}Show as logged out{/t}</button> <button id="show_login_view" onclick="window.location='page_preview.php?id={$page->id}&logged=1'">{t}Show as logged in{/t}</button> <button id="show_login_view" onclick="window.location='edit_page.php?id={$page->id}'">{t}Edit{/t}</button> <button id="show_login_view" onclick="window.location='page.php?id={$page->id}'">{t}Page{/t}</button> {if isset($referral)}<button onclick="window.location='{$referral}'">{t}Go Back{/t}</button>{/if} 
		</div>
		<div style="clear:both">
		</div>
	</div>

<iframe src="page_store.php?id={$page->id}&logged={$logged}&update_heights={$update_heights}&take_snapshot={$take_snapshot}" style="width:100%;height:{$page->get_page_height()}px" frameborder=0></iframe>
</div>
</body>
</html>
