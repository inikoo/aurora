{include file='header.tpl'}
<div id="bd" >
 <div id="yui-main">
    <div class="yui-b" style="padding:0 20px">
      <div class="search_box" >
      </div>
      <h1>{$name} <span style="color:SteelBlue">{$id}</span></h1> 
      <div class="chooser" >
	<ul>
	  <li id="main" {if $edit=='main'}class="selected"{/if} > <img src="art/icons/cog.png"> {t}Details{/t}</li>
	  <li id="contacts" {if $edit=='contacts'}class="selected"{/if} > <img src="art/icons/group.png"> {t}Contacts{/t}</li>
	  <li id="contacts" {if $edit=='contacts'}class="selected"{/if} > <img src="art/icons/group.png"> {t}Contacts{/t}</li>

	</ul>
      </div> 
      <div style="clear:both;padding:20px 20px" id="edit_messages"></div>
      <div  {if $edit!="main"}style="display:none"{/if}  class="edit_block" id="d_main">
      </div>
      <div  {if $edit!="contacts"}style="display:none"{/if}  class="edit_block" id="d_constats">
      </div>

    </div>
 </div> 
</div>


{include file='footer.tpl'}

