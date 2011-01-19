<div id="header" >
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
 
  </div>
  <div style="clear:both"></div>
</div> 
  
