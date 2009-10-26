{include file='header.tpl'}
<div id="bd" >

  <div class="top_navigation">
 
  </div>



<div class="block_list">
<div onclick="location.href='report_sales_server.php'">Sales</div>

</div>



  <div class="chooser" style="display:none">
    <ul>
      <li {if $tipo=='sales'}class="selected"{/if} id="salesx"><img src="art/icons/money.png"> Sales</li>
      <li style="display:none"{if $tipo=='geosales'}class="selected"{/if} id="geosalesx" ><img src="art/icons/world.png"> Geo-Sales</li>
      <li style="display:none"{if $tipo=='customers'}class="selected"{/if} id="customers"><img src="art/icons/user.png"> Customers</li>
      <li style="display:none"{if $tipo=='products'}class="selected"{/if} id="products"><img src="art/icons/brick.png"> Products</li>
      <li style="display:none"{if $tipo=='times'}class="selected"{/if} id="times"><img src="art/icons/clock.png"> Dispaching Times</li>
      <li style="display:none"{if $tipo=='prod'}class="selected"{/if} id="prod"><img src="art/icons/cog.png"> Productivity</li>
      <li {if $tipo=='stock'}class="selected"{/if} id="stock"><img src="art/icons/brick.png"> Stock</li>
    </ul>
    
  </div> 
  </div> 
 

{include file='footer.tpl'}

