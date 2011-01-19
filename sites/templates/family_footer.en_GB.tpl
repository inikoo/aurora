   <div id="footer">
	
	<div style="float:right" id="company_contact" >
	  {if $tel }<span>T {$tel}</span><br/>{/if}
	  {if $fax }<span>F {$fax}</span><br/>{/if}
	  {if $email }{mailto address="$email" encode="javascript"}{/if}
	</div>
	
	<div style="float:left" id="company_info" >
	  <span id="company_tax_number">{$tax_number}</span><br/>
	  <span id="company_name">{$company_name}</span><br/>
	  <span id="company_name">{$address}</span>
	  

	</div>
	<div style="width:400px;color:#f29416;margin:0px auto;padding-top:12px;font-size:9px;text-align:center">
	  Wholesale giftware supplier.<br/> Please note this is a wholesale site we supply wholesale to the trade.
	</div>
	
      </div>
