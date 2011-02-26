{include file='header.tpl'}
<div id="bd" >

 
<div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}campaign List{/t}</span>
	<div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid rgb(153, 153, 153);"></div>
         <span style="font-size:11px;">{$campaign_size} records<span>
     <div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid #4682b4;"></div>
      <table width="913">
           <tr style="border-bottom:1px #4682b4 solid;"><td class="campaign_header">Name</td><td class="campaign_header">Maximum Emails</td><td class="campaign_header">Campaign Objective</td><td class="campaign_header">Status</td>
           
	   </tr>

{section name=record loop=$campaign}
    <tr bgcolor="{cycle values="#eeeeee,#d0d0d0"}"> {* CHANGE HERE *}
    {foreach from=$campaign[record] item=entry}
       <td align='center'>{$entry}</td>
    {/foreach}
    </tr>
{/section} 


			
	
        
	   <tr><td></td>
		<td></td>
		<td></td>
		<td></td>
	  </tr>


	   

     </table>

 
  </div>






  
	
</form>

</div>


  

  
 

  
  {include file='footer.tpl'}
