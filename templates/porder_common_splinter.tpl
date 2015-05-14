<div id="edit_estimated_delivery_dialog" >
	<div class="bd" style="padding-bottom:0px;margin-top:20px">
		<table class="edit" style="width:100%" border=0>
		<tr><td id="estimated_delivery_msg" class="error"></td></tr>
			<tr>
				<td colspan="2"> <span>{t}Estimated Delivery{/t}:</span> 
				<input id="v_calpop_estimated_delivery" type="text" class="text" size="11" maxlength="10" name="from" value="{$po->get_estimated_delivery_date()}" />
				<img id="estimated_delivery_pop" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="choose" /> <br />
				</td>
			</tr>
			<tr class="space10">
				<td colspan="2" > 
				<div class="buttons">
				<button style="margin-left:50px" onclick="submit_edit_estimated_delivery(this)">Save</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>