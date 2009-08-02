<?php
class Name {
	var $db;
	var $data = array ();
	var $id;
	
	function __construct($id = false) {
		$this->db = MDB2::singleton ();
		
		if (is_numeric ( $id )) {
			$this->get_data ( $id );
		}
	
	}
	
	function get_data($id) {
		$sql = sprintf ( "select * from name where  id=%d", $id );
		$result = & $this->db->query ( $sql );
		if ($this->data = $result->fetchRow ()) {
			
			$this->id = $this->data ['id'];
			return true;
		}
		return false;
	
	}
	
	function display($tipo = '') {
		
		switch ($tipo) {
			case ('full_name') :
				$name = ($this->data ['prefix'] != '' ? $this->data ['prefix'] . ' ' : '') . ($this->data ['first'] != '' ? $this->data ['first'] . ' ' : '') . ($this->data ['middle'] != '' ? $this->data ['middle'] . ' ' : '') . ($this->data ['last'] != '' ? $this->data ['last'] . ' ' : '') . ($this->data ['suffix'] != '' ? $this->data ['suffix'] : ' ') . ($this->data ['alias'] != '' ? $this->data ['alias'] : '');
				return _trim ( $name );
			case ('name') :
			default :
				$name = ($this->data ['prefix'] != '' ? $this->data ['prefix'] . ' ' : '') . ($this->data ['first'] != '' ? $this->data ['first'] . ' ' : '') . ($this->data ['middle'] != '' ? $this->data ['middle'] . ' ' : '') . ($this->data ['last'] != '' ? $this->data ['last'] . ' ' : '') . ($this->data ['suffix'] != '' ? $this->data ['suffix'] : ' ');
				return _trim ( $name );
		
		}
	
	}

}
?>