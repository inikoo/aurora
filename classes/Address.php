<?
class Address{
  var $db;
  var $data=array();
  var $id=false;

  
  function __construct($id=false) {
     $this->db =MDB2::singleton();

     if(is_numeric($id)){
       $this->get_data($id);

     }



  }


function get_data($id){
   $sql=sprintf("select * from address where  id=%d",$id);
   $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()){
      $this->id=$this->data['id'];
      return true;
    }
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