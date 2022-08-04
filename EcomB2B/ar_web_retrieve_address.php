<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Jun 2022 16:25:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

include_once 'ar_web_common_logged_out.php';
include_once 'Capture_Interactive_Retrieve_v1_20.php';


$is_middleware = 'False';
if (ENVIRONMENT == 'DEVEL') {
    $is_middleware = 'True';
}
// free GB|RM|B|26772356
// AW GB|RM|B|56879362


$pa = new Capture_Interactive_Retrieve_v1_20 (AU_LOQATE_KEY, "GB|RM|B|56879362", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
$pa->MakeRequest();
if ($pa->HasData()) {
    $data = $pa->HasData();
    foreach ($data as $item) {
        print_r($item);
        /*
        echo $item["Id"] . "<br/>";
        echo $item["DomesticId"] . "<br/>";
        echo $item["Language"] . "<br/>";
        echo $item["LanguageAlternatives"] . "<br/>";
        echo $item["Department"] . "<br/>";
        echo $item["Company"] . "<br/>";
        echo $item["SubBuilding"] . "<br/>";
        echo $item["BuildingNumber"] . "<br/>";
        echo $item["BuildingName"] . "<br/>";
        echo $item["SecondaryStreet"] . "<br/>";
        echo $item["Street"] . "<br/>";
        echo $item["Block"] . "<br/>";
        echo $item["Neighbourhood"] . "<br/>";
        echo $item["District"] . "<br/>";
        echo $item["City"] . "<br/>";
        echo $item["Line1"] . "<br/>";
        echo $item["Line2"] . "<br/>";
        echo $item["Line3"] . "<br/>";
        echo $item["Line4"] . "<br/>";
        echo $item["Line5"] . "<br/>";
        echo $item["AdminAreaName"] . "<br/>";
        echo $item["AdminAreaCode"] . "<br/>";
        echo $item["Province"] . "<br/>";
        echo $item["ProvinceName"] . "<br/>";
        echo $item["ProvinceCode"] . "<br/>";
        echo $item["PostalCode"] . "<br/>";
        echo $item["CountryName"] . "<br/>";
        echo $item["CountryIso2"] . "<br/>";
        echo $item["CountryIso3"] . "<br/>";
        echo $item["CountryIsoNumber"] . "<br/>";
        echo $item["SortingNumber1"] . "<br/>";
        echo $item["SortingNumber2"] . "<br/>";
        echo $item["Barcode"] . "<br/>";
        echo $item["POBoxNumber"] . "<br/>";
        echo $item["Label"] . "<br/>";
        echo $item["Type"] . "<br/>";
        echo $item["DataLevel"] . "<br/>";
        echo $item["Field1"] . "<br/>";
        echo $item["Field2"] . "<br/>";
        echo $item["Field3"] . "<br/>";
        echo $item["Field4"] . "<br/>";
        echo $item["Field5"] . "<br/>";
        echo $item["Field6"] . "<br/>";
        echo $item["Field7"] . "<br/>";
        echo $item["Field8"] . "<br/>";
        echo $item["Field9"] . "<br/>";
        echo $item["Field10"] . "<br/>";
        echo $item["Field11"] . "<br/>";
        echo $item["Field12"] . "<br/>";
        echo $item["Field13"] . "<br/>";
        echo $item["Field14"] . "<br/>";
        echo $item["Field15"] . "<br/>";
        echo $item["Field16"] . "<br/>";
        echo $item["Field17"] . "<br/>";
        echo $item["Field18"] . "<br/>";
        echo $item["Field19"] . "<br/>";
        echo $item["Field20"] . "<br/>";
        */
    }
}