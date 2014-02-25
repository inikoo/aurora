<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='{$language}' xml:lang='{$language}' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$title}</title>
	{if {$page->get('Page Keywords')!=''}} 
	<meta name="keywords" content="{$page->get('Page Keywords')}"> {/if} {if {$page->get('Page Store Resume')!=''}} 
	<meta name="description" content="{$page->get('Page Store Resume')}"> {/if} 
	<link href="{$site->get_favicon_url()}" rel="shortcut icon" type="image/x-icon" />
	{foreach from=$css_files item=i }<link rel="stylesheet" href="{$i}" type="text/css" />
	{/foreach} 
	{foreach from=$js_files item=i }<script type="text/javascript" src="{$i}"></script> 
	{/foreach} 
	<style type="text/css">{$page->get_css()}</style> <script type="text/javascript">{$page->get_javascript()}</script> 
{if $site->get('Site Search Method')=='Custome'}
	<link rel="stylesheet" href="public_search.css.php?id={$site->id}" type="text/css" />
	<script type="text/javascript" src="public_search.js.php?id={$site->id}"></script> 

{else}
	<link rel="stylesheet" href="css/bar_search.css" type="text/css" />
	<script type="text/javascript" src="js/bar_search.js"></script> 
{/if}


	<link rel="stylesheet" href="public_menu.css.php?id={$site->id}" type="text/css" />
<script type="text/javascript" src="public_menu.js.php?id={$site->id}"></script> 
{$page->get_head_includes()} 
</head>
<body class="yui-skin-sam inikoo">
{$page->get_body_includes()} 
<div id="doc4">
	<div id="hd" style="padding:0;margin:0;z-index:3">
		{include file="string:{$page->get_header_template()}" } 
	</div>
	<div id="bd" style="z-index:1;">
		<div {if $type_content=='string'} id="content" class="content" style="position:relative;height:{$page->get('Page Content Height')}px;overflow-x:hidden;overflow-y:auto;clear:both;width:100%;" {else}style="min-height:475px" {/if}>
			{include file="$type_content:$template_string"} 
			
		
			
		</div>
			{if $page->data['Number See Also Links']>0}
			<div id="bottom_see_also" style="margin:auto;padding:20px;margin-top:10px">
			<span style="font-weight:800;font-size:110%">{t}See also{/t}:</span>
			<div style="margin-top:7px">
				{foreach from=$page->get_see_also() item=see_also name=foo}
				
				<div style="height:220px;width:170px;float:left;text-align:center;{if !$smarty.foreach.foo.first}margin-left:20px{/if}">
					<div style="border:1px solid #ccc;height:170px;width:170px;;vertical-align:middle;text-align:center;display: table-cell;">
					<a href="http://{$see_also.see_also_url}">
					<img src="{if $see_also.see_also_image_key}public_image.php?size=small&id={$see_also.see_also_image_key}{else}art/nopic.png{/if}" style="max-height:168px;max-width: 168px;overflow:hidden;"/>
					</a>
					</div>
					<div style="font-size:90%;margin-top:5px">
					{$see_also.see_also_label}
					</div>
					</div>
				{/foreach}
				<div style="clear:both"></div>
		</div>
		</div>
			{/if}
	</div>
	<div id="ft" style="z-index:2" style="{if $page->get('Page Footer Type')=='None'}display:none{/if}">

		{include file="string:{$page->get_footer_template()}" } 
	</div>
</div>
</body>
</html>
