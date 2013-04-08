{include file='header.tpl'} 
<div id="bd">
	<div id="content">
		{if $type=='bug'} 
		<h1>
			{t}Report Problem{/t} 
		</h1>
		{else} 
		<h1>
			{t}Request Feature{/t} 
		</h1>
		{/if} 
	
		
		<div style="float:left;border:1px solid #ccc;margin-top:20px;padding:10px 20px 20px 20px;margin-right:40px;width:420px">
				<h2>
					{t}Issue Tracking{/t} / {t}Wish List{/t} 
				</h2>
				<p style="margin-top:10px">
					<a href="https://app.asana.com" target="blank"><span class="state_details" ">{t}Track the progress of ongoing issues or request a new feature with Asana{/t}</span></a> 
				</p>
				
				<p>
				<a href="https://app.asana.com">https://app.asana.com</a>
				</p>
				
				<p>
					{t}If you have not been invited yet let's us know{/t}: <a href="mailto:mail@inikoo.com">mail@inikoo.com</a>
				</p>
				
				<a href="https://app.asana.com" target="blank"> <img style="width:300px" src="art/asana.jpg"  alt="asana"> </a> 
			</div>
			<div style="float:left;margin-top:20px;width:300px;border:1px solid #ccc;padding:10px 20px 0px 20px">
			
				<h2>
					{t}Help Line{/t}: <span style="color:#ff6600">+44 7984903265</span> 
				</h2>
				<p style="font-size:70%">
					{t}Line open{/t}: 9am-9pm GMT, {t}Mon-Fri{/t}<br> {t}System Down Emergency{/t} 24/7 
				</p>
			</div>
			
		
		
		{*}
		<div id="message_error" style="padding:10px;">
		</div>
		<div id="send_from">
			<table class="edit">
				<input type="hidden" id="metadata" value="{$metadata}" />
				<input type="hidden" id="type" value="{$type}" />
				<tr>
					<td class="label">{t}Summary{/t}:</td>
					<td> 
					<input id="summary" type="text" style="width:400px" />
					</td>
				</tr>
				<tr>
					<td class="label">{t}Description{/t}:</td>
					<td><textarea id="description" rows="10" style="width:400px"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:right"> <span style="display:none" id="sending"><img src="art/loading.gif" /> {t}Sending{/t}</span> 
					<div class="buttons">
						<button id="send" class="positive">{t}Send{/t}</button> <button id="cancel" class="negative" onclick="window.history.back()" return_url="{$return_url}">{t}Cancel{/t}</button> 
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
					<td colspan="2" style="text-align:right"> 
					<div class="buttons">
						<button id="close" onclick="window.history.back()" return_url="{$return_url}">{t}Close{/t}</button> <button id="another" onclick="window.location.reload()" style="margin-left:10px">{if $type=='bug'}{t}Report another problem{/t}{else}{t}Request other feature{/t}{/if}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		{*}
	</div>
</div>
{include file='footer.tpl'} 