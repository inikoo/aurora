<div class="dialog_inikoo" >
{if isset($masterkey) and $masterkey}
<div style="border:1px solid #ccc;clear:both;padding:20px;margin-top:30px;width:400px">
<p></p>
<div class="buttons left">
<button onclick="window.location='login.php?masterkey={$masterkey}'">{t}Reset your password{/t}</button>
</div>
<div style="clear:both"></div>
</div>
{else}
<div style="border:1px solid #ccc;clear:both;padding:20px;margin-top:30px;width:400px">
<h2>{t}Sorry{/t}</h2>
<p>{t}We were not able to reset yout password, please try again or call us{/t}.</p>
		<table style="margin-top:15px;clear:left" >
			<tr class="link space">
				<td colspan="2">{t}Want to try to login again?{/t} <span class="link" id="show_login_dialog2" onclick='window.location="login.php"'>{t}Click Here{/t}</span></td>
			</tr>
			<tr id="tr_link_register_from_login" class="link">
				<td colspan="2">{t}First visit?{/t} <span class="link" onclick='window.location="registration.php"' id="link_register_from_login">{t}Register Here{/t}</span></td>
			</tr>
		</table>
</div>

{/if}

</div>