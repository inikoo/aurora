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
</head>
<body  class="{$theme}">

<div id="custom-doc">

 <div id="loginbd" >
<h1>{t}Welcome{/t}</h1>

<form name="loginform" id="loginform" method="post"   autocomplete="off" action="index.php">
<table style="margin:60px auto;" >
            <tr>
                <td>{t}User{/t}:</td>
                <td style="width:10em" ><input type="text"  class="text"  id="_login_" name="_login_" maxlength="80"  value="" /></td><td>
            </tr>
             <tr  >
                <td>{t}Password{/t}:</td>
                <td><input type="password"  class="password" id="_passwd_"  name="_passwd_" maxlength="80" value="" /></td>

            </tr>
                <td colspan="2">
                    <div style="text-align:center">
                        <button id="login_go"   >{t}Log in{/t}</button>
                        <input type="hidden" name="_lang" value="{$lang_id}" />
			<input type="hidden" id="ep" name="ep" value="{$st}" />
                    </div>
                </td>
            </tr>
</table>

</form>
<div id="other_langs">
{foreach from=$other_langs item=i key=k}
    <a class="choose_lang"  href="index.php?_lang={$k}">{$i}</a>
{/foreach}
</div>
</div> 
{*JFA*}
<div id="mensaje">
<FORM method=post action="cgi-bin/script.pl">
<TABLE frame="border">
<TR>
	<TD>Mensajes</TD>
	<TD>
	<TEXTAREA name="comentarios" cols="50" rows="1">
Esto es algo no esperado
Por lo cual se requiere 
        </TEXTAREA>
	</TD>
</TR>
</TABLE>
</FORM>
</div>


