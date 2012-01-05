{include file='header.tpl'}
<div id="bd" >
<div id="table_type" class="table_type">
 <span  id="export_csv0" style="float:right;margin-left:20px"  class="table_type state_details" tipo="invoices" ><a href="delivery_notes.pdf.php?id={$dn->get('Delivery Note Key')}">PDF Delivery Note</a></span>
</div>


     <div style="border:1px solid #ccc;text-align:left;padding:10px;margin: 30px 0 10px 0">

       <div style="border:0px solid #ddd;width:350px;float:left"> 
         <h1 style="padding:0 0 0px 0">{t}Delivery Note{/t} {$dn->get('Delivery Note ID')}</h1>
	 <h3 >{$dn->get('Delivery Note Title')}</h3>

         <h2 style="padding:0">{$dn->get('Delivery Note Customer Name')} (<a href="customer.php?id={$dn->get("Order Customer ID")}">{$customer->get_formated_id()}</a>)</h2>
         {$contact}<br/>
           {if $tel!=''}{t}Tel{/t}: {$tel}<br/>{/if}
	 <div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444"><span style="font-weight:500;color:#000"><b>{$dn->get('Order Customer Contact Name')}</b></div>
	
	 
	<div style="clear:both"></div>
       </div>
       
       <div style="border:0px solid #ddd;width:290px;float:right">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	 	   <tr><td  class="aright" >{t}Estimated Weight{/t}</td><td width=100 class="aright">{$dn->get('Estimated Weight')}</td></tr>

	
	   
	 </table>
       </div>

       <div style="border:0px solid red;width:250px;float:right">
	 {if $note}<div class="notes">{$note}</div>{/if}
	 <table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right" >
	   
	   <tr><td>{t}Creation Date{/t}:</td><td class="aright">{$dn->get('Date Created')}</td></tr>
	   <tr><td>{t}Orders{/t}:</td><td class="aright">{$dn->get('Delivery Note XHTML Orders')}</td></tr>
	   {if $dn->get('Delivery Note XHTML Invoices')!=''}
	   <tr><td>{t}Invoices{/t}:</td><td class="aright">{$dn->get('Delivery Note XHTML Invoices')}</td></tr>
	    {/if}
	 </table>
	 
       </div>
       
       
       <div style="clear:both"></div>
     </div>



<h2>{t}Items{/t}</h2>
      <div  id="table0" class="dtable btable" style="margin-bottom:0"></div>

	    
    </div>
{if $items_out_of_stock}
<div style="clear:both;margin:30px 0" >
<h2>{t}Items Out of Stock{/t}</h2>
<div  id="table1" class="dtable btable" style="margin-bottom:0"></div>
</div>
{/if}
  </div>
</div>
</div> 
{include file='footer.tpl'}
