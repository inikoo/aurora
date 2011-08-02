<?php
class threessFTP
{
	///////////////////////////////////////////////
	//
	//	3 Service Solution
	//	http://www.3servicesolution.com/
	//	Author: Brant Messenger
	//
	//	COPYRIGHT:
	//	http://creativecommons.org/licenses/by-sa/2.0/
	//
	//	DOWNLOAD:
	//	http://www.phpclasses.org/browse/download/1/file/9585/name/threessftp.class.php
	//
	///////////////////////////////////////////////

	var $host; // FTP HOST
	var $user; // FTP USER
	var $password; // FTP PASSWORD
	var $source; // FILE SOURCE
	var $destination; // FILE DESTINATION
	var $mode; // FTP MODE
	var $str;
	var $write;

	// SET THE CONNECTION VARIABLES
	function threessFTP($host,$user="anonymous",$password="nobody@nobody.com")
	{
  		$this->host = $host;
  		$this->user = $user;
		$this->password = $password;
	}

	// MAKE A CONNECTION
	function connect()
  	{
  		$this->connection = ftp_ssl_connect($this->host);
  		
  		if (!$this->connection)
		{
			$this->error("ERROR FTP->SSL_CONNECT [$this->connection:$this->host]");
			$this->connection = ftp_connect($this->host);
		}
		
		if (!$this->connection)
		{
			$this->error("ERROR FTP->CONNECT [$this->connection:$this->host]");
		}
    	
	}

	// LOGIN
	function login()
  	{
	  	$this->logged = ftp_login($this->connection, $this->user, $this->password);

		if (!$this->logged)
		{
			$this->error("ERROR FTP->LOGIN [$this->connection:$this->user:$this->password]");
		}
    	
		$this->pasv = ftp_pasv($this->connection, true);
    	
		if (!$this->pasv)
		{
			$this->error("ERROR FTP->PASV [$this->connection]");
		}
    	
  	}

	// UPLOAD SOURCE FILE TO DESTINATION
  	function upload($source_file,$destination_file,$type="")
  	{
		$this->source = $source_file;
		$this->destination = $destination_file;
		$this->type = $type;
		
		switch($this->type) // MODE = 'FTP_ASCII' or 'FTP_BINARY'
		{
			case"image/gif":
			case"image/png":
			case"image/jpeg":
				$this->mode = FTP_BINARY;
			break;
			default:
				$this->mode = FTP_ASCII;
		}
    
		$this->put = ftp_put($this->connection, $this->destination, $this->source, $this->mode);
		
		if (!$this->put)
		{ 
			$this->error("ERROR FTP->PUT [$this->connection:$this->source:$this->mode]");
		}
    	

  	}
//---------------------------------------------------------------------------------------------------------
function download($source_file,$destination_file,$type="")
  	{
		$this->source = $source_file;
		$this->destination = $destination_file;
		$this->type = $type;
		
		switch($this->type) // MODE = 'FTP_ASCII' or 'FTP_BINARY'
		{
			case"image/gif":
			case"image/png":
			case"image/jpeg":
				$this->mode = FTP_BINARY;
			break;
			default:
				$this->mode = FTP_ASCII;
		}
    
		$this->get = ftp_get($this->connection, $this->destination, $this->source, $this->mode);
		
		if (!$this->get)
		{ 
			$this->error("ERROR FTP->PUT [$this->connection:$this->source:$this->mode]");
		}
  	}
//---------------------------------------------------------------------------------------------------------
	// DELETE A FILE
  	function delete($source_file)
  	{
  		$this->source = $source_file;
		$this->deleted = ftp_delete($this->connection, $this->source);
		
		if (!$this->deleted)
		{
			$this->error("ERROR FTP->DELETE [$this->connection:$this->source]");
		}
    	
  	}

	// CLEAN ILLEGAL CHARACTERS
	function clean_filename($source_file)
	{
		$search[] = " ";
		$search[] = "&";
		$search[] = "$";
		$search[] = ",";
		$search[] = "!";
		$search[] = "@";
		$search[] = "#";
		$search[] = "^";
		$search[] = "(";
		$search[] = ")";
		$search[] = "+";
		$search[] = "=";
		$search[] = "[";
		$search[] = "]";

		$replace[] = "_";
		$replace[] = "and";
		$replace[] = "S";
		$replace[] = "_";
		$replace[] = "";
		$replace[] = "";
		$replace[] = "";
		$replace[] = "";
		$replace[] = "";
		$replace[] = "";
		$replace[] = "";
		$replace[] = "";
		$replace[] = "";
		$replace[] = "";

		return str_replace($search,$replace,$source_file);

	}

	// DETERMINE IF FILE EXISTS AND RETURN SIZE
  	function check_file($source_file)
  	{
  		$this->source = $source_file;
		  
		return ftp_size($this->connection, $this->source);	
  	}

  	// WRITE ERROR TO LOG
  	function error($str,$write=0)
  	{
		error_log($str, $write);
  	}

	// CLOSE CONNECTION
  	function close()
  	{
  		ftp_close($this->connection);
  	}

	// QUICK START COMMAND
  	function start()
  	{
  		$this->connect();
   		$this->login();
  	}

	// QUICK END COMMAND
  	function end()
  	{
		$this->close();
  	}
}
?>
