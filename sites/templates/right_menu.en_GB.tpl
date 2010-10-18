<div id="right_menu">
{if $logged_in}

 <div id="login_from_left_menu" style="margin-top:10px">
    <h3 style="border-bottom:1px solid #aaa">{$traslated_labels.basket}:</h3>
    <table class="mini_basket">
      <tr><td class="label">{$traslated_labels.items}</td><td>{$order.amount_items}</td></tr>
      <tr><td class="label">{$traslated_labels.shipping}</td><td>{$order.amount_shipping}</td></tr>
      {if $order.charges!=0}
      <tr><td class="label">{$traslated_labels.charges}</td><td>{$order.amount_charges}</td></tr>
      {/if}
      <tr><td class="label">{$traslated_labels.discounts}</td><td>{$order.amount_discounts}</td></tr>
      {if $order.tax==0}
      <tr><td class="label">{$traslated_labels.total}</td><td>{$order.amount_total}</td></tr>
      {else}
      <tr><td class="label">{$traslated_labels.total_net}</td><td>{$order.amount_total_net}</td></tr>
      <tr><td class="label">{$traslated_labels.tax}</td><td>{$order.amount_tax}</td></tr>
      <tr><td class="label">{$traslated_labels.total}</td><td>{$order.amount_total}</td></tr>
      {/if}
    



    </table>    


    <button id="sing_in" style="float:right;margin-right:5px;font-size:95%">{$traslated_labels.checkout}</button>

  </div>

  {else}
  <div id="login_from_left_menu" style="margin-top:10px">
    <h3 style="border-bottom:1px solid #aaa">{$traslated_labels.access}:</h3>

      <input type="hidden" value="{$secret_string}" id="ep">


      {$traslated_labels.email}:
      <input id="login_handle" type="text" value="" style="width:95%"/>
      {$traslated_labels.password}:
      <input  id="login_password"  type="password"  value=""  style="width:95%"/>
      <button id="sing_in" onclick="login()" style="float:right;margin-right:5px;font-size:95%">{$traslated_labels.login}</button>
      
      <div style="margin-top:36px;display:none" id="invalid_credentials">{$traslated_labels.invalid_credentials}</div>

      
      <div style="margin-top:36px"><a href="lost_password.php">{$traslated_labels.forgot_password}</a></div>
      

    
  </div>
  <div id="register">
    <p>{$traslated_labels.new_customer}</p>
    <h3 style="text-align:center;border:1px solid #ccc;padding:2px 3px;margin-bottom:5px;background:#990000">
      <a href="register.php" >{$traslated_labels.register_here}</a>
    </h3>
    {$traslated_labels.public_msg}
  </div>
{/if}
</div>
