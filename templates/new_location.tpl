{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >
{include file='locations_navigation.tpl'}
  <h1 id="welcome" style="padding:10px 20px">{t}New Location{/t}</h1>
  <div id="the_chooser" class="chooser" style="margin:0px 20px">
    <ul id="chooser_ul">
      <li id="individual" class="show"  > {t}Individual{/t}</li>
      <li id="shelf" class="show"style="display:none" > {t}Shelf{/t}</li>
      <li id="rack" class="show" style="display:none"> {t}Pallet Rack{/t}</li>
      <li id="floor" class="show" style="display:none"  > {t}Floor Space{/t}</li>
    </ul>
  </div>
  

  <div id="block_individual"  style="display:none;margin:0px 20px;clear:both;">
    {include file='new_individual_location_splinter.tpl'}

    
  </div>





    



</div>
{include file='footer.tpl'}

