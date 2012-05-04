<?php
function get_options($scope,$scope_key) {

    switch ($scope) {

    case('customers_store'):


        $fields=array(
                    'Ignore'=>_('Ignore'),
                    'Customer Company Name'=>_('Company Name'),
                    'Customer Tax Number'=>_('Tax Number'),
                    'Customer Main Contact Name'=>_('Contact Name'),
                    'Customer Main Plain Email'=>_('Email'),
                    'Customer Main Plain Telephone'=>_('Telephone'),
                    'Customer Main Plain Mobile'=>_('Mobile'),
                    'Customer Main Plain FAX'=>_('Fax'),
                    'Customer Address Line 1'=>_('Address Line 1'),
                    'Customer Address Line 2'=>_('Address Line 2'),
                    'Customer Address Line 3'=>_('Address Line 3'),
                    'Customer Address Town Second Division'=>_('Town Second Division'),
                    'Customer Address Town First Division'=>_('Town First Division'),

                    'Customer Address Town'=>_('Town'),
                    'Customer Address Postal Code'=>_('Postal Code'),

                    'Customer Address Country Fifth Division'=>_('Country Fifth Division'),
                    'Customer Address Country Forth Division'=>_('Country Forth Division'),
                    'Customer Address Country Third Division'=>_('Country Third Division'),
                    'Customer Address Country Second Division'=>_('Country Second Division'),
                    'Customer Address Country First Division'=>_('Country First Division'),
                    'Customer Address Country Name'=>_('Country'),
                    'Customer Address Country Code'=>_('Country Code (XXX)'),
                    'Customer Address Country 2 Alpha Code'=>_('Country Code (XX)'),





                );
                
               $categories=array();
$sql=sprintf("select `Category Key`,`Category Label` from `Category Dimension` where `Category Subject`='Customer' and `Category Deep`=1 and `Category Store Key`=%d",$scope_key);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){


$fields['cat'.$row['Category Key']]=$row['Category Label'];

}

                
                
        break;

    case('supplier_products'):
        $fields=array();
        break;

    case('staff'):
        $fields=array();
        break;

    case('positions'):
        $fields=array();
        break;

    case('areas'):
        $fields=array();
        break;

    case('departments'):
        $fields=array();
        break;

    case('family'):
        $fields=array(
		'Ignore'=>_('Ignore')
		,'Part SKU'=>_('SKU')
		,'Product Code'=>_('Code')
		,'Product Name'=>_('Name')
		,'Product Units Per Case'=>_('Units')
		,'Product Price'=>_('Price')
		,'Product RRP'=>_('RRP')
		,'Product Net Weight'=>_('Weight')
		,'Product Special Characteristic'=>_('Special Characteristic')
		,'Product Description'=>_('Description')
		);
        break;
     case('department'):
        $fields=array(
		'Ignore'=>_('Ignore')
		,'Product Family Code'=>_('Code')
		,'Product Family Name'=>_('Name')
		,'Product Family Description'=>_('Description')
		);
	break;
     case('store'):
        $fields=array(
		 'Ignore'=>_('Ignore')
		,'Product Department Code'=>_('Code')
		,'Product Department Name'=>_('Name')
		);
	break;
    default:
        $fields=array();
    }

$db_fields=array();
$labels=array();
foreach ($fields as $key=>$item) {
   $db_fields[]=$key;
   $labels[]=$item;
}
    return array($db_fields,$labels);

}

?>