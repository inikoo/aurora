{*}
<form  id="id_search_form" action="customers.php" method="GET" style="display:inline">
  <label style="position:relative;left:16px">{t}Id Search{/t}:</label><input  size="12" class="text search" id="prod_search" value="{$search1}" name="q_id1"/><img onclick="document.getElementById('id_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"> 
</form>
<form  id="id2_search_form" action="customers.php" method="GET"  style="display:inline">
  <label style="position:relative;left:16px">{$customer_id2} {t}Search{/t}:</label><input size="12" class="text search" id="prod_search" value="{$search2}" name="q_id2"/><img onclick="document.getElementById('id2_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
</form>
{/*}
<form  id="id3_search_form" action="customers.php" method="GET"  style="display:inline">
  <label style="position:relative;left:16px">{$customer_id3} {t}Search{/t}:</label><input size="12" class="text search" id="prod_search" value="{$search3}" name="q_id3"/><img onclick="document.getElementById('id3_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
</form>
