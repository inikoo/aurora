{include file='header.tpl'}
<div id="bd" >
<div id="content" >
{if $type=='bug'}
<h1>{t}Report Problem{/t}</h1>
{else}
<h1>{t}Request Feature{/t}</h1>
{/if}

<div style="float:right;margin-top:20px;width:300px;" >
<div style="border:1px solid #ccc;padding:10px 20px 0px 20px">
<h2>{t}Help Line{/t}: <span style="color:#ff6600">+44 7984903265</span></h2>
<p style="font-size:70%">
{t}Line open{/t}: 9am-9pm GMT,  {t}Mon-Fri{/t}<br>
{t}System Down Emergency{/t} 24/7
</p>
</div>
<div style="border:1px solid #ccc;margin-top:20px;padding:10px 20px 20px 20px">
<h2>{t}Issue Tracking{/t}</h2>
<p style="margin-top:10px">
<a href="http://aw-kaktus.info:8080" target=blank><span class="state_details"">{t}Track the progress of ongoing issues{/t}</span></a>
</p>

<table style="font-size:90%;margin-bottom:10px">
<tr><td>{t}login{/t}:</td><td>kaktus_user<td/></tr>
<tr><td>{t}password{/t}:</td><td>public<td/></tr>
</table>

<a href="http://aw-kaktus.info:8080" target=blank>
<img style="border:1px solid #ccc" src="art/jira.png" alt="jira">
</a>

</div>
</div>

<div id="message_error" style="padding:10px;"></div>
<div id="send_from">
<table class="edit">
<input type="hidden" id="metadata" value="{$metadata}"/>
<input type="hidden" id="type" value="{$type}"/>


<tr>
<td class="label">{t}Summary{/t}:</td><td><input id="summary" type="text" style="width:400px"/></td>
</tr>
<tr>
<td class="label">{t}Description{/t}:</td><td><textarea id="description" rows=10 style="width:400px"></textarea></td>
<tr>
<tr>
<td colspan=2 style="text-align:right">

<span style="display:none" id="sending"><img src="art/loading.gif"/> {t}Sending{/t}</span>
<div class="buttons">
<button id="send" class="positive">{t}Send{/t}</button>
<button id="cancel"  class="negative" onClick="window.history.back()" return_url="{$return_url}" >{t}Cancel{/t}</button>

</div>
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
<td  colspan=2 style="text-align:right">
<div class="buttons">
<button id="close" onClick="window.history.back()" return_url="{$return_url}">{t}Close{/t}</button>
<button id="another" onClick="window.location.reload()" style="margin-left:10px">{if $type=='bug'}{t}Report another problem{/t}{else}{t}Request other feature{/t}{/if}</button>
</div>
</td>
<tr>
</table>
</div>



</div>
</div>
{include file='footer.tpl'}
