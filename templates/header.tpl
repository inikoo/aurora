<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$title}</title>
	<link href="art/inikoo_logo.png" rel="shortcut icon" type="image/x-icon" />
	{foreach from=$css_files item=i } 
	<link rel="stylesheet" href="{$i}" type="text/css" />{/foreach} 
	<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />

	{foreach from=$js_files item=i }<script type="text/javascript" src="{$i}"></script>
	{/foreach} 
</head>
<body class=" yui-skin-sam inikoo">
<input type="hidden" id="locale" value="{$locale}"> 
<input type="hidden" id="parent_menu_id" value="{$parent}"> 

{if $analyticstracking}{include file='analyticstracking.tpl'}{/if} 


