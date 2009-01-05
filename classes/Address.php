<?
include_once('Country.php');
class Address{
  var $db;
  var $data=array();
  var $id=false;

  
  function __construct($arg1=false,$arg2=false) {
     $this->db =MDB2::singleton();

     if(is_numeric($id)){
       $this->get_data('id',$arg1);
       return;
     }
     if($arg1=='fuzzy all'){
       $this->get_data('fuzzy all');
       return;
     }elseif($arg1=='fuzzy country'){
       if(!is_numeric($arg2)){
	 $this->get_data('fuzzy all');
	 return;
       }
       $country=new Country($arg2);
       if(is_numeric($country_id) and $country->get('country code')!='UNK'){
	 $this->get_data('fuzzy country',$arg2);
	 return;
       }else{
	  $this->get_data('fuzzy all');
	 return;
       }
	 
	 
     }
  }


  function get_data($tipo,$id=false){
    
    if($tipo=='id')
      $sql=sprintf("select * from `Address Dimension` where  'Address Key'=%d",$id);
    elseif('tipo'=='fuzzy country')
      $sql=sprintf("select * from `Address Dimension` where  `Fuzzy Address`=1 and `Address Fuzzy Type`='country' and `Address Country Key`=%d   ",$id);
    else
      $sql=sprintf("select * from `Address Dimension` where  `Fuzzy Address`=1 and `Address Fuzzy Type`='all' ",$id);

    //PRINT $sql;
   $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()){
      $this->id=$this->data['address key'];

    }


}


  function get($key){
    $key=strtolower($key);
    if(isset($this->data[$key]))
      return $this->data[$key];
   
    return false;

  }

 function display($tipo=''){
   $separator="\n";
   switch($tipo){
   case('html'):
     $separator="<br/>";
   default:
     
     
     $header_address=($this->data['internal_address']!=''?$this->data['internal_address'].$separator:'').($this->data['building_address']!=''?$this->data['building_address'].$separator:'').($this->data['street_address']!=''?$this->data['street_address'].$separator:'');
     $town_address='';
     if($this->data['town_d2']!='' or $this->data['town_d1']!=''){
       $town_address=_trim($this->data['town_d2'].' '.$this->data['town_d1']).$separator;
       $town_address=_trim($town_address);
     }
     $town_address.=_trim($this->data['town']).$separator;
     
     if($this->data['country_d2']==$this->data['country_d1'])
       $this->data['country_d1']=='';
     if($this->data['town']==$this->data['country_d2'])
       $this->data['country_d2']=='';
     
     
     $country_d1_address='';
     if($this->data['country_d2']!='' or $this->data['country_d1']!=''){
       $country_d1_address=_trim($this->data['country_d2'].' '.$this->data['country_d1']).$separator;
       $country_d1_address=$country_d1_address;
     }
     
     

     $full_address=$header_address.$town_address.($this->data['postcode']!=''?$this->data['postcode'].$separator:'').$country_d1_address.$this->data['country'];

     return $full_address;
   }
   
 }


}
?>