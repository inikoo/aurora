{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
     <div class="yui-b" style="text-align:right;padding-top:10px">

    	   <table class="but"  id="buts">
	     <tr><td id="add_contact">{t}+ Mail Address{/t}</td></tr>
	     <tr><td id="add_contact">{t}+ Telephone{/t}</td></tr>
	     <tr><td id="add_contact">{t}+ Email{/t}</td></tr>
	     <tr><td id="add_contact">{t}+ Emessages{/t}</td></tr>
	     <tr><td id="add_contact">{t}+ Web Adress{/t}</td></tr>

	   </table>
    

    
    </div>

    <div class="yui-b">
      

	 <fieldset class="newcontact" style="width:780px">
	   <legend>{$ftipo}</legend>
	   <h2>{$f_date}</h2>
	   
	   {if $tipo=="person"}
	   <table class="newcontact">
	     <tr><td>{t}Name{/t}</td></tr>
	     <tr><td id="f_name"><span class="req">*</span>{t}Full name:{/t}</td><td id="name"><input type="text" class="text" size="36" /></td><td id="f_alias">{t}Nickname:{/t}</td><td id="alias"><input type="text" class="text" size="12" /></td></tr>
	     <tr><td id="f_name_order"><span class="req">*</span>{t}File Under:{/t}</td><td colspan="3" id="name_order"><input type="text" class="text" size="36" /></td></tr>
	   </table>
	   {/if}
	   {if $tipo=="company"}

	   <table class="newcontact">
	     <tr><td>{t}Name{/t}</td></tr>
	     <tr><td id="f_name"><span class="req">*</span>{t}Company name:{/t}</td><td id="name"><input type="text" class="text" size="36" /></td></tr>
	     <tr><td id="f_alias">{t}Street Name:{/t}</td><td id="alias"><input type="text" class="text" size="36" /></td></tr>
	     <tr><td id="f_name_order"><span class="req">*</span>{t}File Under:{/t}</td><td colspan="3" id="name_order"><input type="text" class="text" size="36" /></td></tr>
	   </table>
	   {/if}


	   <table id="waddress" style="clear:both;display:none">
	     <tr><td>{t}Mailing Address{/t}</td></tr>
	     <tbody >
	     <tr><td id="f_a1">{t}Internal{/t}:</td><td id="a1"><input type="text" class="text" size="36" /></td></tr>
	     <tr><td id="f_a2">{t}Bulding{/t}:</td><td id="a2"><input type="text" class="text" size="36" /></td></tr>
	     <tr><td id="f_a3">{t}Street{/t}:</td><td id="a3"><input type="text" class="text" size="36" /></td></tr>
	     <tr><td id="f_a3">{t}Town/City{/t}:</td><td id="town"><input type="text" class="text" size="36" /></td></tr>
	     <tr><td id="f_a3">{t}County{/t}:</td><td id="subdistrict"><input type="text" class="text" size="36" /></td></tr>
	     <tr><td id="f_a3">{t}Postcode{/t}:</td><td id="postcode"><input type="text" class="text" size="10" /></td></tr>
	     <tr><td for="country">{t}Country{/t}:</td><td>
		 <select id="country"  >
		   {foreach from=$country item=country key=country_id}
		   <option   {if $country_id==$default_country_id  }selected{/if}     value="{$country_id}">{$country}</option>
		   {/foreach}
		 </select>
		 </td></tr>
</tpody>
	     </table>

	   <table style="clear:both;">
	     <tr style="display:none"   ><td>{t}Email{/t}</td></tr>
	     <tbody style="display:none">
	     <tr><td id="f_email0">{t}Work:{/t}</td><td id="email0"><input type="text" class="text" size="36" /></td></tr>
	     <tr><td id="f_email1">{t}Personal:{/t}</td><td id="email1"><input type="text" class="text" size="36" /></td></tr>
	     </tbody>
	     <tr style="display:none"><td>{t}Telephone{/t}</td></tr>
	     <tbody style="display:none">
	     <tr><td id="f_tel0">{t}Work Phone:{/t}</td><td id="tel0"><input type="text" class="text" size="4" /> <input type="text" class="text" size="16" /> {t}Ext{/t}: <input type="text" class="text" size="4" /></td></tr>
	     <tr><td id="f_tel1">{t}Mobile:{/t}</td><td id="tel1"><input type="text" class="text" size="4" /> <input type="text" class="text" size="16" /></td></tr>
	     <tr><td id="f_tel2">{t}Home Phone:{/t}</td><td id="tel2"><input type="text" class="text" size="4" /> <input type="text" class="text" size="16" /></td></tr>
	     </tbody>
	     <tr style="display:none"  ><td>{t}Instant Messanging{/t}</td></tr>
	     <tbody style="display:none">
	     <tr><td id="f_im0">{t}Skype:{/t}</td><td id="im0"><input type="text" class="text" size="36" /></td></tr>
	     <tr><td id="f_im1">{t}MNS:{/t}</td><td id="im1"><input type="text" class="text" size="36" /></td></tr>
	     </tbody>
	     <tr style="display:none"  ><td>{t}Web Addresses{/t}</td></tr>
	     <tbody style="display:none">
	     <tr><td id="f_www0">{t}Home Page:{/t}</td><td id="www0"><input type="text" class="text" size="36" /></td></tr>
	     </tbody>
	   </table>
	   
	   
      </fieldset>

	 




    </div>
  </div>
   <div class="yui-b" style="text-align:right">

   </div>

</div>
{include file='footer.tpl'}
