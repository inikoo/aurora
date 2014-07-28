<?php


function get_translation_language($str){
switch($str){
case 'English':
$translated_str=_('English');
break;
case 'Spanish':
$translated_str=_('Spanish');
break;
case 'German':
$translated_str=_('German');
break;
case 'French':
$translated_str=_('French');
break;
case 'Czech':
$translated_str=_('Czech');
break;
case 'Slovak':
$translated_str=_('Slovak');
break;
case 'Chinese':
$translated_str=_('Chinese');
break;
case 'Polish':
$translated_str=_('Polish');
break;
case 'Italian':
$translated_str=_('Italian');
break;
default:
$translated_str=$str;
}
return $translated_str;
}

$locale_product_record_type['Discontinued']=_('Discontinued');
$locale_product_record_type['Normal']=_('Normal');
$locale_product_record_type['Discontinuing']=_('Discontinuing');
$locale_product_record_type['Historic']=_('Historic');
$locale_product_record_type['In Process']=_('In Process');
$locale_product_record_type['New']=_('New');


$_web_status['onsale']=_('On sale');
$_web_status['outofstock']=_('Out of stock');
$_web_status['discontinued']=_('Discontinued');
$_web_status['hidden']=_('Hidden');
$_web_status['offline']='';

$_location_tipo['picking']=_('Picking Area');
$_location_tipo['storing']=_('Storing Area');
$_location_tipo['loading']=_('Goods In');
$_location_tipo['unknown']=_('Unknown');
$_location_tipo['white_hole']=_('Error');
$_location_tipo['display']=_('On display');

$_shape['0']='Choose one';
$_shape['1']='Box';
$_shape['2']='Sphere';
$_shape['3']='Cilinder';
$_shape['4']='Stick';
$_shape['5']='Sheet';

$_shape_example['0']='';
$_shape_example['1']='width x deep x height (in cm)';
$_shape_example['2']='diameter (in cm)';
$_shape_example['3']='diameter x height (in cm)';
$_shape_example['4']='lenght (in cm)';
$_shape_example['5']='width x height (in cm)';


?>