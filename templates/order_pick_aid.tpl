{include file='header.tpl'}
<div id="bd" >
     <div style="bdelivery_note:1px solid #ccc;text-align:left;padding:10px;margin: 30px 0 10px 0">

       <div style="bdelivery_note:0px solid #ddd;width:400px;float:left"> 
        <h1 style="padding:0 0 10px 0">{$delivery_note->get('Delivery Note Title')}</h1>
         <span style="color:#555;margin-left:10px">{t}Picking by{/t}: {$delivery_note->get('Delivery Note XHTML Pickers')}</span>
       </div>



 <div style="bdelivery_note:0px solid red;width:290px;float:right">
       {if $note}<div class="notes">{$note}</div>{/if}
<table bdelivery_note=0  style="bdelivery_note-top:1px solid #333;bdelivery_note-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right" >

<tr><td>{t}Creation Date{/t}:</td><td class="aright">{$delivery_note->get('Date Created')}</td></tr>

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
