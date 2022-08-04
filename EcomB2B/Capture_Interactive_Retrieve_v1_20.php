<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Jun 2022 16:22:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */


class Capture_Interactive_Retrieve_v1_20
{

    //Credit: Thanks to Stuart Sillitoe (https://stu.so/me) for the original PHP that these samples are based on.

    private $Key; //The key to use to authenticate to the service.
    private $Id; //The Id from a Find method to retrieve the details for.
    private $Field1Format; //
    private $Field2Format; //
    private $Field3Format; //
    private $Field4Format; //
    private $Field5Format; //
    private $Field6Format; //
    private $Field7Format; //
    private $Field8Format; //
    private $Field9Format; //
    private $Field10Format; //
    private $Field11Format; //
    private $Field12Format; //
    private $Field13Format; //
    private $Field14Format; //
    private $Field15Format; //
    private $Field16Format; //
    private $Field17Format; //
    private $Field18Format; //
    private $Field19Format; //
    private $Field20Format; //
    private $Data; //Holds the results of the query

    function Capture_Interactive_Retrieve_v1_20($Key, $Id, $Field1Format, $Field2Format, $Field3Format, $Field4Format, $Field5Format, $Field6Format, $Field7Format, $Field8Format, $Field9Format, $Field10Format, $Field11Format, $Field12Format, $Field13Format, $Field14Format, $Field15Format, $Field16Format, $Field17Format, $Field18Format, $Field19Format, $Field20Format)
    {
        $this->Key = $Key;
        $this->Id = $Id;
        $this->Field1Format = $Field1Format;
        $this->Field2Format = $Field2Format;
        $this->Field3Format = $Field3Format;
        $this->Field4Format = $Field4Format;
        $this->Field5Format = $Field5Format;
        $this->Field6Format = $Field6Format;
        $this->Field7Format = $Field7Format;
        $this->Field8Format = $Field8Format;
        $this->Field9Format = $Field9Format;
        $this->Field10Format = $Field10Format;
        $this->Field11Format = $Field11Format;
        $this->Field12Format = $Field12Format;
        $this->Field13Format = $Field13Format;
        $this->Field14Format = $Field14Format;
        $this->Field15Format = $Field15Format;
        $this->Field16Format = $Field16Format;
        $this->Field17Format = $Field17Format;
        $this->Field18Format = $Field18Format;
        $this->Field19Format = $Field19Format;
        $this->Field20Format = $Field20Format;
    }

    function MakeRequest()
    {
        $url = "https://api.addressy.com/Capture/Interactive/Retrieve/v1.20/xmla.ws?";
        $url .= "&Key=" . urlencode($this->Key);
        $url .= "&Id=" . urlencode($this->Id);
        $url .= "&Field1Format=" . urlencode($this->Field1Format);
        $url .= "&Field2Format=" . urlencode($this->Field2Format);
        $url .= "&Field3Format=" . urlencode($this->Field3Format);
        $url .= "&Field4Format=" . urlencode($this->Field4Format);
        $url .= "&Field5Format=" . urlencode($this->Field5Format);
        $url .= "&Field6Format=" . urlencode($this->Field6Format);
        $url .= "&Field7Format=" . urlencode($this->Field7Format);
        $url .= "&Field8Format=" . urlencode($this->Field8Format);
        $url .= "&Field9Format=" . urlencode($this->Field9Format);
        $url .= "&Field10Format=" . urlencode($this->Field10Format);
        $url .= "&Field11Format=" . urlencode($this->Field11Format);
        $url .= "&Field12Format=" . urlencode($this->Field12Format);
        $url .= "&Field13Format=" . urlencode($this->Field13Format);
        $url .= "&Field14Format=" . urlencode($this->Field14Format);
        $url .= "&Field15Format=" . urlencode($this->Field15Format);
        $url .= "&Field16Format=" . urlencode($this->Field16Format);
        $url .= "&Field17Format=" . urlencode($this->Field17Format);
        $url .= "&Field18Format=" . urlencode($this->Field18Format);
        $url .= "&Field19Format=" . urlencode($this->Field19Format);
        $url .= "&Field20Format=" . urlencode($this->Field20Format);

        //Make the request to Postcode Anywhere and parse the XML returned
        $file = simplexml_load_file($url);

        //Check for an error, if there is one then throw an exception
        if ($file->Columns->Column->attributes()->Name == "Error")
        {
            throw new Exception("[ID] " . $file->Rows->Row->attributes()->Error . " [DESCRIPTION] " . $file->Rows->Row->attributes()->Description . " [CAUSE] " . $file->Rows->Row->attributes()->Cause . " [RESOLUTION] " . $file->Rows->Row->attributes()->Resolution);
        }

        //Copy the data
        if ( !empty($file->Rows) )
        {
            foreach ($file->Rows->Row as $item)
            {
                $this->Data[] = array('Id'=>$item->attributes()->Id,'DomesticId'=>$item->attributes()->DomesticId,'Language'=>$item->attributes()->Language,'LanguageAlternatives'=>$item->attributes()->LanguageAlternatives,'Department'=>$item->attributes()->Department,'Company'=>$item->attributes()->Company,'SubBuilding'=>$item->attributes()->SubBuilding,'BuildingNumber'=>$item->attributes()->BuildingNumber,'BuildingName'=>$item->attributes()->BuildingName,'SecondaryStreet'=>$item->attributes()->SecondaryStreet,'Street'=>$item->attributes()->Street,'Block'=>$item->attributes()->Block,'Neighbourhood'=>$item->attributes()->Neighbourhood,'District'=>$item->attributes()->District,'City'=>$item->attributes()->City,'Line1'=>$item->attributes()->Line1,'Line2'=>$item->attributes()->Line2,'Line3'=>$item->attributes()->Line3,'Line4'=>$item->attributes()->Line4,'Line5'=>$item->attributes()->Line5,'AdminAreaName'=>$item->attributes()->AdminAreaName,'AdminAreaCode'=>$item->attributes()->AdminAreaCode,'Province'=>$item->attributes()->Province,'ProvinceName'=>$item->attributes()->ProvinceName,'ProvinceCode'=>$item->attributes()->ProvinceCode,'PostalCode'=>$item->attributes()->PostalCode,'CountryName'=>$item->attributes()->CountryName,'CountryIso2'=>$item->attributes()->CountryIso2,'CountryIso3'=>$item->attributes()->CountryIso3,'CountryIsoNumber'=>$item->attributes()->CountryIsoNumber,'SortingNumber1'=>$item->attributes()->SortingNumber1,'SortingNumber2'=>$item->attributes()->SortingNumber2,'Barcode'=>$item->attributes()->Barcode,'POBoxNumber'=>$item->attributes()->POBoxNumber,'Label'=>$item->attributes()->Label,'Type'=>$item->attributes()->Type,'DataLevel'=>$item->attributes()->DataLevel,'Field1'=>$item->attributes()->Field1,'Field2'=>$item->attributes()->Field2,'Field3'=>$item->attributes()->Field3,'Field4'=>$item->attributes()->Field4,'Field5'=>$item->attributes()->Field5,'Field6'=>$item->attributes()->Field6,'Field7'=>$item->attributes()->Field7,'Field8'=>$item->attributes()->Field8,'Field9'=>$item->attributes()->Field9,'Field10'=>$item->attributes()->Field10,'Field11'=>$item->attributes()->Field11,'Field12'=>$item->attributes()->Field12,'Field13'=>$item->attributes()->Field13,'Field14'=>$item->attributes()->Field14,'Field15'=>$item->attributes()->Field15,'Field16'=>$item->attributes()->Field16,'Field17'=>$item->attributes()->Field17,'Field18'=>$item->attributes()->Field18,'Field19'=>$item->attributes()->Field19,'Field20'=>$item->attributes()->Field20);
            }
        }
    }

    function HasData()
    {
        if ( !empty($this->Data) )
        {
            return $this->Data;
        }
        return false;
    }

}

//Example usage
//-------------
//$pa = new Capture_Interactive_Retrieve_v1_20 ("AA11-AA11-AA11-AA11","GBR|52509479","","","","","","","","","","","","","","","","","","","","");
//$pa->MakeRequest();
//if ($pa->HasData())
//{
//   $data = $pa->HasData();
//   foreach ($data as $item)
//   {
//      echo $item["Id"] . "<br/>";
//      echo $item["DomesticId"] . "<br/>";
//      echo $item["Language"] . "<br/>";
//      echo $item["LanguageAlternatives"] . "<br/>";
//      echo $item["Department"] . "<br/>";
//      echo $item["Company"] . "<br/>";
//      echo $item["SubBuilding"] . "<br/>";
//      echo $item["BuildingNumber"] . "<br/>";
//      echo $item["BuildingName"] . "<br/>";
//      echo $item["SecondaryStreet"] . "<br/>";
//      echo $item["Street"] . "<br/>";
//      echo $item["Block"] . "<br/>";
//      echo $item["Neighbourhood"] . "<br/>";
//      echo $item["District"] . "<br/>";
//      echo $item["City"] . "<br/>";
//      echo $item["Line1"] . "<br/>";
//      echo $item["Line2"] . "<br/>";
//      echo $item["Line3"] . "<br/>";
//      echo $item["Line4"] . "<br/>";
//      echo $item["Line5"] . "<br/>";
//      echo $item["AdminAreaName"] . "<br/>";
//      echo $item["AdminAreaCode"] . "<br/>";
//      echo $item["Province"] . "<br/>";
//      echo $item["ProvinceName"] . "<br/>";
//      echo $item["ProvinceCode"] . "<br/>";
//      echo $item["PostalCode"] . "<br/>";
//      echo $item["CountryName"] . "<br/>";
//      echo $item["CountryIso2"] . "<br/>";
//      echo $item["CountryIso3"] . "<br/>";
//      echo $item["CountryIsoNumber"] . "<br/>";
//      echo $item["SortingNumber1"] . "<br/>";
//      echo $item["SortingNumber2"] . "<br/>";
//      echo $item["Barcode"] . "<br/>";
//      echo $item["POBoxNumber"] . "<br/>";
//      echo $item["Label"] . "<br/>";
//      echo $item["Type"] . "<br/>";
//      echo $item["DataLevel"] . "<br/>";
//      echo $item["Field1"] . "<br/>";
//      echo $item["Field2"] . "<br/>";
//      echo $item["Field3"] . "<br/>";
//      echo $item["Field4"] . "<br/>";
//      echo $item["Field5"] . "<br/>";
//      echo $item["Field6"] . "<br/>";
//      echo $item["Field7"] . "<br/>";
//      echo $item["Field8"] . "<br/>";
//      echo $item["Field9"] . "<br/>";
//      echo $item["Field10"] . "<br/>";
//      echo $item["Field11"] . "<br/>";
//      echo $item["Field12"] . "<br/>";
//      echo $item["Field13"] . "<br/>";
//      echo $item["Field14"] . "<br/>";
//      echo $item["Field15"] . "<br/>";
//      echo $item["Field16"] . "<br/>";
//      echo $item["Field17"] . "<br/>";
//      echo $item["Field18"] . "<br/>";
//      echo $item["Field19"] . "<br/>";
//      echo $item["Field20"] . "<br/>";
//   }
//}