<?php

$percentage      = 35;
$net             = 3.50;
$discount_amount = round(($net) * $percentage / 100, 2);
print $discount_amount."\n";

$discount_amount = round(($net) * .35, 2);
print $discount_amount."\n";


?>