<div id="header" style="{$page_data.header_style}" >
  <div id="header_home" >
    <div>
      <span id="second_slogan">{$store_slogan}</span>
      
    </div>
  </div>
  <div id="header_title" >

    <div id="header_login" >
{if $logged_in}
      {$traslated_labels.hello} {$user->get('User Alias')}<a href="logout.php" style="margin-left:20px">{$traslated_labels.logout}</a>  
{else}
   <a href="register.php">{$traslated_labels.register}</a>
      <a href="login.php" style="margin-left:20px">{$traslated_labels.login}</a>
{/if}
    </div>
 


   <div id="header_info" >
      <h1>{$page_data.title}</h1>
      <h2>{$page_data.subtitle}</h2>
      <div id="header_commentary" style="font-size:80%">
	{$page_data.resume}
      </div>  
      
    </div>
    <div style="margin-top:10px;height:70px;width:300px">
    <a href="index.php" alt="home"><img src="{$page_data.logo}" alt="logo" /></a><br/>
    </div>
    <div  id="slogan">{$page_data.slogan}</div>
    {include file="$main_menu_template"}
  </div>
</div>
