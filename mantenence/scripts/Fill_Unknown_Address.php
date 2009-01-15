<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Country.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');





$software='Fill_Unknown_Address.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$sql="insert into `Address Dimension` (`Address Location`,`Fuzzy Address`,`Address Data Creation`,`Address Fuzzy Type`) values (".prepare_mysql('<img src="art/flags/un.png" alt="?" title="UNK"/>').",1,NOW(),'All')";
$db->exec($sql);

$sql="SELECT * FROM dw.`Country Dimension` C where `Country key`!=244 group by `World Region` order by `World Region`  ";
  $res = $db->query($sql); 
    while($row=$res->fetchRow()) {

      $country_id=$row['country key'];
      $country=new Country('id',$country_id);

      if(preg_match('/^(cental|south|north|east|west|australia|Micron|central|Melanes|polyne)/i',$country->data['world region']))
	$xhtml_address='Somewhere in '.$country->data['world region'];
      else
	$xhtml_address='Somewhere in the '.$country->data['world region'];
      $sql=sprintf("insert into `Address Dimension` (`Address Location`,`Fuzzy Address`,`Address Data Creation`,`Address World Region`,`Address Continent`,`XHTML Address`,`Address Fuzzy Type`) values (".prepare_mysql('<img src="art/flags/un.png" alt="?" title="UNK"/>').",1,NOW(),%s,%s,%s,'World Region')"
		   
		   ,prepare_mysql($country->data['world region'])
		   ,prepare_mysql($country->data['continent'])
		   ,prepare_mysql($xhtml_address)
		   );
      $db->exec($sql);
      //      print "$sql\n";
      //print_r($country->data);
    }


$sql="SELECT * FROM dw.`Country Dimension` C where `Country key`!=244 order by `Country Code`  ";
  $res = $db->query($sql); 
    while($row=$res->fetchRow()) {

      $country_id=$row['country key'];
      $country=new Country('id',$country_id);
      $sql=sprintf("insert into `Address Dimension` (`Address Location`,`Fuzzy Address`,`Address Data Creation`,`Address Country Key`,`Address Country Name`,`Address Country Code`,`Address Country 2 Alpha Code`,`Address World Region`,`Address Continent`,`XHTML Address`,`Address Fuzzy Type`) values (%s,1,NOW(),%d,%s,%s,%s,%s,%s,%s,'Country')",
		   prepare_mysql(
				 '<img src="art/flags/'.
				 strtolower($country->data['country 2 alpha code']).'.png" alt="'.
				 addslashes($country->data['country name']).'" title="'.$country->data['country code'].'"/>')
		   ,$country->data['country key']
		   ,prepare_mysql($country->data['country name'])
		   ,prepare_mysql($country->data['country code'])
		   ,prepare_mysql($country->data['country 2 alpha code'])
		   ,prepare_mysql($country->data['world region'])
		   ,prepare_mysql($country->data['continent'])
		   ,prepare_mysql('Somewhere in '.$country->data['country name'])
		   );
      $db->exec($sql);
      //print "$sql\n";
      //print_r($country->data);
    }



?>