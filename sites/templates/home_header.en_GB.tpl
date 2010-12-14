<div id="header" >
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
      <h1>{$header_title}</h1>
      <h2>{$header_subtitle}</h2>
      <div id="header_commentary" style="font-size:80%">
	{$comentary}
      </div>  
      
    </div>
    <a href="index.php" alt="home"><img src="image.php?code=logo"/></a><br/>
    <div  id="slogan">{$slogan}</div>
    {include file="$main_menu_template"}
  </div>
</div>
