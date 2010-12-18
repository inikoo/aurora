<div id="header" style="{$page_data.header_style}">
  <div id="header_family" >
 
  
    <a href="index.php" alt="home"><img src="{$store_code}/art/logo.png"/></a>
    <div>
      <span id="category">{$department_slogan}</span><br/>
      <span id="slogan">{$store_slogan}</span>
    </div>
  </div>
  <div id="header_title" >
  
    {if $logged_in}
<div style="" id="top_menu">
     <div style="display:none">{$traslated_labels.hello} {$user->get('User Alias')}</div>
          
          <a href="myaccount.php" style="margin-left:20px">{$traslated_labels.myaccount}</a>  
          <a href="orders.php" style="margin-left:20px">{$traslated_labels.orders}</a>  
          <a href="logout.php" style="margin-left:20px;left-right:10px">{$traslated_labels.logout}</a>  

</div>
{/if}
  
    <h1>{$header_title}</h1>
    <h2>{$header_subtitle}</h2>
    {include file="$main_menu_template"}
  </div>
  <div id="header_info" >
  {if $logged_in}


 <div id="login_from_left_menu" style="margin-top:0px">
    <table class="mini_basket" >
      <tr><td class="label">{$traslated_labels.items}</td><td id="basket_items">{$order.amount_items}</td><td id="basket_discounts" style="width:52px">{if $order.discounts!=0}(-{$order.amount_discounts}){/if}</td></tr>
      <tr><td class="label">{$traslated_labels.shipping_and_handing}</td><td id="basket_shipping_and_handing">{$order.amount_shipping_and_handing}</td></tr>
        <tr id="tr_basket_net" {if $order.tax==0}style="display:none"{/if} ><td class="label">{$traslated_labels.total_net}</td><td id="basket_net" >{$order.amount_total_net}</td></tr>
      <tr id="tr_basket_tax" {if $order.tax==0}style="display:none"{/if} ><td class="label">{$traslated_labels.tax}</td><td id="basket_tax" >{$order.amount_tax}</td></tr>
          <tr><td class="label" >{$traslated_labels.total}</td><td id="basket_total" >{$order.amount_total}</td></tr>
<tr class="checkout"><td></td><td ><button id="checkout" style="font-size:95%;position:relative;left:5px">{$traslated_labels.checkout}</button></td></tr>



    </table>    



  </div>

  {else}
  
  
   <div id="header_login" >
      <a href="register.php">{$traslated_labels.register}</a>
      <a href="login.php" style="margin-left:20px">{$traslated_labels.login}</a>
    </div>
    <div style="text-align:center;border:1px solid #ccc;padding:0 5px;margin-top:5px; margin-left:10px">
      {$traslated_labels.public_msg}
    </div>
    {/if}
  </div>
  <div style="clear:both"></div>
</div> 
  
