<?php
$footer_description='Wholesale giftware supplier. Please note this is a wholesale site we supply wholesale to the trade.';
$other="";
$address=$store->data['Store Address'];
$telephone=$store->data['Store Telephone'];
$footer=<<<EOD
<div id="footer_container" style="display:none" >
<div id="footer">
<table class="footer_table">
<tr><td class="address">$address<br/>$telephone</td><td class="description">$footer_description</td><td>$other</td></tr>
</table>
</div>
</div>
EOD;
$footer_=<<<EOD
<div id="footer">

<table class="footer_table">
<tr><td class="address">$address<br/>$telephone</td><td class="description">$footer_description</td><td>$other</td></tr>
</table>

</div>
EOD;
?>

