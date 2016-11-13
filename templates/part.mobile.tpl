{assign "image_key" $part->get_main_image_key()}
<!-- Square card -->
<style>
.demo-card-square.mdl-card {
  width: 100%;
  height: 320px;
}
.demo-card-square > .mdl-card__title {
 
  color: #fff;
  background:
    url('/{if $image_key}image_root.php?id={$image_key}&thumbnail=small{else}art/nopic.png{/if}') bottom right 15% no-repeat #46B6AC;
    background-color: #fff;
    background-size:contain;
}

.tab_buttons{
padding:15px 10px;

}

.tab_buttons button{
margin-left:5px;}

</style>
<div class="demo-card-square mdl-card mdl-shadow--2dp">
  <div class="mdl-card__title mdl-card--expand">
    <div style="background-color:rgba(0, 0, 0, 0.7) ;padding:10px 15px">
   <div>  {$part->get('Current Stock Available')} <span style="font-size:120%">{$part->get('Stock Status Icon')}</span> </div>
    <div style="margin-top:5px" class="Products_Web_State small ">{$part->get('Products Web Status')}</div>
     <div class="aright Available_Forecast small" >{$part->get('Available Forecast')}</div>

    
  
    
    </div>    
  </div>
  <div class="mdl-card__supporting-text">
   {$part->get('Package Description')}
  </div>
  <div class="mdl-card__actions mdl-card--border tab_buttons">
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
<i class="fa fa-database discreet" aria-hidden="true"></i>
    </button>
   <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
   <i class="fa fa-usd discreet" aria-hidden="true"></i>
    </button>
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
<i class="fa fa-map-marker discreet" aria-hidden="true"></i>
    </button>
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
<i class="fa fa-cube discreet" aria-hidden="true"></i>
    </button>

  </div>
</div>