<input type="hidden" id="dn_key" value="{$id}"/>
<div class="top_page_menu" style="padding:10px 20px 5px 20px">
<div class="buttons" style="float:left">
<button  onclick="window.location='profile.php?view=change_password'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Change Password{/t}</button>
<button  onclick="window.location='profile.php?view=address_book'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  class="selected" onclick="window.location='profile.php?view=orders'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button  onclick="window.location='profile.php?view=contact'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>
</div>
<div style="clear:both">
</div>
</div>



<div  class="buttons">
 <button  onclick="window.location='delivery_notes.pdf.php?id={$dn->id}'">PDF Delivery Note</button>
</div>

     <div style="border:1px solid #ccc;text-align:left;padding:10px;margin: 30px 0 10px 0">

       <div style="border:0px solid #ddd;width:350px;float:left"> 
         <h1 style="padding:0 0 0px 0">{t}Delivery Note{/t} {$dn->get('Delivery Note ID')}</h1>


         <h2 style="padding:0">{$dn->get('Delivery Note Customer Name')} ({$page->customer->get_formated_id()})</h2>
        <br/>

	 <div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000"><b>{$dn->get('Order Customer Contact Name')}</b></div>
	
	 
	<div style="clear:both"></div>
       </div>
       
       <div style="border:0px solid #ddd;width:290px;float:right">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	 	   <tr><td  class="aright" >{t}Estimated Weight{/t}</td><td width=100 class="aright">{$dn->get('Estimated Weight')}</td></tr>

	
	   
	 </table>
       </div>

       <div style="border:0px solid red;width:250px;float:right">

	 <table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right" >
	   
	   <tr><td>{t}Creation Date{/t}:</td><td class="aright">{$dn->get('Date Created')}</td></tr>
	   <tr style="display:none"><td>{t}Orders{/t}:</td><td class="aright">{$dn->get('Delivery Note XHTML Orders')}</td></tr>
	   {if $dn->get('Delivery Note XHTML Invoices')!=''}
	   <tr style="display:none"><td>{t}Invoices{/t}:</td><td class="aright">{$dn->get('Delivery Note XHTML Invoices')}</td></tr>
	    {/if}
	 </table>
	 
       </div>
       
       
       <div style="clear:both"></div>
     </div>



<h2>{t}Items{/t}</h2>
      <div  id="table0" class="dtable btable" style="margin-bottom:0"></div>

	    
