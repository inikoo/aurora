{include file='header.tpl'}
<div id="bd" >
<div id="content" >
{if $type=='bug'}
<h1>{t}Report Problem{/t}</h1>
{else}
<h1>{t}Request Feature{/t}</h1>
{/if}
<div id="send_from">
<div id="message_error" style="padding:10px;"></div>

<table class="edit">
<input type="hidden" id="metadata" value="{$metadata}"/>
<input type="hidden" id="email" value="{$email}"/>


<tr>
<td class="label">{t}Summary{/t}:</td><td><input id="summary" type="text" style="width:400px"/></td>
</tr>
<tr>
<td class="label">{t}Description{/t}:</td><td><textarea id="description" rows=10 style="width:400px"></textarea></td>
<tr>
<tr>
<td colspan=2 style="text-align:right"><button id="cancel" onClick="window.history.back()" return_url="{$return_url}" >{t}Cancel{/t}</button><button id="send" style="margin-left:10px">{t}Send{/t}</button>
</td>
</tr>
</table>
</div>
<div id="issue_send" style="display:none">
<div style="margin:15px 0">
{t}Thank you for submitting the issue{/t}
</div>
<table class="edit">
<tr>
<td>
<td  colspan=2 style="text-align:right"><button id="close" onClick="window.history.back()" return_url="{$return_url}">{t}Close{/t}</button><button id="another" onClick="window.location.reload()" style="margin-left:10px">{if $type=='bug'}{t}Report another problem{/t}{else}{t}Request other feature{/t}{/if}</button>
</td>
<tr>
</table>
</div>



</div>
</div>
{include file='footer.tpl'}
