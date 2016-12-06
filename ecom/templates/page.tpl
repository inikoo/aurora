{if !isset($_version_)} {assign '_version_' 1} {/if}
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='{$language}' xml:lang='{$language}' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$title}</title>
	{if {$page->get('Page Keywords')!=''}} 
	<meta name="keywords" content="{$page->get('Page Keywords')}"/> {/if} {if {$page->get('Page Store Description')!=''}} 
	<meta name="description" content="{$page->get('Page Store Description')}"/> {/if} 
	
	
	<link href="css/froala_style.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.min.css" rel="stylesheet"> 
	<link href="{$site->get_favicon_url()}" rel="shortcut icon" type="image/x-icon" />
	{if $css_files!=''}
	<link rel="stylesheet" href="min/f={$css_files}" type="text/css"/>
	{/if}
	{if $js_files!=''}
	<script async type="text/javascript" src="min/f={$js_files}"></script>
	{/if}
	{if isset($js_no_async_files) and $js_no_async_files!=''}
	<script  type="text/javascript" src="min/f={$js_no_async_files}"></script>
	{/if}
	{if isset($js_extra_file)}
	<script async type="text/javascript" src="{$js_extra_file}"></script>
	{/if}
	{if isset($js_no_async_extra_file) and $js_no_async_extra_file!=''}
	<script type="text/javascript" src="{$js_no_async_extra_file}"></script>
	{/if}
	
	<script type="text/javascript" src="js/jquery-2.2.1.js">
	
	
	{$page->get_head_includes()} 
	
	
	<style>
#related_products .product_showcase{ margin-left:18px;}

#related_products .product_showcase:first-child{ margin-left:5px;}
#related_products .product_showcase:nth-child(4n+1){ margin-left:5px;}

</style>
	
</head>
<body class="yui-skin-sam inikoo">
	
	<div id="doc4">
		<input type="hidden" id="site_key" value="{$_site['id']}" />
		<input type="hidden" id="page_key" value="{$_page['id']}" />
		<input type="hidden" id="request" value="{$request}" />
		<input type="hidden" id="selfurl" value="{$selfurl}" />
		<input type="hidden" id="checkout_order_button_url" value="{$checkout_order_button_url}" />
		<input type="hidden" id="checkout_order_list_url" value="{$checkout_order_list_url}" />
		<input type="hidden" id="site_locale" value="{$_site['locale']}" />
		<iframe id="basket_iframe" src="dummy.html" style="display:none"></iframe> 

		<div id="top_bar">
			{$page->display_top_bar()}
		</div>
		<div id="hd" style="padding:0;margin:0;z-index:3;">
			{include file="string:{$page->get_header_template()}" } 
		</div>
		
	
		{if $_version_==2}
		<div id="bd" style="z-index:1;">
		
		{include file="$type_content:$template_string"} 
		
		</div>
		{else}
		<div id="bd" style="z-index:1;">
		
		
			<div {if isset($user_template) } id="content" class="content" style="position:relative;height:{$page->get('Page Content Height')}px;overflow-x:hidden;overflow-y:auto;clear:both;width:100%;" {else}style="min-height:475px" {/if}>
				{include file="$type_content:$template_string"} 
			</div>
			{if $page->data['Number See Also Links']>0    } 
			<div id="bottom_see_also" style="margin:auto;padding:20px;margin-top:10px">
				<span style="font-weight:800;font-size:110%">{t}See also{/t}:</span> 
				<div style="margin-top:7px">
					{foreach from=$page->get_see_also() item=see_also name=foo} 
					<div style="height:220px;width:170px;float:left;text-align:center;{if !$smarty.foreach.foo.first}margin-left:20px{/if}">
						<div style="border:1px solid #ccc;height:170px;width:170px;;vertical-align:middle;text-align:center;display: table-cell;">
							<a href="http://{$see_also.see_also_url}"> <img src="{$see_also.see_also_image_url}" style="max-height:168px;max-width: 168px;overflow:hidden;" /> </a> 
						</div>
						<div style="font-size:90%;margin-top:5px">
							{$see_also.see_also_label}
						</div>
					</div>
					{/foreach} 
					<div style="clear:both">
					</div>
				</div>
			</div>
			{/if} 
			
			
		</div>
		{/if}
		<div id="ft" style="z-index:2" style="{if $page->get('Page Footer Type')=='None'}display:none{/if}">
		{include file="string:{$page->get_footer_template()}" }
		</div>
	</div>
	{$page->get_body_includes()} 
</body>
</html>