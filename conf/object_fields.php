<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 12:10:26 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function get_object_fields($object, $db, $user,$options=false) {

 $edit=true;

	switch ($object->get_object_name()) {

	case 'Product':
	case 'StoreProduct':
		include 'fields/product.fld.php';
		return $product_fields;
		break;

	case 'Supplier':
		include 'fields/supplier.fld.php';
		return $supplier_fields;
		break;

	case 'Supplier Part':
		include 'fields/supplier_part.fld.php';
		if (isset($options['new'])  ) {
			$object=new Part(0);
			include 'fields/part.fld.php';
			$supplier_part_fields = array_merge($supplier_part_fields, $part_fields);
		}
		return $supplier_part_fields;
		break;

	case 'Part':
		include 'fields/part.fld.php';
		return $part_fields;
		break;

	case 'Warehouse':
		include 'fields/store.fld.php';
		return $object_fields;
		break;

	case 'Store':
	
		if (!in_array($object->id, $user->stores)) {
            $edit=false;
	    }
		include 'fields/store.fld.php';
		return $object_fields;
		break;
	case 'Staff':
		include 'fields/employee.fld.php';
		return $object_fields;
		break;

	default:
		return '';
		break;
	}

}



?>
