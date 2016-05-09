<div class="subject_profile">
	<div id="contact_data">
		<div class="data_container">
			<div class="data_field  {if $agent->get('Agent Company Name')==''}hide{/if}">
				<i title="{t}Company name{/t}" class="fa fa-building-o"></i> <span class="Agent_Name">{$agent->get('Agent Name')}</span> 
			</div>
			<div class="data_field {if $agent->get('Agent Main Contact Name')==''}hide{/if}">
				<i title="{t}Contact name{/t}" class="fa fa-user"></i> <span class="Agent_Main_Contact_Name">{$agent->get('Agent Main Contact Name')}</span> 
			</div>
			<div class="data_container" style=";margin-top:10px">
			<div style="min-height:80px;float:left;width:28px;">
				<i class="fa fa-map-marker"></i> 
			</div>
			<div style="float:left;width:272px" class="Agent_Contact_Address">
				{$agent->get('Contact Address')} 
			</div>
		</div>
		</div>
		<div class="data_container">
			<div id="Agent_Main_Plain_Email_display" class="data_field   {if !$agent->get('Agent Main Plain Email')}hide{/if}">
				<i class="fa fa-fw fa-at"></i> <span id="Agent_Other_Email_mailto">{if $agent->get('Agent Main Plain Email')}{mailto address=$agent->get('Main Plain Email')}{/if}</span> 
			</div>
			{foreach $agent->get_other_emails_data() key=other_email_key item=other_email}
			<div id="Agent_Other_Email_{$other_email_key}_display" class="data_field ">
				<i  class="fa fa-fw fa-at discreet"></i> <span id="Agent_Other_Email_{$other_email_key}_mailto">{mailto address=$other_email.email}</span> 
			</div>
			{/foreach}
			<div id="Agent_Other_Email_display" class="data_field hide">
				<i  class="fa fa-fw fa-at discreet"></i> <span class="Agent_Other_Email_mailto"></span> 
			</div>
			<span id="display_telephones"></span> {if $agent->get('Agent Preferred Contact Number')=='Mobile'} 
			<div id="Agent_Main_Plain_Mobile_display" class="data_field {if !$agent->get('Agent Main Plain Mobile')}hide{/if}">
				<i class="fa fa-fw fa-mobile"></i> <span class="Agent_Main_Plain_Mobile">{$agent->get('Main Plain Mobile')}</span> 
			</div>
			<div id="Agent_Main_Plain_Telephone_display" class="data_field {if !$agent->get('Agent Main Plain Telephone')}hide{/if}">
				<i class="fa fa-fw fa-phone"></i> <span class="Agent_Main_Plain_Telephone">{$agent->get('Main Plain Telephone')}</span> 
			</div>
			{else} 
			<div id="Agent_Main_Plain_Telephone_display" class="data_field {if !$agent->get('Agent Main Plain Telephone')}hide{/if}">
				<i title="Telephone" class="fa fa-fw fa-phone"></i> <span class="Agent_Main_Plain_Telephone">{$agent->get('Main Plain Telephone')}</span> 
			</div>
			<div id="Agent_Main_Plain_Mobile_display" class="data_field {if !$agent->get('Agent Main Plain Mobile')}hide{/if}">
				<i title="Mobile" class="fa fa-fw fa-mobile"></i> <span class="Agent_Main_Plain_Mobile">{$agent->get('Main Plain Mobile')}</span> 
			</div>
			{/if} 
			<div id="Agent_Main_Plain_FAX_display" class="data_field {if !$agent->get('Agent Main Plain FAX')}hide{/if}">
				<i title="Fax" class="fa fa-fw fa-fax"></i> <span>{$agent->get('Main Plain FAX')}</span> 
			</div>
			
			{foreach $agent->get_other_telephones_data() key=other_telephone_key item=other_telephone}
			<div id="Agent_Other_Telephone_{$other_telephone_key}_display" class="data_field ">
				<i  class="fa fa-fw fa-phone discreet"></i> <span>{$other_telephone.formatted_telephone}</span> 
			</div>
			{/foreach}
			<div id="Agent_Other_Telephone_display" class="data_field hide">
				<i  class="fa fa-fw fa-phone discreet"></i> <span></span> 
			</div>
			
		</div>
		<div style="clear:both">
		</div>
		
		<div class="data_container {if $agent->get('Sticky Note')==''}hide{/if} ">
			<div class="sticky_note_button">
				<i class="fa fa-sticky-note"></i> 
			</div>
			<div class="sticky_note">
				{$agent->get('Sticky Note')} 
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="info" >
		<div id="overviews">
			
			<table border="0" class="overview">
				
				<tr>
					<td>{t}Suppliers{/t}:</td>
					<td  class="aright">{$agent->get('Number Suppliers')}</td>
				</tr>
				<tr>
					<td>{t}Parts{/t}:</td>
					<td class="aright">{$agent->get('Number Parts')}</td>
				</tr>
				
				<tr>
					<td>{t}Delivery time{/t}:</td>
					<td>{$agent->get('Delivery Time')}</td>
				</tr>
				
				
			</table>
			 {if $agent->get('Agent Orders')>0} 
			<table class="overview">
				{if $agent->get('Agent Type by Activity')=='Lost'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Lost Agent{/t}</span></td>
				</tr>
				{/if} {if $agent->get('Agent Type by Activity')=='Losing'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Warning!, loosing agent{/t}</span></td>
				</tr>
				{/if} 
				<tr>
					<td class="text"> {if $agent->get('Agent Orders')==1} 
					<p>
						{$agent->get('Name')} {t}has place one order{/t}.
					</p>
					{elseif $agent->get('Agent Orders')>1 } {$agent->get('Name')} {if $agent->get('Agent Type by Activity')=='Lost'}{t}placed{/t}{else}{t}has placed{/t}{/if} <b>{$agent->get('Agent Orders')}</b> {if $agent->get('Agent Type by Activity')=='Lost'}{t}orders{/t}{else}{t}orders so far{/t}{/if}, {t}which amounts to a total of{/t} <b>{$agent->get('Net Balance')}</b> {t}plus tax{/t} ({t}an average of{/t} {$agent->get('Total Net Per Order')} {t}per order{/t}). {if $agent->get('Agent Orders Invoiced')}
					</p>
					<p>
						{if $agent->get('Agent Type by Activity')=='Lost'}{t}This agent used to place an order every{/t}{else}{t}This agent usually places an order every{/t}{/if} {$agent->get('Order Interval')}.{/if} {else} Agent has not place any order yet. {/if}
					</p>
					</td>
				</tr>
			</table>
			{/if} 
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>
<script>
function email_width_hack() {
    var email_length = $('#showcase_Agent_Main_Plain_Email').text().length

    if (email_length > 30) {
        $('#showcase_Agent_Main_Plain_Email').css("font-size", "90%");
    }
}

email_width_hack();

</script>