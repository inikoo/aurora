<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html
	  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	  "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_code}">
  <head>
    <title>{$title}</title>
    <link href="art/kaktus-icon.png" rel="shortcut icon" type="image/x-icon" />
    {foreach from=$css_files item=i }
    <link rel="stylesheet" href="{$i}" type="text/css" />
    {/foreach}	
    {foreach from=$js_files item=i }
    <script type="text/javascript" src="{$i}"></script>
    {/foreach}
    <script type="text/javascript">{$script}</script>
        
  </head>
  <body  class=" yui-skin-sam kaktus">
    <div id="{$page_layout}" class="{$box_layout}">
      <div id="hd" >
  	        <div style="float:right;font-size:77%;margin:0px 20px 0.15em 0">
	            <span id="top_message" style=";margin-right:30px"></span>
	            <a style=";margin-right:15px">{t}Help{/t}</a>
	            <span id="language_flag"><img src="art/flags/{$lang_country_code}.gif" alt="{$lang_country_code}" align="absbottom"  /></span>
	            <span>{t}Hello{/t} {$user}</span>
	            <span><a style="margin-left:20px;" href="index.php?logout=1">{t}Logout{/t}</a></span>
	        </div>
	        <h1>{$my_name}</h1>
	        <div id="navsite" style="clear:right">
        	  <ul>
	            {foreach from=$nav_menu item=menu }
	            <li {if $menu[2]==$parent} class="selected"{/if}><a href="{$menu[1]}">{$menu[0]}</a></li>
	            {/foreach}
	          </ul>
        	</div> 
      </div> 
      
